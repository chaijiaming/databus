<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/10/18
 * Time: 8:00 PM
 */

session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);


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


</head>";

if (isset($_SESSION['first_name'])) {
    $ti = $_SESSION['first_name'];
    echo"
<body>
<div class=\"topnav\">
    <a class=\"active\" href=\"index.php\">Databus.com</a>
    <a class=\"right\" href='logout.php'>Logout </a>
    <a class=\"right\" style=\"background-color: #2196F3; color: white;\" href=\"me.php\">$ti</a>
    <form action='search.php' method='post'>
    <input type=\"search\" name='search' placeholder=\"Search space now...\">
    </form>
</div>
";}
else {
    echo"
<body>
    <div class=\"topnav\">
    <a class=\"active\" href=\"index.php\">Databus.com</a>
    <a class=\"right\" href=\"login.html\">Login</a>
    <a class=\"right\" href=\"Signup.html\">Signup</a>
    <form action='search.php' method='post'>
    <input type=\"search\" name='search' placeholder=\"Search space now...\">
    </form>
</div>
    
    ";
    }



    echo"
<div class=\"jumbotron\" style=\"margin-bottom: 0px;background: linear-gradient(141deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%); border: none;\" >

  <div class=\"container text-center\" style=\" background: transparent\">
    <h1 style=\"color: beige;\">Databus</h1>      
    <p style=\"color: aliceblue;\">A place where you can reserve professional space</p>
  </div>
</div >
<img src=\"indexpic.jpg\" style=\"max-width: 100%; height: auto; \">

<div class=\"jumbotron text-center\" style=\"background-color: #032B3A;color: white; margin-bottom: 0px;\">
    <h1>Trending</h1>      
    <p>Look what's popular</p>
  <div class=\"container\" >
  <div class=\"row\">
  <div class=\"col-md-4\">
      <div class=\"thumbnail\" style='border-radius: 10px;' >
      <a href='search.php?StateCollege'>
          <div class=\"caption text-center\" >
          <img  class='img-responsive' src= \"state_college.jpg\"  style = \"width:100%\" >
            <h3  class='carousel-caption'> State College</h3 >
          </div>
       </a>  
      
      </div >
   
    </div >
    
    <div class=\"col-md-4\">
      <div class=\"thumbnail\" style='border-radius: 10px;'>
      <a href='search.php?Altoona'>
          <div class=\"caption text-center\" >
          <img  class='img-responsive' src= \"altoona.jpg\"  style ='width:100%; height: 220px;' >
            <h3  class='carousel-caption'> Altoona</h3 >
          </div>
       </a>
      </div >
   
    </div >
    
    <div class=\"col-md-4\">
      <div class=\"thumbnail\" style='border-radius: 10px;'>
      <a href='search.php?Philadelphia'>
          <div class=\"caption text-center\" >
          <img  class='img-responsive' src= \"philly.jpg\"  style = \"width:100%\" >
            <h3  class='carousel-caption'> Philadelphia</h3 >
          </div>
       </a>
      </div >
   
    </div >
    </div>
    </div>
    </div>


<div class=\"jumbotron\" style='margin-bottom: 0px;'>

  <div class=\"container text-center\" style=\"background: transparent;\">
    <h1>Best Deals</h1>      
    <p>Here is our Weekly Deals</p>
  </div>
  <div class=\"container\">
  <div class=\"row\">
  ";

for ($x = 23; $x <= 28; $x++) {
    $sql_get_search_result = "SELECT * FROM space WHERE Space_id = '$x';";
    $search_result = mysqli_query($connect, $sql_get_search_result);
    $currentrow = mysqli_fetch_assoc($search_result);

    $currentname = $currentrow['space_name'];
    $spaceid = $currentrow['Space_id'];
    $space_addr_sql = "SELECT * FROM space_addr WHERE '$spaceid' = space_addr_id;";
    $get_space_addr = mysqli_query($connect, $space_addr_sql);
    $space_addr_row = mysqli_fetch_assoc($get_space_addr);

    $descript = $currentrow['descr'];

    $current_city = $space_addr_row['city'];
    $current_company = $space_addr_row['company'];
    $starttiem = $currentrow['resv_start_date'];
    $endtime = $currentrow['resv_end_date'];
    $price = $currentrow['price'];
    $imgsrc = $currentrow['img'];


    echo "
  
    <div class=\"col-md-4\">
      <div class=\"thumbnail\">
        <a href=\"spacepage.php?$spaceid\" >
          <img src=\"$imgsrc\" alt=\"$currentname at $current_city\" style=\"width:100%\">
          <div class=\"caption\">
            <h3>Best Deal: $$price / Day</h3>
            <h4>$currentname at $current_city </h4>
            <p>$descript</p>
          </div>
        </a>
      </div>
    </div>
    ";

}

    echo"
  </div>
  
  
 
</div>
</div>
    


</body>

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
</html>

    ";

