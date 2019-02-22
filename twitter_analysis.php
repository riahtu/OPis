<!doctype HTML>
<html lang="en">
<head>
	<title>Twitter Timeline with API 1.1</title>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="robots" content="index,follow">
	<link rel="stylesheet" href="twitter/styles.css">
</head>

<body>

	<?php 
	
	$class = $_REQUEST['radio_button'];
	$twt_id = $_REQUEST['twitter_account'];		// 트위터 ID 받아오기, 이 계정의 트윗을 검색
	$discount_fee = $_REQUEST['discounted_fee'];
	
	include_once('twitter/twitter.php'); 
	
	?>
	
	<form action="join_insurance_3.php" method="post" name="frm">
		<input type="hidden" name="radio_button" value="<?print($class);?>">
		<input type="hidden" name="discounted_fee" value="<?print($discount_fee);?>">
		<input type="submit" value="다음 페이지로">
	</form>
	
<script language = "javascript">
document.frm.submit();
</script>
	
	<footer>
		<a href="https://github.com/kmaida/twitter-timeline-php">twitter-timeline-php</a> on <a href="http://github.com">GitHub</a><br>
		GNU Public License
	</footer>
	
	<!-- jQuery library -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<!-- Optional: include //code.jquery.com/jquery-migrate-1.2.1.js if IE6/7/8 support is needed -->
	
	<!-- Web Intents for Reply / Retweet / Favorite popup functionality (https://dev.twitter.com/docs/intents) -->
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	<!-- Custom Twitter functions -->
	<script type="text/javascript" src="twitter/twitter.js"></script>
</body>
</html>