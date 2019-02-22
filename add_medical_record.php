<?php
session_start();

$doctor_id = $_SESSION['user_id'];

$user_id = $_REQUEST['input_pai_id'];
$disease_name = $_REQUEST['input_disease'];
$medicine_name = $_REQUEST['input_medicine'];
$comment = $_REQUEST['input_comment'];

$date = date("Y-m-d");

?>

<?php



      if ( !( $database = mysql_connect( "localhost",
            "root", "apmsetup" ) ) )                      
            die( "Could not connect to database </body></html>" );
   
         // open Products database
         if ( !mysql_select_db( "opis", $database ) )
            die( "Could not open products database </body></html>" );

$query = "select google_id from google_users where google_email = '$user_id'";

         // query Products database
         if ( !( $result = mysql_query( $query, $database ) ) ) 
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         
        $query = "set names utf8";
		mysql_query( $query, $database );

if($row = mysql_fetch_row($result)){
	$query = "insert into medical_record (google_id, disease_id, medicine_list, record_date, comment)
				 values('$row[0]','$disease_name','$medicine_name', '$date', '$comment')";
	
	if ( !( $result = mysql_query( $query, $database ) ) ) 
    {
      print( "<p>Could not execute query!</p>" );
      die( mysql_error() . "</body></html>" );
    } // end if
    
    echo "<script>alert('Record Success.');
	</script>";
	echo '<script>document.location.href="doctor_mainpage.php";</script>';
}

else{
	echo "<script>alert('Wrong User ID Input.');
	</script>";
	echo '<script>document.location.href="doctor_enrollrecord.php";</script>';
}
mysql_close( $database );
?>
