<?php
namespace Custom\Widgets\search;

class OrgList2 extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
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
        return parent::getData();

    }
}