<?php
session_start();
$user_id = $_SESSION['user_id'];

     	 if ( !( $database = mysql_connect( "localhost",
            "root", "apmsetup" ) ) )                      
            die( "Could not connect to database </body></html>" );
   
         // open Products database
         if ( !mysql_select_db( "opis", $database ) )
            die( "Could not open products database </body></html>" );

		$query = "set names utf8";
		mysql_query( $query, $database );
		
		$query = "select id, name from admin_doctor where admin_doctor.id='$user_id'";

         // query Products database
         if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         
         // fetch each record in result set
         while ( $row = mysql_fetch_row( $result ) )
         {
		   $doctor_id   = $row[0];
		   $doctor_name = $row[1];
         } // end while
         
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
	        <h2>보험금 청구 관리</h2>
	        <br>
	    	<p>시스템에 등록된 진료기록 중, 환자가 가입한 보험의 혜택을 받을 수 있는 케이스를 출력합니다.</p>
	    	<br>
	    	
	    	<?
	    	
	    	$query = "select SUM(real_fee) from contract_list";
			$result = mysql_query( $query, $database );
			$row = mysql_fetch_row( $result );
			$total_fee = $row[0];
			
			$query = "select SUM(money) from receive_list";
			$result = mysql_query( $query, $database );
			$row = mysql_fetch_row( $result );
			$total_send_money = $row[0];
			
	    	$date = date("Y-m-d");
	    	
	    	print("<p>$date 현재 시스템에 등록된 회원들의 총 1개월 납입 보험료는 <strong>".$total_fee."</strong> 원,<br>");
			print("<p>현재까지 지급한 보험금의 총합은 <strong>".$total_send_money."</strong> 원입니다.<br>");
	    	?>
	    	
	      </div>
	      
	      <br>
	      <table class="table">			<!-- Bootstrap Table -->
	      <thead>
	      	<tr>
	      	<th>진료기록 번호</th>
	      	<th>환자 ID</th>
<!--	      	<th>질병코드</th>-->
			<th>질병명</th>
	      	<th>가입 보험 이름</th>
	      	<th>지급예정 보험금</th>
			<th>지급 여부</th>
	      	<th>소견</th>
	      	<th>날짜</th>
	      	</tr>
	      </thead>
	      <tbody>
	      <?
	      
		$query = "select * 
				from medical_record";
									
		//contract 리스트의 구글id = 의료기록의 구글id  
		//contract 리스트의 보험id
		//-> 보험-질병 table 의 질병 id = contract리스트의 질병id
		
		if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
	      
	     while ( $row = mysql_fetch_row( $result ) )
    	 { 
	  
	  
					$disease_name_query = "select disease_data.disease_name from disease_data
									where disease_data.disease_id = $row[2]";
					
					$result_disease = mysql_query($disease_name_query);
					$row_disease = mysql_fetch_row ($result_disease);
					$disease_name = $row_disease[0];
					//echo('<td>'.'ddd'.'</td>'); //medical_record.disease_name
					//echo('<td>'.$row[2].'</td>'); //medical_record.disease_id
					
					$medical_record_id = $row[0];
					$medical_google_id = $row[1];
					$medical_disease_id = $row[2];
					//$date_record = $row[4];
					
				    //다만 첫번쨰  보험 아닌경우에는 hidden data로 바꾸기
								
				//1:1이 아니니까 각 질병 이름 별로 보험을 받아와야됨! 
 				$query_insu_p_disease = "select insurance_disease.insurance_id, insurance_disease.disease_id 
									from insurance_disease 
									
									where insurance_disease.disease_id = $row[2] ";								
									//row[2]가 진료 기록의 질병 ID
									//를 질병-보험 table과 매칭							
					
					$chk = 0;
    				print("<tr>");
					echo('<td>'.$row[0].'</td>'); //medical_record.record_id
					echo('<td>'.$row[1].'</td>'); //medical_record.google_id
					
					echo('<td>'.$disease_name.'</td>'); //medical_record.disease_name				

										
				$result_disease = mysql_query( $query_insu_p_disease, $database );		//db접속 요청		
				
				while(  $row_disease =  mysql_fetch_row($result_disease)){ //위의 쿼리문 수행. 각 질병ID 당 보험 ID 갖고옴 while문.
					
///////////////////					
					
					
					// 질병=>갖고온 보험ID에 해당하는 보험 이름 찾기while 
					
					   $query_insu = "select name, money, insurance_id  from insurance_data 
						where insurance_data.insurance_id = $row_disease[0]";   
					   $result_insu = mysql_query( $query_insu, $database );	
					   
				    while( $row_insu = mysql_fetch_row($result_insu)){  // 2. 보험테이블에서 보험ID for문	
						
						//이 시점에서 contract list를 뒤져야됨
						$insurance_id = $row_insu[2]; //지금 처리할 보험 명
						$insurance_money = $row_insu[1];

						$query_contract_yn = "select * from contract_list  
											where contract_list.insurance_id = $insurance_id
											and   contract_list.google_id    = $medical_google_id";
						
						$result_contract_yn=mysql_query($query_contract_yn);
						
						$row_contract_yn = mysql_fetch_row( $result_contract_yn );
						if(!$row_contract_yn[0]) continue;
						if($chk >= 1)	{ print ("<tr>"); print("<td></td><td></td><td></td>");	 }
						
						echo ('<td>'.$row_insu[0].'</td>'); //보험이름 출력합니다
						echo ('<td>'.$row_insu[1].'</td>'); // 보장액 출력합니다
						
						
						/* 신청하기 버튼 클릭 되있는지(send list에 들어가 있는지) 체크하고 체크 안되있으면 지급완료 출력, 아니면 지급버튼 출력*/
						$query = "select * from receive_list, medical_record
								
								where receive_list.record_id = $medical_record_id
								 and receive_list.insurance_id = $insurance_id 
								 and receive_list.google_id = $medical_google_id"; //row_disease[0] = 보험ID 
									
						$result_send = mysql_query( $query, $database );					
						$row_send = mysql_fetch_row( $result_send ); 
						//print($row_Receive[0]);
					//버튼. 안중요함 
						if( !$row_send[0] )
						{
							echo('<form action = "send_money.php" method="post">');
							echo('<input type="hidden" name="record_id" value='.$medical_record_id.'>'); //맨위 while. 의료기록record
							echo('<input type="hidden" name="google_id" value='.$medical_google_id.'>'); 
							echo('<input type="hidden" name="money" value='.$insurance_money.'>');
							echo('<input type="hidden" name="insurance_id" value='.$insurance_id.'>');
							echo('<td><button type="submit" class="btn btn-success">지급하기</button></td>');
							echo('</form>');
						}
						
						else
						{
							echo('<td>지급 완료</td>');
						}
						
						if($chk>=1) echo  ('<td></td><td></td>');
						else {
							echo('<td>'.$row[5].'</td>'); //medical_record.comment,
							echo('<td>'.$row[4].'</td>'); //medical_record.record_date
						}
				   } //진료기록 당 for문 끝
				   $chk++; 
				   print("</tr>");
				}
					
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

