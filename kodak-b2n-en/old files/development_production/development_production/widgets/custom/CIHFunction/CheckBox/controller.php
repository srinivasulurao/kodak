<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CheckBox extends Widget
{
    /*
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();

        $this->attrs['name']  = new Attribute(msg_get_rnw(FIELD_NAME_LBL), 'STRING', msg_get_rnw(COLUMN_NAME_OF_A_FORM_FIELD_LBL), '');
        $this->attrs['value'] = new Attribute('Value', 'STRING', 'Value to store in the field.', '');
        $this->attrs['required'] = new Attribute(getMessage(REQUIRED_LBL), 'BOOL', getMessage(SET_TRUE_FLD_CONT_VAL_CF_SET_REQD_MSG), false);
        $this->attrs['label_required'] = new Attribute(getMessage(REQUIRED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_REQUIREMENT_MESSAGE_LBL), getMessage(PCT_S_IS_REQUIRED_MSG));
        $this->attrs['checked'] = new Attribute('Checked', 'BOOL', 'Checked by default', true);
        $this->attrs['help_text'] = new Attribute('Value', 'STRING', 'Value to display on hover over.', '');
    }

    /*
    * Generate widget information
    */
    public function generateWidgetInformation()
    {
        $this->info['form']  = FORM;
        $this->info['type']  = 'Mixed';
        $this->info['notes'] = 'Text CheckBox allows you to set a Boolean value for a field.';
    }

    /*
    * Get data
    */
    public function getData()
    {
        $this->data['js']['value'] = $this->data['attrs']['value'];
    }
}
