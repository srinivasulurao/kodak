<?php

use RightNow\Connect\v1_1 as RNCPHP;

require_once(get_cfg_var('doc_root').'/include/ConnectPHP/Connect_init.phph');
require_once(APPPATH . "controllers/ibase_search.php");
initConnectAPI();

include_once(APPPATH . "libraries/PSLog-2.0.php");




/** 

* This class is a model used to make a SOAP request to the SAP ECC Credit Checking API

* to retreive a credit pass/fail based upon an SAP payer ID and Org country. Based on the

* response SOAP envelope, properties are set upon the $formData array to be read later by

* the custom_incident_model for modifying the new incident bein created.

* 

* Author: Jaime Grochowski

* 7.22.14

* Copyright(c) 2014 Oracle

*/ 

class credit_check_model extends Model {

    

    // PSLog instance

    private $log;

    static $payer;


    function __construct() {

        parent::__construct();

        $this->log = new \PS\Log\v2\Log(\PS\Log\v2\Type::CP);

    }

    

    /** 

    * Function validates incoming contact ID, looks up contact's org, calls credit check 

    * function, and updates reference to $formData with properties if credit check fail or error occurs

    * @param $c_id number Contact ID to use for looking up associated org fields

    * @param &$formatData array Reference to array containing values submitted from repair request incident creation form

    */ 

    public function repairRequestCreditCheck($c_id, &$formData) {

       
        // Ensure we have a contact ID, otherwise add error note and return
      
        if ($c_id===NULL || $c_id <= 0) {

            $this->addPrivateMessage("no contact ID available", $formData, true);

            return;

        }

    

        try {

            // Get country code and SAP payer ID based upon contact ID

            list($country, $payer_id) = $this->getOrganizationFieldsByContactId($c_id);

            $productID = $formData["ek_sap_product_id"];
            
            

            // If either payer ID or country are not available, add error note

            if (!isset($payer_id)) {

                $this->addPrivateMessage("no organization SAP payer ID available", $formData, true);

            }

            if (!isset($country)) {

                $this->addPrivateMessage("no organization country available", $formData, true);

            }

            if(!isset($productID))
            {
                $this->addPrivateMessage("no organization SAP product ID available", $formData, true);
            }

            

            if (isset($payer_id, $country, $productID)) {

                // Pass payer ID and country code to credit check function to get back a private note for the

                // incident and if the credit check failed requiring a credit hold.

                list($message, $credit_hold, $zterm, $smail) = $this->doCreditCheck($payer_id, $country, $productID);

                

                if (!empty($message)) {

                    $this->addPrivateMessage("Credit Check result: " . $message, $formData);

                }

                // When we have a credit hold, we set the incident hold custom fields properties to Yes

                if ($credit_hold===true) {

                    

                    $formData['ek_credit_hold'] = 1;

                    $formData['ek_incident_hold'] = 1;

        

                    $this->log->debug(sprintf("zterm - %s smail - %s", $zterm, $smail));

                    // There should have been a zterm value returned by API to set to the payment terms custom incident field

                    if (!empty($zterm)) {

                        $formData['ek_pymt_terms'] = $zterm;

                    }

                    // There also should be an smail value that matches the login of an account record. If confirmed to exist,

                    // set incident assigned to value to account ID

                    if (!empty($smail)) {

                        $account = RNCPHP\Account::first("login = '" . $smail . "'");

                        if (isset($account)) $formData['assigned_to'] = $account->ID;

                    }

                }

            }

        } catch(Exception $exp) {

            // If any functions throw an error, add a note to the incident and log it

            $this->addPrivateMessage($exp->getMessage(), $formData, true);

        }

    }

    

    /** 

    * Function uses Connect API to fetch contact for ID supplied. Contact's associated organization is used to get

    * its address country code and c$ek_sap_payer_id custom field values.

    * @param $c_id number Contact ID to use for looking up associated org fields

    * @return array $country, $payer_id

    */ 

    private function getOrganizationFieldsByContactId($c_id) {

        $country = $payer_id = "";

        try {

            $this->log->debug("repairRequestCreditCheck c_id = " . $c_id);

            $contact = RNCPHP\Contact::fetch($c_id);

            

            if ($contact!==NULL) {

                $org = $contact->Organization;

                if ($org!==NULL) {

                    $this->log->debug("repairRequestCreditCheck org_id = " .  $org->ID);

                    

                    $addrs = $org->Addresses;

                    if ($addrs!==NULL && count($addrs) >= 1) {

                        $addr = $addrs[0];

                        if ($addr!==NULL) {

                            $country = $addr->Country->LookupName;

                            if (!empty($country)) {

                                $this->log->debug("repairRequestCreditCheck country = " .  $country);

                            }

                        }

                    }

                    

                    if ($org->CustomFields!==NULL) {

                        $payer_id = $this->session->getSessionData("Payerinfo");
                                      
                        if (!empty($payer_id)) {

                            $this->log->debug("repairRequestCreditCheck payer_id = " .  $payer_id);

                        }


                    }



                }

            }

        } catch(RNCPHP_CO\ConnectAPIError $err) {

            $this->log->error($err->getMessage());

        }

        return array($country, $payer_id);

    }

    

    /** 

    * Function uses Connect API ROQL query to first fetch API details from CBO, post SOAP envelope to API,

    * parse SOAP XML response, and evaluate values to determine if credit hold is needed for incident.

    * @param $payer_id string SAP Payer ID

    * #param $country string 2 digit country code

    * @return array $message, $credit_hold, $zterm, $smail

    */ 

    private function doCreditCheck($payer_id, $country, $productID) {

        $message = $zterm = $smail = "";

        $credit_hold = false;

        

        $api_url = $api_user = $api_pass = "";

        try {

            // Use ROQL query to fetch single record from CBO containing URL and credentials

            $res = RNCPHP\ROQL::query("select ek_ws_user, ek_ws_password, ek_credit_ws_PI from CIH.ek_configuration where ID = 1")->next();

            

            if ($config = $res->next()) {

                $api_user = $config['ek_ws_user'];

                $api_pass = $config['ek_ws_password'];

                $api_url = $config['ek_credit_ws_PI'];

            }

        } catch (Exception $e) {

            // If issue getting config values, we can't continue, log error and throw exception to be added as note to incident

            $this->log->error($e->getMessage());

            throw new Exception("error looking up credit check web service configuration: " . $e->getMessage());

        }

        

        // Build entire request SOAP envelope XML document using payer ID and country values for Kunnr and Land1 nodes

        $req_envelope = "<s:Envelope xmlns:s=\"http://schemas.xmlsoap.org/soap/envelope/\"><s:Body xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"><MT_ZBapiGetCreditCheck xmlns=\"urn:kodak.com/Z_IC02532/CreditCheck\"><EtCreditlines xmlns=\"\"/><Kunnr xmlns=\"\">$payer_id</Kunnr><Land1 xmlns=\"\">$country</Land1><Matnr xmlns = \"\">$productID</Matnr><TmCustInd xmlns=\"\">E</TmCustInd></MT_ZBapiGetCreditCheck></s:Body></s:Envelope>";

        $this->log->debug("Request Envelope", null, $req_envelope);
		$this->log->debug("URL", null, $api_url);
		
        $resp_envelope = "";

        $xml = null;

        try {

            // Load cURL library extension if not already available

            if(!extension_loaded('curl')) {

              load_curl();

            }

            

            // Setup cURL request with SSL, timeout, and authentication options. Tell it to return us

            // the response XML string for request envelope posted to API URL.

            $req = curl_init();

              curl_setopt($req, CURLOPT_SSLVERSION,3);
           // curl_setopt($req, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
            //curl_setopt($req, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_0);

            curl_setopt($req, CURLOPT_SSL_VERIFYPEER,FALSE);

            curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 0);
           
            curl_setopt($req, CURLOPT_CONNECTTIMEOUT, 30);

            curl_setopt($req, CURLOPT_TIMEOUT, 45);

            curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt($req, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            curl_setopt($req, CURLOPT_USERPWD, "$api_user:$api_pass");

            curl_setopt($req, CURLOPT_HTTPHEADER, array("Content-Type: text/xml; charset=UTF-8"));

            curl_setopt($req, CURLOPT_URL, $api_url);

            curl_setopt($req, CURLOPT_POST, true);

            curl_setopt($req, CURLOPT_POSTFIELDS, $req_envelope);

            

            // Get SOAP envelope response after POST is submitted to API

            $resp_envelope = curl_exec($req);

            

            $this->log->debug("Response Envelope", null, $resp_envelope);

            

            // Parse the XML document to fetch the node values needed to determine credit check pass/fail

            list($parse_error, $error, $zterm, $smail, $return_message, $fault) = $this->parseSOAPEnvelope($resp_envelope);

            // If we are unable to parse XML for any reason, get HTTP code, URL used, and cURL error message before closing

            // request object. Log the error details and response string to PSLog and throw exception with error details.

            if ($parse_error) {

                $http_code = curl_getinfo($req, CURLINFO_HTTP_CODE);

                $last_url = curl_getinfo($req, CURLINFO_EFFECTIVE_URL);

                $curl_err = curl_error($req);

                curl_close($req);

                

                $errMsg = sprintf("Unable to parse API response, HTTP Code:%d, URL:%s %s", $http_code, $last_url, $curl_err);

                $this->log->error($errMsg, null, $resp_envelope);
				
				throw new Exception($errMsg);

            } else { // Otherwise just close cURL request object since we are done with it

                curl_close($req);

            }

            

            if (!$fault) { // Ensure SOAP response wasn't fault

                if ($error==="X") { // error value equaling X means credit hold is needed for incident

                    $credit_hold = true;

                }

                // Set message for incident private note from SOAP response

                $message = $return_message;

            } else { // Otherwise log any fault message parsed from SOAP envelope and throw exception with it

                $errMsg = sprintf("response was invalid or fault: %s\r\n", $return_message);

                $this->log->error("Invalid response from credit check API", null, $resp_envelope);

                throw new Exception($errMsg);

            }

        } catch(Exception $e) {

            // Indicate to caller that thrown exception occurred during API web service call

            throw new Exception("error calling API: " . $e->getMessage());

        }

        

        // Return any messages we retreived from API, if credit hold is required, and if so zterm and smail values for incident

        return array($message, $credit_hold, $zterm, $smail);

    }

    

    /**

    * Function uses PHP XML parser to take SOAP envelope document and get an array. It then loops over the elements to

    * find tags at certain levels and assign the node values to variables to return.

    * @param $env string SOAP XML envelope

    * @return array $parse_error, $error, $zterm, $smail, $return_message, $fault

    */

    private function parseSOAPEnvelope($env) {

        $error = $zterm = $smail = $return_message = $item_message = $fault_string = "";

        $fault = $parse_error = $soap_env = false;

        try {

            // Convert XML string into a multi-dimensional array

            $parser = xml_parser_create();

            if(!xml_parse_into_struct($parser, $env, $struc, $index)){ // If return is false, set a flag to return incidating such

                $parse_error = true;

            }

            xml_parser_free($parser);

            

            if (!$parse_error) {

                // Iterate through response XML parsed into multi array and save needed node values

                $count = count($struc);
				$debugMsg = "==Debug Start==\r\n";
                for($i = 0; $i < $count; $i++) {

                    $item = $struc[$i];
					
					$debugMsg .= "Index=".$i." Tag=".$item["tag"]." Value=".$item["value"]." Level=".$item["level"]." \r\n";

                    // Check each array item's tag and level value, if its a needed one, ensure it has a value property and variable isn't already set

                    if($item["tag"] == "SOAPENV:ENVELOPE" || $item["tag"] == "SOAP:ENVELOPE") {

                        $soap_env = true;
						$debugMsg .= "Soap Env= ".$soap_env." \r\n";

                    } else if($item["tag"] == "WEBM:EXCEPTION") { // this tag indicates a fault occurred, should also find faultstring node

                        $fault = true;
						$debugMsg .= "Fault 1 \r\n";

                    } else if(($item["tag"] == "ERROR") && ($item["level"] == 6) && isset($item["value"]) && empty($error)) {

                        $error = $item["value"];
						$debugMsg .= "Error 1 = ".$item["value"]." \r\n";

                    } else if(($item["tag"] == "ZTERM") && ($item["level"] == 6) && isset($item["value"]) && empty($zterm)) {

                        $zterm = $item["value"];
						$debugMsg .= "ZTerm = ".$zterm." \r\n";

                    } else if(($item["tag"] == "SMAIL") && ($item["level"] == 6) && isset($item["value"]) && empty($smail)) {

                        $smail = $item["value"];
						$debugMsg .= "Smail = ".$smail." \r\n";

                    } else if(($item["tag"] == "MESSAGE") && ($item["level"] == 5) && isset($item["value"]) && empty($return_message)) {

                        $return_message = $item["value"];
						$debugMsg .= "Return Msg = ".$return_message." \r\n";

                    } else if(($item["tag"] == "MESSAGE") && ($item["level"] == 6) && isset($item["value"]) && empty($item_message)) {

                        $item_message = $item["value"];
						$debugMsg .= "Item Msg = ".$item_message." \r\n";

                    } else if(($item["tag"] == "FAULTSTRING") && isset($item["value"]) && empty($fault_string)) {

                        $fault = true;

                        $fault_string = $item["value"];
						$debugMsg .= "Fault 2 Fault String = ".$fault_string." \r\n";

                    } else if(($item["tag"] == "WEBM:MESSAGE") && isset($item["value"]) && empty($fault_string)) {

                        $fault_string = $item["value"];
						$debugMsg .= "Fault String = ".$fault_string." \r\n";

                    }

                }
				$debugMsg .= "==Debug End==\r\n";
				$this->log->debug("Parser Debug", null, $debugMsg);
            }

        } catch (Exception $err) {

            throw new Exception("exception parsing SOAP envelope: " . $err->getMessage());

        }

        

        if (!$soap_env && !$fault) {

            $fault = true;

            $return_message = $env;

        } else if ($fault) {

            $return_message = $fault_string;

        }

        // $parse_error boolean If XML was able to be parsed

        // $error string Should be either empty or "X" (indicating credit fail)

        // $zterm string If credit fail, payment term code

        // $smail string If credit fail, account login to assign incident

        // $return_message string If credit fail, message from SOAP envelope, if fault, fault message

        // $fault boolean If fault nodes were found in parsed SOAP envelope

        return array($parse_error, $error, $zterm, $smail, $return_message, $fault);

    }

    

    /**

    * Function takes a string message and sets a form data array property with it so that the incident will create a private note. 

    * When it is in indicated to be an error, it prepends a string to indicate this on the note and also logs error to PSLog.

    * @param $msg string Message to add to 'credit_private_note' property

    * @param &$formData array Reference to form data object

    * @param $error boolean If this message is an error note

    */

    private function addPrivateMessage($msg, &$formData, $error) {

        $formData['credit_private_note'] = (($error)?"Error retrieving Credit Status:\r\n":"") . $msg;

        if ($error) {

            $this->log->error($msg);

        }

    }
	

    public function get_payer($payer_num)
	{
                     
           $arr = array("Payerinfo" => $payer_num);
           $this->session->setSessionData($arr);
           		
	 }
	

}