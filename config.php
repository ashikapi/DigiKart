<?php
$servername="localhost";
$username="root";
$password="";
$dbname="digikart";
$conn = mysqli_connect($servername, $username, $password,$dbname);
if(!$conn){
    echo "unsuccessful" .mysqli_connect_error();
}
else{
    echo "";
}




?>