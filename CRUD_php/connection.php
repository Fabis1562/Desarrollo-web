
<?php 
/*
function connection(){
$host="localhost";
$user="root";
$pass="";
$db="users_crud";

$connect=mysqli_connect($host,$user,$pass,$db);
mysqli_select_db($connect,$db);
return $connect;

}
*/

function connection(){
$host="localhost";
$user="root";
$pass="";
$db="users_crud";
$connect=new mysqli($host,$user,$pass,$db);
if($connect->connect_error){
    die("Error al conectar a la base de datos: " . $connect->connect_error);
}
$connect->set_charset("utf8");
return $connect;

}                                                                                                                                                                                                                                                                                                                       

?>
