<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<?
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
?>


		<div id="rn_<?=$this->instanceID;?>_container" class="rn_FormPanel">

			<div id="panelContent" class="rn_Accordion_content">

			<!-- Panel content goes here -->

			 <div id="rn_<?=$this->instanceID;?>_ErrorLocation"></div>

			<form id="rn_<?=$this->instanceID;?>_form" onsubmit="return false">

			<table style="width:100%">

				<tr>

					<td  valign="top" >

										

					</td>

					<td >

						&nbsp;

					</td>

					<td valign="top">

						<table style="width:100%" cellspacing="2">

							<tr>

								<td>

									<span><? echo $cih_lang_msg_base_array['contactselection']; ?></span><br/>

									<rn:widget path="custom/CIHFunction/ContactSelect" name="c_id" panel_name="#rn:php:$this->data['attrs']['panel_name']#"/>	

								</td>

								<td>

									<span><? echo $cih_lang_msg_base_array['contacterrors']; ?></span><br/>

									<rn:widget path="custom/CIHFunction/ListFailedOrgContacts" name="c_id"/>

									<div id="rn_<?=$this->instanceID;?>_ppErrorMessage" class="rn_ppErrorMessage rn_Hidden"></div>

								</td>

								<td></td>

							</tr>

							<tr>

								<td>

									<span><? echo $cih_lang_msg_base_array['cd_firstname']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

									<rn:widget path="custom/CIHFunction/CustomTextInput" name="firstname" field_name="First Name" value="" required="true" label_required="#rn:php:$cih_lang_msg_base_array['mc_firstnamereq']#" />

								</td>

								<td>

									<span><? echo $cih_lang_msg_base_array['cd_lastname']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

									<rn:widget path="custom/CIHFunction/CustomTextInput" name="lastname" field_name="Last Name" value="" required="true" label_required = "#rn:php:$cih_lang_msg_base_array['mc_lastnamereq']#"/>

								</td>

								<td>

									<span><? echo $cih_lang_msg_base_array['cd_email']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

									<rn:widget path="custom/CIHFunction/CustomTextInput" name="emailaddress" field_name="Email Address" value="" required="true" label_required="#rn:php:$cih_lang_msg_base_array['mc_emailreq']#"/>

								</td>

							</tr>

							<tr>

								<td>

									<span><? echo $cih_lang_msg_base_array['cd_officetel']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

									<rn:widget path="custom/CIHFunction/CustomTextInput" name="officephone" field_name="Office #" value="" required="true" label_required="#rn:php:$cih_lang_msg_base_array['mc_officephonereq']#" />

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

									<rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref1" name="language1" required="true" label_required="#rn:php:$cih_lang_msg_base_array['mc_preflang1req']#" />

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

									<rn:widget path="custom/CIHFunction/CheckBox" name="optinglobal" value="" required="false" checked="false" />&nbsp;&nbsp;

								</td>

								<td nowrap="nowrap" valign="top">

									<span><? echo $cih_lang_msg_base_array['cd_optinincident']; ?></span>

									<rn:widget path="custom/CIHFunction/CheckBox" name="optinincident" value="" required="false" checked="false" />&nbsp;&nbsp;

								</td>

								<td nowrap="nowrap" valign="top">

									<span><? echo $cih_lang_msg_base_array['cd_optinsurvey']; ?></span>

									<rn:widget path="custom/CIHFunction/CheckBox" name="optincisurvey" value="" required="false" checked="false" />

								</td>

							</tr>

							<tr>

								<td >

								<span><? echo $cih_lang_msg_base_array['cd_country']; ?></span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

									<rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_country_safe_harbor" name="country" required="true" label_required = "#rn:php:$cih_lang_msg_base_array['mc_countryreq']#" />

                                                                        <rn:widget path="custom/CIHFunction/HiddenInput" name="selectedOrg" hide="true"/>

								</td>
								<td colspan="2" nowrap="nowrap" valign="top">

									<span>Telephone # Ext.</span><br/>

						<rn:widget path="custom/CIHFunction/CustomTextInput" width="6" name="ek_phone_extension" custom_field="ek_phone_extension" value="" required="false" label_required="Telephone # Ext." />

								</td>

							</tr>

						</table>

					</td>

				</tr>

				<tr>

					<td colspan="3">

						<h2><? echo $cih_lang_msg_base_array['custportalselection']; ?></h2>

					</td>

				</tr>				

				<tr>

				<td colspan="3">

					<table style="width:100%">

						<tr>

							<td nowrap="nowrap" valign="bottom">

								<span><? echo $cih_lang_msg_base_array['disablewebaccess']; ?></span> &nbsp;<rn:widget path="custom/CIHFunction/CheckBox" name="disabled" value="" checked="true" />

							</td>

							<td width="150px">

							<span><? echo $cih_lang_msg_base_array['login']; ?></span><span id="rn_<?=$this->instanceID;?>_loginRequiredIndicator" class="rn_Required rn_Hidden"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

							<rn:widget path="custom/CIHFunction/CustomTextInput" name="login" required="false" value="" label_required="#rn:php:$cih_lang_msg_base_array['mc_loginreq']#" readonly="true" />

							</td>

							<td width="150px">

								<span><? echo $cih_lang_msg_base_array['role']; ?></span><span id="rn_<?=$this->instanceID;?>_roleRequiredIndicator" class="rn_Required rn_Hidden"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>

								<rn:widget path="custom/CIHFunction/RoleSelect" name="role" label_required="#rn:php:$cih_lang_msg_base_array['mc_rolereq']#" />								

							</td>

							<td nowrap="nowrap" valign="bottom">

								<!--<span>Deactivated:</span> &nbsp;<rn:widget path="custom/CIHFunction/CheckBox" name="deactivate" value="" />-->

							</td>

							<td >&nbsp;

							</td>				

							<td valign="top" align="right" width="850px" style="margin-right:200px;">

                                                               &nbsp; 

							</td>

						</tr>

					</table>					

				</td>					

				</tr>

                                <tr>

                                <td colspan="3">

                                      <table style="width:100%">

                                        <tr>

					<td nowrap="nowrap" valign="bottom">

                                         <rn:widget path="custom/CIHFunction/ContactCommunicationOptin" />

                                        </td>

							<td valign="top" align="right" width="850px" style="margin-right:200px;">

                                                           <rn:widget path="custom/CIHFunction/AjaxFormSubmit" error_location="rn_#rn:php:$this->instanceID#_ErrorLocation" ajax_method="contact_custom/contact_update_submit" challenge_required="false" disable_result_handler="true"/> 

                                                  </td>

                                        </tr>

                                      </table>

                                </td>

                                </tr>

			</table>
                <rn:widget path="custom/CIHFunction/HiddenInput" name="sesslang" value="#rn:php:$sesslang#" />

			</form>	

			</div>

		</div>    
</div>