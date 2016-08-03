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
  <link rel="stylesheet" href="../cv/vendor/font-awesome/css/font-awesome.min.css">
  <link href="css/print.css" type="text/css" rel="stylesheet" media="screen">
  
  <script src="../cv/vendor/jquery-1.11.1.min.js"></script>
  <script src="../cv/vendor/bootstrap/js/bootstrap.min.js"></script>
</head>

<body data-spy="scroll" data-target="#side-menu">
	<?php if($user->img_display == 0) { ?>
		<div class="col-md-10"><div class="profile-pic">
			<div class="profile-border">
			  <img src="../profile_pic/<?php echo $user->profile_pic;  ?>" alt="">
			</div>          
		</div></div>
	<?php } ?>
	
	<div class="col-md-10 pContent content-wrap bg1">
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
	
	<div class="col-md-10 timeline-wrap">
        <div class="timeline-bg">
			<section class="timeline profile-infos big_section profile_row" id="profile">
				<div class="content-wrap bg1">
					<h2 class="section-title">Profile</h2>
				</div>
				
				<div class="line ">
					<div class="content-wrap bg1">
						<div class="line-content">
						  <h3 class="section-item-title-1">Full Name</h3>
						  <h3 class="h3_input border-width-0 userdata"><? echo $user->full_name; ?></h3>
						</div>
					</div>
				</div>
				
				<div class="line ">
				  <div class="content-wrap bg1">
					<div class="line-content">
					  <h3 class="section-item-title-1">Email</h3>
					  <h3 class="h3_input border-width-0 userdata"><? echo $user->email; ?></h3>
					</div>
				  </div>
				</div>
				
				<div class="line ">
				  <div class="content-wrap bg1">
					<div class="line-content">
					  <h3 class="section-item-title-1">Phone Number</h3>
					  <h3 class="h3_input border-width-0 userdata"><? echo $user->phone; ?></h3>
					</div>
				  </div>
				</div>
			</section>
			
			<section class="timeline education big_section break" id="education">
				<div class="line ">
				  <div class="content-wrap bg1">
					<h2 class="section-title">Education</h2>
				  </div>
				</div>
				
				<? for($i=0;$i<count($education);$i++){ ?>
					<div class="item line edu_row">
						<div class="content-wrap bg1">
							<div class="line-content line-content-education">
								<h3 class="h3_input section-item-title-1"><? echo $education[$i]['major']; ?></h3>

								<i class="fa fa-university"></i><h3 class="h3_input border-width-0 zero section-item-title-1 graduation_input graduation-time"><? echo $education[$i]['school_name']; ?></h3>
							
								<br><span class="graduation-date">Graduation&nbsp;</span><h3 class="h3_input zero border-width-0 graduation_small_input graduation-date"><? echo $education[$i]['graduate_year']; ?></h3>

								<div class="graduation-description">
								  <textarea id="intro" readonly="true" name="intro" maxlength="740" class="savedata"><?
									  if($education[$i]['intro']){
										echo $education[$i]['intro'];
									  }else{
										echo "You may want to tell your employers more about your education";
									  }
								  ?></textarea>
								</div>
							</div>
						</div>
					</div>
				<? } ?>
			</section>
			
			<section class="timeline work-experience big_section break" id="works">
				<div class="line ">
				  <div class="content-wrap bg1">
					<h2 class="section-title">Work Experience</h2>
				  </div>
				</div>
				<? for($i=0;$i<count($work);$i++){ ?>
					<div class="item line work_row">
						<div class="content-wrap bg1">
							<div class="line-content line-content-education">
								<h3 class="h3_input section-item-title-1"><? echo $work[$i]['name']; ?></h3>
								<h4 class="zero border-width-0 userdata"><i class="fa fa-flag"></i>&nbsp;<? echo $work[$i]['position']; ?></h4>
								<h4 class="zero border-width-0 userdata work_small_input">
									<?php echo $work[$i]['start'];?>
								</h4>
								<h4 class="zero border-width-0 userdata work_small">
									&nbsp;-&nbsp;
								</h4>
								<h4 class="zero border-width-0 userdata work_small_input">
									<?php echo $work[$i]['end'];?>
								</h4>
								
								<div class="job-description">
									<textarea id="wIntro" readonly="true" name="wIntro"  maxlength="740" class="savedata"><?
									  if($work[$i]['intro']){
										echo $work[$i]['intro'];
									  }else{
										echo "You may want to tell your employers more about your education";
									  }
								  ?></textarea>
								  </div>
							</div>
						</div>
					</div>
				<? } ?>
			</section>
			
			
			<section class="timeline skills big_section break" id="skills">
				<div class="line ">
				  <div class="content-wrap bg1">
					<h2 class="section-title">Skills</h2>
				  </div>
				</div>
				
				<?php for( $i=0 ; $i<count($skills) ; $i++ ) { ?>
					<div class="item line skill_row">
						<div class="content-wrap bg1">
							<div class="line-content">
								<h3 class="h3_input section-item-title-1"><? echo $skills[$i]["Header"]["skName"]; ?></h3>
								
								<ul id="skl_<?=$skills[$i]["Header"]["skHid"]?>" class="skills-list">
									<?php 
										$skc = $skills[$i]["Content"];
										for( $j=0 ; $j<count($skc) ; $j++ ) {
									?>
										<li class="skill_item">
											<div class="skill_in_edit" >
												<h4 class="zero skill_name border-width-0 userdata"><? echo $skc[$j]["skill_name"]; ?></h4>
												
												<h4 class="zero skill_mark border-width-0 userdata"><? echo $skc[$j]["skill_mark"]*10?>%</h4>
											</div>
										</li>
									<? } ?>
								<ul>
							</div>
						</div>
					</div>
				<? } ?>
			</section>
			
			<section class="timeline langs big_section" id="langs">
				<div class="line ">
				  <div class="content-wrap bg1">
					<h2 class="section-title">Languages</h2>
				  </div>
				</div>
				<? for($i=0;$i<count($lang);$i++){ ?>
					<div class="content-wrap bg1">
						<div class="line-content">
							<h3 class="h3_input section-item-title-1"><? echo $lang[$i]['major']; ?></h3>
							<h3 class="zero skill_name border-width-0 userdata"><? echo $lang[$i]['lv']; ?></h3>
						</div>
					</div>
				<? } ?>
			</section>
			
			<section class="timeline interests big_section break" id="awards">
				<div class="line ">
				  <div class="content-wrap bg1">
					<h2 class="section-title">Awards</h2>
				  </div>
				</div>
				
				<? for($i=0;$i<count($award);$i++) { ?>
					<div class="item line award_row">
						<div class="content-wrap bg1">
							<div class="line-content">
								<h3 class="h3_input section-item-title-1"><? echo $award[$i]['name']; ?></h3>
								
								<textarea id="aIntro" readonly="true" name="aIntro"  maxlength="740" class="savedata"><?
									  if($award[$i]['intro']){
										echo $award[$i]['intro'];
									  }else{
										echo "You may want to tell your employers more about your award";
									  }
								  ?></textarea>
							</div>
						</div>
					</div>
				<? } ?>
			</section>
			
		</div>
	</div>
</body>
</html>