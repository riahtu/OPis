<?php

//start session
session_start();

$discount = $_REQUEST['discount'];

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
   	$query = "set names utf8";
    $database = mysql_connect( "localhost","root", "apmsetup" );                 
    mysql_select_db( "opis", $database );
    mysql_query( $query, $database );

	$query = "select contract_id, real_fee, lastdiscounted from contract_list where google_id = $user_id";

	$result = mysql_query( $query, $database );
	
	$present_month = date("Y-m-d");
	$present_month2 = substr($present_month, 0, 7);
         
         // fetch each record in result set
         while ( $row = mysql_fetch_row( $result ) )
         {
         	$discounted_fee = $row[1] - $discount;
		    $last_discount_month = substr($row[2], 0, 7);
			
			//print($present_month2);
			//print($last_discount_month);
			
			if($present_month2 == $last_discount_month)
			{
				echo "<script>alert('You already give a discount this month.');
	</script>";
				echo '<script>document.location.href="my_fitness.php";</script>';
			}
			else
			{
				$query = "update contract_list set real_fee = $discounted_fee where contract_id = $row[0]";
				mysql_query( $query, $database );
				//print($present_month);
				$query = "update contract_list set lastdiscounted = DATE_FORMAT('$present_month', '%Y-%m-%d') where contract_id = $row[0]";
				mysql_query( $query, $database );
			}
         } // end while
         
    	echo "<script>alert('insurance fee of this month is discounted.');
	</script>";
	echo '<script>document.location.href="my_fitness.php";</script>';

}
 
?>
