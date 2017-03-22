<?php /* Originating Release: February 2012 */ ?>
<rn:meta controller_path="custom/search/OrgList2" js_path="custom/search/OrgList2" presentation_css="widgetCss/OrgList2.css"  compatibility_set="November '09+"/>
<div id="rn_<?=$this->instanceID;?>" class="rn_OrgList2">
    <label for="rn_<?=$this->instanceID;?>_Options"><?=$this->data['attrs']['label_title']?></label>
    <select id="rn_<?=$this->instanceID;?>_Options" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>>
            <option value='1' <?=($this->data['js']['defaultIndex'] == 1) ? 'selected = "selected"' : '';?>><?=$this->data['attrs']['label_org']?></option>
            <option value='0' <?=($this->data['js']['defaultIndex'] == 0) ? '' : '';?>><?=$this->data['attrs']['label_individual']?></option>
    </select>
</div>
