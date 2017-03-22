<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class HiddenInput extends Widget
{
    /*
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();

        $this->attrs['name']  = new Attribute(msg_get_rnw(FIELD_NAME_LBL), 'STRING', msg_get_rnw(COLUMN_NAME_OF_A_FORM_FIELD_LBL), '');
        $this->attrs['value'] = new Attribute('Value', 'STRING', 'Value to store in the hidden field.', '');
    }

    /*
    * Generate widget information
    */
    public function generateWidgetInformation()
    {
        $this->info['form']  = FORM;
        $this->info['type']  = 'Mixed';
        $this->info['notes'] = 'Hidden input allows you to submit hidden values that are not tied to the database to a submission controller.';
    }

    /*
    * Get data
    */
    public function getData()
    {
        $this->data['js']['value'] = $this->data['attrs']['value'];
    }
}
