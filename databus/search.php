<?php
session_start();
if(isset($_POST["search"])) {
    $user_input = $_POST["search"];
} else{
    $user_input = $_SERVER['QUERY_STRING'];
}

$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);



if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}
$sql_get_search_result = "SELECT * FROM space;";
$search_result = mysqli_query($connect, $sql_get_search_result);
$result_count = 0;
$isresult = 0;
$space_count = mysqli_num_rows($search_result);
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
    <title>Databus.com</title>


</head>
<body>
<div class=\"topnav\">
    <a class=\"active\" href=\"index.php\">Databus.com</a>


";

if (isset($_SESSION['first_name'])) {
    $ti = $_SESSION['first_name'];
    echo"<a class=\"right\" href='logout.php'>Logout </a>";
    echo "
        <a class=\"right\" style=\"background-color: #2196F3; color: white;\" href=\"me.php\">$ti</a> ";
} else {
    echo " <a class=\"right\" href=\"login.html\">Login</a>
    <a class=\"right\" href=\"Signup.html\">Signup</a>";
}

echo" <form action='search.php' method='post'>
    <input type=\"search\" name='search' placeholder=\"Search space now...\">
    </form>
</div>


<div class=\"jumbotron\" style=\"margin-bottom: 0px;background: linear-gradient(141deg, #074e49 0%, #20696a 51%, #196683 75%); border: none;\" >

  <div class=\"container text-center\" style = \" background: transparent\" >
    <h1 style = \"color: white;\" > $user_input </h1 >      
  
  <section class=\"col-xs-12 col-sm-6 col-md-12\" style=''>
  </div >
    
    ";

for ($x = 1; $x <= $space_count; $x++) {

    $currentrow = mysqli_fetch_assoc($search_result);
    $currentname = $currentrow['space_name'];
    $currentser = $currentrow['service'];
    $spaceid = $currentrow['Space_id'];
    $space_addr_sql = "SELECT * FROM space_addr WHERE '$spaceid' = space_addr_id;";
    $get_space_addr = mysqli_query($connect, $space_addr_sql);
    $space_addr_row = mysqli_fetch_assoc($get_space_addr);
    $descript = $currentrow['descr'];

    $current_zipcode = $space_addr_row['zip_code'];
    $current_street = $space_addr_row['street'];
    $current_state = $space_addr_row['state'];
    $current_city = $space_addr_row['city'];
    $current_company = $space_addr_row['company'];
    $starttiem = $currentrow['resv_start_date'];
    $endtime = $currentrow['resv_end_date'];
    $price = $currentrow['price'];
    $imgsrc = $currentrow['img'];

    $namesimmilar = similar_text($user_input, $currentname, $namep);
    $servicesimmilar = similar_text($user_input, $currentser, $serp);
    $zipcodesimmilar = similar_text($user_input, $current_zipcode, $zipp);
    $streetsimmilar = similar_text($user_input, $current_street, $streetp);
    $citysimillar = similar_text($user_input, $current_city, $cityp);
    $companysimillar = similar_text($user_input, $current_company, $compp);

    $nc = $currentname. '' .$current_city;



    $ncsimilalr = similar_text($user_input, $nc, $ncp);


    if (strcmp($current_state, $user_input)==0){
        $isresult = 1;
        $result_count = $result_count + 1;
    }

    if ($isresult == 0){
        if ($namep > 80 || $serp >60 || $zipp > 60 || $streetp > 50 || $cityp > 80 || $compp > 70 || $ncp > 70){
            $isresult = 1;
            $result_count = $result_count + 1;
        }
    }

    if ($isresult == 1){
        echo "
   <article class=\"search-result row\" style='padding: 20px 10px; margin: 50px 50px; ; border-color: transparent ;background: white ; border-radius: 10px'>
			<div class=\"col-xs-12 col-sm-12 col-md-3\">
				<a href=\"spacepage.php?$spaceid\" title=\"$currentname\" class=\"thumbnail\"><img src=\"$imgsrc\" alt=\"$currentname\" /></a>
			</div>
			<div class=\"col-xs-12 col-sm-12 col-md-2\">
				<ul class=\"meta-search\" style='padding-top: 30px; font-weight: 200;'>
					<li><i class=\"glyphicon glyphicon-calendar\"></i> <span>From: $starttiem</span></li><br>
					<li><i class=\"glyphicon glyphicon-calendar\"></i> <span>To: $endtime</span></li><br>
					<li><i class=\"glyphicon glyphicon-globe\"></i> <span> $currentser</span></li><br>
					<li><i class=\"glyphicon glyphicon-book\"></i> <span> $current_company</span></li>
				</ul>
			</div>
			<div class=\"col-xs-12 col-sm-12 col-md-7 excerpet\">
				<h3><a href=\"spacepage.php?$spaceid\" title=\"\">$currentname at $current_city</a></h3>
				<p>This space is located in $current_street, $current_city, $current_state. $descript</p>
				<h3 class='col-sm-4' style='padding: 0px;  ;' > $ $price / Day </h3>
				
</div>
			<span class=\"clearfix borda\"></span>
		</article>
";

    }

    $isresult = 0;

}

echo"</section>";


if($result_count == 0){

    echo "


        <div class=\"container text-center\">
        <div class=\"row\">
        <div class=\"gallery col-lg-12 col-md-12 col-sm-12 col-xs-12\">
            <h2 class=\"gallery-title\" style='color: white'>Sorry, we could not find the result!</h2>
        </div>

       ";
} else {
    echo "


        <div class=\"container text-center\">
        <div class=\"row\">
        <div class=\"gallery col-lg-12 col-md-12 col-sm-12 col-xs-12\">
            <h3 class=\"gallery-title\" style='color: white'>We have searched $result_count spaces for you.</h3>
        </div>

       ";
}


echo "</div></div></div>
 <link href=\"//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css\" rel=\"stylesheet\">

<footer class=\"footer text-center\" style='padding-top: 5rem;
    padding-bottom: 5rem;
    background-color: #2c3e50;
    color: #fff;display: block;'>
      <div class=\"container\">
        <div class=\"row\">
          <div class=\"col-md-4 mb-5 mb-lg-0\">
            <h4 class=\"text-uppercase mb-4\">Location</h4>
            <p class=\"lead mb-0\">W204 Westgate Building
              <br>State College, PA, 16803</p>
          </div>
          <div class=\"col-md-4 mb-5 mb-lg-0\">
            <h4 class=\"text-uppercase mb-4\">Around the Databus.com</h4>
            <ul class=\"list-inline mb-0\" >
              <li class=\"list-inline-item\">
                <a class=\"btn btn-outline-light btn-social text-center rounded-circle\" href=\"#\">
                  <i class=\"fa fa-fw fa-facebook\" style='color: white;'></i>
                </a>
              </li>
              <li class=\"list-inline-item\">
                <a class=\"btn btn-outline-light btn-social text-center rounded-circle\" href=\"#\">
                  <i class=\"fa fa-fw fa-google-plus\"style='color: white;'></i>
                </a>
              </li>
              <li class=\"list-inline-item\">
                <a class=\"btn btn-outline-light btn-social text-center rounded-circle\" href=\"#\">
                  <i class=\"fa fa-fw fa-twitter\"style='color: white;'></i>
                </a>
              </li>
              <li class=\"list-inline-item\">
                <a class=\"btn btn-outline-light btn-social text-center rounded-circle\" href=\"#\">
                  <i class=\"fa fa-fw fa-linkedin\"style='color: white;'></i>
                </a>
              </li>
              <li class=\"list-inline-item\">
                <a class=\"btn btn-outline-light btn-social text-center rounded-circle\" href=\"#\">
                  <i class=\"fa fa-fw fa-dribbble\"style='color: white;'></i>
                </a>
              </li>
            </ul>
          </div>
          <div class=\"col-md-4\">
            <h4 class=\"text-uppercase mb-4\">About Databus.com</h4>
            <p class=\"lead mb-0\">Databus.com is a student non-profit space finding Website. <br>
              <a href=\"mailto:help@databus.com\" style='color: white'>Contact Us</a>.</p>
          </div>
        </div>
      </div>
    </footer>
<div class=\"copyright py-4 text-center text-white\" style='background-color: black;'>
      <div class=\"container\" style='padding: 30px; color: white;'>
        <small>Copyright © Databus.com 2018</small>
      </div>
    </div>

";