<?php
include "../backend/config.php";
include "../backend/functions.php";
include "../backend/db_connection.php";
if($_GET['key'] == ""){	
  header('Location: '.'http://www.getmecv.com/');
}
if($_GET['key'] != ""){
  $stmt = $dbc->prepare ("SELECT * FROM user WHERE skey =?");
  $stmt->bindValue(1, $_GET['key'], PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetchObject();
}

if( is_null($user->skey) )
	header('Location: '.'http://www.getmecv.com/');

$stmt = $dbc->prepare ("SELECT * FROM work WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $user->uid, PDO::PARAM_STR);
$stmt->execute();
$work = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM education WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $user->uid, PDO::PARAM_STR);
$stmt->execute();
$education = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM award WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $user->uid, PDO::PARAM_STR);
$stmt->execute();
$award = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM lang WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $user->uid, PDO::PARAM_STR);
$stmt->execute();
$lang = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM  `skillheader` WHERE `uid` = ? ORDER BY `skListPos` ASC ");
$stmt->bindValue(1, $user->uid, PDO::PARAM_STR);
$stmt->execute();
$skillheader = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM `skill` WHERE `uid` = ? ORDER BY  list_position ASC ");
$stmt->bindValue(1, $user->uid, PDO::PARAM_STR);
$stmt->execute();
$skillcontent = $stmt->fetchAll(PDO::FETCH_ASSOC);

$skills = array();
for($i=0;$i<count($skillheader);$i++)
{
	$skHid = $skillheader[$i]['skHid'];
	$skills[$i]["Header"] = array(
			"skHid" => $skHid,
			"skName" => $skillheader[$i]['skName'],
			"skListPos" => $skillheader[$i]['skListPos']
		); 
	
	for($j=0;$j<count($skillcontent);$j++)
	{
		if( $skHid == $skillcontent[$j]['skHid'])
			$skills[$i]["Content"][] = array(
				"sid" => $skillcontent[$j]['sid'],
				"skHid" => $skHid,
				"skill_name" => $skillcontent[$j]['skill_name'],
				"skill_mark" => $skillcontent[$j]['skill_mark'],
				"list_position" => $skillcontent[$j]['list_position']
			);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
  <link href="../cv/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/print.css" type="text/css" rel="stylesheet" media="screen">
</head>

<body data-spy="scroll" data-target="#side-menu">
	<?php if($user->img_display == 0) { ?>
		<div class="profile-pic">
			<div class="profile-border">
			  <img src="../profile_pic/<?php echo $user->profile_pic;  ?>" alt="">
			</div>          
		</div>
	<?php } ?>
	
	<div class="col-md-8 pContent content-wrap bg1">
	  <!-- Welcome Title-->
	  <h1 class="intro-title1">I am <div class="color1 bold" style="display: inline-block;"><?php echo $user->full_name;  ?></div></h1>
	  <!-- /Welcome Title -->
	  <!-- Job - -->
	  <h2 class="intro-title2"><?php echo $user->major ?></h2>
	  <!-- /job -->
	  <!-- Description -->
	  <textarea id="pIntro" readonly="true" name="pIntro" maxlength="740" class="savedata"><?
		echo $user->intro;  
	  ?></textarea>
	  <!-- /Description -->
	</div>
	
	<div class="timeline-wrap">
        <div class="timeline-bg">
			<section class="timeline profile-infos big_section profile_row" id="profile">
				<div class="col-md-8 content-wrap bg1">
					<h2 class="section-title">Profile</h2>
				</div>
				
				<div class="line  row">
					<div class="col-md-9 content-wrap bg1">
						<div class="line-content">
						  <!-- Subtitle -->
						  <h3 class="section-item-title-1">Full Name</h3>
						  <!-- /Subtitle -->
						  <!-- content -->
						  <input id="user_name" readonly="true" value="<?
							  echo $user->full_name;
						  ?>" class="h3_input savedata" name="full_name"></input> 
						  <!-- /Content -->
						</div>
					</div>
				</div>
				
				<div class="line  row">
				  <div class="col-md-9 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Email</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <input id="email" readonly="true" value="<?
						  echo $user->email;
					  ?>" class="h3_input savedata" name="email"></input>
					  <!-- /Content -->
					</div>
				  </div>
				</div>
				
				<div class="line  row">
				  <div class="col-md-9 content-wrap bg1">
					<div class="line-content">
					  <h3 class="section-item-title-1">Phone Number</h3>
					  <input id="phone" readonly="true" value="<?
						if($user->phone){
						  echo $user->phone;  
						}else{
						  echo "Input your phone number";  
						}
					  ?>" class="h3_input savedata" name="phone"></input>
					</div>
				  </div>
				</div>
			</section>
		</div>
	</div>
</body>
</html>