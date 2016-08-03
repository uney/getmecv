<?php
require_once '../backend/config.php';
require_once '../backend/functions.php';
require_once '../backend/db_connection.php';
	
if ( 0 < $_FILES['file']['error'] ) {
	echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
	$key  = $_POST['skey'];
	
	if( strlen( $key ) != 36 )
	{
		echo 'Upload Failed';
	}
	else
	{
		$stmt = $dbc->prepare ("SELECT `uid`, `profile_pic`  FROM user WHERE skey = :key");
		$stmt->bindValue(':key', $key);
		$stmt->execute();
		if($stmt->rowCount()==0)
			echo 'Update Failed';
		else
		{
			$fetch = $stmt->fetch();
			$uid = $fetch['uid'];
			$profile_pic = $fetch['profile_pic'];
			
			
			$extsAllowed = array( 'jpg', 'jpeg', 'png' );
	
			$extUpload = strtolower( substr( strrchr($_FILES['file']['name'], '.') ,1) ) ;
	
			if (in_array($extUpload, $extsAllowed) ) { 
				$tmp_name = $_FILES['file']['tmp_name'];
				$size = @getimagesize($tmp_name); 
				if( $size[0] != 188 || $size[1] != 188 )
				{
					echo 'Image only allow 188px x 188px';
				}
				else
				{
					$return = array();
					
					$profile_pic = $profile_pic ? uniqid(). "." . $extUpload : $profile_pic;
					
					$uploaddir = "../profile_pic/";
					$uploadfile = $uploaddir . $profile_pic;
					
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
						$stmt = $dbc->prepare("UPDATE user SET `profile_pic`=:v1 WHERE `uid` = :uid");
						$stmt->bindValue(':v1',  $profile_pic);
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
						
						$return["message"] = "File is valid, and was successfully uploaded.";
						$return["file"] = $profile_pic;
						header('Content-Type: application/json');
						echo json_encode($return);
					} else {
						echo "Possible file upload attack!";
					}
				}
			} 
			else { echo 'File is not valid. Please try again'; }
		}
	}
}

?>