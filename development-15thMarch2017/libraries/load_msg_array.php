<rn:meta title="" template="kodak_b2b_template.php" />
<?
use RightNow\Connect\v1_2 as RNCPHP;
require_once(get_cfg_var('doc_root').'/include/ConnectPHP/Connect_init.phph');
initConnectAPI();

function load_array($file_name = null){
    if(empty($file_name)){
        $request_url = $_SERVER["REQUEST_URI"];
        $url_ary = explode("/",$request_url);
        $file_name = $url_ary[2].".php";
    }
   // $file_path = "/vhosts/kodak_b2b_en/euf/assets/csv/".$file_name;
   $file_path = get_cfg_var('doc_root')."/cp/customer/development/data/".$file_name;
    if(file_exists($file_path) && $file_name){
        $msg_file = fopen($file_path, "r");
        $header_row=fgetcsv($msg_file, 1000000);
        $intf_id= intf_id();
		
		$interface_details = RNCPHP\SiteInterface::find("ID=".$intf_id);
		$intf_name = $interface_details[0]->LookupName;

        foreach($header_row as $key=> $val){
            $header = explode("/",$val);
            $interface = $header[0];
            if($interface == $intf_name){
                $index=$key;
            }
        }

        while ($data = fgetcsv($msg_file, 1000000))
                {
                    $msg_base_array[$data[0]]=$data[$index];
                }
    }
    return $msg_base_array;
}
?>
