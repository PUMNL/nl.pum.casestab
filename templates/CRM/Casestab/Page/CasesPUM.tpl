<div class="crm-content-block crm-block">
  <div id="help">If you want to add a project case, please create the case via the Projects tab under the desinated project.</div>
  <div class="action-link">
    <a accesskey="N" href="/civicrm/case/add?reset=1&amp;action=add&amp;cid={$clientId}&amp;context=case" class="button"><span><div class="icon add-icon"></div>Add Case</span></a>
  </div>
  <div id="project_wrapper" class="dataTables_wrapper">
    <table id="project-table" class="display">
      <thead>
        <tr>
          <th class="sorting-disabled" rowspan="1" colspan="1">ID</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Title</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Case type</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Case status</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Case manager</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Start date</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">End date</th>
          <th class="sorting-disabled" rowspan="1" colspan="1">Actions</th>
        </tr>
      </thead>
      <tbody>
        {assign var="rowClass" value="odd-row"}
        {foreach from=$cases item=cas}
            <tr class={$rowClass}>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.case_id}</td>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.subject}</td>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.case_type}</td>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.case_status}</td>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.case_manager}</td>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.start_date}</td>
              <td{if $cas.is_deleted eq 1} class="font-red"{/if}>{$cas.end_date}</td>

              <td>
                <span>
                  {if $cas.is_deleted eq 0}
                  {foreach from=$cas.actions item=actionLink}
                    {$actionLink}
                  {/foreach}
                  {foreach from=$cas.moreActions item=moreActionLink}
                    {$moreActionLink}
                  {/foreach}
                  {/if}
                  {if $cas.is_deleted eq 1}
                  {$cas.restore}
                  {/if}
                </span>
              </td>
            </tr>
          {if $rowClass eq "odd-row"}
            {assign var="rowClass" value="even-row"}
          {else}
            {assign var="rowClass" value="odd-row"}
          {/if}

        {/foreach}
      </tbody>
    </table>
  </div>

  {if $pager and $pager->_response}
    {if $pager->_response.numPages > 1}
        <div class="crm-pager">
          {if ! isset($noForm) || ! $noForm}
            <span class="element-right">
            {if $location eq 'top'}
              {$pager->_response.titleTop}&nbsp;<input class="form-submit" name="{$pager->_response.buttonTop}" value="{ts}Go{/ts}" type="submit"/>
            {else}
              {$pager->_response.titleBottom}&nbsp;<input class="form-submit" name="{$pager->_response.buttonBottom}" value="{ts}Go{/ts}" type="submit"/>
            {/if}
            </span>
          {/if}
          <span style="margin-left:10px;">
          {if !empty($pager->linkTagsRaw.first.url)}
          <input type="submit" onclick="cj('#Cases_PUM').load('{$pager->linkTagsRaw.first.url}&snippet=1')" class="form-submit" style="cursor:pointer;" value="<< First" />&nbsp;
          {/if}
          {if !empty($pager->linkTagsRaw.prev.url)}
          <input type="submit" onclick="cj('#Cases_PUM').load('{$pager->linkTagsRaw.prev.url}&snippet=1')" class="form-submit" style="cursor:pointer;" value="< Previous" />&nbsp;
          {/if}
          {if !empty($pager->linkTagsRaw.next.url)}
          <input type="submit" onclick="cj('#Cases_PUM').load('{$pager->linkTagsRaw.next.url}&snippet=1')" class="form-submit" style="cursor:pointer;" value="Next >" />&nbsp;
          {/if}
          {if !empty($pager->linkTagsRaw.last.url)}
          <input type="submit" onclick="cj('#Cases_PUM').load('{$pager->linkTagsRaw.last.url}&snippet=1')" class="form-submit" style="cursor:pointer;" value="Last >>" />&nbsp;
          {/if}
          {$pager->_response.status}
          </span>

        </div>
    {/if}

    {* Controller for 'Rows Per Page' *}
    {if $location eq 'bottom' and $pager->_totalItems > 25}
     <div class="form-item float-right">
           <label>{ts}Rows per page:{/ts}</label> &nbsp;
           {$pager->_response.twentyfive}&nbsp; | &nbsp;
           {$pager->_response.fifty}&nbsp; | &nbsp;
           {$pager->_response.onehundred}&nbsp;
     </div>
     <div class="clear"></div>
    {/if}

  {/if}
</div>