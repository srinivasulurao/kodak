<?php
namespace Custom\Widgets\CIHSearch;

class CustomerSearch extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

       //parent::__construct();
        // $this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
        // $this->attrs['report_page_url'] = new Attribute(getMessage(REPORT_PAGE_LBL), 'STRING', getMessage(PG_DSP_BTN_CLICKED_LEAVE_BLANK_MSG), '/app/answers/list');
        // $this->attrs['icon_path'] = new Attribute(getMessage(ICON_PATH_LBL), 'STRING', getMessage(LOCATION_IMAGE_FILE_SEARCH_ICON_LBL), 'images/icons/search.png');
        // $this->attrs['alt_text'] = new Attribute(getMessage(ALTERNATIVE_TEXT_MSG), 'STRING', getMessage(TEXT_DISPLAYED_IMAGE_AVAILABLE_MSG), getMessage(SEARCH_CMD));
        // $this->attrs['label_hint'] = new Attribute(getMessage(HINT_LABEL_LBL), 'STRING', getMessage(LABEL_DISP_SRCH_FLDS_INIT_HINT_VAL_LBL), getMessage(SEARCH_CMD));

    }

    function getData() {
        $this->data['js']['email'] = $this->CI->session->getProfileData('email');
        $this->data['js']['isDirect'] = "N";
        $lang = get_instance()->session->getSessionData("lang");
        switch ($lang) {
        case "en":
            $ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
            break;
        case "fr":
            $ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
            break;
        case "es":
            $ccih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
            break;
        case "pt":
            $ccih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
            break;
        default:
            $ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
            break;
        }                       
        $this->data['js']['loadingmessage'] = $ccih_lang_msg_base_array['loadingmessage'];
        $this->data['js']['direct'] = $ccih_lang_msg_base_array['direct'];
        $this->data['js']['corporate'] = $ccih_lang_msg_base_array['corporate'];
        $this->data['js']['ibaseupdate'] = $ccih_lang_msg_base_array['ibaseupdate'];
        $this->data['js']['repairrequest'] = $ccih_lang_msg_base_array['repairrequest'];
        $this->data['js']['managecontacts'] = $ccih_lang_msg_base_array['managecontacts'];
        $this->data['js']['knowledgesearch'] = $ccih_lang_msg_base_array['knowledgesearch'];
        $this->data['js']['selectsiterecord'] = $ccih_lang_msg_base_array['selectsiterecord'];
        $this->data['js']['sitecustid'] = $ccih_lang_msg_base_array['sitecustid'];    
        $this->data['js']['mysites_name'] = $ccih_lang_msg_base_array['mysites_name'];
        $this->data['js']['mysites_street'] = $ccih_lang_msg_base_array['mysites_street'];
        $this->data['js']['mysites_city'] = $ccih_lang_msg_base_array['mysites_city'];
        $this->data['js']['mysites_postalcode'] = $ccih_lang_msg_base_array['mysites_postalcode'];
        $this->data['js']['mysites_province'] = $ccih_lang_msg_base_array['mysites_province'];
        $this->data['js']['mysites_country'] = $ccih_lang_msg_base_array['mysites_country'];
        $this->data['js']['mysites_action'] = $ccih_lang_msg_base_array['mysites_action'];
        $this->data['js']['prodidentifier'] = $ccih_lang_msg_base_array['prodidentifier'];
        $this->data['js']['prodidentifierdetails'] = $ccih_lang_msg_base_array['prodidentifierdetails'];
        $this->data['js']['serialnumber'] = $ccih_lang_msg_base_array['serialnumber'];
        $this->data['js']['pic_proddescription'] = $ccih_lang_msg_base_array['pic_proddescription'];
        $this->data['js']['pic_entitlement'] = $ccih_lang_msg_base_array['pic_entitlement'];
        $this->data['js']['pic_contractstart'] = $ccih_lang_msg_base_array['pic_contractstart'];
        $this->data['js']['pic_contractend'] = $ccih_lang_msg_base_array['pic_contractend'];
        $this->data['js']['pic_name'] = $ccih_lang_msg_base_array['pic_name'];
        $this->data['js']['pid_action'] = $ccih_lang_msg_base_array['pid_action'];
        $this->data['js']['pide_product'] = $ccih_lang_msg_base_array['pide_product'];
        $this->data['js']['pide_coveragetime'] = $ccih_lang_msg_base_array['pide_coveragetime'];
        $this->data['js']['pide_prioritizedresponse'] = $ccih_lang_msg_base_array['pide_prioritizedresponse'];
        $this->data['js']['pide_contractstart'] = $ccih_lang_msg_base_array['pide_contractstart'];
        $this->data['js']['pide_contractend'] = $ccih_lang_msg_base_array['pide_contractend'];
        $this->data['js']['pide_contracttype'] = $ccih_lang_msg_base_array['pide_contracttype'];
        $this->data['js']['pide_contractstatus'] = $ccih_lang_msg_base_array['pide_contractstatus'];
        $this->data['js']['pide_contractid'] = $ccih_lang_msg_base_array['pide_contractid'];
        $this->data['js']['pidsi_floorbldg'] = $ccih_lang_msg_base_array['pidsi_floorbldg'];
        $this->data['js']['pidsi_entrancedoor'] = $ccih_lang_msg_base_array['pidsi_entrancedoor'];
        $this->data['js']['pidsi_addaddress'] = $ccih_lang_msg_base_array['pidsi_addaddress'];
        $this->data['js']['pidscpi_kodakcustnum'] = $ccih_lang_msg_base_array['pidscpi_kodakcustnum'];
        $this->data['js']['pidscpi_customername'] = $ccih_lang_msg_base_array['pidscpi_customername'];
        $this->data['js']['pidscpi_address'] = $ccih_lang_msg_base_array['pidscpi_address'];

        if($this->CI->agent->browser() === 'Internet Explorer')
            $this->data['isIE'] = true;

        $this->CI->load->model('custom/custom_org_model');
        $org_id = $this->CI->session->getProfileData('org_id');
    if($org_id < 0) $org_id = 0;

        $list = array('sitename'=> 'test');

        //returns array of [ID,TYPE]
        $partnerTypes = $this->CI->custom_org_model->getPartnerTypeList($org_id);


        $this->CI->load->model('custom/custom_contact_model');
        $role_id = $this->CI->custom_contact_model->getLoggedInUsersRole();

        $internal = $this->CI->custom_contact_model->checkInternalContact($this->CI->session->getProfileData('c_id'));

        $this->data['js']['internal_user'] = $internal;

        $this->data['js']['roleID'] = $role_id;

        $this->CI->load->model('custom/roles_model');
        $this->data['js']['role_functions'] = $this->CI->roles_model->getRole2FunctionsByRoleID($role_id);

        //if only Direct type is returned, do default search

        if(count($partnerTypes) == 1 && $partnerTypes[0]['Type'] == $ccih_lang_msg_base_array['direct']) {
          $list['types'] = $partnerTypes;
          $this->data['js']['isDirect'] = "Y";
          $this->data['js']['defaultPartnerSearch'] = "00000002";

          $partner_type = "00000002";  //value for direct
          $sap_partner_id = $partnerTypes[0]['ID'];
          $list['sapid'] = $sap_partner_id;
          $this->CI->load->model('custom/ibase_product_model');
          $site_list = $this->CI->ibase_product_model->getIbaseList("shipto", $partner_type, $sap_partner_id,'', '',  '', '', '', '', '', '', $internal);

          $site_list['status'] = 1;
          $this->data['js']['direct_site_data'] = $site_list;

        }

        else if(count($partnerTypes) == 2 && $partnerTypes[0]['Type'] == $ccih_lang_msg_base_array['direct']) {
          $list['types'] = $partnerTypes;
          $this->data['js']['isDirect'] = "";

          switch($partnerTypes[1]['Type']) {


            case $ccih_lang_msg_base_array['direct']:
              $partner_type = "00000002";
              break;

            case "Enabling Partner":
              $partner_type = "ZENABPRN";
              break;

            case "Manufacturing Partner":
              $partner_type = "ZMVSMFG";
              break;

            case "Service Distributor":
              $partner_type = "ZSVCDIST";
              break;

            case "Service Reseller":
              $partner_type = "ZSVCRESL";
              break;


            case $ccih_lang_msg_base_array['corporate']:
              $partner_type = "ZCORPACC";
              break;

          }
          
          $sap_partner_id = $partnerTypes[1]['ID'];
          $list['sapid'] = $sap_partner_id;
         
          //2012.10.17 scott harris: kodak does not want auto search when not direct
          /* 
          $this->CI->load->model('custom/ibase_product_model');
          $site_list = $this->CI->ibase_product_model->getIbaseList("shipto", $partner_type, $sap_partner_id, '', '', '', '', '', '');

          $site_list['status'] = 1;
          $this->data['js']['direct_site_data'] = $site_list;
          */
          $this->data['js']['defaultPartnerSearch'] = $partner_type;
        }

    }


    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_PROV_SRCH_FLD_INTENDED_MSG);
    }

    
}