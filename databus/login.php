<?php
session_start();
$email = $_POST["email"];
$user_password = $_POST["password"];
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);
if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}
$sql_get_username_password = "SELECT vendor_id, first_name, password, company FROM VENDOR WHERE email = '$email';";
$user_name_result = mysqli_query($connect, $sql_get_username_password);
if(!$user_name_result){
    echo mysqli_error($connect);
}else{
    if (mysqli_num_rows($user_name_result) <= 0){
        $sql_get_username_password = "SELECT regular_id, first_name, password FROM REGULAR WHERE email = '$email';";
        $user_name_result = mysqli_query($connect, $sql_get_username_password);
        if (mysqli_num_rows($user_name_result) <= 0) {
            echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"indexStyle.css\">
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">

    <!-- jQuery library -->
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>

    <!-- Latest compiled JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <title>Login</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
<h3>Please try again</h3>
  <div class=\"alert alert-danger\">
    
    <strong>Sorry...</strong> You entered email or password incorrectly!
  </div>
</div>
";
            header("refresh:3;//localhost/databus/login.html");
        }
        else{
            $row = mysqli_fetch_assoc($user_name_result);
        }
    }
    else{
        $row = mysqli_fetch_assoc($user_name_result);
    }
    if ($row["password"] == $user_password){
        if(isset($row["vendor_id"])){
            $_SESSION["id"] = $row["vendor_id"];
            $_SESSION["company"] = $row['company'];
        } else {
            $_SESSION["id"] = $row["regular_id"];
        }


        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $row["first_name"];
        if (isset($_SESSION['login_space_id'])){
            $s_id = $_SESSION['login_space_id'];
            $_SESSION['login_space_id'] = null;
            header("refresh:3;//localhost/databus/spacepage.php?$s_id");
        } else{
            header("refresh:3;//localhost/databus/index.php");
        }

        echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"indexStyle.css\">
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">

    <!-- jQuery library -->
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>

    <!-- Latest compiled JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <title>Login</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
<h3>Welcome Back</h3>
  <div class=\"alert alert-success\">
    
    <strong>Login Successfully!</strong>  Please wait while we are taking you back.
  </div>
</div>
";

    }else{
        header("refresh:3;//localhost/databus/login.html");
        echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"indexStyle.css\">
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">

    <!-- jQuery library -->
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>

    <!-- Latest compiled JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <title>Login</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
<h3>Please try again</h3>
  <div class=\"alert alert-danger\">
    
    <strong>Sorry...</strong> You entered email or password incorrectly!
  </div>
</div>
";

    }
}
mysqli_close($connect);
