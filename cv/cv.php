<?php
include "../backend/config.php";
include "../backend/functions.php";
include "../backend/db_connection.php";
session_start(); 
//if(!$user){
if($_SESSION['SKEY'] == ""){	
  //redirect
  header('Location: '.'http://www.getmecv.com/');
}
if($_GET['id']!=""){
  $stmt = $dbc->prepare ("SELECT * FROM user WHERE uid =?");
  $stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetchObject();
}
 
if($_SESSION['SKEY'] !== $user->skey)
	header('Location: '.'http://www.getmecv.com/');

$stmt = $dbc->prepare ("SELECT * FROM work WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$work = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM education WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$education = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM award WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$award = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM lang WHERE uid =? ORDER BY list_position ASC");
$stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$lang = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM  `skillheader` WHERE `uid` = ? ORDER BY `skListPos` ASC ");
$stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$skillheader = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbc->prepare ("SELECT * FROM `skill` WHERE `uid` = ? ORDER BY  list_position ASC ");
$stmt->bindValue(1, $_GET['id'], PDO::PARAM_STR);
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

$userObject = json_encode($user);
$userObject = str_replace("'", "\'", $userObject);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Page title -->
  <title>Get Me CV</title>
  <!-- /Page title -->

  <!-- CSS Files
  ========================================================= -->
  <!-- Google web fonts - Open Sans -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
  <!-- /Google web fonts -->
  <link href="css/jqueryui.min.css" rel="stylesheet">
  <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="css/toastr.css" rel="stylesheet"/>
  <!-- Bootstrap CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- /Bootstrap CSS -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
  <!-- /Font Awesome -->
  <!-- Nivo Lightbox -->
  <link href="vendor/nivo-lightbox/nivo-lightbox.css" rel="stylesheet">
  <link rel="stylesheet" href="vendor/nivo-lightbox/themes/default/default.css" type="text/css" />
  <!-- /Nivo Lightbox -->
  <!-- Animate CSS -->
  <link href="vendor/animate.css" rel="stylesheet">
  <!-- /Animate CSS -->
  <!-- Theme Styles -->
  <link href="css/styles.css" rel="stylesheet">
  <!-- Theme Styles -->
  <!-- / CSS Files
  ========================================================= -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="vendor/html5shiv.js"></script>
    <script src="vendor/respond.min.js"></script>
  <![endif]-->

  <!-- Favicon and Touch Icons
  ========================================================= -->
  <link rel="shortcut icon" href="favicon-vertica.ico" type="image/x-icon">
  <link rel="icon" href="favicon-vertica.ico" type="image/x-icon">
  <link href="apple-touch-icon-144.png" rel="apple-touch-icon-precomposed" sizes="144x144">
  <link href="apple-touch-icon-114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114">
  <link href="apple-touch-icon-72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72">
  <link href="apple-touch-icon-57.png" rel="apple-touch-icon-precomposed">
  <!-- /Favicon
  ========================================================= -->

  <!-- Javascript Files
  ========================================================= -->
  <!-- jQuery (necessary for plugins) -->
  <script src="vendor/jquery-1.11.1.min.js"></script>
  <script src="js/jqueryui.min.js"></script>
  <!-- /jquery -->
  <!-- bootstrap -->
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <!-- /bootstrap -->
  <!-- easing -->
  <script src="vendor/jquery.easing.min.js"></script>
  <!-- /easing -->
  <!-- jQuery Mousewheel -->
  <script src="vendor/jquery.mousewheel.min.js"></script>
  <!-- /jQuery Mousewheel -->
  <!-- jQuery Mousewheel Smoothscroll -->
  <script src="vendor/jquery.nicescroll.min.js"></script>
  <script src="vendor/jquery.nicescroll.plus.js"></script>
  <!-- /jQuery Mousewheel Smoothscroll -->
  <!-- Waypoints (for CSS3 animations) -->
  <script src="vendor/waypoints.min.js"></script>
  <!-- /waypoints -->
  <!-- Modal box-->
  <script src="vendor/nivo-lightbox/nivo-lightbox.min.js"></script>
  <!-- /Modal Box -->
  <!-- Carousel-->
  <script src="vendor/jquery.bxslider.min.js"></script>
  <!-- /Carousel -->
  <!-- Front-end Validator (for forms) -->
  <script src="vendor/jquery.validate.min.js"></script>
  <!-- /Front-end Validator -->
  <!-- placeholder Support for IE -->
  <script src="vendor/placeholders.jquery.min.js"></script>
  <!-- /placeholder support -->
  <!-- Cross-browser -->
  <script src="vendor/cross-browser.js"></script>
  <!-- /Cross-browser -->
  <!-- Configurations -->
  <script src="js/main.js"></script>
  <script src="js/toastr.min.js"></script>
  <script src="js/Sortable.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script>
	var userObject = [];

	function initObject(){
		userObject  = $.parseJSON('<?=$userObject?>');
		//console.log(userObject);
	}
	initObject();
  </script>
  <script src="js/cv.js"></script>
</head>

<body data-spy="scroll" data-target="#side-menu">
<!-- Page Loader
========================================================= -->
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
<!-- /End of Page loader
========================================================= -->

<!-- CONTENT
========================================================= -->
<section id="content-body" class="container animated">
  <div class="row" id="intro">

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
      
      <!-- Header Buttons -->
      <div class="row">
        <div class="header-buttons col-md-11 col-md-offset-1">
          <!-- Download Resume Button -->
          <a href="#" onclick="getPdf();" class="btn btn-default btn-top-resume"><i class="fa fa-download"></i><span class="btn-hide-text">Download my resume</span></a>
          <!-- /Download Resume Button -->
          <!-- View Resume Button -->
          <a href="#" id="view_public" class="btn btn-default btn-top-view"><i class="fa fa-eye"></i><span class="btn-hide-text">View as public</span></a>
          
        </div>
      </div>
      <!-- /Header Buttons -->

      <!-- =============== PROFILE INTRO ====================-->
      <div class="profile-intro row intro_row" id="profile_intro">
        <section class="big_section">
			<!-- Left Collum with Avatar pic -->
			<div class="col-md-3 profile-col">
			  <!-- Avatar pic -->
			  <div class="profile-pic" style="<?php if($user->img_display == 1) echo "background: #249991"; ?> ">
				<div class="profile-border">  
				  <input id="profile_img" type="file" name="profile_img" style="display: none;">
					<img id="profile_curr_img" class="<?php if($user->img_display == 1) echo "hidden"; ?> " src="../profile_pic/<?php echo $user->profile_pic;  ?>" alt="" onclick="upload_img();">
				</div>          
			  </div>
			   <!-- /Avatar pic -->
			   <div class="col-md-4 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
			   <div class="col-md-4 timeline-progress hidden-sm hidden-xs full-height"></div>
			</div>
			<!-- /Left columm with avatar pic -->
			
			<div class="col-md-7 edit_handler_div content-wrap bg1 full-height table_div ">
			  <div class="edit_handler_div">
				<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('profile_intro', '')"></span>
				<span id="profile_intro_save" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('profile_intro', 'intro_')"></span>
			  </div>
			</div>
 
			<!-- Right Columm -->
			<div class="col-md-8 pContent content-wrap bg1">
			  <div id="hide_pro_pic" class="hidden"><input type="checkbox" value="1" name="hide_pro_pic" <?php echo($user->img_display ? "checked"  : "" )?> >Hide Profile picture</input></div>
			  <!-- Welcome Title-->
			  <h1 class="intro-title1">I am <span class="color1 bold"><?php echo $user->full_name;  ?></span></h1>
			  <!-- /Welcome Title -->
			  <!-- Job - -->
			  <h2 class="intro-title2">
				<input id="pMajor" readonly="true" value="<?
				  if($user->major){
					echo $user->major;  
				  }else{
					echo "Input your Major/Occupation";  
				  }
				?>" class="h3_input savedata" name="pMajor"></input> 
			  </h2>
			  <!-- /job -->
			  <!-- Description -->
			  <textarea id="pIntro" readonly="true" name="pIntro" maxlength="740" class="savedata"><?
				echo $user->intro;  
			  ?></textarea>
			  <!-- /Description -->
			</div>
			<!-- /Right Collum -->
			
			<!-- Margin Collum -->
            <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
            <!-- /Margin Collum -->
		</section>
      </div>
      <!-- ============  /PROFILE INTRO ================= -->
      
      <!-- ============  TIMELINE ================= -->
      <div class="timeline-wrap">
        <div class="timeline-bg">

          <!-- ====>> SECTION: PROFILE INFOS <<====-->
          <section class="timeline profile-infos big_section profile_row" id="profile">

            <!-- SECTION TITLE -->
            <div class="line row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs timeline-title full-height">
              </div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-8 content-wrap bg1">
                <!-- Section title -->
                <h2 class="section-title">Profile</h2>
                <!-- /Section title -->
              </div>
			  <div class="col-md-1 edit_handler_div content-wrap bg1 full-height table_div">
				  <div class="edit_handler_div">
					<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('profile', '')"></span>
					<span id="profile_save" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('profile', 'profile_')"></span>
				  </div>
			  </div>
              <!-- /Item Content -->
            </div>
            <!-- /SECTION TITLE -->

            <!-- SECTION ITEM -->
            <div class="line  row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
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
              <!-- /Item Content -->
            </div>
            <!-- /SECTION ITEM -->

            <!-- SECTION ITEM -->
            <div class="line  row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
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
              <!-- /Item Content -->
            </div>
            <!-- /SECTION ITEM --> 

            <!-- SECTION ITEM -->
            <div class="line  row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-9 content-wrap bg1">
                <div class="line-content">
                  <!-- Subtitle -->
                  <h3 class="section-item-title-1">Phone Number</h3>
                  <!-- /Subtitle -->
                  <!-- content -->
                  <input id="phone" readonly="true" value="<?
                    if($user->phone){
                      echo $user->phone;  
                    }else{
                      echo "Input your phone number";  
                    }
                  ?>" class="h3_input savedata" name="phone"></input>
                  <!-- /Content -->
                </div>
              </div>
              <!-- /Item Content -->
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
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-10 bg1 full-height"></div>
              </div>
            </div>
            <!-- /Margin -->

            <!-- SECTION TITLE -->
            <div class="line row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs timeline-title full-height">
              </div>              
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-8 content-wrap bg1">
                <!-- Section title -->
                <h2 class="section-title">Education
                </h2>
                <!-- /Section title --> 
              </div>
              <!-- /Item Content -->
              <div class="col-md-1 edit_handler_div content-wrap bg1 full-height table_div">
                  <div class="edit_handler_div">
                    <span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('education', 'edu_')"></span>
                    <span id="saveEdu" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('education', 'edu_')"></span>
                  </div>
              </div>
              <!-- Margin Collum-->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <!-- /Margin Collum-->
            </div>
            <!-- /SECTION TITLE -->
            <? for($i=0;$i<count($education);$i++){ ?>
              <!-- SECTION ITEM -->
              <div id="edu_<?=$education[$i]['eid']?>" data-edu_id="<?=$education[$i]['eid']?>" class="item line row edu_row" list_position="<?=$education[$i]['list_position']?>">
                <!-- Margin Collums (necessary for the timeline effect) -->
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-education "></div>
                <!-- /Margin Collums -->
                <!-- Item Content -->
                <div class="col-md-9 content-wrap bg1">
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

                    <br><span class="graduation-date">Graduation&nbsp;</span><input id="graduate_year" name="graduate_year" readonly="true" class="graduation_small_input graduation-date  savedata needDatePicker"  class="graduation-date" value="<?
                    if($education[$i]['graduate_year']){
                      echo $education[$i]['graduate_year'];
                    }else{
                      echo  "When did you_graduate";
                    }?>" > 
                    
                   </input>
                    <!-- /Graduation time -->
                    <!-- content -->
                    <div class="graduation-description">
                      <textarea id="intro" readonly="true" name="intro" maxlength="740" class="savedata"><?
						  if($education[$i]['intro']){
							echo $education[$i]['intro'];
						  }else{
							echo "You may want to tell your employers more about your education";
						  }
                      ?></textarea>
                    </div>
                    <!-- /Content -->
                  </div>
                </div>
                <div class="col-md-1 move_handler content-wrap bg1 full-height table_div">
                  <span class="move_handler_span">
                    <span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                  </span>
                  <span class="move_handler_span">
                    <i class="pull-right fa fa-trash-o" aria-hidden="true" parent="edu_<?=$education[$i]['eid']?>" onclick="del('education', this)"></i>
                  </span>
                </div>
                <!-- /Item Content -->
              </div>
              <!-- /SECTION ITEM -->     
            <? } ?>
            <div id="add_more_edu" class="item line row add_more_div hidden" onclick="addMore('education', 'edu')">
                <!-- Margin Collums (necessary for the timeline effect) -->
                <div class="col-md-1 bg1 timeline-space hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <!-- /Margin Collums -->
                <!-- Item Content -->
                <div class="col-md-8 content-wrap bg1 table_div">
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
          <section class="timeline work-experience big_section" id="works">

            <!-- VERTICAL MARGIN (necessary for the timeline effect) -->
            <div class="line row timeline-margin">
              <div class="content-wrap">
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-10 bg1 full-height"></div>
              </div>
            </div>
            <!-- /VERTICAL MARGIN -->

            <!-- SECTION TITLE -->
            <div class="line row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-8 content-wrap bg1">
                <!-- Section title -->
                <h2 class="section-title">Work Experience</h2>
                <!-- /Section title -->
              </div>
			  <div class="col-md-1 edit_handler_div content-wrap bg1 full-height table_div">
				  <div class="edit_handler_div">
					<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('works', 'work_')"></span>
					<span id="work_save" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('works', 'work_')"></span>
				  </div>
			  </div>
              <!-- /Item Content -->
              <!-- Margin Collum-->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <!-- /Margin Collum-->
            </div>
            <!-- /SECTION TITLE -->
            <? for($i=0;$i<count($work);$i++){ ?>
            <!-- SECTION ITEM -->
            <div id="work_<?=$work[$i]['wid']?>" data-work_id="<?=$work[$i]['wid']?>" list_position="<?=$work[$i]['list_position']?>" class="item line row work_row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-work "></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-9 content-wrap bg1">
                <div class="line-content line-content-education">
                  <!-- Work Place -->
                  <h3>
				  <input id="wMajor" readonly="true" value="<?
                      if($work[$i]['name']){
                        echo $work[$i]['name'];
                      }else{
                        echo "Input Your Work Major";
                      }
                  ?>" class="h3_input section-item-title-1 savedata" name="wMajor"></input></h3>
                  <!-- /work place -->
                  <!-- Graduation time -->
                  <h4 class="job"><i class="fa fa-flag"></i>&nbsp;
                  <input id="wPosition" readonly="true" value="<? 
					  if($work[$i]['position']){
						echo $work[$i]['position'];
					  }else{
						echo "What is your position";
					  } 
				  ?>" class="h4_input nf-Width savedata" name="wPosition"></input>
				  <input id="work_year_start" name="work_year" readonly="true" class="job-date work_small_input work-date savedata needDatePicker"  class="work-date" value="<? 
                  if($work[$i]['start']){
                    echo $work[$i]['start'];
                  }else{
                    echo "When did you start";
                  } ?>"name="work_year"></input>&nbsp;-&nbsp;<input id="work_year_end" name="work_year" readonly="true" class="job-date work_small_input work-date savedata needDatePicker"  class="work-date" value="<? 
                  if($work[$i]['start']){
                    echo $work[$i]['end'];
                  }else{
                    echo "When did you end";
                  }
                  ?>" name="work_year"></input>
				  </h4>
                  <!-- /Graduation time -->
                  <!-- content -->
                  <div class="job-description">
                    <textarea id="wIntro" readonly="true" name="wIntro"  maxlength="740" class="savedata"><?
					  if($work[$i]['intro']){
						echo $work[$i]['intro'];
					  }else{
						echo "You may want to tell your employers more about your education";
					  }
                  ?></textarea>
                  </div>
                  <!-- /Content -->
                </div>
              </div>
              <div class="col-md-1 move_handler content-wrap bg1 full-height table_div">
                  <span class="move_handler_span">
                    <span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                  </span>
                  <span class="move_handler_span">
                    <i class="pull-right fa fa-trash-o" aria-hidden="true" parent="work_<?=$work[$i]['wid']?>" onclick="del('works', this)"></i>
                  </span>
                </div>
			  <!-- /Item Content -->
            </div>
            <!-- /SECTION ITEM -->      
            <? } ?>
			<div id="add_more_work" class="item line row add_more_div hidden " onclick="addMore('works', 'work')">
                <!-- Margin Collums (necessary for the timeline effect) -->
                <div class="col-md-1 bg1 timeline-space hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <!-- /Margin Collums -->
                <!-- Item Content -->
                <div class="col-md-8 content-wrap bg1 table_div">
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
          <!-- ==>> /SECTION: WORK EXPERIENCE <<==== --> 

          <!-- ====>> SECTION: SKILLS <<====-->
          <section class="timeline skills big_section" id="skills">

            <!-- VERTICAL MARGIN (necessary for the timeline effect) -->
            <div class="line row timeline-margin">
              <div class="content-wrap">
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-10 bg1 full-height"></div>
              </div>
            </div>
            <!-- /VERTICAL MARGIN -->

            <!-- SECTION TITLE -->
            <div class="line row">
              <!-- VERTICAL MARGIN (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
              <!-- /VERTICAL MARGIN (necessary for the timeline effect) -->
              <!-- Item Content -->
              <div class="col-md-8 content-wrap bg1">
                <!-- Section title -->
                <h2 class="section-title">Skills</h2>
                <!-- /Section title -->
              </div>
              <!-- /Item Content -->
			  <div class="col-md-1 edit_handler_div content-wrap bg1 full-height table_div">
				  <div class="edit_handler_div">
					<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('skills', 'skill_')"></span>
					<span id="skill_save" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('skills', 'skill_')"></span>
				  </div>
			  </div>
              <!-- Margin Collum-->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <!-- /Margin Collum-->
            </div>
            <!-- /SECTION TITLE -->
			<?php for( $i=0 ; $i<count($skills) ; $i++ ) { ?>
				<div id="skill_<?=$skills[$i]["Header"]["skHid"]?>" data-skill_id="<?=$skills[$i]["Header"]["skHid"]?>" list_position="<?=$skills[$i]["Header"]["skListPos"]?>" class="item line row skill_row">
					<div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
					<div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
             
					<div class="col-md-9 content-wrap bg1">
						<div class="line-content">
							<h3><input id="sMajor" readonly="true" value="<?
								  if( $skills[$i]["Header"]["skName"] ){
									echo $skills[$i]["Header"]["skName"];
								  }else{
									echo "Input Your Skill";
								  }
							?>" class="h3_input section-item-title-1 savedata" name="sMajor"></input></h3>

							<ul id="skl_<?=$skills[$i]["Header"]["skHid"]?>" class="skills-list">
								<?php 
									$skc = $skills[$i]["Content"];
									for( $j=0 ; $j<count($skc) ; $j++ ) {
								?>
									<li id="skCid_<?=$skc[$j]["sid"]?>" data-id="<?=$skc[$j]["sid"]?>" list_position="<?=$skc[$j]["list_position"]?>" class="skill_item">
									  <div class="skill_in_display progress">
										<div class="progress-bar progress-bar-<?=($j+1)?>" role="progressbar" data-percent="<?=$skc[$j]["skill_mark"]*10?>%" style="width: <?=$skc[$j]["skill_mark"]*10?>%;">
											<span class="sr-only"><?=$skc[$j]["skill_mark"]*10?>% Complete</span>
										</div>
										<span class="progress-type"><?=$skc[$j]["skill_name"]?></span>
										<span class="progress-completed"><?=$skc[$j]["skill_mark"]*10?>%</span>
									  </div>
									  <div class="skill_in_edit hidden" >
										<div class="content-wrap bg1 ">
											<div class="col-md-10 content-wrap bg1">
												<div class="line-content">
												  <h4><input id="skName" value="<?
													  if($skc[$j]["skill_name"]){
														echo $skc[$j]["skill_name"];
													  }else{
														echo "Input Skill Name";
													  }
												  ?>" class="h4_input savedata" name="skName"></input>
												  <input id="skMark" value="<?
													  if($skc[$j]["skill_mark"]){
														echo $skc[$j]["skill_mark"];
													  }else{
														echo "Input Your Mark";
													  }
												  ?>" class="h4_input savedata" name="skMark"></input>/10</h4>
												</div>
											</div>
											<div class="col-md-1 move_handler content-wrap bg1">
											  <span class="move_handler_span">
												<span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
											  </span>
											  <span class="move_handler_span">
												<i class="delete_btn pull-right fa fa-trash-o" aria-hidden="true" parent="skCid_<?=$skc[$j]["sid"]?>" onclick="del('skills', this)"></i>
											  </span>
											</div>
										</div>
									  </div>
									</li>
								<?php } ?>
							</ul>
							<div id="add_more_skillitem" class="item line row add_more_item_div hidden " onclick="addSkillItem('skills', '#skl_<?=$skills[$i]["Header"]["skHid"]?>')">
								<div class="col-md-12 content-wrap bg1 table_div">
								  <div class="line-content " style='text-align:center;'>
									 <h1 class="add_more"><i class="fa fa-plus" aria-hidden="true"></i>Add More Item</h1>
								  </div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="col-md-1 move_handler content-wrap bg1 full-height table_div">
					  <span class="move_handler_span">
						<span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
					  </span>
					  <span class="move_handler_span">
						<i class="pull-right fa fa-trash-o" aria-hidden="true" parent="skill_<?=$skills[$i]["Header"]["skHid"]?>" onclick="del('skills', this)"></i>
					  </span>
					</div>
				
				</div>
			<?php } ?>

			
			<div id="add_more_skill" class="item line row add_more_div hidden " onclick="addMore('skills', 'skill')">
                <div class="col-md-1 bg1 timeline-space hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-8 content-wrap bg1 table_div">
                  <div class="line-content " style='text-align:center;'>
                     <h1 class="add_more" style="padding-top: 17px;"><i class="fa fa-plus" aria-hidden="true"></i>Add More Skill</h1>
                  </div>
                </div>
                <div class="col-md-1 content-wrap bg1 full-height table_div">
                </div>
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
			</div>
 
          </section>
          <!-- ==>> /SECTION: SKILLS -->

		  <!-- ====>> SECTION: Lang <<====-->
		  <section class="timeline langs big_section" id="langs">
			
			<div class="line row timeline-margin">
			  <div class="content-wrap">
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-10 bg1 full-height"></div>
              </div>
			</div>
		    
			<div class="line row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-8 content-wrap bg1">
                <!-- Section title -->
                <h2 class="section-title">Languages</h2>
                <!-- /Section title -->
              </div>
			  <div class="col-md-1 edit_handler_div content-wrap bg1 full-height table_div">
				  <div class="edit_handler_div">
					<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('langs', 'lang_')"></span>
					<span id="lang_save" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('langs', 'lang_')"></span>
				  </div>
			  </div>
              <!-- /Item Content -->
              <!-- Margin Collum-->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <!-- /Margin Collum-->
            </div>
			
			<? for($i=0;$i<count($lang);$i++){ ?>
			<div id="lang_<?=$lang[$i]['lid']?>" data-lang_id="<?=$lang[$i]['lid']?>" list_position="<?=$lang[$i]['list_position']?>" class="item line row lang_row">
			  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-work "></div>
              
			  <div class="col-md-8 content-wrap bg1">
                <div class="line-content">
                  <h3><input id="lMajor" readonly="true" value="<?
                      if($lang[$i]['major']){
                        echo $lang[$i]['major'];
                      }else{
                        echo "Input Your Language";
                      }
                  ?>" class="h3_input section-item-title-1 savedata" name="lMajor"></input></h3>
                </div>

				<div class="line-content">
				  <h4 class="job"><i class="fa fa-flag"></i>&nbsp;
                  <input id="langlv" readonly="true" value="<?
                      if($lang[$i]['lv']){
                        echo $lang[$i]['lv'];
                      }else{
                        echo "Input your level";
                      }
                  ?>" class="h4_input nf-Width savedata" name="langlv"></input></h4>
                </div>
              </div>
			  
              <div class="col-md-1 move_handler content-wrap bg1 full-height table_div">
                  <span class="move_handler_span">
                    <span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                  </span>
                  <span class="move_handler_span">
                    <i class="pull-right fa fa-trash-o" aria-hidden="true" parent="lang_<?=$lang[$i]['lid']?>" onclick="del('langs', this)"></i>
                  </span>
              </div>
			  
			  
			  <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
			</div>
			<? } ?>
			
			<div id="add_more_lang" class="item line row add_more_div hidden " onclick="addMore('langs', 'lang')">
                <div class="col-md-1 bg1 timeline-space hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-8 content-wrap bg1 table_div">
                  <div class="line-content " style='text-align:center;'>
                     <h1 class="add_more"><i class="fa fa-plus" aria-hidden="true"></i> Add More</h1>
                  </div>
                </div>
                <div class="col-md-1 content-wrap bg1 full-height table_div">
                </div>
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
			</div>
		  </section>
		  <!-- ====>> SECTION: Lang <<====-->
		  
          <!-- ====>> SECTION: INTERESTS <<====-->
          <section class="timeline interests big_section" id="awards">

            <!-- VERTICAL MARGIN (necessary for the timeline effect) -->
            <div class="line row timeline-margin">
              <div class="content-wrap">
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <div class="col-md-10 bg1 full-height"></div>
              </div>
            </div>
            <!-- /VERTICAL MARGIN -->

            <!-- SECTION TITLE -->
            <div class="line row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs timeline-title full-height"></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-8 content-wrap bg1">
                <!-- Section title -->
                <h2 class="section-title">Awards</h2>
                <!-- /Section title -->
              </div>
			  <div class="col-md-1 edit_handler_div content-wrap bg1 full-height table_div">
				  <div class="edit_handler_div">
					<span class="edit_handler glyphicon glyphicon-edit" aria-hidden="true" onclick="edit('awards', 'award_')"></span>
					<span id="skills_save" class="glyphicon glyphicon-save saveBtn hidden" aria-hidden="true" onclick="save('awards', 'award_')"></span>
				  </div>
			  </div>
              <!-- /Item Content -->
              <!-- Margin Collum-->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <!-- /Margin Collum-->
            </div>
            <!-- /SECTION TITLE -->

            <!-- SECTION ITEM -->
			<? for($i=0;$i<count($award);$i++) : ?>
            <div id="award_<?=$award[$i]['aid']?>" data-award_id="<?=$award[$i]['aid']?>"  list_position="<?=$award[$i]['list_position']?>" class="item line row award_row">
              <!-- Margin Collums (necessary for the timeline effect) -->
              <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs"></div>
              <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height timeline-point "></div>
              <!-- /Margin Collums -->
              <!-- Item Content -->
              <div class="col-md-9 content-wrap bg1">
                <div class="line-content">
                  <!-- Subtitle -->
                  <h3><input id="aMajor" readonly="true" value="<?
                      if($award[$i]['name']){
                        echo $award[$i]['name'];
                      }else{
                        echo "Input Your Major";
                      }
                    ?>" class="h3_input section-item-title-1 savedata" name="aMajor"></h3>
                  <!-- /Subtitle -->
                  <!-- content -->
                  <textarea id="aIntro" readonly="true" name="aIntro"  maxlength="740" class="savedata"><?
					  if($award[$i]['intro']){
						echo $award[$i]['intro'];
					  }else{
						echo "You may want to tell your employers more about your award";
					  }
                  ?></textarea>
                  <!-- /Content -->
                </div>
              </div>
              <div class="col-md-1 move_handler content-wrap bg1 full-height table_div">
                  <span class="move_handler_span">
                    <span class="move_handler glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                  </span>
                  <span class="move_handler_span">
                    <i class="pull-right fa fa-trash-o" aria-hidden="true" parent="award_<?=$award[$i]['aid']?>" onclick="del('awards', this)"></i>
                  </span>
                </div>
			  <!-- /Item Content -->
            </div>
			<?php endfor; ?>
            <!-- /SECTION ITEM -->
			<div id="add_more_award" class="item line row add_more_div hidden " onclick="addMore('awards', 'award')">
                <!-- Margin Collums (necessary for the timeline effect) -->
                <div class="col-md-1 bg1 timeline-space hidden-sm hidden-xs"></div>
                <div class="col-md-1 timeline-progress hidden-sm hidden-xs full-height"></div>
                <!-- /Margin Collums -->
                <!-- Item Content -->
                <div class="col-md-9 content-wrap bg1 table_div">
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
          <!-- ==>> /SECTION: INTERESTS -->
        </div>
      </div>
      <!-- ============  /TIMELINE ================= -->

      <!-- ============  FOOTER ================= -->
      <footer id="footer" class="row">
      </footer>
      <!-- ============  /FOOTER ================= -->
    </div> 
  </div> 
</section>
<!-- /CONTENT
========================================================= -->
<script>
function edit(ContainerID, prefix)
{
	//console.log(ContainerID);
	cv.init(ContainerID)
	cv.edit(prefix);
}

function save(ContainerID, prefix)
{
	//console.log(ContainerID);
	cv.init(ContainerID);
	cv.save(prefix);
}

function del(ContainerID, obj)
{
	cv.init(ContainerID);
	cv.deleted(obj);
}

function addMore(ContainerID, prefix)
{
	cv.init(ContainerID);
	cv.addMore(prefix);
}

function addSkillItem(ContainerID, list_id)
{
	console.log("addSkillItem");
	cv.init(ContainerID);
	cv.addSkillItem(list_id);
}

function changeProfileIntroProgressHeight(){
	var orgH = $("#profile_intro .profile-col").find('.timeline-progress').height();
	var btnH = $("#profile_intro .table_div").height();
	var ConH = $("#profile_intro .pContent").height();
	var picH = $("#profile_intro .profile-col").height();
	var OffSetH = 16;
	$("#profile_intro .profile-col").find('.timeline-progress').css("height", orgH + btnH + ConH - picH + OffSetH );
}

var span = $('<span>').css('display','inline-block')
.css('word-break','break-all').appendTo('body').css('visibility','hidden');

function changeProgressHeight(){
  $(".item").each(function() {
    var parentHeight = $(this).height();
	
    $(this).find('.timeline-progress').height(parentHeight);
    $(this).find('.full-height').height(parentHeight);
  });
}

function initSpan(textarea){
  span.text(textarea.text())
      .width(textarea.width())      
      .css('font',textarea.css('font'));
}
$(document).on("input focus keypress", 'textarea', function(event)
{
	if(event.type == 'input')
	{
		var text = $(this).val();      
		span.text(text);      
		
		if(span.height() >= 152){
			$(this).attr("rows", 8);
			$(this).css("height", 8 * 19);
		}
		else
		{
			var h = Math.ceil(span.height()/19);
			$(this).attr("rows", h);
			$(this).css("height", h * 19);
		}
		  
		if( $(this).attr("id") == 'pIntro' )
			changeProfileIntroProgressHeight();
		else
			changeProgressHeight();
	}
	else if(event.type == 'focus')
	{
		initSpan($(this));
	}
	else if(event.type == 'keypress')
	{
		if(event.which == 13) 
			event.preventDefault();
	}
});
 

$("#view_public").click(function() {
	var postData = {};
	postData["skey"] = userObject.skey;
	
	$.ajax({
	  type: "POST",
	  url: '/ws/createPublic.php',
	  data: postData,
      success: function(response){
		  //window.location.replace("http://www.getmecv.com/cv/cvp.php?query=" + response);
		   window.open("http://www.getmecv.com/cv/cvp.php?query=" + response, '_blank');
	  },
      failure: function(errMsg) {
          alert(errMsg);
      }
	});
});

function getPdf()
{
	//javascript:window.print();
	
	addloading();
	
	var postData = {};
	postData["skey"] = userObject.skey;
	
	$.ajax({
	  type: "POST",
	  url: '/ws/savePDFv2.php',
	  data: postData,
	  success: function(response){
		removeloading();
		console.log("response");
		window.open(response, '_blank');
	  },
	  failure: function(errMsg) {
		  alert(errMsg);
	  }
	});
}

function upload_img()
{
	$("input[id='profile_img']").click();
}

function addloading()
{
	$.when( $('body').append("<div class='overlay'><div class='loading'></div></div>") ).done(function(){
		var w = $(window).width();
		var h = $(window).height();
		$('.loading').css({ "top": (h/2 - 75) + "px", "left": (w/2 - 75) + "px"});
	});
}
function removeloading()
{
	$(".overlay").remove();
}

$(function()
{
	$('#profile_img').change(function() {
		addloading();
		
		var file_data = $("#profile_img").prop("files")[0];   
		var form_data = new FormData();   
		form_data.append("skey", userObject.skey);
		form_data.append("file", file_data);
                            
		$.ajax({
			url: "/ws/profileimg.php",
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(data){
				removeloading();
				if(data instanceof Object)
				{
					var re = data;    
					$("#profile_curr_img").attr("src", "../profile_pic/"+data['file']);
					alert(data['message']); 
				}
				else
				{
					alert(data); 
				}
			}
		 });
		
	});
 
	$('textarea').css("height", function()
	{
	  initSpan($(this));
	  var text = $(this).val();      
      span.text(text);      

	  var h = 0;
	  if(span.height() >= 152){
	    $(this).attr("rows", 8);
		h = 8 * 19;
	  }
	  else
	  {
		var h = Math.ceil(span.height()/19);
	    $(this).attr("rows", h);
		h = h * 19;
	  }
	  
	  if( $(this).attr("id") == 'pIntro' )
	    changeProfileIntroProgressHeight();
	  else
	    changeProgressHeight();
	  return h;
	});
});
 
</script>
<!-- /Configurations -->
</body>
</html>