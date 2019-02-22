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

?>

	<!DOCTYPE html>
	<html lang="ko">
		<head>
			<meta charset="utf-8">
	
			<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
			Remove this if you use the .htaccess -->
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
			<title>OPIS - 관리자 페이지</title>
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
	              <li class="active"><a href="admin_mainpage.php">Home</a></li>
	              <li><a href="admin_manage_req.php">보험금 청구 관리</a></li>
	              <li><a href="admin_manage_item.php">보험 상품 관리</a></li>
	            </ul>
	          </div><!--/.nav-collapse -->
	        </div>
	      </div>
	    </div>
	
	    <div class="container">
	
	      <!-- Main hero unit for a primary marketing message or call to action -->
	      <div class="hero-unit">
	        
	        <h2>보험 상품 관리</h2>
	        <br>   
	        <h3>등록된 보험 상품 리스트</h3>
	      
	      <table class="table">			<!-- Bootstrap Table -->
	      <thead>
	      	<tr>
	      	<th>보험 번호</th>
	      	<th>보험 이름</th>
	      	<th>기본 보험료</th>
	      	<th>보장 질병 목록</th>
	      	<th>보장 금액</th>
	      	<th>설명</th>
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
		
		$query = "select insurance_data.insurance_id, insurance_data.name, insurance_data.default_fee,
						insurance_data.money, insurance_data.content
						from insurance_data";
         // query Products database
		 
										
         if ( !( $result = mysql_query( $query, $database ) ) )    //쿼리문을 호출!!, 보험정보 DB에서 보험정보 불러옴
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
	      
         while ( $row = mysql_fetch_row( $result ) )
    	{
					print("<tr>");
					
					echo('<td>'.$row[0].'</td>'); //보험 id
					echo('<td>'.$row[1].'</td>'); //보험 이름
					echo('<td>'.$row[2].'</td>'); //보험 료

				echo ('<td>');
 				$query_dis_p_insu = "select insurance_disease.insurance_id, insurance_disease.disease_id 
									from insurance_disease 
									where insurance_disease.insurance_id = $row[0]";								
					
				$result_insu = mysql_query( $query_dis_p_insu, $database );		//db접속 요청		

				while($row_insu =  mysql_fetch_row($result_insu)){ //각 보험 당 질병 ID 선택.

				$query_disease = "select disease_id, disease_name       
								from disease_data 
								where disease_data.disease_id = $row_insu[1]";

				$result_disease = mysql_query( $query_disease, $database );	//1.접속요청
				
				   while($row_disease = mysql_fetch_row($result_disease)){ //2. 순환 (disease name per disease table)
						print ($row_disease[1].' '); 
				   } // 질병ID에 해당하는 이름 찾기while
				}
				print ('</td>');	
			///보장 질병 목록 출룍
			echo('<td>'.$row[3].'</td>'); //보장액
			echo('<td>'.$row[4].'</td>'); //설명
					
			print("</tr>");
    	}	      
	      ?>
	      </tbody>
	      </table>
	      
	      </div>
	      
	      <h3>새 보험 상품 등록하기</h3>
	      
	<form class="form-horizontal" action = "add_insurance.php" method="post">
        <div class="form-group">
            <label class="control-label col-xs-2">보험 이름</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_ins_name">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">기본 보험료</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_ins_fee">
            </div>
        </div>
        
        <br>
        
        <div class="form-group">
            <label class="control-label col-xs-2">보장 질병 선택</label>
            <div class="col-xs-10">
                <!--<input type="text" class="form-control" name="input_ins_disease">-->
				<input type="checkbox" name="check_disease[]" value="1">골관절염
				<input type="checkbox" name="check_disease[]" value="2">당뇨병
				<input type="checkbox" name="check_disease[]" value="3">아토피성 피부염
				<input type="checkbox" name="check_disease[]" value="4">우울증
				<input type="checkbox" name="check_disease[]" value="5">고혈압
            </div>
        </div> 
		
		<br>
		
        <div class="form-group">
            <label class="control-label col-xs-2">보장 금액</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_ins_money">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">설명</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="input_ins_content">
            </div>
        </div>
        
        <br>
        
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <button type="submit" class="btn btn-success">새 보험 상품 등록</button>
            </div>
        </div>
    </form>
	      
	      
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

