<?php
include "../backend/config.php";
include "../backend/functions.php";
include "../backend/db_connection.php";
session_start(); 
if($_GET['query'] == "")
	header('Location: '.'http://www.getmecv.com/');

$pUrl = $_GET['query'];
$stmt = $dbc->prepare ("SELECT * FROM user WHERE pUrl =?");
$stmt->bindValue(1, $pUrl, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetchObject();
$uid = $user->uid;

$stmt = $dbc->prepare ("SELECT * FROM work WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $uid, PDO::PARAM_STR);
$stmt->execute();
$work = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM education WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $uid, PDO::PARAM_STR);
$stmt->execute();
$education = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM award WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $uid, PDO::PARAM_STR);
$stmt->execute();
$award = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM skill WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $uid, PDO::PARAM_STR);
$stmt->execute();
$skill = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userObject = json_encode($user);
$userObject = str_replace("'", "\'", $userObject);

$eduArray = json_encode($education);
$eduArray = str_replace("'", "\'", $eduArray);

$workArray = json_encode($work);
$workArray = str_replace("'", "\'", $workArray);

$awardArray = json_encode($award);
$awardArray = str_replace("'", "\'", $awardArray);

$skillArray = json_encode($skill);
$skillArray = str_replace("'", "\'", $skillArray);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Page title -->
  <title>Get Me CV</title><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Page title -->
  <title>Get Me CV</title>

  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
  <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
  <link href="vendor/nivo-lightbox/nivo-lightbox.css" rel="stylesheet">
  <link rel="stylesheet" href="vendor/nivo-lightbox/themes/default/default.css" type="text/css" />
  <link href="vendor/animate.css" rel="stylesheet">
  <link href="css/pView.css" rel="stylesheet">
  
  <link rel="shortcut icon" href="favicon-vertica.ico" type="image/x-icon">
  <link rel="icon" href="favicon-vertica.ico" type="image/x-icon">
  <link href="apple-touch-icon-144.png" rel="apple-touch-icon-precomposed" sizes="144x144">
  <link href="apple-touch-icon-114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114">
  <link href="apple-touch-icon-72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72">
  <link href="apple-touch-icon-57.png" rel="apple-touch-icon-precomposed">
  
  <script src="vendor/jquery-1.11.1.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="vendor/jquery.easing.min.js"></script>
  <script src="vendor/jquery.mousewheel.min.js"></script>
  <script src="vendor/jquery.nicescroll.min.js"></script>
  <script src="vendor/jquery.nicescroll.plus.js"></script>
  <script src="vendor/waypoints.min.js"></script>
  <script src="vendor/nivo-lightbox/nivo-lightbox.min.js"></script>
  <script src="vendor/jquery.bxslider.min.js"></script>
  <script src="vendor/jquery.validate.min.js"></script>
  <script src="vendor/placeholders.jquery.min.js"></script>
  <script src="vendor/cross-browser.js"></script>
  <script src="js/main.js"></script>
  <script src="js/Sortable.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script>
    var eduArray = [];
	var neweduArray = [];
	var workArray = [];
	var awardArray = [];
	var skilldArray = [];
	var userObject = [];

	function initObject(){
		eduArray    = $.parseJSON('<?=$eduArray?>');
		//workArray   = $.parseJSON('<?=$workArray?>');
		//awardArray  = $.parseJSON('<?=$awardArray?>');
		//skilldArray = $.parseJSON('<?=$skilldArray?>');
		userObject  = $.parseJSON('<?=$userObject?>');
		console.log(eduArray);
		console.log(workArray);
		console.log(awardArray);
		console.log(userObject);
	}
	initObject();
  </script>
</head>
<body data-spy="scroll" data-target="#side-menu">
	<div class="loader" id="page-loader"> 
	  <div class="loading-wrapper">
		<div class="tp-loader spinner"></div>
		<!-- Edit With Your Name -->
		<div class="side-menu-name">
		  <strong><?php echo $_SESSION['FULLNAME']; ?></strong>
		</div>
		<!-- /Edit With Your Name -->
		<!-- Edit With Your Job -->
		<p class="side-menu-job">Designer / Web Developer</p>
		<!-- /Edit With Your Job -->
	  </div>   
	</div>
	
	<section id="content-body" class="container animated">
		<!-- Header Colors -->
		<div class="col-md-12 col-sm-12 clearfix top-colors">
		  <div class="top-color top-color1"></div>
		  <div class="top-color top-color2"></div>
		  <div class="top-color top-color3"></div>
		  <div class="top-color top-color1"></div>
		  <div class="top-color top-color2"></div>
		</div>
		<!-- /Header Colors -->   
		
		<!-- Beginning of Content -->
		<div class="col-md-12 col-sm-12 resume-container">	  
		  <!-- =============== PROFILE INTRO ====================-->
		  <div class="profile-intro row">
			<!-- Left Collum with Avatar pic -->
			<div class="col-md-4 profile-col">
			  <!-- Avatar pic -->
			  <div class="profile-pic">
				<div class="profile-border">
				  <!-- Put your picture here ( 308px by 308px for retina display)-->
				  <img src="../profile_pic/<?php echo $user->profile_pic;  ?>" alt="">
				  <!-- /Put your picture here -->
				</div>          
			  </div>
			   <!-- /Avatar pic -->
			</div>
			<!-- /Left columm with avatar pic -->
	  
			<!-- Right Columm -->
			<div class="col-md-7">
			  <!-- Welcome Title-->
			  <h1 class="intro-title1">I am <span class="color1 bold"><?php echo $user->full_name;  ?></span></h1>
			  <!-- /Welcome Title -->
			  <!-- Job - -->
			  <h2 class="intro-title2">
				<?
				  if($user->major){
					echo $user->major;  
				  }else{
					echo "Input your Major/Occupation";  
				  }
				?>
			  </h2>
			  <!-- /job -->
			  <!-- Description -->
			  <p><? echo $user->intro;  ?></p>
			  <p>
			  </p>
			  <!-- /Description -->
			</div>
			<!-- /Right Collum -->
		  </div>
		  <!-- ============  /PROFILE INTRO ================= -->
		  
		  <!-- ============  TIMELINE ================= -->
		  <div class="timeline-wrap">
			<div class="timeline-bg">

			  <!-- ====>> SECTION: PROFILE INFOS <<====-->
			  <section class="timeline profile-infos">

				<!-- VERTICAL MARGIN (necessary for the timeline effect) -->
				<div class="line row timeline-margin">
				  <div class="content-wrap">
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height"></div>
					<div class="col-md-9 bg1 full-height"></div>
				  </div>
				</div>
				<!-- /VERTICAL MARGIN -->

				<!-- SECTION TITLE -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs timeline-title full-height">
				  </div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<!-- Section title -->
					<h2 class="section-title">Profile</h2>
					<!-- /Section title -->
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- / Margin Collum-->
				</div>
				<!-- /SECTION TITLE -->

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Full Name</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <p><?php echo $user->full_name;  ?></p>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM -->

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Email</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <p><?php echo $user->email;  ?></p>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collums -->
				</div>
				<!-- /SECTION ITEM --> 

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Phone Number</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <p>
					  <?
						if($user->phone){
						  echo $user->phone;  
						}else{
						  echo "Input your phone number";  
						}
					  ?>
					  </p>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collums -->
				</div>
				<!-- /SECTION ITEM --> 

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Find Me On</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <a href="#" class="btn btn-default"><i class="fa fa-facebook"></i></a> 
					  <a href="#" class="btn btn-default"><i class="fa fa-twitter"></i></a>
					  <a href="#" class="btn btn-default"><i class="fa fa-linkedin"></i></a>
					  <a href="#" class="btn btn-default"><i class="fa fa-link"></i></a>
					  <a href="#" class="btn btn-default"><i class="fa fa-paper-plane-o"></i></a>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum -->
				</div>
				<!-- /SECTION ITEM --> 
			  </section>
			  <!-- ==>> /SECTION: PROFILE INFOS -->     

			  <!-- ====>> SECTION: EDUCATION <<====-->
			  <section class="timeline education big_section" id="education">

				<!-- Margin (necessary for the timeline effect) -->
				<div class="line row timeline-margin">
				  <div class="content-wrap">
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height"></div>
					<div class="col-md-9 bg1 full-height"></div>
				  </div>
				</div>
				<!-- /Margin -->

				<!-- SECTION TITLE -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs timeline-title full-height">
				  </div>              
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-6 content-wrap bg1">
					<!-- Section title -->
					<h2 class="section-title">Education
					</h2>
					<!-- /Section title --> 
				  </div>
				  <!-- /Item Content -->
				  <div class="col-md-2 content-wrap bg1 full-height table_div">
					  <div class="edit_handler_div">
						<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="editEdu();"></span>
						<span id="saveEdu" class="glyphicon glyphicon-save" aria-hidden="true" onclick="saveEdu();"></span>
					  </div>
					</div>
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION TITLE -->
				<? for($i=0;$i<count($education);$i++){ ?>
				  <!-- SECTION ITEM -->
				  <div id="edu_<?=$education[$i]['eid']?>" class="item line row edu_row">
					<!-- Margin Collums (necessary for the timeline effect) -->
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-education "></div>
					<!-- /Margin Collums -->
					<!-- Item Content -->
					<div class="col-md-7 content-wrap bg1">
					  <div class="line-content line-content-education">
						<!-- Graduation title -->
						<input id="major" readonly="true" value="<?
						  if($education[$i]['major']){
							echo $education[$i]['major'];
						  }else{
							echo "Input Your Major";
						  }
						?>" class="h3_input section-item-title-1 savedata" name="major"></input>

						<!-- /Graduation title -->
						<!-- Graduation time -->
						<i class="fa fa-university"></i><input id="school_name" name="school_name" readonly="true"  class="graduation_input graduation-time savedata" value="&nbsp;<?echo $education[$i]['school_name']?>"> </input>

						<br><span class="graduation-date">Graduation&nbsp;</span><input id="graduate_year" name="graduate_year" readonly="true" class="graduation_small_input graduation-date  savedata"  class="graduation-date" value="<?
						if($education[$i]['graduate_year']){
						  echo $education[$i]['graduate_year'];
						}else{
						  echo "When did you graduate";
						}?>" > 
						
					   </input>
						<!-- /Graduation time -->
						<!-- content -->
						<div class="graduation-description">
						  <textarea id="intro" readonly="true" name="intro" class="savedata"><?
						  if($education[$i]['intro']){
							echo $education[$i]['intro'];
						  }else{
							echo "You may want to tell your employers more about your education";
						  }
						  ?></textarea>
						  <p></p>
						</div>
						<!-- /Content -->
					  </div>
					</div>
					<div class="col-md-1 content-wrap bg1 full-height table_div">
					  <span class="move_handler_span">
						<span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
					  </span>

					</div>
					<!-- /Item Content -->
					<!-- Margin Collum -->
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<!-- /Margin Collum -->
				  </div>
				  <!-- /SECTION ITEM -->     
				<? } ?>
				<div id="add_more_edu" class="item line row" onclick="addMoreEdu();">
					<!-- Margin Collums (necessary for the timeline effect) -->
					<div class="col-md-1 bg1 timeline-space hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height"></div>
					<!-- /Margin Collums -->
					<!-- Item Content -->
					<div class="col-md-7 content-wrap bg1">
					  <div class="line-content " style='text-align:center;'>
						 <h1 class="add_more"><i class="fa fa-plus" aria-hidden="true"></i> Add More</h1>
					  </div>
					</div>
					<div class="col-md-1 content-wrap bg1 full-height table_div">
					</div>
					<!-- /Item Content -->
					<!-- Margin Collum -->
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<!-- /Margin Collum -->
				  </div>
			  </section>
			  <!-- ==>> /SECTION: EDUCATION <<==== -->  

			  <!-- ====>> SECTION: WORK EXPERIENCE <<====-->
			  <section class="timeline work-experience" id="works">

				<!-- VERTICAL MARGIN (necessary for the timeline effect) -->
				<div class="line row timeline-margin">
				  <div class="content-wrap">
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height"></div>
					<div class="col-md-9 bg1 full-height"></div>
				  </div>
				</div>
				<!-- /VERTICAL MARGIN -->

				<!-- SECTION TITLE -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<!-- Section title -->
					<h2 class="section-title">Work Experience</h2>
					<!-- /Section title -->
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION TITLE -->
				<? for($i=0;$i<count($work);$i++){ ?>
				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-work "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content line-content-education">
					  <!-- Work Place -->
					  <h3 class="section-item-title-1"><? echo $work[$i]['name']?></h3>
					  <!-- /work place -->
					  <!-- Graduation time -->
					  <h4 class="job"><i class="fa fa-flag"></i>&nbsp;
					  <? 
					  if($work[$i]['position']){
						echo $work[$i]['position'];
					  }else{
						echo "What is your position";
					  } ?>&nbsp;-&nbsp;

					  <span class="job-date">
					  <? 
					  if($work[$i]['start']){
						echo $work[$i]['start'];
					  }else{
						echo "When did you start";
					  } ?>&nbsp;-&nbsp;<? 
					  if($work[$i]['start']){
						echo $work[$i]['end'];
					  }else{
						echo "When did you end";
					  }
					  ?> 
					  </span></h4>
					  <!-- /Graduation time -->
					  <!-- content -->
					  <div class="job-description">
						<p><? echo $work[$i]['intro'] ?> </p>
					  </div>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM -->      
				<? } ?>

			  </section>
			  <!-- ==>> /SECTION: WORK EXPERIENCE <<==== --> 

			  <!-- ====>> SECTION: SKILLS <<====-->
			  <section class="timeline skills" id="skills">

				<!-- VERTICAL MARGIN (necessary for the timeline effect) -->
				<div class="line row timeline-margin">
				  <div class="content-wrap">
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height"></div>
					<div class="col-md-9 bg1 full-height"></div>
				  </div>
				</div>
				<!-- /VERTICAL MARGIN -->

				<!-- SECTION TITLE -->
				<div class="line row">
				  <!-- VERTICAL MARGIN (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
				  <!-- /VERTICAL MARGIN (necessary for the timeline effect) -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<!-- Section title -->
					<h2 class="section-title">Skills</h2>
					<!-- /Section title -->
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION TITLE -->

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- / Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Professional Skills</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <ul class="skills-list">
						<!-- item-list -->
						<? for($i=0;$i<count($skills);$i++){ ?>
						  <li>
							<div class="progress">
							  <div class="progress-bar" role="progressbar" data-percent="70%" style="width: 70%;">
								  <span class="sr-only">70% Complete</span>
							  </div>
							  <span class="progress-type">Comunication</span>
							  <span class="progress-completed">70%</span>
							</div>
						  </li>
						<?}?>
						<li>
						  <div class="progress">
							<div class="progress-bar" role="progressbar" data-percent="70%" style="width: 70%;">
								<span class="sr-only">70% Complete</span>
							</div>
							<span class="progress-type">Comunication</span>
							<span class="progress-completed">70%</span>
						  </div>
						</li>
						<!-- /item list -->
						<!-- item-list -->
						<li>
						  <div class="progress">
							<div class="progress-bar progress-bar-2" role="progressbar" data-percent="90%" style="width: 90%;">
								<span class="sr-only">90% Complete</span>
							</div>
							<span class="progress-type">Leadership</span>
							<span class="progress-completed">90%</span>
						  </div>
						</li>
						<!-- /item list -->
						<!-- item-list -->
						<li>
						  <div class="progress" title="Doing my best!">
							<div class="progress-bar progress-bar-3" role="progressbar" data-percent="85%" style="width: 85%;">
								<span class="sr-only">85% Complete</span>
							</div>
							<span class="progress-type">Confidence</span>
							<span class="progress-completed">85%</span>
						  </div>
						</li>
						<!-- /item list -->
					  </ul>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM -->
				
				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Gereranl Skills</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <ul class="skills-list">
						<!-- item-list -->
						<li>
						  <div class="progress">
							<div class="progress-bar" data-percent="85%" role="progressbar" style="width: 85%;">
								<span class="sr-only">85% Complete</span>
							</div>
							<span class="progress-type">Adobe Photoshop</span>
							<span class="progress-completed">85%</span>
						  </div>
						</li>
						<!-- /item list -->
						<!-- item-list -->
						<li>
						  <div class="progress">
							<div class="progress-bar progress-bar-2" data-percent="90%" role="progressbar" style="width: 90%;">
								<span class="sr-only">90% Complete</span>
							</div>
							<span class="progress-type">Adobe Illustrator</span>
							<span class="progress-completed">90%</span>
						  </div>
						</li>
						<!-- /item list -->
						<!-- item-list -->
						<li>
						  <div class="progress">
							<div class="progress-bar progress-bar-3" data-percent="40%" role="progressbar" style="width: 40%;">
								<span class="sr-only">40% Complete</span>
							</div>
							<span class="progress-type">Adobe Fireworks</span>
							<span class="progress-completed">40%</span>
						  </div>
						</li>
						<!-- /item list -->
					  </ul>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM -->   
			  </section>
			  <!-- ==>> /SECTION: SKILLS -->

			  <!-- ====>> SECTION: INTERESTS <<====-->
			  <section class="timeline" id="interests">

				<!-- VERTICAL MARGIN (necessary for the timeline effect) -->
				<div class="line row timeline-margin">
				  <div class="content-wrap">
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height"></div>
					<div class="col-md-9 bg1 full-height"></div>
				  </div>
				</div>
				<!-- /VERTICAL MARGIN -->

				<!-- SECTION TITLE -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<!-- Section title -->
					<h2 class="section-title">Awards</h2>
					<!-- /Section title -->
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION TITLE -->

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Art</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <p>Praesent tellus ligula, tincidunt et fringilla vel, tincidunt ut dui. Nulla feugiat, lacus ac malesuada lobortis, elit nunc congue nunc, vel imperdiet lorem leo a lectus.</p>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM -->
				
				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Games</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <p>Praesent tellus ligula, tincidunt et fringilla vel, tincidunt ut dui. Nulla feugiat, lacus ac malesuada lobortis, elit nunc congue nunc, vel imperdiet lorem leo a lectus.</p>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM -->   

				<!-- SECTION ITEM -->
				<div class="line row">
				  <!-- Margin Collums (necessary for the timeline effect) -->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
				  <!-- /Margin Collums -->
				  <!-- Item Content -->
				  <div class="col-md-8 content-wrap bg1">
					<div class="line-content">
					  <!-- Subtitle -->
					  <h3 class="section-item-title-1">Books</h3>
					  <!-- /Subtitle -->
					  <!-- content -->
					  <p>Praesent tellus ligula, tincidunt et fringilla vel, tincidunt ut dui. Nulla feugiat, lacus ac malesuada lobortis, elit nunc congue nunc, vel imperdiet lorem leo a lectus.</p>
					  <!-- /Content -->
					</div>
				  </div>
				  <!-- /Item Content -->
				  <!-- Margin Collum-->
				  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
				  <!-- /Margin Collum-->
				</div>
				<!-- /SECTION ITEM --> 
			  </section>
			  <!-- ==>> /SECTION: INTERESTS -->
			</div>
		  </div>
		  <!-- ============  /TIMELINE ================= -->
		  
		  <!-- ============  FOOTER ================= -->
		  <footer id="footer" class="row"></footer>
		</div>
	</section>
</body>
</html>