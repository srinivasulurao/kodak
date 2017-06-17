<rn:meta controller_path="standard/input/FormInput" 
    presentation_css="widgetCss/FormInput.css" 
    compatibility_set="November '09+" 
    required_js_module="november_09,mobile_may_10"/>
<? switch($this->field->data_type): case (4): case (12): case (3):?>
        <rn:widget path="input/SelectionInput"/>
        <? break;
case (2): case (1):?>
        <rn:widget path="input/DateInput"/>
        <? break;
default:?>
        <rn:widget path="custom/input/TextInput"/>
        <? break;
endswitch;?>
