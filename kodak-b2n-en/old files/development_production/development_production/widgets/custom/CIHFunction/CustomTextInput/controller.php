<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CustomTextInput extends Widget
{
    /*
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();

        $this->attrs['name']  = new Attribute(msg_get_rnw(FIELD_NAME_LBL), 'STRING', msg_get_rnw(COLUMN_NAME_OF_A_FORM_FIELD_LBL), '');
		$this->attrs['label_input']  = new Attribute( 'Field Name','STRING', 'Friendly name of the field', '');
        $this->attrs['value'] = new Attribute('Value', 'STRING', 'Value to store in the field.', '');
		$this->attrs['width'] = new Attribute('Width', 'STRING', 'Width of the input box.', '');
		$this->attrs['required'] = new Attribute(getMessage(REQUIRED_LBL), 'BOOL', getMessage(SET_TRUE_FLD_CONT_VAL_CF_SET_REQD_MSG), false);
		$this->attrs['label_required'] = new Attribute(getMessage(REQUIRED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_REQUIREMENT_MESSAGE_LBL), getMessage(PCT_S_IS_REQUIRED_MSG));
		$this->attrs['is_search_filter'] = new Attribute('Search Filter', 'BOOL', 'Field is being used as a search filter', false);
		$this->attrs['search_filer_id'] = new Attribute('Filter ID', 'INT', 'Search filter id.', null);
		$this->attrs['search_report_id'] = new Attribute('Report ID', 'INT', 'Search report id.', null);
		$this->attrs['search_operator_id'] = new Attribute('Operator ID', 'INT', 'Search operator id.', null);
		$this->attrs['readonly'] = new Attribute('Read only', 'BOOL', 'Field is read only', false);
				
		
    }

    /*
    * Generate widget information
    */
    public function generateWidgetInformation()
    {
        $this->info['form']  = FORM;
        $this->info['type']  = 'Mixed';
        $this->info['notes'] = 'Text input allows you to submit values that are not tied to the database to a submission controller.';
    }

    /*
    * Get data
    */
    public function getData()
    {
        $this->data['js']['value'] = $this->data['attrs']['value'];
    }
}
