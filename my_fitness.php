<?php

//start session
session_start();

########## Google Settings.. Client ID, Client Secret from https://cloud.google.com/console #############
$google_client_id 		= '324510649830-ecboqitednkqi9asav6f4cdj7o8mknau.apps.googleusercontent.com';
$google_client_secret 	= 'Ji3TejsoAUYXkg6bIfT1xAbi';
$google_redirect_url 	= 'http://opis.lodestar.com/mainpage_after_login.php'; //path to your script
$google_developer_key 	= 'AIzaSyDxwAxXSZyv17AxhjtstvEMzyg3yjMA7yc';

########## MySql details (Replace with yours) #############
$db_username = "root"; //Database Username
$db_password = "apmsetup"; //Database Password
$hostname = "localhost"; //Mysql Hostname
$db_name = 'opis'; //Database Name
###################################################################

//include google api files
require_once 'google-api-php-client/src/Google/autoload.php';
//require_once 'src/Google_Client.php';
//require_once 'src/contrib/Google_Oauth2Service.php';

$gClient = new Google_Client();
$gClient->setApplicationName('Login to Sanwebe.com');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$gClient->addScope(Google_Service_Fitness::FITNESS_ACTIVITY_READ);
$gClient->addScope(Google_Service_Fitness::FITNESS_BODY_READ);
$gClient->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$gClient->addScope(Google_Service_Oauth2::USERINFO_EMAIL);

$google_oauthV2 = new Google_Service_Oauth2($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  echo "<script>alert('Successfully Logouted');</script>";
  echo '<script>document.location.href="index.php";</script>';
}

//If code is empty, redirect user to google authentication page for code.
//Code is required to aquire Access Token from google
//Once we have access token, assign token to session variable
//and we can redirect user back to page and login.
if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	return;
}


if (isset($_SESSION['token'])) 
{ 
	$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  //For logged in user, get details from google using access token
	  $user 				= $google_oauthV2->userinfo->get();
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else 
{
	//For Guest user, get google login url
	$authUrl = $gClient->createAuthUrl();
}

?>

	<!DOCTYPE html>
	<html lang="ko">
		<head>
			<meta charset="utf-8">
	
			<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
			Remove this if you use the .htaccess -->
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
			<title>OPIS - 건강 관리</title>
			<meta name="description" content="">
			<meta name="author" content="user">
	
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
			<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
			<link rel="shortcut icon" href="/favicon.ico">
			<link rel="apple-touch-icon" href="/apple-touch-icon.png">
			
			<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" title="no title" charset="UTF-8"/>
			<link rel="stylesheet" href="css/bootstrap-responsive.css" type="text/css" media="screen" title="no title" charset="UTF-8"/>
			<link rel="stylesheet" href="css/font-awesome.css" type="text/css" media="screen" title="no title" charset="UTF-8"/>
			<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="no title" charset="UTF-8"/>
			
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
			<script src="js/bootstrap.js"></script>
			
		</head>
		
	  <body>
	
	    <div class="navbar navbar-inverse navbar-fixed-top">
	      <div class="navbar-inner">
	        <div class="container">
	          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="brand" href="#">Online Personal Insurance System</a>
	          <div class="nav-collapse collapse">
	            <ul class="nav">
	              <li class="active"><a href="#">Home</a></li>
	              <li><a href="my_insurance.php">내 보험</a></li>
	              <li><a href="join_insurance.php">보험 가입</a></li>
	              <li><a href="my_fitness.php">건강 관리</a></li>
	              <li><a href="my_medical_record.php">진료기록 관리</a></li>
	  			  
	  			  <li><a class="logout pull-right" href="?reset=1">Logout</a></li>
	              
	            </ul>
	          </div><!--/.nav-collapse -->
	        </div>
	      </div>
	    </div>
	
	    <div class="container">
	
	      <!-- Main hero unit for a primary marketing message or call to action -->
	      <div class="hero-unit">
	        <br>
	        <h2>Google Fitness 연동 정보</h2>
	        <br>
			<?
 
$fitness_service = new Google_Service_Fitness($gClient);
$dataSources = $fitness_service->users_dataSources;
$dataSets = $fitness_service->users_dataSources_datasets;
$listDataSources = $dataSources->listUsersDataSources("me");

$timezone = "GMT+0900";
$today = date("Y-m-01");
print($today);
print(" 0시 기준 <br>");
$endTime = strtotime($today.' 00:00:00 '.$timezone);
$startTime = strtotime('-30 day', $endTime);			// 월에 따라 28, 29, 30, 31

while($listDataSources->valid()) {
     $dataSourceItem = $listDataSources->next();
	$dataSourceItem = $listDataSources->next();		///// 왜 이렇게 해줘야 하는지는...

     if ($dataSourceItem['dataType']['name'] == "com.google.step_count.delta") {
            $dataStreamId = $dataSourceItem['dataStreamId'];
            $listDatasets = $dataSets->get("me", $dataStreamId, $startTime.'000000000'.'-'.$endTime.'100000000');

            $step_count = 0;
            while($listDatasets->valid()) {
                $dataSet = $listDatasets->next();
				//$dataSet = $listDatasets->next();
                $dataSetValues = $dataSet['value'];

                if ($dataSetValues && is_array($dataSetValues)) {
                   foreach($dataSetValues as $dataSetValue) {
                   		//print($step_count."<br>");
                       $step_count += $dataSetValue['intVal'];
                   }
                }
            }
            print("지난 한 달동안 <h3>".$step_count."</h3> 걸음 걸으셨습니다!<br>");
			break;		// 왜 이렇게 해줘야 하는지는...
     };

/*
	 if ($dataSourceItem['dataType']['name'] == "com.google.calories.expended") {
            $dataStreamId = $dataSourceItem['dataStreamId'];
            $listDatasets = $dataSets->get("me", $dataStreamId, $startTime.'000000000'.'-'.$endTime.'100000000');

            $cal = 0.0;
            while($listDatasets->valid()) {
                $dataSet = $listDatasets->next();
                $dataSetValues = $dataSet['value'];
	
                if ($dataSetValues && is_array($dataSetValues)) {
                   foreach($dataSetValues as $dataSetValue) {
                   	//print($dataSetValue['intVal']);
                       $cal += $dataSetValue['fpVal'];
                   }
                }
            }
            print("Burned cal: ".$cal."<br />");
     };
 * 
 */
 } 

$discount = sprintf("%d", $step_count/1000);

print("이번 달의 보험료를 ".($discount * 10)." 원 할인할 수 있습니다.");
print("<br><br>");
  
?>

<form action="fitness_discount.php" method="post">
	<input type="hidden" name="discount" value=<?print($discount*10);?>>
	<input type="submit" class="btn btn-primary btn-large" value="할인 받기">
</form>
			  
	      </div>
	      <hr>
	      
		  <br>
		  <br>
	      <footer>
	        <p>&copy; CSE Graduation Assignment 2015</p>
	      </footer>
	
	    </div> <!-- /container -->
	
	    <!-- Le javascript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    <script src="../assets/js/jquery.js"></script>
	    <script src="../assets/js/bootstrap-transition.js"></script>
	    <script src="../assets/js/bootstrap-alert.js"></script>
	    <script src="../assets/js/bootstrap-modal.js"></script>
	    <script src="../assets/js/bootstrap-dropdown.js"></script>
	    <script src="../assets/js/bootstrap-scrollspy.js"></script>
	    <script src="../assets/js/bootstrap-tab.js"></script>
	    <script src="../assets/js/bootstrap-tooltip.js"></script>
	    <script src="../assets/js/bootstrap-popover.js"></script>
	    <script src="../assets/js/bootstrap-button.js"></script>
	    <script src="../assets/js/bootstrap-collapse.js"></script>
	    <script src="../assets/js/bootstrap-carousel.js"></script>
	    <script src="../assets/js/bootstrap-typeahead.js"></script>
	
	  </body>
	</html>

