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
	
			<title>OPIS - 보험 가입</title>
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
	        <h2>보험 가입하기</h2>
	        <br>
	        <p>OPIS에서는 당신에 대한 몇 가지 정보를 수집하여, 그에 따라 적절한 보험 상품을 추천해 드립니다.</p>
	        <p>(인공지능 기법을 이용해 걸릴 위험이 높은 질병을 예측하여, 해당 질병에 대한 보장에 특화된 보험 상품을 추천합니다.)</p>
	        <br>
	        <p>만약 Twitter 계정 정보를 제공한다면 OPIS에서는 당신의 트윗을 분석하여 리스크의 정도를 추정할 수 있습니다. 
	        	이를 이용한다면, 결과에 따라 추천받을 보험의 보험료를 할인받을 수도 있습니다.</p>
	        <p>또한 만약 당신이 '건강 관리' 메뉴에서 Google Fit과 연동하여 헬스 케어를 하고 있는 상태라면, 그 기록에 따라서 보험료를 할인받을 수도 있습니다.</p>
	      </div>
	        <br>
	        <p>아래에 몇 가지 정보를 입력하여 주세요. </p>	
	        
	   <form action = "weka_test.jsp" method="post">
			allownc (기초생활수급 여부) <select name="att1_1">
				<option value="0">잘 모르겠다</option>
				<option value="1">그렇다</option>
				<option value="2">지금은 아니다</option>
				<option value="3">아니다</option>
				</select>
			<br>
			DM2_dg (골관절염 의사진단 여부) <select name="att1_2">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
		    D_8_1 (무릎관절통 여부) <select name="att1_3">
				<option value="0">잘 모르겠다</option>
				<option value="1">있음</option>
				<option value="2">없음</option>
			</select>
			<br>
			D_8_2_t (무릎강직 지속시간) <select name="att1_4">
				<option value="0">잘 모르겠다</option>
				<option value="1">30분 미만</option>
				<option value="2">30분 ~ 1시간</option>
				<option value="3">1시간 이상</option>
			</select>
			<br>
			
			OA_K (무릎관절 방사선학적 유병여부) <select name="att1_5">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			OA_H (엉덩관절 방사선학적 유병여부) <select name="att1_6">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			DJ4_dg (천식 여부) <select name="att1_7">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			BO3_12 (체중조절을 위한 한약복용) <select name="att1_8">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			HE_HLdr (검진당일 고지혈증 약 복용 여부) <select name="att1_9">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			BM13_5 (치아손상사유 : 기타) <select name="att1_10">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			OR1_2 (최근 1년간 구강검진 여부) <select name="att1_11">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			
			DI1_dg (고혈압 의사진단 여부)<select name="att2_1">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			DJ4_3 (천식 약복용) <select name="att2_2">
				<option value="0">잘 모르겠음 / 비해당</option>
				<option value="1">정기적으로 치료받음</option>
				<option value="2">증상이 있을 때만 치료받음</option>
				<option value="3">치료받지 않음</option>
				</select>
			<br>
		    DE1_31 (당뇨병치료_인슐린주사) <select name="att2_3">
		    	<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			DE1_32 (당뇨병치료_당뇨병약) <select name="att2_4">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			DE2_33 (당뇨병치료_비약물 요법) <select name="att2_5">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			DE1_4 (안저검사) <select name="att2_6">
				<option value="0">비해당 / 잘 모르겠음</option>
				<option value="1">있음</option>
				<option value="2">없음</option>
			</select>
			<br>
			BO1_2 (1년간 체중 감소량) <select name="att2_7">
				<option value="0">비해당 / 잘 모르겠음</option>
				<option value="1">3~6kg</option>
				<option value="2">6~10kg</option>
				<option value="3">10kg~</option>
			</select>
			<br>
			HE_DMfh3 (당뇨병 의사진단여부 형제자매) <select name="att2_8">
				<option value="0">없음</option>
				<option value="1">있음</option>
				</select>
			<br>
			HE_THfh2 (갑상선질환 의사진단여부 어머니) <select name="att2_9">
				<option value="0">없음</option>
				<option value="1">있음</option>
				</select>
			<br>
			MO4_11 (진료항목: 발치 도는 구강내수술) <select name="att2_10">
				<option value="0">없음</option>
				<option value="1">있음</option>
				</select>
			<br>
			HE_glu (공복혈당) <input type="text" name="att2_11">
			<br>
			BS6_2 (과거흡연자 흡연기간) <input type="text" name="att2_12">
			<br>
			
						
						
						
						
			marri_1 (기혼 여부)<select name="att3_1">
				<option value="0">잘 모르겠음</option>
				<option value="1">기혼</option>
				<option value="2">미혼</option>
			</select>
			<br>
			marri_2 (결혼상태) <select name="att3_2">
				<option value="0">비해당 / 무응답</option>
				<option value="1">유배우자, 동거</option>
				<option value="2">유배우자, 별거</option>
				<option value="3">사별</option>
				<option value="4">이혼</option>
				</select>
			<br>
			<!-- 속성3 : 천식약복용 - 위에서도 묻고 있음 -->
		    DJ8_dg (알레르기비염 의사진단여부) <select name="att3_3">
		    	<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			CH2_2 (교육이수 - 졸업여부)<select name="att3_4">
				<option value="0">비해당 / 무응답</option>
				<option value="1">졸업</option>
				<option value="2">수료</option>
				<option value="3">중퇴</option>
				<option value="4">재학/휴학중</option>
			</select>
			<br>
			BM1_0 (어제 하루 칫솔질 여부) <select name="att3_5">
				<option value="0">안함</option>
				<option value="1">함</option>
			</select>
			<br>
			age (나이) <input type="text" name="att3_6">
			<br>
			HE_wt (체중) <input type="text" name="att3_7">
			<br>
			
			
			
			
			
			
			<!-- 1번째 DJ4_dg : 위에 있음 -->
			LQ4_12 (활동제한 사유 : 시력 문제)<select name="att4_1">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			LQ4_14 (활동제한 사유 : 치매) <select name="att4_2">
				<option value="0">아니오</option>
				<option value="1">예</option>
				</select>
			<br>
		    LQ_5EQL (불안/우울) <select name="att4_3">
		    	<option value="0">비해당 / 무응답</option>
		    	<option value="1">불안하거나 우울하지 않다</option>
				<option value="2">다소 불안하거나 우울하다</option>
				<option value="3">심하게 불안하거나 우울하다</option>
			</select>
			<br>
			BO3_10 (1년간 체중조절 노력 : 기타)<select name="att4_4">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			BP7 (1년간 정신문제 상담) <select name="att4_5">
				<option value="0">비해당 / 무응답</option>
				<option value="1">예</option>
				<option value="2">아니오</option>
			</select>
			<br>
			mh_suicide (자살 생각 여부) <select name="att4_6">
				<option value="0">없음</option>
				<option value="1">있음</option>
			</select>
			<br>
			HE_STRfh3 (뇌졸중 의사진단 여부 - 형제자매) <select name="att4_7">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			HE_THfh1 (갑상선질환 의사진단 여부 - 아버지) <select name="att4_8">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			MO4_12 (다쳐서 빠지거나 부러진 치아 치료) <select name="att4_9">
				<option value="0">안함</option>
				<option value="1">함</option>
			</select>
			<br>			
			
			
			
			
			
			DI2_dg (이상지혈증 의사진단여부) <select name="att5_1">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			DI3_dg (뇌졸중 의사진단여부)<select name="att5_2">
				<option value="0">아니오</option>
				<option value="1">예</option>
				</select>
			<br>
		    DI4_dg (심근경색 또는 협심증 진단여부)<select name="att5_3">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			
			<!-- D_8_1 == att1_3 -->
			
			DE1_dg (당뇨병 진단여부)<select name="att5_4">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			DN1_dg (신부전증 진단여부)<select name="att5_5">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			LQ4_16 (활동제한사유 : 정신지체)<select name="att5_6">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			HE_HPfh3 (형제자매 고혈압 진단여부)<select name="att5_7">
				<option value="0">아니오</option>
				<option value="1">예</option>
			</select>
			<br>
			

			<input type="submit" class="btn btn-info" value="보험 가입하기">
		</form>

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

