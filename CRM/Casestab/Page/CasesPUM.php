<?php
use CRM_Casestab_ExtensionUtil as E;

require_once 'CRM/Core/Page.php';

class CRM_Casestab_Page_CasesPUM extends CRM_Core_Page {

  protected $clientId;
  protected $action;
  protected $_pager;
  protected $_deleted;
  //protected $_count;
  //protected $_deletedCount;

  public function run() {
    $this->setPageConfiguration();
    $displayCases = array();
    $deletedCases = array();
    $this->initializePager(FALSE);
    $daoCases = $this->getDaoCases();

    while($daoCases->fetch()) {
      $row = $this->buildRow($daoCases);

      $displayCases[] = $row;

    }

    $this->assign('clientId', $this->clientId);
    $this->assign('cases', $displayCases);
    $this->assign('currentPage',$this->_pager->_currentPage);
    $this->assign('totalPages',$this->_pager->_totalPages);

    parent::run();
  }

  protected function setPageConfiguration() {
    CRM_Utils_System::setTitle(ts('List of cases'));

    $this->clientId = CRM_Utils_Request::retrieve('cid', 'Positive');
  }

  /**
   * Function to get cases
   *
   * @param $deleted_cases - Whether to return deleted cases or not.
   *    When this parameter is set to TRUE only deleted cases will be returned
   *    When this parameter is set to FALSE only non-deleted cases will be returned
   * @return object DAO
   * @access protected
   */
  protected function getDaoCases() {
    $params = array();
    list($offset, $limit) = $this->_pager->getOffsetAndRowCount();
    $query = 'SELECT  c.id,
                      c.subject,
                      (SELECT ov.label FROM civicrm_option_value ov WHERE ov.option_group_id = (SELECT id FROM civicrm_option_group og WHERE og.name = \'case_status\') AND ov.value = c.status_id) AS \'case_status\',
                      (SELECT ov.label FROM civicrm_option_value ov WHERE ov.option_group_id = (SELECT id FROM civicrm_option_group og WHERE og.name = \'case_type\') AND ov.value = c.case_type_id) AS \'case_type\',
                      c.start_date,
                      c.end_date,
                      cc.contact_id,
                      c.is_deleted
              FROM civicrm_case c
              LEFT JOIN civicrm_case_contact cc ON cc.case_id = c.id
              WHERE cc.contact_id = %1';

    $query .= ' ORDER BY c.id ASC
              LIMIT %2, %3';

    $params = array(1 => array($this->clientId, 'Integer'),
                    2 => array($offset, 'Integer'),
                    3 => array($limit, 'Integer')
    );

    return CRM_Core_DAO::executeQuery($query, $params);
  }



  /**
   * Fucntion to build the display row
   *
   * @param object $dao
   * @return array
   * @access protected
   */
  protected function buildRow($dao) {
    $caseTypeName = CRM_Case_BAO_Case::getCaseType($dao->id, 'name');
    $xmlProcessor = new CRM_Case_XMLProcessor_Process();
    $managerRoleId = $xmlProcessor->getCaseManagerRoleId($caseTypeName);

    $params = array(
      'version' => 3,
      'sequential' => 1,
      'case_id' => $dao->id,
      'relationship_type_id' => $managerRoleId,
    );
    $result = civicrm_api('Relationship', 'get', $params);

    foreach($result['values'] as $key => $value){
      $contact = civicrm_api('Contact', 'get', array('version'=>3,'sequential'=>1,'id'=>$value['contact_id_b']));
    }

    $displayRow = array();
    $displayRow['id'] = $dao->id;
    $displayRow['url'] = CRM_Utils_System::url('civicrm/contact/view/case', 'reset=1&id='.$dao->id.'&cid='.$dao->contact_id.'&action=view&context=case&selectedChild=case', TRUE);
    $displayRow['case_id'] = '<a href="'.$displayRow['url'].'">'.$dao->id.'</a>';
    $displayRow['subject'] = $dao->subject;
    $displayRow['case_status'] = $dao->case_status;
    $displayRow['case_type'] = $dao->case_type;
    $displayRow['case_manager'] = $contact['values'][0]['display_name'];
    $displayRow['start_date'] = $dao->start_date;
    $displayRow['end_date'] = $dao->end_date;
    $displayRow['is_deleted'] = $dao->is_deleted;

    // check is the user has view/edit signer permission
    $permissions = array(CRM_Core_Permission::VIEW);
    //validate access for all cases.
    $allCases = TRUE;
    if ($allCases && !CRM_Core_Permission::check('access all cases and activities')) {
      $allCases = FALSE;
    }
    if (CRM_Core_Permission::check('access all cases and activities') ||
      (!$allCases && CRM_Core_Permission::check('access my cases and activities'))
    ) {
      $permissions[] = CRM_Core_Permission::EDIT;
    }
    if (CRM_Core_Permission::check('delete in CiviCase')) {
      $permissions[] = CRM_Core_Permission::DELETE;
    }
    $mask = CRM_Core_Action::mask($permissions);
    $actions = CRM_Case_Selector_Search::links();

    if($dao->is_deleted == 0) {
      $displayRow['actions'] = CRM_Core_Action::formLink($actions['primaryActions'], $mask,
        array(
          'id' => $dao->id,
          'cid' => $dao->contact_id,
          'cxt' => 'case',
        )
      );
      $displayRow['moreActions'] = CRM_Core_Action::formLink($actions['moreActions'],
        $mask,
        array(
          'id' => $dao->id,
          'cid' => $dao->contact_id,
          'cxt' => 'case',
        ),
        ts('more'),
        TRUE
      );
    }

    if ($dao->is_deleted == 1 && CRM_Core_Permission::check('delete in CiviCase')) {
      $displayRow['restore'] = '<a href="/civicrm/contact/view/case?reset=1&action=renew&id='.$dao->id.'&cid='.$dao->contact_id.'&context=case">Restore</a>';
    }

    $menuItems = CRM_Contact_BAO_Contact::contextMenu();
    $primaryActions = CRM_Utils_Array::value('primaryActions', $menuItems, array());
    $contextMenu = CRM_Utils_Array::value('moreActions', $menuItems, array());

    return $displayRow;
  }

  /**
   * Method to initialize pager
   *
   * @access protected
   */
  protected function initializePager($deleted_cases=FALSE) {
    if ($this->clientId) {
      try {
        $values = array(
          1 => array($this->clientId, 'Integer')
        );

        $query = "SELECT COUNT(*)
                  FROM civicrm_case c
                  LEFT JOIN civicrm_case_contact cc ON cc.case_id = c.id
                  WHERE cc.contact_id = %1";

        /*if ($deleted_cases == TRUE) {
          $query .= ' AND c.is_deleted = 1';
        } else {
          $query .= ' AND c.is_deleted = 0';
        }*/

        $params           = array(
          'total' => CRM_Core_DAO::singleValueQuery($query,
            $values
          ),
          'rowCount' => 50,
          'buttonBottom' => 'PagerBottomButton',
          'buttonTop' => 'PagerTopButton',
          'pageID' => $this->get(CRM_Utils_Pager::PAGE_ID)
        );

        $this->_pager = new CRM_Utils_Pager($params);
        $this->assign_by_ref('pager', $this->_pager);
      } catch (Exception $ex) {

      }
    }
  }
}