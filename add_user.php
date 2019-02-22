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

// HTML POST Request ///////////////////
$age = $_REQUEST['input_age'];
$sex = $_REQUEST['input_sex'];
$job = $_REQUEST['input_job'];
////////////////////////////////////////

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

$google_oauthV2 = new Google_Service_Oauth2($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
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

if(isset($authUrl)) //user is not logged in, show login button
{
	// echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" /></a>';
} 
else // user logged in 
{
   /* connect to database using mysqli */
	$mysqli = new mysqli($hostname, $db_username, $db_password, $db_name);
	
	if ($mysqli->connect_error) {
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}
	
	$mysqli->query("set names utf8");
	$mysqli->query("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link, age, sex, job) 
		VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url', $age, $sex, '$job')");

	echo '<script>document.location.href="mainpage_after_login.php";</script>';
}
 
?>
