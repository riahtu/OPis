<?php
session_start();

$userid = $_REQUEST['input_id'];
$password = $_REQUEST['input_password'];

$_SESSION['user_id'] = $userid;

?>

<?php

$query = "select * from admin_doctor where admin_doctor.id='$userid' and admin_doctor.password='$password'";

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


// $result = oci_parse($link, $query);
// oci_execute($result);

if($row = mysql_fetch_row($result)){
		if($row[3] == 0)
			echo '<script>document.location.href="admin_mainpage.php";</script>';
		else
			echo '<script>document.location.href="doctor_mainpage.php";</script>';
}

else{
	echo "<script>alert('Wrong ID or Password Input.');
	</script>";
	echo '<script>document.location.href="index.php";</script>';
}
mysql_close( $database );
?>
