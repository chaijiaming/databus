<?php

session_start();
$space_id = $_GET["space_id"];
$tran_id = $_GET["trans_id"];
$review = $_POST["review"];
$ratting = $_POST["ratting"];
$user_id = $_SESSION["id"];
//$user_id = $_SESSION["idid"];

$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);

if(mysqli_connect_error())
{
  die("connection failed:" . mysqli_connect_error());
}

$max_rev_id = "SELECT MAX(Review_id) AS max_id FROM Review";
$max_result = mysqli_query($connect, $max_rev_id);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['max_id'] + 1;

echo "rev_id" . $max_id_increment . "<br><br>";

$sql_insert_review = "INSERT INTO `Review` (`Review_id`, `rating`, `Space_id`, `Regular_id`, `content`, `Trans_id`)
                      VALUES ('$max_id_increment', $ratting, '$space_id', '$user_id', '$review', '$tran_id');";
$max_result = mysqli_query($connect, $sql_insert_review);
if (!$max_result){
  echo mysqli_error($connect);
}

echo $review. "<br><br>";
echo "space id: " .$space_id. "<br><br>";
echo "tran id: " .$tran_id. "<br><br>";
echo "ratting: " .$ratting. "<br><br>";

header("refresh:2;//localhost/databus/me.php");

?>


<html>
<header>this is add review page </header>
</html>
