<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'db_connection.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication( '922898757822309','545fc7c50b12572f4ba2217bf1e1ff95' );

// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('http://www.getmecv.com/backend/fbconfig.php' );

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
   echo $ex->getMessage();
} catch( Exception $ex ) {
  // When validation fails or other local issues
  echo $ex->getMessage();
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me?fields=id,name,email,education,about,friends,work' );
  $response = $request->execute();

  // get response
  $graphObject = $response->getGraphObject();
  $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
  $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
  $femail = $graphObject->getProperty('email');    // To Get Facebook email
  $education = $graphObject->getProperty('education');    // To Get Facebook email ID
  $work = $graphObject->getProperty('work');    // To Get Facebook email ID
  $about = $graphObject->getProperty('about');    // To Get Facebook email ID
  $friends = $graphObject->getProperty('friends');    // To Get Facebook email ID
      
  $stmt = $dbc->prepare ("SELECT `uid` FROM user WHERE fb_id = ?");
  $stmt->bindValue(1, $fbid, PDO::PARAM_STR);
  $stmt->execute();
  if($stmt->rowCount()==0){
    $fielName = uniqid().".jpg";
    downloadImg("https://graph.facebook.com/".$fbid."/picture?type=large", "profile_pic", $fielName);

    if(!$about){
      $about = "Fill in the self introduction!";
    }
    $stmt = $dbc->prepare ("INSERT INTO user (fb_id, full_name, email, intro, profile_pic, skey) VALUES (?,?,?,?,?, uuid())");
    $stmt->bindValue(1, $fbid, PDO::PARAM_STR);
    $stmt->bindValue(2, $fbfullname, PDO::PARAM_STR);
    $stmt->bindValue(3, $femail === NULL ? "" :  $femail, PDO::PARAM_STR);
    $stmt->bindValue(4, $about  === NULL ? "" :  $about,  PDO::PARAM_STR);
    $stmt->bindValue(5, $fielName, PDO::PARAM_STR);
    $stmt->execute();
    $id = $dbc->lastInsertId();
    if($education){
      $education_data = $education->asArray();
      $listPosition = 0;
      foreach($education_data as $row){
        $stmt = $dbc->prepare ("INSERT INTO education (uid, school_name, graduate_year, type, major, list_position) VALUES (?,?,?,?,?,?)");
        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->bindValue(2, $row->school->name, PDO::PARAM_STR);
        $stmt->bindValue(3, $row->year->name, PDO::PARAM_STR);
        $stmt->bindValue(4, $row->type, PDO::PARAM_STR);
        $stmt->bindValue(5, $row->concentration[0]->name, PDO::PARAM_STR);
        $stmt->bindValue(6, $listPosition , PDO::PARAM_STR);
        $stmt->execute();
        $listPosition++;
      }
    }
    
   
    if($work){
      $listPosition = 0;
      $work_data = $work->asArray();
        foreach($work_data as $row){
          $stmt = $dbc->prepare ("INSERT INTO work (uid, name, position, list_position) VALUES (?,?,?,?)");
          $stmt->bindValue(1, $id, PDO::PARAM_STR);
          $stmt->bindValue(2, $row->employer->name, PDO::PARAM_STR);
          $stmt->bindValue(3, $row->position->name, PDO::PARAM_STR);
          $stmt->bindValue(4, $listPosition, PDO::PARAM_STR);
          $stmt->execute();
          $listPosition++;
        }
    }
    
  }else{
    $f = $stmt->fetch();
    $id = $f['uid'];
  }

  // https://api.aylien.com/api/v1/entities
  $stmt = $dbc->prepare ("SELECT `skey` FROM user WHERE fb_id = ?");
  $stmt->bindValue(1, $fbid, PDO::PARAM_STR);
  $stmt->execute();
  $f = $stmt->fetch();
  $_SESSION['SKEY'] = $f['skey'];
  
	/* ---- Session Variables -----*/
  $_SESSION['FBID'] = $fbid;           
  $_SESSION['FULLNAME'] = $fbfullname;
  $_SESSION['EMAIL'] =  $femail;
  $_SESSION['USERNAME'] =  $fbusername;
    /* ---- header location after session ----*/
  header("Location: /cv/cv.php?id=$id");
} else {
  $permissions = ['email', 'user_education_history', 'user_about_me', 'user_friends', 'user_work_history']; // optional
  $loginUrl = $helper->getLoginUrl( $permissions );
 header("Location: ".$loginUrl);
}
?>