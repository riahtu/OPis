<?php

//start session
session_start();
$user_id = $_SESSION['user_id'];

?>

	<!DOCTYPE html>
	<html lang="ko">
		<head>
			<meta charset="utf-8">
	
			<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
			Remove this if you use the .htaccess -->
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
			<title>OPIS - 의사 - 진료기록 등록</title>
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
	              <li class="active"><a href="doctor_mainpage.php">Home</a></li>
	              <li><a href="doctor_viewrecord.php">환자 의료기록 조회</a></li>
	              <li><a href="doctor_enrollrecord.php">환자 의료기록 등록</a></li>
	            
	            </ul>
	          </div><!--/.nav-collapse -->
	        </div>
	      </div>
	    </div>
	
	    <div class="container">
	
	      <!-- Main hero unit for a primary marketing message or call to action -->
	      <div class="hero-unit">
	        <br>
	        <p>환자의 진료기록을 입력합니다.</p>
	        <br>
	      </div>
	      
	      
	      
	       <form class="form-horizontal" action = "add_medical_record.php" method="post">
        <div class="form-group">
            <label class="control-label col-xs-2">환자 Google E-mail</label>
            <div class="col-xs-10">
                <input type="email" class="form-control" name="input_pai_id">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">질병코드</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_disease">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">처방할 약</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_medicine">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">소견</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_comment">
            </div>
        </div>
        
        <br>
        
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <button type="submit" class="btn btn-success">진료기록 등록</button>
            </div>
        </div>
    		</form>
	      
	   
	      
	    <!--  
	    <form action = "add_medical_record.php" method="post">
	  		환자 Google E-mail  <input type="text" name="input_pai_id">
	  		<br>
			질병코드  <input type="text" name="input_disease">
			<br>
			처방할 약  <input type="text" name="input_medicine">
			<br>
			소견  <input type="text" name="input_comment">
			<br>
			<input type="submit" class="btn" value="진료기록 등록">
		</form>
	    -->
	    
	    
	      <hr>
	
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

