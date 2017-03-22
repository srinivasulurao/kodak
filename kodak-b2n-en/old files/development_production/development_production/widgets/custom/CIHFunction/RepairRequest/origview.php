<rn:meta controller_path="custom/CIHFunction/RepairRequest" js_path="custom/CIHFunction/RepairRequest" base_css="custom/CIHFunction/RepairRequest" presentation_css="widgetCss/FormPanel.css"/>
<?
    $CI = get_instance();	$sesslang = $CI->session->getSessionData("lang");	if ($sesslang == "fr")		$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php");	else $cih_lang_msg_base_array=load_array("csv_cih_english_strings.php");  
    $CI->load->model('standard/Contact_model');
    $contact = $CI->Contact_model->get($CI->session->getProfileData("c_id"));
?>
        <div id="rn_<?=$this->instanceID;?>_container" class="rn_FormPanel">
            <div id="panelContent" class="rn_Accordion_content">
            <!-- Panel content goes here -->
            <div id="rn_<?=$this->instanceID;?>_ErrorLocation"></div>
            <form id="rn_<?=$this->instanceID;?>_form" method="post" action="" onsubmit="return false;">
<div id="rn_<?=$this->instanceID;?>_ppErrorMessage" class="rn_ppErrorMessage rn_Hidden"></div>

            <table style="width:100%">
                <tr>
                    <td colspan="3">
                        <h2><? echo $cih_lang_msg_base_array['contactdetails']; ?></h2>
                    </td>
                </tr>
                <tr>
                    <td  valign="top" >
                                                                
                    </td>
                    <td >
                        &nbsp;
                    </td>
                    <td valign="top">
                        <div id="rn_<?=$this->instanceID;?>_createContact" class="rn_Hidden">
                            <span>- or -</span>&nbsp;&nbsp;&nbsp;<button id="rn_<?=$this->instanceID;?>_createContactButton" style="margin-top:10px;">New Contact</button>
                        </div>
                    
                        <table cellspacing="2" >
                            <tr>
                                <td colspan="2">
                                    <span><? echo $cih_lang_msg_base_array['cd_personkodaktocontact']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/ContactSelect" name="c_id" include_deactivated="false" panel_name="#rn:php:$this->data['attrs']['panel_name']#"/>   
                                </td>
                            </tr>                       
                        </table>

                        <table style="width:100%" cellspacing="2" id="rn_<?=$this->instanceID;?>_contactForm">
                            <tr>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_firstname']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="firstname" label_input="First Name" value="" required="true" />
                                </td>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_lastname']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="lastname" label_input="Last Name" value="" required="true"/>
                                </td>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_email']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="emailaddress" label_input="Email Address" value="" required="true"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_officetel']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="officephone" label_input="Office Phone" value="" required="true" />
                                </td>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_mobiletel']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="mobilephone" value="" />
                                </td>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_faxtel']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="faxnumber" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_preflang1']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref1" name="language1" required="true" label_input="Preferred Language 1" />
                                </td>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_preflang2']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref2" name="language2" />
                                </td>
                                <td>
                                    <span><? echo $cih_lang_msg_base_array['cd_preflang3']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref3" name="language3" />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['cd_optinglobal']; ?></span>
                                    <rn:widget path="custom/CIHFunction/CheckBox" name="optinglobal" checked="false" value="" />&nbsp;&nbsp;
                                </td>
                                <td nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['cd_optinincident']; ?></span>
                                    <rn:widget path="custom/CIHFunction/CheckBox" name="optinincident" checked="false" value="" />&nbsp;&nbsp;
                                </td>
                                <td nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['cd_optinsurvey']; ?></span>
                                    <rn:widget path="custom/CIHFunction/CheckBox" name="optincisurvey" checked="false" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                <span><? echo $cih_lang_msg_base_array['cd_country']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_country_safe_harbor" name="country" label_input="Country" required="true" />
                                </td>
                            </tr>
                        </table>
                                                <table cellspacing="2" >
                                                        <tr>
                                                                <td>
                                                                  <span><strong><? echo $cih_lang_msg_base_array['cd_personsubmittinginc']; ?></strong></span><br/>
                                                                  <div class="rn_Hidden">
                                                                    <rn:widget path="standard/input/FormInput" name="incidents.c$orig_submit_id" default_value="#rn:php:$contact->c_id->value#"/>
                                                                    <rn:widget path="standard/input/FormInput" name="incidents.c$orig_submit_name" default_value="#rn:php:$contact->full_name->value#"/>
                                                                  </div>
                                                                  <rn:widget path="output/FieldDisplay" name="contacts.email" label="" left_justify="true" />
                                                                </td>
                                                                <td nowrap="nowrap" valign="bottom">
                                                                        &nbsp;&nbsp;<rn:widget path="custom/CIHFunction/CheckBox" name="secondarycontact" value="" checked="true" help_text="If you would like to receive email updates when this incident is open and closed, please check this box." /><span><? echo $cih_lang_msg_base_array['cd_checkemailnotif']; ?></span>
                                                                </td>
                                                        </tr>
                                                </table>

                                                <table style="visibility:hidden" id="rn_<?=$this->instanceID;?>_disableContactForm" >
                                                  <tr><td><span><? echo $cih_lang_msg_base_array['disablewebaccess']; ?></span> &nbsp;<rn:widget path="custom/CIHFunction/CheckBox" name="disabled" value="" checked="true" /> </td></tr>
                                                </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h2><? echo $cih_lang_msg_base_array['request_details']; ?></h2>
                    </td>
                </tr>               
                <tr>
                <td colspan="3">
                    <table style="width:100%">
                        <tr>
                                <td colspan="3" nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['rr_partnertrackingnum']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_ext_ref_no" value="" width="20" />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['rr_problemfound']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                                                        <rn:widget path="custom/CIHFunction/ProdCatSelection" required_lvl="3" data_type="categories" table="incidents" vis="" required="true" label_input="#rn:php:$cih_lang_msg_base_array['probfound_is_required']#" />
                                                                        <rn:widget path="custom/CIHFunction/ProdCatSelection" required_lvl="4" data_type="products" table="incidents" vis="display:none;" />
                                <td nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['rr_severity']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/MenuSelect" table="Incident" custom_field="ek_severity" name="ek_severity" required="true" label_input="#rn:php:$cih_lang_msg_base_array['rr_severity']#" label_required="#rn:php:$cih_lang_msg_base_array['is_required']#" />
                                </td>
                                <td nowrap="nowrap" valign="top">
                                        <span><? echo $cih_lang_msg_base_array['rr_repeatability']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                        <rn:widget path="custom/CIHFunction/MenuSelect" table="Incident" custom_field="ek_repeatability" name="ek_repeatability" required="true" label_input="#rn:php:$cih_lang_msg_base_array['rr_repeatability']#" label_required="#rn:php:$cih_lang_msg_base_array['is_required']#" />
                                </td>
                                <td nowrap="nowrap" valign="top">
                                    <span><? echo $cih_lang_msg_base_array['rr_errorcode']; ?></span><br/>
                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_error_code" value="" width="20" />
                                </td>
                            </tr>							                            <tr>                                <td colspan="4">                                    <rn:widget path="input/SelectionInput" name="incidents.c$ek_remote_access_perm" required="false" default_value="0" display_as_checkbox="true" label_input="#rn:php:$cih_lang_msg_base_array['allow_remote_access']#" hint="#rn:php:$cih_lang_msg_base_array['allow_remote_access_hint']#" />                                  </td>                            </tr>							
                            <tr>
                                <td colspan="3">
                                    <span><? echo $cih_lang_msg_base_array['rr_comments']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
                                    <rn:widget path="custom/CIHFunction/TextAreaInput" name="thread" value="" width="100%" height="150px" required="true" label_input="#rn:php:$cih_lang_msg_base_array['rr_comments']#" label_required="#rn:php:$cih_lang_msg_base_array['is_required']#" />
                                </td>
                                <td valign="bottom" align="right" width="850px" style="margin-right:200px;">
                                <rn:widget path="custom/CIHFunction/AjaxFormSubmit" error_location="rn_#rn:php:$this->instanceID#_ErrorLocation" ajax_method="incident_custom/incident_submit" challenge_required="false" disable_result_handler="true"/>
                                
                            </td>
                            </tr>
                    </table>
                
                </td>                   
                </tr>
            </table>
                <!--TODO: Need to set these values from the SAP IBase Record -->
                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_enabling_partner" value="" />
                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_mvs_manfacturer" value="" />
                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_service_dist" value="" />
                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_service_reseller" value="" />
                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_corporate" value="" />
                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_type" value="Repair" />

                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_sds" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_k_number" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_serial_number" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_service_profile" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_response_profile" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_equip_component_id" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_sap_product_id" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_sap_soldto_custid" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_customer_sapid" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_remote_eos" hide="true"/>
                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_onsite_eos" hide="true"/>


                <rn:widget path="custom/CIHFunction/HiddenInput" name="panel" value="repairrequest" />                <rn:widget path="custom/CIHFunction/HiddenInput" name="sesslang" value="#rn:php:$sesslang#" />
            </form> 
            </div>
        </div>    

    <?/*
        
    if(Roles::hasRoleFunction('repair request') === true)
        {
            echo "Has repair request";  
        }
    else{ 
        echo "No Access";
        }   
    */?>

