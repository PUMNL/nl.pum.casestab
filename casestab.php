<?php

require_once 'casestab.civix.php';
use CRM_Casestab_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function casestab_civicrm_config(&$config) {
  _casestab_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function casestab_civicrm_xmlMenu(&$files) {
  _casestab_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function casestab_civicrm_install() {
  _casestab_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function casestab_civicrm_postInstall() {
  _casestab_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function casestab_civicrm_uninstall() {
  _casestab_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function casestab_civicrm_enable() {
  _casestab_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function casestab_civicrm_disable() {
  _casestab_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function casestab_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _casestab_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function casestab_civicrm_managed(&$entities) {
  _casestab_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function casestab_civicrm_caseTypes(&$caseTypes) {
  _casestab_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function casestab_civicrm_angularModules(&$angularModules) {
  _casestab_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function casestab_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _casestab_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function casestab_civicrm_entityTypes(&$entityTypes) {
  _casestab_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function casestab_civicrm_themes(&$themes) {
  _casestab_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function casestab_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function casestab_civicrm_navigationMenu(&$menu) {
  _casestab_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _casestab_civix_navigationMenu($menu);
} // */


/**
 * Implementation of hook civicrm_tabs
 * to add a contact segment tab to the contact summary
 *
 * @param array $tabs
 * @param int $contactID
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_tabs
 */
function casestab_civicrm_tabs(&$tabs, $contactID) {
  $weight = 0;
  foreach ($tabs as $tabId => $tab) {
    if ($tab['id'] == 'case') {
      $weight = $tab['weight']++;
    }
  }
  $count = civicrm_api('Case', 'getcount', array('version' => 3,'sequential' => 1,'contact_id' => $contactID, 'is_active' => 1));

  $tabs[] = array(
    'id'      => 'casesPUM',
    'url'     => CRM_Utils_System::url('civicrm/casespum', 'snippet=1&cid='.$contactID),
    'title'   => 'Cases PUM',
    'weight'  => $weight,
    'count'   => $count);

  // remove little emtpy tab, don't know where it comes from, but not from this extension
  foreach($tabs as $key => $tab) {
    if($tab['id'] == 'case'){
      unset($tabs[$key]);
    }
    if (is_null($tabs[$key])) {
      unset($tabs[$key]);
    }
  }
}