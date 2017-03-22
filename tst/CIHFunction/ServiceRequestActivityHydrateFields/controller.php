<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ServiceRequestActivityHydrateFields extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['report_id'] = new Attribute(getMessage(REPORT_ID_LBL), 'INT', getMessage(ID_RPT_DISP_DATA_SEARCH_RESULTS_MSG), null);
		$this->attrs['report_id']->min = 1;
		$this->attrs['report_id']->optlistId = OPTL_CURR_INTF_PUBLIC_REPORTS;
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_PROV_SRCH_FLD_INTENDED_MSG);
    }

    function getData()
    {
		$sesslang = get_instance()->session->getSessionData("lang");
		switch ($sesslang) {
        case "en":
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
        $this->data['js']['direct'] = $ccih_lang_msg_base_array['direct'];

    }
}

