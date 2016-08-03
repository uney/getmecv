<?php
	require_once '../backend/config.php';
	require_once '../backend/functions.php';
	require_once '../backend/db_connection.php';
 
	if( strlen( json_encode($_POST) ) < 3 )
		echo "empty post data";
	else
	{
		$key  = $_POST['skey'];
		$type = $_POST['type'];
		$data = $_POST['data'];
 
		$stmt = $dbc->prepare ("SELECT `uid` FROM user WHERE skey = :key");
		$stmt->bindValue(':key', $key);
		$stmt->execute();
		if($stmt->rowCount()==0)
			echo 'Update Failed';
		else
		{
			$return = array();
			$delete = array();
			$updated = array();
			
			$fetch = $stmt->fetch();
			$uid = $fetch['uid'];
			switch ($type) {
				case "profile_intro":
				{
					//UPDATE `user` SET `major`=[value-5],`intro`=[value-6] WHERE `uid` = ?
					foreach($data as $item)
					{
						$uid = $item['uid'];
						$hide = $item['hide'];
						$intro = $item['intro'];
						$major = $item['major'];
						
						$stmt = $dbc->prepare("UPDATE user SET `major`=:v1,`intro`=:v2, img_display=:v3 WHERE `uid` = :uid");
						$stmt->bindValue(':v1',  $major);
						$stmt->bindValue(':v2',  $intro);
						$stmt->bindValue(':v3',  $hide);
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
					}
				}
				break;
				case "profile":
				{
					//UPDATE `user` SET `full_name`=:v1,`phone`=:v2, `email`=:v3 WHERE `uid` = :uid
					foreach($data as $item)
					{
						$uid = $item['uid'];
						$full_name = $item['full_name'];
						$email = $item['email'];
						$phone = $item['phone'];
						
						$stmt = $dbc->prepare("UPDATE `user` SET `full_name`=:v1,`phone`=:v2, `email`=:v3 WHERE `uid` = :uid");
						$stmt->bindValue(':v1',  $full_name);
						$stmt->bindValue(':v2',  $phone);
						$stmt->bindValue(':v3',  $email);
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
					}
				}
				break;
				case "education":
				{
					$stmt = $dbc->prepare("SELECT `eid` FROM education WHERE uid = :uid");
					$stmt->bindValue(':uid', $uid);
					$stmt->execute();
					$eids = $stmt->fetchAll();

					$exitsEids = array();
					foreach($eids as $value)
						$exitsEids[] = $value['eid'];
						
					if(count($data) == 0)
					{
						$stmt = $dbc->prepare("delete from education where uid = :uid");
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
					}
					else
					{
						$tmp = array();
						foreach($data as $item)
						{
							$eid = $item['eid'];
							if (strpos($eid, 'new_') !== false) {
								// Add new_
								$school_name = $item['school_name'];
								$graduate_year = $item['graduate_year'];
								$intro = $item['intro'];
								$type = $item['type'];
								$major = $item['major'];
								$list_position = $item['list_position'];
 
								$stmt = $dbc->prepare("INSERT INTO `education`(`uid`, `school_name`, `graduate_year`, `program_name`, `intro`, `type`, `major`, `list_position`) VALUES (:v1,:v2,:v3,:v4,:v5,:v6,:v7,:v8)");
								$stmt->bindValue(':v1', $uid);
								$stmt->bindValue(':v2', $school_name);
								$stmt->bindValue(':v3', $graduate_year);
								$stmt->bindValue(':v4', "");
								$stmt->bindValue(':v5', $intro);
								$stmt->bindValue(':v6', $type);
								$stmt->bindValue(':v7', $major);
								$stmt->bindValue(':v8', $list_position);
								$stmt->execute();
 
								$stmt = $dbc->prepare ("SELECT `eid` FROM education WHERE uid = :uid AND school_name = :v1 AND major = :v2");
								$stmt->bindValue(':v1',  $school_name);
								$stmt->bindValue(':v2',  $major);
								$stmt->bindValue(':uid', $uid);
								$stmt->execute();

								$fetch = $stmt->fetch();
								$new_eid = $fetch['eid'];
								
								$tmp = array();
								$tmp["old"] = $eid;
								$tmp["new"] = $new_eid;
								
								$return[] = $tmp;
							}
							else
							{
								$school_name = $item['school_name'];
								$graduate_year = $item['graduate_year'];
								$intro = $item['intro'];
								$type = $item['type'];
								$major = $item['major'];
								$list_position = $item['list_position'];

								$stmt = $dbc->prepare("UPDATE `education` SET `school_name`=:v1,`graduate_year`=:v2,`intro`=:v3,`type`=:v4,`major`=:v5,`list_position`=:v6 WHERE eid = :eid");
								$stmt->bindValue(':v1',  $school_name);
								$stmt->bindValue(':v2',  $graduate_year);
								$stmt->bindValue(':v3',  $intro);
								$stmt->bindValue(':v4',  $type);
								$stmt->bindValue(':v5',  $major);
								$stmt->bindValue(':v6',  $list_position);
								$stmt->bindValue(':eid', $eid);
								$stmt->execute();
								
								$updated[] = $eid;
							}
						}
						
						foreach(array_diff($exitsEids, $updated) as $value)
						{
							$stmt = $dbc->prepare("delete from education where eid = :eid AND uid = :uid");
							$stmt->bindValue(':eid', $value);
							$stmt->bindValue(':uid', $uid);
							$stmt->execute();
						}
					}
					
					header('Content-Type: application/json');
					echo json_encode($return);
				}
				break;
				case "works":
				{
					//UPDATE `user` SET `full_name`=:v1,`phone`=:v2, `email`=:v3 WHERE `uid` = :uid
					$stmt = $dbc->prepare("SELECT `wid` FROM work WHERE uid = :uid");
					$stmt->bindValue(':uid', $uid);
					$stmt->execute();
					$wids = $stmt->fetchAll();

					$exitsWids = array();
					foreach($wids as $value)
						$exitsWids[] = $value['wid'];
						
					if(count($data) == 0)
					{
						$stmt = $dbc->prepare("delete from work where uid = :uid");
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
					}
					else
					{
						$tmp = array();
						
						foreach($data as $item)
						{
							$wid = $item['wid'];
							if (strpos($wid, 'new_') !== false) {
								// Add new_
								$name = $item['name'];
								$position = $item['position'];
								$start = $item['start'];
								$end = $item['end'];
								$intro = $item['intro'];
								$list_position = $item['list_position'];
 
								$stmt = $dbc->prepare("INSERT INTO `work`(`uid`, `name`, `position`, `start`, `end`, `intro`, `list_position`) VALUES (:uid, :v1,:v2,:v3,:v4,:v5,:v6)");
								$stmt->bindValue(':uid', $uid);
								$stmt->bindValue(':v1', $name);
								$stmt->bindValue(':v2', $position);
								$stmt->bindValue(':v3', $start);
								$stmt->bindValue(':v4', $end);
								$stmt->bindValue(':v5', $intro);
								$stmt->bindValue(':v6', $list_position);
								$stmt->execute();
 
								$stmt = $dbc->prepare ("SELECT `wid` FROM work WHERE uid = :uid AND name = :v1 AND intro = :v2");
								$stmt->bindValue(':v1',  $name);
								$stmt->bindValue(':v2',  $intro);
								$stmt->bindValue(':uid', $uid);
								$stmt->execute();

								$fetch = $stmt->fetch();
								$new_eid = $fetch['wid'];
								
								$tmp = array();
								$tmp["old"] = $wid;
								$tmp["new"] = $new_eid;
								
								$return[] = $tmp;
							}
							else
							{
								$name = $item['name'];
								$position = $item['position'];
								$start = $item['start'];
								$end = $item['end'];
								$intro = $item['intro'];
								$list_position = $item['list_position'];

								$stmt = $dbc->prepare("UPDATE `work` SET `name`=:v1,`position`=:v2,`start`=:v3,`end`=:v4,`intro`=:v5,`list_position`=:v6 WHERE wid = :wid");
								$stmt->bindValue(':v1',  $name);
								$stmt->bindValue(':v2',  $position);
								$stmt->bindValue(':v3',  $start);
								$stmt->bindValue(':v4',  $end);
								$stmt->bindValue(':v5',  $intro);
								$stmt->bindValue(':v6',  $list_position);
								$stmt->bindValue(':wid', $wid);
								$stmt->execute();
								
								$updated[] = $wid;
							}
						}
						
						foreach(array_diff($exitsWids, $updated) as $value)
						{
							$stmt = $dbc->prepare("delete from work where wid = :wid AND uid = :uid");
							$stmt->bindValue(':wid', $value);
							$stmt->bindValue(':uid', $uid);
							$stmt->execute();
						}
						
						header('Content-Type: application/json');
						echo json_encode($return);
					}
				}
				break;
				case "awards":
				{
					$stmt = $dbc->prepare("SELECT `aid` FROM award WHERE uid = :uid");
					$stmt->bindValue(':uid', $uid);
					$stmt->execute();
					$aids = $stmt->fetchAll();

					$exitsAids = array();
					foreach($aids as $value)
						$exitsAids[] = $value['aid'];
						
					if(count($data) == 0)
					{
						$stmt = $dbc->prepare("delete from award where uid = :uid");
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
					}
					else
					{
						$tmp = array();
						
						foreach($data as $item)
						{
							$aid = $item['aid'];
							if (strpos($aid, 'new_') !== false) {
								// Add new_
								$name = $item['name'];
								$intro = $item['intro'];
								$list_position = $item['list_position'];
 
								$stmt = $dbc->prepare("INSERT INTO `award`(`uid`, `name`, `intro`, `list_position`) VALUES (:uid,:v1,:v2,:v3)");
								$stmt->bindValue(':uid', $uid);
								$stmt->bindValue(':v1', $name);
								$stmt->bindValue(':v2', $intro);
								$stmt->bindValue(':v3', $list_position);
								$stmt->execute();
 
								$stmt = $dbc->prepare ("SELECT `aid` FROM award WHERE uid = :uid AND name = :v1 AND intro = :v2");
								$stmt->bindValue(':v1',  $name);
								$stmt->bindValue(':v2',  $intro);
								$stmt->bindValue(':uid', $uid);
								$stmt->execute();

								$fetch = $stmt->fetch();
								$new_eid = $fetch['aid'];
								
								$tmp = array();
								$tmp["old"] = $aid;
								$tmp["new"] = $new_eid;
								
								$return[] = $tmp;
							}
							else
							{
								$name = $item['name'];
								$intro = $item['intro'];
								$list_position = $item['list_position'];

								$stmt = $dbc->prepare("UPDATE `award` SET `name`=:v1, `intro`=:v2,`list_position`=:v3 WHERE aid = :aid");
								$stmt->bindValue(':v1',  $name);
								$stmt->bindValue(':v2',  $intro);
								$stmt->bindValue(':v3',  $list_position);
								$stmt->bindValue(':aid', $aid);
								$stmt->execute();
								
								$updated[] = $aid;
							}
						}
						
						foreach(array_diff($exitsAids, $updated) as $value)
						{
							$stmt = $dbc->prepare("delete from award where aid = :aid AND uid = :uid");
							$stmt->bindValue(':aid', $value);
							$stmt->bindValue(':uid', $uid);
							$stmt->execute();
						}
						
						header('Content-Type: application/json');
						echo json_encode($return);
					}
				}
				break;
				case "langs":
				{
					$stmt = $dbc->prepare("SELECT `lid` FROM `lang` WHERE uid = :uid");
					$stmt->bindValue(':uid', $uid);
					$stmt->execute();
					$eids = $stmt->fetchAll();

					$exitsLids = array();
					foreach($eids as $value)
						$exitsLids[] = $value['lid'];
						
					if(count($data) == 0)
					{
						$stmt = $dbc->prepare("delete from `lang` where uid = :uid");
						$stmt->bindValue(':uid', $uid);
						$stmt->execute();
					}
					else
					{
						$tmp = array();
						foreach($data as $item)
						{
							$lid = $item['lid'];
							if (strpos($lid, 'new_') !== false) {
								// Add new_
								$major = $item['major'];
								$lv    = $item['lv'];
								$list_position = $item['list_position'];
 
								$stmt = $dbc->prepare("INSERT INTO `lang`(`uid`, `major`, `lv`, `list_position`) VALUES (:v1,:v2,:v3,:v4)");
								$stmt->bindValue(':v1', $uid);
								$stmt->bindValue(':v2', $major);
								$stmt->bindValue(':v3', $lv);
								$stmt->bindValue(':v4', $list_position);
								$stmt->execute();
 
								$stmt = $dbc->prepare ("SELECT `lid` FROM `lang` WHERE uid = :uid AND lv = :v1 AND major = :v2");
								$stmt->bindValue(':v1',  $lv);
								$stmt->bindValue(':v2',  $major);
								$stmt->bindValue(':uid', $uid);
								$stmt->execute();

								$fetch = $stmt->fetch();
								$new_lid = $fetch['lid'];
								
								$tmp = array();
								$tmp["old"] = $lid;
								$tmp["new"] = $new_lid;
								
								$return[] = $tmp;
							}
							else
							{
								$major = $item['major'];
								$lv    = $item['lv'];
								$list_position = $item['list_position'];

								$stmt = $dbc->prepare("UPDATE `lang` SET `lv`=:v1, `major`=:v2, `list_position`=:v3 WHERE lid = :lid");
								$stmt->bindValue(':v1',  $lv);
								$stmt->bindValue(':v2',  $major);
								$stmt->bindValue(':v3',  $list_position);
								$stmt->bindValue(':lid', $lid);
								$stmt->execute();
								
								$updated[] = $eid;
							}
						}
						
						foreach(array_diff($exitsLids, $updated) as $value)
						{
							$stmt = $dbc->prepare("delete from `lang` where lid = :lid AND uid = :uid");
							$stmt->bindValue(':lid', $value);
							$stmt->bindValue(':uid', $uid);
							$stmt->execute();
						}
					}
					
					header('Content-Type: application/json');
					echo json_encode($return);
				}
				break;
				case "skills":
				{
					$temp = array();
					
					$stmt1 = $dbc->prepare("SELECT `skHid` FROM `skillheader` WHERE uid = :uid");
					$stmt1->bindValue(':uid', $uid);
					$stmt1->execute();
					$skHids = $stmt1->fetchAll();

					foreach($skHids as $value)
						$temp["HeaderExist"][] = $value['skHid'];
					
					if(count($data) == 0)
					{
						//"Delete skillheader, skill";
						$stmt1 = $dbc->prepare("DELETE FROM `skillheader` where uid = :uid");
						$stmt1->bindValue(':uid', $uid);
						$stmt1->execute();
						
						$stmt2 = $dbc->prepare("DELETE FROM `skill` where uid = :uid");
						$stmt2->bindValue(':uid', $uid);
						$stmt2->execute();
					}
					else
					{
						$tmp = array();
						foreach($data as $item)
						{
							// Header Part
							$header  = $item["Header"];
							
							$skHid = $header['skHid'];
							if (strpos($skHid, 'new_') !== false) {
								// Add new_
								$skName    = $header['skName'];
								$skListPos = $header['skListPos'];
 
								$stmt = $dbc->prepare("INSERT INTO `skillheader`(`uid`, `skName`, `skListPos`) VALUES (:v1, :v2, :v3)");
								$stmt->bindValue(':v1', $uid);
								$stmt->bindValue(':v2', $skName);
								$stmt->bindValue(':v3', $skListPos);
								$stmt->execute();
 
								$stmt = $dbc->prepare("SELECT `skHid` FROM `skillheader` WHERE uid = :uid AND skName = :v1 AND skListPos = :v2");
								$stmt->bindValue(':v1',  $skName);
								$stmt->bindValue(':v2',  $skListPos);
								$stmt->bindValue(':uid', $uid);
								$stmt->execute();

								$fetch = $stmt->fetch();
								$new_skHid = $fetch['skHid'];
								
								$tmp = array();
								$tmp["old"] = $skHid;
								$tmp["new"] = $new_skHid;
								
								$skHid  = $new_skHid;
								
								$return["Header"][] = $tmp;
							} 
							else {
								$skName    = $header['skName'];
								$skListPos = $header['skListPos'];
								
								$stmt1 = $dbc->prepare("UPDATE `skillheader` SET `skName`=:v1,`skListPos`=:v2 WHERE skHid = :skHid AND uid = :uid");
								$stmt1->bindValue(':v1',    $skName);
								$stmt1->bindValue(':v2',    $skListPos);
								$stmt1->bindValue(':skHid', $skHid);
								$stmt1->bindValue(':uid',   $uid);
								$stmt1->execute();

								$temp["HeaderUpdated"][] = $skHid;
							}
							
							// Content Part
							$stmt2 = $dbc->prepare("SELECT `sid` FROM `skill` WHERE skHid = :skHid AND uid = :uid");
							$stmt2->bindValue(':uid',   $uid);
							$stmt2->bindValue(':skHid', $skHid);
							$stmt2->execute();
							$sids = $stmt2->fetchAll();

							foreach($sids as $value)
								$temp["ContentExist"][] = $value['sid'];
							
							$content = $item["Content"];
							if(count($content) == 0)
							{
								$stmt2 = $dbc->prepare("DELETE FROM `skill` where skHid = :skHid AND uid = :uid");
								$stmt2->bindValue(':uid', $uid);
								$stmt2->bindValue(':skHid', $skHid);
								$stmt2->execute();
							} 
							else {
																
								foreach($content as $contentitem)
								{
									$sid = $contentitem['sid'];
									if (strpos($sid, 'new_') !== false) {
										// Add new_
										$skill_name    = $contentitem['skill_name'];
										$skill_mark    = $contentitem['skill_mark'];
										$list_position = $contentitem['list_position'];
										
										$stmt = $dbc->prepare("INSERT INTO `skill`(`uid`, `skHid`, `skill_name`, `skill_mark`, `list_position`) VALUES (:v1,:v2,:v3,:v4,:v5)");
										$stmt->bindValue(':v1', $uid);
										$stmt->bindValue(':v2', $skHid);
										$stmt->bindValue(':v3', $skill_name);
										$stmt->bindValue(':v4', $skill_mark);
										$stmt->bindValue(':v5', $list_position);
										$stmt->execute();
										
										$stmt = $dbc->prepare("SELECT `sid` FROM `skill` WHERE uid = :uid AND skHid = :v1 AND skill_name = :v2 AND skill_mark = :v3");
										$stmt->bindValue(':v1',  $skHid);
										$stmt->bindValue(':v2',  $skill_name);
										$stmt->bindValue(':v3',  $skill_mark);
										$stmt->bindValue(':uid', $uid);
										$stmt->execute();
										
										$fetch = $stmt->fetch();
										$new_sid = $fetch['sid'];
										
										$tmp = array();
										$tmp["old"] = $sid;
										$tmp["new"] = $new_sid;
										
										$return["Content"][] = $tmp;
									}
									else
									{
										$skill_name    = $contentitem['skill_name'];
										$skill_mark    = $contentitem['skill_mark'];
										$list_position = $contentitem['list_position'];
										
										$stmt2 = $dbc->prepare("UPDATE `skill` SET `skill_name`=:v1,`skill_mark`=:v2,`list_position`=:v3 WHERE skHid = :skHid AND uid = :uid AND sid = :sid");
										$stmt2->bindValue(':v1',    $skill_name);
										$stmt2->bindValue(':v2',    $skill_mark);
										$stmt2->bindValue(':v3',    $list_position);
										$stmt2->bindValue(':skHid', $skHid);
										$stmt2->bindValue(':sid',   $sid);
										$stmt2->bindValue(':uid',   $uid);
										$stmt2->execute();
										
										$temp["ContentUpdated"][] = $sid;
									}
								}
							}
						}
						
						// delete skill header and skill
						$updated["Header"] = array_diff($temp["HeaderExist"], $temp["HeaderUpdated"]);
						foreach($updated["Header"] as $value)
						{
							//"Delete skillheader, skill";
							$stmt1 = $dbc->prepare("DELETE FROM `skillheader` where uid = :uid AND skHid = :skHid");
							$stmt1->bindValue(':uid', $uid);
							$stmt1->bindValue(':skHid', $value);
							$stmt1->execute();
							
							$stmt1 = $dbc->prepare("DELETE FROM `skill` where uid = :uid AND skHid = :skHid");
							$stmt1->bindValue(':uid', $uid);
							$stmt1->bindValue(':skHid', $value);
							$stmt1->execute();
						}
						
						// delete skill header and skill
						$updated["Content"] = array_diff($temp["ContentExist"], $temp["ContentUpdated"]);
						foreach($updated["Content"] as $value)
						{
							//"Delete skillheader, skill";
							$stmt1 = $dbc->prepare("DELETE FROM `skill` where uid = :uid AND sid = :sid");
							$stmt1->bindValue(':uid', $uid);
							$stmt1->bindValue(':sid', $value);
							$stmt1->execute();
						}

						header('Content-Type: application/json');
						echo json_encode($return);
					}
				}
				break;
			}
		}
	}
	
?>