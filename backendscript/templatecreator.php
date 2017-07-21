<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '/var/www/html/platform/class/Ovirt.php';
require '/var/www/html/platform/class/Constants.php';

$x = Curl::curl_get_and_getresponse(Constants::OVIRT_API_URL."/vms");
$xml = simplexml_load_string($x);
include '/var/www/html/platform/plattemplate/connection.php';
foreach($xml->vm as $vm){
	$sql = mysqli_query($connection, "SELECT FOLDER,VMNO FROM backend WHERE PROCESSING='1'");
	while($row = mysqli_fetch_assoc($sql)){
		for($i = 0; $i<=$row['VMNO']; $i++){
			if($vm->name == $row['FOLDER'].$i){
				$r = Ovirt::ovirt_create_template_with_vmid($row['FOLDER'], $vm['id']);
				$result = simplexml_load_string($r);
				if($result->reason == "Operation Failed"){
					echo "Failed".$r;
				}else{
					echo $r;
				}
			}			
		}
	}
}
//echo '<pre>'.$x.'</pre>';
?>