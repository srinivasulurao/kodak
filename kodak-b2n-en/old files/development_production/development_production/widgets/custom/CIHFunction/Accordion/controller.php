<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accordion extends Widget {
    function __construct() {
        parent::__construct();
        $this->attrs['toggle'] = new Attribute(getMessage(TOGGLE_LBL), 'STRING', getMessage(ID_HTML_ELEMENT_PRESSED_TOGGLES_LBL), '');
        $this->attrs['item_to_toggle'] = new Attribute(getMessage(ITEM_TO_TOGGLE_LBL), 'STRING', getMessage(ID_HTML_ELMENT_HIDDEN_SHOWN_TOGGLE_MSG), '');
        $this->attrs['expanded_css_class'] = new Attribute(getMessage(EXPANDED_CSS_CLASS_LBL), 'STRING', getMessage(CSS_CLASS_TOGGLE_ELEMENT_EXPANDED_MSG), 'rn_Expanded');
        $this->attrs['collapsed_css_class'] = new Attribute(getMessage(COLLAPSED_CSS_CLASS_LBL), 'STRING', getMessage(CSS_CLASS_TOGGLE_ELEMENT_COLLAPSED_MSG), 'rn_Collapsed');
        $this->attrs['label_collapsed'] = new Attribute(getMessage(COLLAPSED_LABEL_LBL), 'STRING', getMessage(LABEL_PLACED_TOGGLE_ELEMENT_NOTIF_MSG), getMessage(SECTION_CLOSED_CLICK_DOUBLE_TAP_LBL));
        $this->attrs['label_expanded'] = new Attribute(getMessage(EXPANDED_LABEL_LBL), 'STRING', getMessage(LBEL_PLACED_TOGGLE_ELEMENT_NOTIF_MSG), getMessage(SECT_OPENED_CLICK_DOUBLE_TAP_CLOSE_LBL));
        $this->attrs['label_header'] = new Attribute('Accordion header label', 'STRING', '', '');
        $this->attrs['name'] = new Attribute('Panel name','STRING','','');
        $this->attrs['visible'] = new Attribute('Is the panel visible','BOOL','',True);
        $this->attrs['expanded'] = new Attribute('Is the panel visible','BOOL','',False);
		
    }

    function generateWidgetInformation() {
        $this->info['notes'] = getMessage(WIDGET_HANDLES_FUNCTIONALITY_MSG);
    }

    function getData() {
    }
}
