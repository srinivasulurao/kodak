<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CustomerSearch extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
        $this->attrs['report_page_url'] = new Attribute(getMessage(REPORT_PAGE_LBL), 'STRING', getMessage(PG_DSP_BTN_CLICKED_LEAVE_BLANK_MSG), '/app/answers/list');
        $this->attrs['icon_path'] = new Attribute(getMessage(ICON_PATH_LBL), 'STRING', getMessage(LOCATION_IMAGE_FILE_SEARCH_ICON_LBL), 'images/icons/search.png');
        $this->attrs['alt_text'] = new Attribute(getMessage(ALTERNATIVE_TEXT_MSG), 'STRING', getMessage(TEXT_DISPLAYED_IMAGE_AVAILABLE_MSG), getMessage(SEARCH_CMD));
        $this->attrs['label_hint'] = new Attribute(getMessage(HINT_LABEL_LBL), 'STRING', getMessage(LABEL_DISP_SRCH_FLDS_INIT_HINT_VAL_LBL), getMessage(SEARCH_CMD));
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_PROV_SRCH_FLD_INTENDED_MSG);
    }

    function getData()
    {
        $this->data['js']['email'] = $this->CI->session->getProfileData('email');

        $this->data['js']['isDirect'] = "N";

        if($this->CI->agent->browser() === 'Internet Explorer')
            $this->data['isIE'] = true;

        $this->CI->load->model('custom/custom_org_model');
        $org_id = $this->CI->session->getProfileData('org_id');

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

        if(count($partnerTypes) == 1 && $partnerTypes[0]['Type'] == 'Direct') {
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

        else if(count($partnerTypes) == 2 && $partnerTypes[0]['Type'] == 'Direct') {
          $list['types'] = $partnerTypes;
          $this->data['js']['isDirect'] = "";

          switch($partnerTypes[1]['Type']) {

            case "Direct":
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

            case "Corporate":
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
}

