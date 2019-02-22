<?php
session_start();

$record_id = $_REQUEST['record_id'];
$google_id = $_REQUEST['google_id'];
$amountmoney = $_REQUEST['money'];
$insurance_id = $_REQUEST['insurance_id'];
$date = date("Y-m-d");

      if ( !( $database = mysql_connect( "localhost",
            "root", "apmsetup" ) ) )                      
            die( "Could not connect to database </body></html>" );
   
         // open Products database
         if ( !mysql_select_db( "opis", $database ) )
            die( "Could not open products database </body></html>" );
         
        $query = "set names utf8";
		mysql_query( $query, $database );
		
		$query = "insert into receive_list (record_id, google_id, money, receive_date, insurance_id) 
					values ($record_id, '$google_id', $amountmoney, '$date', '$insurance_id')";
		mysql_query( $query, $database );

mysql_close( $database );

    echo "<script>alert('Task Success.');
	</script>";
	echo '<script>document.location.href="admin_manage_req.php";</script>';

?>
