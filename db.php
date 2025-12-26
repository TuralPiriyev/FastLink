<?php 
 $dbhost = 'localhost';
 $dbuser = 'root';
 $dbpass = '';
 $dbname = 'fast_link';

 $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
 if(!$conn) {echo "ugursuz oldu!";}
?>