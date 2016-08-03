<?php
	require_once '../backend/config.php';
	require_once '../backend/functions.php';
	require_once '../backend/db_connection.php';
 
	if( strlen( json_encode($_POST) ) < 3 )
		echo "empty post data";
	else
	{
		$key  = $_POST['skey'];

		$stmt = $dbc->prepare ("SELECT `uid` FROM user WHERE skey = :key");
		$stmt->bindValue(':key', $key);
		$stmt->execute();
		if($stmt->rowCount()==0)
			echo "error";
		else
		{
			$fetch = $stmt->fetch();
			$uid = $fetch['uid'];
			
			$stmt1 = $dbc->prepare ("SELECT pUrl FROM user WHERE uid = :uid");
			$stmt1->bindValue(':uid', $uid);
			$stmt1->execute();
			$fetch = $stmt1->fetch();
			$pUrl = $fetch['pUrl'];
			
			if($pUrl == "")
			{
				$pUrl = uniqid();
				
				$stmt = $dbc->prepare("UPDATE user SET pUrl= :purl WHERE uid = :uid");
				$stmt->bindValue(':purl', $pUrl);
				$stmt->bindValue(':uid', $uid);
				$stmt->execute();
				
				echo $pUrl;
			}
			else
			{
				echo $pUrl;
			}
		}
	}
?>