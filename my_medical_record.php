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
	
			<title>OPIS - 메인 페이지</title>
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
	              <li class="active"><a href="mainpage_after_login.php">Home</a></li>
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
	        <h2>내 진료기록 정보</h2>
	        <br>
	        
	      <table class="table">			<!-- Bootstrap Table -->
	      <thead>
	      	<tr>
	      	<th>진료기록 번호</th>
	      	<!--<th>환자 Google ID</th>-->
	      	<th>진단 병명</th>
	      	<th>처방 약품</th>
	      	<th>소견</th>
	      	<th>날짜</th>
			<th>보험 이름</th>
	      	<th>보험금 청구 상태</th>
	      	</tr>
	      </thead>
	      <tbody>
	        
	      <?

     	 if ( !( $database = mysql_connect( "localhost",
            "root", "apmsetup" ) ) )                      
            die( "Could not connect to database </body></html>" );
   
         // open Products database
         if ( !mysql_select_db( "opis", $database ) )
            die( "Could not open products database </body></html>" );
		 
		$query = "set names utf8";
		mysql_query( $query, $database );
		
		$query = "select * from medical_record where medical_record.google_id=$user_id";
		
		//유저별로 진료기록 가져옴
         // query Products database
         if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if

		 
         while ( $row = mysql_fetch_row( $result ) ) //진료기록while
    	 {
			 $medical_record_id = $row[0];
					$medical_record_googleid = $row[1];
					$medical_record_diseaseid = $row[2];
					$query_dis = "select disease_name from disease_data
								where disease_data.disease_id = $medical_record_diseaseid";
					/////		
					   $result_dis = mysql_query( $query_dis, $database );
					   $row_dis = mysql_fetch_row( $result_dis );
					   $disease_name=$row_dis[0]; //disease_name get
					/////
					
					$query_insu = "select * from insurance_disease
								 where $medical_record_diseaseid = insurance_disease.disease_id"; 
 
					$result_insu = mysql_query( $query_insu, $database); 
					
			while( $row_insu    = mysql_fetch_row( $result_insu )) {//그 진단받은 질병에 해당되는 보험 set가져옴		
					$medical_insurance_id = $row_insu[0];
					$query_enroll_yn = "select * from contract_list 
									  where contract_list.google_id = $user_id
									  and contract_list.insurance_id = $medical_insurance_id";	
									//insurance_id  //(그 보험,유저) 가 contract list에 있는지 확인!
					 $result_yn = mysql_query( $query_enroll_yn, $database );
				
					 /////////////
						$query_insurance_name = "select insurance_data.name 
												from insurance_data
												where insurance_data.insurance_id = $medical_insurance_id";
												
						$result_insuname = mysql_query( $query_insurance_name, $database);
						$row_insuname = mysql_fetch_row( $result_insuname);
						$insurance_name = $row_insuname[0];
					 ///////////// get 보험이름
					 
				$chk_insu = 0;		

			
							
				while( $row_yn = mysql_fetch_row( $result_yn )) { //계약리스트서 그 보험검쉑
				 $chk_insu++;	
				 if($chk_insu == 1) {	//첫번째보험
				  
				 print("<tr>");
					echo('<td>'.$row[0].'</td>');  //진료기록 번호
										  
					echo ('<td>'.$disease_name.'</td>'); //병명
					 //질병 이름 가져옴
									
					echo('<td>'.$row[3].'</td>'); //약명
					echo('<td>'.$row[5].'</td>');//소견
					echo('<td>'.$row[4].'</td>');//기록날짜
					echo('<td>'.$insurance_name.'</td>');//보험이름
					
					//보험이름
					if(!($row_yn[0])) // 진단한 병에 대해 보험에 가입되어있지 않음.(계약리스트에 없다.) 
					{
						echo('<td>지급 불가 (가입 X)</td>');
					}
					
					else{ //가입되어 있으면
						$query_send_yn = "select * from receive_list 
									  where receive_list.google_id = $user_id
									  and receive_list.insurance_id = $medical_insurance_id
									  and receive_list.record_id = $medical_record_id";	
						$result_send_yn = mysql_query( $query_send_yn, $database );
						$row_send_yn = mysql_fetch_row( $result_send_yn );						
						//(보험id, 사용자id, 진료기록id)
						
						if(!($row_send_yn[0])) {//진단을 받았는데 그 진단에 대한 보험에 가입이 되어있음. & 관리자 승인X
							echo('<td>심사중입니다</td>');}					
						else { echo('<td>지급 완료</td>');}
					}
					print("</tr>");
					//보험-질병 매칭*/
					}
				
					}//end of 보험while	
			}	//end of 진료기록 while
		 }
         ?>
	     
	     </tbody>
	     </table>
	        
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


	