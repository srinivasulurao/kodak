<?php
    $CI = get_instance();
	$sesslang = $CI->session->getSessionData("lang");
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
    //$CI->load->model('standard/Contact_model');
    //$contact = $CI->Contact_model->get($CI->session->getProfileData("c_id"));
	$contact=$CI->session->getProfile();
	$contact_id=$contact->c_id->value;
	$contact_full_name=$contact->first_name->value." ".$contact->last_name->value;

?>

        <div id="rn_<?=$this->instanceID;?>_container" class="rn_FormPanel">


            <div id="panelContent" class="rn_Accordion_content">


            <!-- Panel content goes here -->


            <div id="rn_<?=$this->instanceID;?>_ErrorLocation"></div>


            <form id="rn_<?=$this->instanceID;?>_form" method="post" action="/cc/incident_custom/incident_submit" onsubmit="return false;">


            <table style="width:100%">


                <tr>


                    <td colspan="3">


                        <h2><? echo $cih_lang_msg_base_array['contactdetails']; ?></h2>


                    </td>


                </tr>


                <tr>


                    <td valign="top" >


                                                                


                    </td>


                    <td >


                        &nbsp;


                    </td>


                    <td valign="top">


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


                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="lastname" label_input="Last Name" value="" required="true" />


                                </td>


                                <td>


                                    <span><? echo $cih_lang_msg_base_array['cd_email']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="emailaddress" label_input="Email Address" value="" required="true" />


                                </td>


                            </tr>


                            <tr>


                                <td>


                                    <span><? echo $cih_lang_msg_base_array['cd_officetel']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                    <rn:widget path="custom/CIHFunction/CustomTextInput" name="officephone" value="" required="true" label_required="Office Phone is required" />


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


                                    <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref1" name="language1" required="true" label_required="Preferred Language 1 is required" />


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


                                <td >


                                <span><? echo $cih_lang_msg_base_array['cd_country']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                    <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_country_safe_harbor" name="country" required="true" label_required="Country is required" />


                                </td>
								<td colspan="2" nowrap="nowrap" valign="top">

									<span>Telephone # Ext.</span><br/>

						<rn:widget path="custom/CIHFunction/CustomTextInput" width="6" name="ek_phone_extension" custom_field="ek_phone_extension" value="" required="false" label_required="Telephone # Ext." />

								</td>
								


                            </tr>


                        </table>


                                                <table cellspacing="2" >


                                                        <tr>


                                                                <td>


                                                                  <span><strong><? echo $cih_lang_msg_base_array['cd_personsubmittinginc']; ?></strong></span><br/>


                                                                  <div class="rn_Hidden">


                                                                    <rn:widget path="standard/input/FormInput" name="incidents.c$orig_submit_id" default_value="#rn:php:$contact_id#" required="true" readonly="true"/>


                                                                    <rn:widget path="standard/input/FormInput" name="incidents.c$orig_submit_name" default_value="#rn:php:$contact_full_name#" required="true" readonly="true"/>


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


                            <td nowrap="nowrap" valign="top" style="width:20%">


                                <span><? echo $cih_lang_msg_base_array['rd_ibaseupdatetype']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                <rn:widget path="custom/CIHFunction/MenuSelect" table="Incident" custom_field="ek_ibase_updt_type" name="ek_ibase_updt_type" required="true" label_input="Ibase Update Type" />


                                                                <rn:widget path="custom/CIHFunction/ProdCatSelection" required_lvl="4" data_type="products" table="incidents" vis="display: none;" />


                            </td>


                            <td style="width:20px;">


                                &nbsp;


                            </td>


                            <td valign="top">


                                <table id="form_EquipmenRemovalForm<?=$this->instanceID;?>"  style="width:100%" cellspacing="2" class="rn_Hidden">


                                    <tr>


                                        <td valign="top">


                                            <?


                                                $options = array();


                                                $options[] = array('ID'=>'Can\'t locate equipment','LookupName'=>$cih_lang_msg_base_array['rd_erem_dd_cantlocate']);


                                                $options[] = array('ID'=>'Damaged/inoperable','LookupName'=>$cih_lang_msg_base_array['rd_erem_dd_damaged']);


                                                $options[] = array('ID'=>'Disposed of','LookupName'=>$cih_lang_msg_base_array['rd_erem_dd_disposedof']);


                                                $options[] = array('ID'=>'No longer using','LookupName'=>$cih_lang_msg_base_array['rd_erem_dd_nolongerusing']);


                                                $options[] = array('ID'=>'Sold','LookupName'=>$cih_lang_msg_base_array['rd_erem_dd_sold']);


                                                $options[] = array('ID'=>'Other - Please specify below','LookupName'=>$cih_lang_msg_base_array['rd_erem_dd_otherspecify']);


                                                $options_json = json_encode($options);  


                                            ?>


                                            <span><? echo $cih_lang_msg_base_array['rd_erem_reasonforremoval']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/MenuSelect" name="removal_reason" data="#rn:php:$options_json#" required="true" label_input="Reason for Removal"/>


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erem_effectivedate']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="effective_date" required="true" label_input="Effective Date" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erem_comments']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/TextAreaInput" name="thread" width="100%" height="150px" required="true" label_input="Comments" />


                                        </td>


                                    </tr>                                   


                                </table>


                                <table id="form_EquipmenRelocationForm<?=$this->instanceID;?>" style="width:100%" cellspacing="2"  class="rn_Hidden">


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_productident']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="product_identifier" required="true" label_input="Product Identifier" value="" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_firstname']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="ibase_firstname" required="true" label_input="First Name" value="" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_lastname']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="ibase_lastname" required="true" label_input="Last Name" value="" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_teloffice']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="ibase_phone" required="true" label_input="Telephone # (Office)" value="" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_scn']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="sitecustomername" required="true" label_input="Site Customer Name" value="" />


                                        </td>


                                    </tr>


                                                                        <tr>


                                                                                <td>


                                                                                        <span><? echo $cih_lang_msg_base_array['rd_erel_sca']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                                                                        <rn:widget path="custom/CIHFunction/TextAreaInput" name="ibase_address" value="" width="50%" required="true" label_input="Site Customer Address" />


                                                                                </td>


                                                                        </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_sa1']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="street" value="" required="true" label_input="Street Address 1" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_city']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="city" value="" required="true" label_input="City" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_stateprovince']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="state" value="" required="true" label_input="State/Province" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_zip']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="zipcode" value="" required="true" label_input="Postal Code/Zip" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_country']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_country_safe_harbor" name="ibase_country" label_input="Country" required="true" usetextasvalue="true" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_storenum']; ?></span><!--<span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?>  --></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="storenumber" required="false" label_input="Store #" value="" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_equiplocation']; ?></span><!--<span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?>  --><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="equipment_location" required="false" label_input="Equipment Location" value="" />


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_effdate']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="effective_date" value="" required="true" label_input="Effective Date"/>


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_erel_comments']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/TextAreaInput" name="thread" value="" width="100%" height="150px" required="true" label_input="Comments" />


                                        </td>


                                    </tr>                                   


                                </table>


                                <table id="form_EntitlementChangeForm<?=$this->instanceID;?>" style="width:100%" cellspacing="2" class="rn_Hidden">


                                    <tr>


                                        <td>


                                            <p><? echo $cih_lang_msg_base_array['rd_note']; ?></p>


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <?


                                            $options = array();


                                            $options[] = array('ID'=>'Warranty','LookupName'=>$cih_lang_msg_base_array['rd_echange_dd_warranty']);


                                            $options[] = array('ID'=>'Service Contract','LookupName'=>$cih_lang_msg_base_array['rd_echange_dd_sc']);


                                            $options[] = array('ID'=>'Time and Materials','LookupName'=>$cih_lang_msg_base_array['rd_echange_dd_tandm']);


                                            $options[] = array('ID'=>'Rental','LookupName'=>$cih_lang_msg_base_array['rd_echange_dd_rental']);


                                            $options[] = array('ID'=>'Request for new service contract','LookupName'=>$cih_lang_msg_base_array['rd_echange_dd_reqnewsc']);


                                            $options_json = json_encode($options);


                                            ?>


                                            <span><? echo $cih_lang_msg_base_array['rd_echange_etype']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/MenuSelect" name="entitlement_type" data="#rn:php:$options_json#" required="true" label_input="Entitlement Type"/>


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_echange_comments']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/TextAreaInput" name="thread" value="" width="100%" height="150px"  required="true" label_input="Comments" />


                                        </td>


                                    </tr>                                   


                                </table>


                                <table id="form_IBaseUpdateOtherForm<?=$this->instanceID;?>"  style="width:100%" cellspacing="2"  class="rn_Hidden">


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_other_effdate']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/CustomTextInput" name="effective_date" value="" required="true" label_input="Effective Date"/>


                                        </td>


                                    </tr>


                                    <tr>


                                        <td>


                                            <span><? echo $cih_lang_msg_base_array['rd_echange_comments']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>


                                            <rn:widget path="custom/CIHFunction/TextAreaInput" name="thread" value="" width="100%" height="150px"  required="true" label_input="Comments" />


                                        </td>


                                    </tr>                                   


                                </table>


                            </td>                               


                        </tr>


                        <tr>


                            <td valign="bottom" align="right" width="850px" style="margin-right:200px;" colspan="3">


                                <rn:widget path="custom/CIHFunction/AjaxFormSubmit" error_location="rn_#rn:php:$this->instanceID#_ErrorLocation" ajax_method="incident_custom/incident_submit" challenge_required="false" disable_result_handler="true" on_success_url='none' label_on_success_banner="Updated Successfully !" />


                                <rn:widget path="custom/CIHFunction/HiddenInput" name="panel" value="ibaseupdate" />
								<rn:widget path="custom/CIHFunction/HiddenInput" name="sesslang" value="#rn:php:$sesslang#" />								


                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_type" value="Administrative" />





                                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_enabling_partner" value="" />


                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_mvs_manfacturer" value="" />


                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_service_dist" value="" />


                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_service_reseller" value="" />


                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_corporate" value="" />





                                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_k_number" hide="true"/>


                                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_serial_number" hide="true"/>


                                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_equip_component_id" hide="true"/>


                                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_sap_product_id" hide="true"/>


                                                                <rn:widget path="custom/CIHFunction/HiddenInput" name="ek_sap_soldto_custid" hide="true"/>





                            </td>


                        </tr>


                    </table>


                


                </td>                   


                </tr>


            </table>


            </form> 


            </div>


        </div>    

