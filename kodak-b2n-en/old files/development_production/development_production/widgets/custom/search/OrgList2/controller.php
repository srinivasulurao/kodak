<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class OrgList2 extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['report_id'] = new Attribute(getMessage(REPORT_ID_LC_LBL), 'INT', getMessage(ID_RPT_DISP_DATA_SEARCH_RESULTS_MSG), 159);
        $this->attrs['report_id']->min = 1;
        $this->attrs['report_id']->optlistId = OPTL_CURR_INTF_PUBLIC_REPORTS;
        $this->attrs['label_title'] = new Attribute(getMessage(TITLE_LABEL_UC_LBL), 'STRING', getMessage(STRING_LABEL_TO_DISPLAY_LBL), getMessage(SHOW_INCIDENTS_CMD));
        $this->attrs['label_sub'] = new Attribute(getMessage(LABEL_SUBSIDIARIES_LBL), 'STRING', getMessage(STRING_LABEL_DISPLAY_SUBSIDIARIES_LBL), getMessage(MY_ORGANIZATION_AND_SUBSIDIARIES_LBL));
        $this->attrs['label_org'] = new Attribute(getMessage(LABEL_ORGANIZATION_LBL), 'STRING', getMessage(STRING_LABEL_DISPLAY_ORGANIZATION_LBL), getMessage(FROM_ANYONE_IN_MY_ORGANIZATION_LBL));
        $this->attrs['label_individual'] = new Attribute(getMessage(LABEL_INDIVIDUAL_LBL), 'STRING', getMessage(STRING_LABEL_DISPLAY_INDIVIDUAL_LBL), getMessage(ONLY_MY_INCIDENTS_LBL));
        $this->attrs['display_type'] = new Attribute(getMessage(DISPLAY_TYPE_CMD), 'INT', getMessage(SPECIFIES_INC_VIEW_END_LOGGED_VAL_1_MSG), 2);
        $this->attrs['display_type']->min = 1;
        $this->attrs['display_type']->max = 2;
        $this->attrs['search_on_select'] = new Attribute(getMessage(SEARCH_ON_SELECTED_CMD), 'BOOL', getMessage(START_SEARCH_SOON_ITEM_IS_SELECTED_MSG), false);
        $this->attrs['report_page_url'] = new Attribute(getMessage(REPORT_PAGE_LBL), 'STRING', getMessage(PG_DISP_ITEM_SEL_SRCH_SEL_SET_TRUE_MSG), '');
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_DISP_DROPDOWN_MENU_ALLOWS_MSG);
        $this->parms['org'] = new UrlParam(getMessage(ORGANIZATION_TYPE_LBL), 'org', false, getMessage(SETS_ORG_TYPE_URL_PARAMETER_VALUE_LBL), 'org/2');
    }

    function getData()
    {
        $profile = $this->CI->session->getProfile();
        if ($profile->org_id->value <= 0)
        {
            // no organization associated to contact
            // nothing to see here move along
            return false;
        }
        if ($this->data['attrs']['report_page'] == '{current_page}')
            $this->data['attrs']['report_page'] = '';
        $level = getConfig(MYQ_VIEW_ORG_INCIDENTS);
        if ($level < $this->data['attrs']['display_type'])
        {
            echo $this->reportError(getMessage(WARN_CFG_MYQ_VIEW_ORG_INC_SET_EQ_MSG));
            return false;
        }
        $this->data['js']['defaultIndex'] = (getUrlParm('org') != null) ? getUrlParm('org') : 0;
        $this->data['js']['org_id'] = $profile->org_id->value;
        require_once(DOCROOT.'/views/view_utils.phph');
        $incidentAlias = view_tbl2alias($this->data['attrs']['report_id'], TBL_INCIDENTS);
        $organizationAlias = view_tbl2alias($this->data['attrs']['report_id'], TBL_ORGS);

        // 0 - individual
        // 1 = organization
        // 2 - organization and subsidiaries
        $this->data['js']['0'] = array('fltr_id' => $incidentAlias.'.c_id',
                                       'val' => $profile->c_id->value,
                                       'oper_id' => 1
                                       );

        if ($incidentAlias)
        {
            $this->data['js']['1']= array('fltr_id' => $incidentAlias.'.org_id',
                                          'val' => $profile->org_id->value,
                                          'oper_id' => 1
                                          );
        }
        else
        {
            echo $this->reportError(sprintf(getMessage(INCIDENTS_TABLE_REPORT_PCT_D_MSG), $this->data['attrs']['report_id']));
            return false;
        }
        if ($incidentAlias && $organizationAlias)
        {
            $lvl = ($profile->o_lvlN->value) ? $profile->o_lvlN->value : 1;
            $this->data['js']['2'] = array('fltr_id' => $organizationAlias.'.lvl'.$lvl .'_id',
                                           'val' => $profile->org_id->value,
                                           'oper_id' => 1
                                           );
        }
        else
        {
            echo $this->reportError(sprintf(getMessage(ORGANIZATION_TABLE_REPORT_PCT_D_MSG), $this->data['attrs']['report_id']));
            return false;

        }
        $this->data['js']['rnSearchType'] = 'org';
        $this->data['js']['searchName'] = 'org';
    }
}
