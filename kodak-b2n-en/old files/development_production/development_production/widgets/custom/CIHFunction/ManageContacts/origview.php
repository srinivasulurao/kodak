<rn:meta controller_path="custom/CIHFunction/ManageContacts" js_path="custom/CIHFunction/ManageContacts" base_css="custom/CIHFunction/ManageContacts" presentation_css="widgetCss/FormPanel.css"/>

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
									<span>Contact Selection:</span><br/>
									<rn:widget path="custom/CIHFunction/ContactSelect" name="c_id" panel_name="#rn:php:$this->data['attrs']['panel_name']#"/>	
								</td>
								<td>
									<span>Contact Errors:</span><br/>
									<rn:widget path="custom/CIHFunction/ListFailedOrgContacts" name="c_id"/>
									<div id="rn_<?=$this->instanceID;?>_ppErrorMessage" class="rn_ppErrorMessage rn_Hidden"></div>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<span>First Name:</span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
									<rn:widget path="custom/CIHFunction/CustomTextInput" name="firstname" field_name="First Name" value="" required="true" label_required="First Name is required" />
								</td>
								<td>
									<span>Last Name:</span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
									<rn:widget path="custom/CIHFunction/CustomTextInput" name="lastname" field_name="Last Name"value="" required="true" label_required = "Last Name is required"/>
								</td>
								<td>
									<span>Email Address:</span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
									<rn:widget path="custom/CIHFunction/CustomTextInput" name="emailaddress" field_name="Email Address" value="" required="true" label_required="Email Address is required"/>
								</td>
							</tr>
							<tr>
								<td>
									<span>Telephone # (Office):</span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
									<rn:widget path="custom/CIHFunction/CustomTextInput" name="officephone" field_name="Office #" value="" required="true" label_required="Telephone # (Office)" />
								</td>
								<td>
									<span>Telephone # (Mobile):</span><br/>
									<rn:widget path="custom/CIHFunction/CustomTextInput" name="mobilephone" value="" />
								</td>
								<td>
									<span>Telephone # (Fax):</span><br/>
									<rn:widget path="custom/CIHFunction/CustomTextInput" name="faxnumber" value="" />
								</td>
							</tr>
							<tr>
								<td>
									<span>Preferred Language 1:</span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
									<rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref1" name="language1" required="true" label_required="Preferred Language 1 required" />
								</td>
								<td>
									<span>Preferred Language 2:</span><br/>
									<rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref2" name="language2" />
								</td>
								<td>
									<span>Preferred Language 3:</span><br/>
									<rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_lang_pref3" name="language3" />
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" valign="top">
									<span>Opt-In (Global):</span>
									<rn:widget path="custom/CIHFunction/CheckBox" name="optinglobal" value="" required="false" checked="false" />&nbsp;&nbsp;
								</td>
								<td nowrap="nowrap" valign="top">
									<span>Opt-In (Incident):</span>
									<rn:widget path="custom/CIHFunction/CheckBox" name="optinincident" value="" required="false" checked="false" />&nbsp;&nbsp;
								</td>
								<td nowrap="nowrap" valign="top">
									<span>Opt-In (Survey):</span>
									<rn:widget path="custom/CIHFunction/CheckBox" name="optincisurvey" value="" required="false" checked="false" />
								</td>
							</tr>
							<tr>
								<td colspan="3">
								<span>Country:</span><span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
									<rn:widget path="custom/CIHFunction/MenuSelect" custom_field="ek_country_safe_harbor" name="country" required="true" label_required = "Country is required" />
                                                                        <rn:widget path="custom/CIHFunction/HiddenInput" name="selectedOrg" hide="true"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<h2>Customer Portal Access</h2>
					</td>
				</tr>				
				<tr>
				<td colspan="3">
					<table style="width:100%">
						<tr>
							<td nowrap="nowrap" valign="bottom">
								<span>Disable Web Access:</span> &nbsp;<rn:widget path="custom/CIHFunction/CheckBox" name="disabled" value="" checked="true" />
							</td>
							<td width="150px">
							<span>Login:</span><span id="rn_<?=$this->instanceID;?>_loginRequiredIndicator" class="rn_Required rn_Hidden"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
							<rn:widget path="custom/CIHFunction/CustomTextInput" name="login" required="false" value="" label_required="Login is required" readonly="true" />
							</td>
							<td width="150px">
								<span>Role:</span><span id="rn_<?=$this->instanceID;?>_roleRequiredIndicator" class="rn_Required rn_Hidden"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><br/>
								<rn:widget path="custom/CIHFunction/RoleSelect" name="role" label_required="Role is required" />								
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
			</form>	
			</div>
		</div>    
