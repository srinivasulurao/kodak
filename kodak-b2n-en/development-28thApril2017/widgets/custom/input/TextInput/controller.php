<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('FormInput')) requireWidgetController('custom/input/FormInput');
class TextInput extends FormInput {
function __construct() {
parent::__construct();
$this->attrs['always_show_mask'] = new Attribute(getMessage((128)), 'BOOL', getMessage((2495)), false);
}
function generateWidgetInformation() {
parent::generateWidgetInformation();
$this->info['notes'] = getMessage((3023));
}
function getData() {
if(parent::retrieveAndInitializeData() === false) return false;
if($this->field->data_type !== (7) && $this->field->data_type !== (10) && $this->field->data_type !== (6) && $this->field->data_type !== (8) && $this->field->data_type !== (5)) {
echo $this->reportError(sprintf(getMessage((2022)), $this->fieldName));
return false;
}
if($this->data['js']['mask'] && $this->data['value']) $this->data['value'] = $this->_addMask($this->data['value'], $this->data['js']['mask']);
if(!($this->field instanceof CustomField)) {
if($this->field->data_type === (7)) {
if(!getConfig((4))) return false;
$this->data['value'] = '';
$this->data['js']['passwordLength'] = min(getConfig((94)), 20);
if($this->data['js']['passwordLength'] > 0 && !in_array($this->fieldName, array('password', 'organization_password'), true)) $this->data['attrs']['required'] = true;
}
if(($this->fieldName === 'alt_first_name' || $this->fieldName === 'alt_last_name') && LANG_DIR !== 'ja_JP') {
echo $this->reportError(getMessage((121)));
return false;
}
if($this->fieldName === 'email' && !$this->field->value && $this->CI->session->getSessionData('previouslySeenEmail')) $this->data['value'] = $this->CI->session->getSessionData('previouslySeenEmail');
}
$this->data['js']['contactToken'] = createToken(1);
}
private static function _addMask($value, $mask) {
$j = 0;
$result = '';
for($i = 0;
$i < strlen($mask);
$i+=2) {
while($mask[$i] === 'F') {
$result .= $mask[$i + 1];
$i+=2;
}
$result .= $value[$j];
$j++;
}
return $result;
}

}
