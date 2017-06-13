<?php

class PlatformDB{
	
	##platform code 
	
	public static function getTemplateWithScenarioName($input_tempname){
		include '../plattemplate/connection.php';
		if($connection->connect_error){
			return "Technical Error [DB1001]".$connection->connect_error;
		}
		
		$stmt = $connection->prepare("SELECT TEMPLATE,TEMP_SIZE FROM smenu WHERE TEMP_NAME=?");
		$stmt->bind_param("s", $tempname);
		$tempname = Validator::filterString($input_tempname);
		$stmt->execute();
		$stmt->bind_result($col1,$col2);
		$arr = array();
		while($stmt->fetch()){
			$arr[$col1] = $col2;
		}
		return $arr;
		$stmt->close();
		$connection->close();
		
	}
	
	public static function checkVMNumberCount($username){
		include '../plattemplate/connection.php';	
		$result = mysqli_query($connection, "SELECT VMNAME FROM vm WHERE USERNAME='$username'");
		if(mysqli_num_rows($result) <= 4){
			return true;
		}else{
			return false;
		}
	}
	
	public static function insertVMDetails($username, $vmname, $vmid){
		include '../plattemplate/connection.php';
		$result = mysqli_query($connection, "INSERT INTO vm (USERNAME, VMNAME, VMID) VALUES ('$username', '$vmname', '$vmid')");
		if($result){
			return Constants::DB_SUCCESS;
		}else{
			return Constants::DB_FAILURE;
		}
	}
	
	public static function checkIfVMIdExistsForUser($vmid, $user){
		include '../plattemplate/connection.php';
		$result = mysqli_query($connection, "SELECT VMID FROM vm WHERE USERNAME='$user' AND VMID='$vmid'");
		return mysqli_num_rows($result);
	}
	
	public static function deleteVMfromDbWithVmIdandUser($vmid, $user){
		include '../plattemplate/connection.php';
		$result = mysqli_query($connection, "DELETE FROM vm WHERE USERNAME='$user' AND VMID='$vmid'");
		if($result){
			return Constants::DB_SUCCESS;
		}else{
			return Constants::DB_FAILURE;
		}
	}
	
}

?>