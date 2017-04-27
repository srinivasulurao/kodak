<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class FormInput extends Widget {
protected $field;
protected $table;
protected $fieldName;
function __construct() {
parent::__construct();
$this->attrs['label_input'] = new Attribute(getMessage((1275)), 'STRING', getMessage((1396)), '{default_label}');
$this->attrs['label_required'] = new Attribute(getMessage((2322)), 'STRING', getMessage((1418)), getMessage((1989)));
$this->attrs['name'] = new Attribute(getMessage((3622)), 'STRING', getMessage((374)), '');
$this->attrs['required'] = new Attribute(getMessage((5883)), 'BOOL', getMessage((2493)), false);
$this->attrs['hint'] = new Attribute(getMessage((8441)), 'STRING', getMessage((1160)), '');
$this->attrs['always_show_hint'] = new Attribute(getMessage((127)), 'BOOL', getMessage((2494)), false);
$this->attrs['initial_focus'] = new Attribute(getMessage((1270)), 'BOOL', getMessage((2492)), false);
$this->attrs['validate_on_blur'] = new Attribute(getMessage((2944)), 'BOOL', getMessage((2945)), false);
$this->attrs['always_show_mask'] = new Attribute(getMessage((128)), 'BOOL', getMessage((2495)), false);
$this->attrs['default_value'] = new Attribute(getMessage((26791)), 'STRING', getMessage((613)), '');
$this->attrs['allow_external_login_updates'] = new Attribute(getMessage((104)), 'BOOL', getMessage((115)), false);
$this->attrs['hide'] = new Attribute("Hide", 'BOOL', "Make element hidden", false);
}
function generateWidgetInformation() {
$this->info['notes'] = sprintf(getMessage((3024)), 'name', 'name');
$this->parms['i_id'] = new UrlParam(getMessage((9348)), 'i_id', false, getMessage((1227)), 'i_id/7');
}
function getData() {
if($this->retrieveAndInitializeData() === false) return false;
if($this->field->data_type === (9)) {
echo $this->reportError(sprintf(getMessage((1983)), $this->fieldName));
return false;
}
if($this->field->data_type === (11)) {
echo $this->reportError(sprintf(getMessage((1982)), $this->fieldName));
return false;
}
}
protected function retrieveAndInitializeData() {
$cacheKey = 'Input_' . $this->data['attrs']['name'];
$cacheResults = checkCache($cacheKey);
if(is_array($cacheResults)) {
list($this->field, $this->table, $this->fieldName, $this->data) = $cacheResults;
$this->field = unserialize($this->field);
return;
}
$this->data['attrs']['name'] = strtolower($this->data['attrs']['name']);
$validAttributes = parseFieldName($this->data['attrs']['name'], true);
if(!is_array($validAttributes)) {
echo $this->reportError($validAttributes);
return false;
}
$this->table = $validAttributes[0];
$this->fieldName = $validAttributes[1];
$this->field = getBusinessObjectField($this->table, $this->fieldName, $isProfileField);
if(is_string($this->field)) {
echo $this->reportError($this->field);
return false;
}
if(is_null($this->field)) return false;
$this->data['js']['type'] = $this->field->data_type;
$this->data['js']['table'] = $this->table;
$this->data['js']['name'] = $this->fieldName;
if($isProfileField === true) $this->data['js']['profile'] = true;
$this->data['readOnly'] = $this->field->readonly;
if($this->field->readonly) {
echo $this->reportError(sprintf(getMessage((2007)), $this->fieldName));
return false;
}
$this->data['js']['mask'] = $this->field->mask;
if($this->field->menu_items) $this->data['menuItems'] = $this->field->menu_items;
if(!is_null($this->field->max_val)) $this->data['js']['maxVal'] = $this->field->max_val;
if(!is_null($this->field->min_val)) $this->data['js']['minVal'] = $this->field->min_val;
if($this->data['attrs']['label_input'] === '{default_label}') $this->data['attrs']['label_input'] = $this->field->lang_name;
if($this->field->field_size) {
$this->data['maxLength'] = $this->field->field_size;
if($this->field->data_type === (5)) $this->data['maxLength']++;
else if($this->field->data_type === (6)) $this->data['js']['fieldSize'] = $this->field->field_size;
}
if($this->field instanceof CustomField) {
if(((($this->field->visibility & (0x00000020)) == false) && $this->CI->page === getConfig((31))) || ((($this->field->visibility & (0x00000004)) == false) && (!($this->CI->page === getConfig((31)))))) {
echo $this->reportError(sprintf(getMessage((2007)), $this->fieldName));
return false;
}
$this->data['js']['name'] = preg_replace('(^c\$)', '', $this->fieldName);
$this->data['js']['customID'] = $this->field->custom_field_id;
if($this->field->lang_hint && strlen(trim($this->field->lang_hint))) $this->data['js']['hint'] = $this->field->lang_hint;
$this->data['attrs']['required'] = ($this->field->required === 1) ? true : $this->data['attrs']['required'];
if(($this->field->data_type === (8)) && ($this->field->attr & (0x0001))) $this->data['js']['url'] = true;
if(($this->field->data_type === (8)) && ($this->field->attr & (0x0002))) $this->data['js']['email'] = true;
}
else if($this->field instanceof ChannelField) {
$this->data['js']['name'] = str_replace('$', '', $this->fieldName);
$this->data['js']['channelID'] = $this->field->id;
}
elseif($this->field->readonly) {
return false;
}
if($this->data['attrs']['hint'] && strlen(trim($this->data['attrs']['hint']))){
$this->data['js']['hint'] = $this->data['attrs']['hint'];
}
$this->data['value'] = $this->setFieldValue();
if($this->field->data_type !== (7)){
$this->data['js']['prev'] = $this->data['value'];
}
if($this->table === 'contacts' && isLoggedIn()) {
if($this->CI->page === getConfig((31)) || (getConfig((174)) && $this->fieldName === 'login') || (isPta() && (!$this->data['attrs']['allow_external_login_updates'] || $this->fieldName === 'login'))) {
$this->data['readOnly'] = true;
}
}
setCache($cacheKey, array(serialize($this->field), $this->table, $this->fieldName, $this->data));
}
private function setFieldValue() {
$fieldValue = null;
$valueSpecifiedInUrl = getUrlParm($this->data['attrs']['name']);
$valueSpecifiedInPost = $this->CI->input->post(str_replace(".", "_", $this->data['attrs']['name']));
$dynamicDefaultValue = '';
if($valueSpecifiedInPost !== false && $valueSpecifiedInPost !== '') $dynamicDefaultValue = str_replace("'", '&#039;', str_replace('"', '&quot;', $valueSpecifiedInPost));
else if($valueSpecifiedInUrl !== null && $valueSpecifiedInUrl !== '') $dynamicDefaultValue = $valueSpecifiedInUrl;
else if($this->data['attrs']['default_value'] !== '') $dynamicDefaultValue = $this->data['attrs']['default_value'];
if($this->field instanceof CustomField && $this->field->default_value !== null && ((string)$this->field->value === (string)$this->field->default_value) && $dynamicDefaultValue !== '') {
$this->field->value = null;
}
if($this->field->value !== null && !is_array($this->field->value)){
$fieldValue = htmlspecialchars($this->field->value, ENT_QUOTES, 'UTF-8', false);
}
else if($dynamicDefaultValue !== ''){
$fieldValue = $dynamicDefaultValue;
}
return $fieldValue;
}
}
