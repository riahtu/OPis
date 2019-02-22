<?php
session_start();
$user_id = $_SESSION['user_id'];

$query = "select id, name from admin_doctor where admin_doctor.id='$user_id'";

     	 if ( !( $database = mysql_connect( "localhost",
            "root", "apmsetup" ) ) )                      
            die( "Could not connect to database </body></html>" );
   
         // open Products database
         if ( !mysql_select_db( "opis", $database ) )
            die( "Could not open products database </body></html>" );

         // query Products database
         if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         
         // fetch each record in result set
         while ( $row = mysql_fetch_row( $result ) )
         {
		   $doctor_id = $row[0];
		   $doctor_name = $row[1];
         } // end while
         
// logout
//if (isset($_REQUEST['logout'])) 
//{
//  echo "<script>alert('Successfully Logouted');</script>";
//  echo '<script>document.location.href="index.php";</script>';
//}    

if (isset($_REQUEST['input_pai_id']))
{
	
	$query = "set names utf8";
	mysql_query( $query, $database );
	
	$pai_email = $_REQUEST['input_pai_id'];
	$query = "select google_id from google_users where google_email = '$pai_email'";
	
	if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         
    if ( $row = mysql_fetch_row( $result ) )
    {
		$pai_id = $row[0];
		//$isSearch = true;
    }
	else
	{
		echo "<script>alert('Error : Cannot find ID.');
	</script>";
		echo '<script>document.location.href="doctor_viewrecord.php";</script>';	
	}
	
	//echo '$pai_id';
	
	$query = "select * from medical_record where medical_record.google_id=$pai_id";
	if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
    
}

?>

	<!DOCTYPE html>
	<html lang="ko">
		<head>
			<meta charset="utf-8">
	
			<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
			Remove this if you use the .htaccess -->
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
			<title>index</title>
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
	        
	        <h2>진료기록 확인</h2>
	        <br>
	        
	        <form action = "doctor_viewrecord.php" method="post">
	  		진료기록을 확인할 환자 Google E-mail 입력 : <input type="text" name="input_pai_id">
	  		<br>
	  		<input type="submit" class="btn btn-success" value="진료기록 확인">
		</form>
	        
	      </div>
	      
	      <h3>해당 환자의 진료기록 정보</h3>
	      <br>
	      
	      <table class="table">			<!-- Bootstrap Table -->
	      <thead>
	      	<tr>
	      	<th>진료기록 번호</th>
	      	<th>환자 Google ID</th>
	      	<th>질병코드</th>
	      	<th>처방 약품</th>
	      	<th>소견</th>
	      	<th>날짜</th>
	      	</tr>
	      </thead>
	      <tbody>
	      <?
	          while ( $row = mysql_fetch_row( $result ) )
    			{
    				print("<tr>");
					echo('<td>'.$row[0].'</td>');
					echo('<td>'.$row[1].'</td>');
					echo('<td>'.$row[2].'</td>');
					echo('<td>'.$row[3].'</td>');
					echo('<td>'.$row[5].'</td>');
					echo('<td>'.$row[4].'</td>');
					
					print("</tr>");
    			}
	      
	      ?>
	      </tbody>
	      </table>
	      
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

