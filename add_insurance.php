<?php
session_start();

$name = $_REQUEST['input_ins_name'];
$fee = $_REQUEST['input_ins_fee']; 
$money = $_REQUEST['input_ins_money'];
$content = $_REQUEST['input_ins_content'];

?>

<?php									
      if ( !( $database = mysql_connect( "localhost",
            "root", "apmsetup" ) ) )
            die( "Could not connect to database </body></html>" );
   
         // open Products database
         if ( !mysql_select_db( "opis", $database ) )
            die( "Could not open products database </body></html>" );

	$query = "set names utf8";
	mysql_query( $query, $database );

	$query = "insert into insurance_data (name, default_fee, money, content) values('$name', $fee, $money, '$content') ";
	mysql_query($query, $database); //일단 보험 상품 추가 쿼리문 수행후
	
	$count_query = "select Count(*) from insurance_data";
	$result_count = mysql_query( $count_query , $database ); //1줄 가로로 받아옴
	$count_row    = mysql_fetch_row($result_count);
//	echo "Data count = ".$count_row[0]."<br>";
		
	foreach($_POST['check_disease'] as $entry){
			
				$entry_int = (int)$entry;
	//			echo "---".$entry_int."---";
				
				$query_chkbox = "insert into insurance_disease (insurance_id, disease_id) values( $count_row[0],$entry_int)";
				
				if ( !( $result = mysql_query( $query_chkbox, $database ) ) )  {
					print( "<p>Could not execute query!</p>" );
					die( mysql_error() . "</body></html>" );					
				}
				echo "<script>alert('Record Success.');
				</script>";
      } //이제 체크된 체크박스 값도 받아와서 체크한 결과를 insurance_disease폴더에 넣어야됨 	 
    
	
	mysql_close( $database );
	
	echo '<script>document.location.href="admin_manage_item.php";</script>';
?>
