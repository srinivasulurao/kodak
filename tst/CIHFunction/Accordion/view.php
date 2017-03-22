<rn:meta controller_path="custom/CIHFunction/Accordion" 
         js_path="custom/CIHFunction/Accordion" 
         presentation_css="widgetCss/Accordion.css" 
         compatibility_set="November '09+" 
         required_js_module="mobile_may_10,november_09"/>
<div id="rn_<?=$this->instanceID;?>_header" class="rn_Accordion_header">		
	<div id="rn_<?=$this->instanceID;?>_header_label" class="rn_Accordion_header_label"><?=$this->data['attrs']['label_header']?></div>
	<div id="rn_<?=$this->instanceID;?>_trigger" class="rn_Accordion_trigger rn_Accordion_panel_up <?=$this->data['attrs']['expanded'] == true ? $this->data['attrs']['expanded_css_class'] : $this->data['attrs']['collapsed_css_class'] ?>">&nbsp;&nbsp;</div>
</div>  