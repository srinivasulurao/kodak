<rn:meta controller_path="custom/input/ROSelectionInput" 
    js_path="custom/input/ROSelectionInput" 
    base_css="custom/input/ROSelectionInput" 
    presentation_css="widgetCss/SelectionInput.css" 
    compatibility_set="November '09+"
    required_js_module="november_09,mobile_may_10"/>

<? if($this->data['readOnly']):?>
<rn:widget path="output/FieldDisplay" left_justify="true"/>
<? else:?>

<div id="rn_<?=$this->instanceID;?>" class="rn_SelectionInput">

<span class="rn_Hidden">
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> disabled="disabled" class="rn_Hidden">
>
    <? if(!$this->data['hideEmptyOption']):?>
        <option value="">--</option>
    <? endif;?>
    <? if(is_array($this->data['menuItems'])):?>
        <? foreach($this->data['menuItems'] as $key => $item):
             $selected = '';
             if($key==$this->data['value']) $selected = 'selected="selected"';?>
            <option value="<?=$key;?>" <?=$selected;?>><?=$item;?></option>
        <? endforeach;?>
    <? endif;?>
    </select>
</span>
</div>

<? endif;?>
