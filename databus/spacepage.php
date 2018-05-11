<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/21/18
 * Time: 4:14 PM
 */
session_start();

$spaceid = $_SERVER['QUERY_STRING'];

$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);

$_SESSION['space_id'] = $spaceid;

if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}

$longtitude = 0;
$latitude = 0;



$sql_get_search_result = "SELECT * FROM space WHERE Space_id = '$spaceid';";
$search_result = mysqli_query($connect, $sql_get_search_result);

$currentrow = mysqli_fetch_assoc($search_result);

$currentname = $currentrow['space_name'];
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
$current_zipcode = $space_addr_row['zip_code'];
$current_street = $space_addr_row['street'];
$current_state = $space_addr_row['state'];
$room = $space_addr_row['room'];
$currentser = $currentrow['service'];

$sub_category = "SELECT size, Category_id FROM sub_category WHERE '$spaceid' = Sub_category_id;";
$sub_result = mysqli_query($connect, $sub_category);
$sub_category_result = mysqli_fetch_assoc($sub_result);

$room_size = $sub_category_result['size'];
$category = $sub_category_result['Category_id'];

$category_sql = "SELECT category_name FROM category WHERE '$category' = Category_id;";
$category_result = mysqli_query($connect, $category_sql);
$category_row = mysqli_fetch_assoc($category_result);

$category_name = $category_row['category_name'];


$get_term_sql = "SELECT start_date, end_date FROM Terms WHERE Space_id = '$spaceid';";
$term_result = mysqli_query($connect, $get_term_sql);

$ven_id = $currentrow['Vendor_id'];

$ven_sql = "SELECT first_name, last_name, email, company FROM vendor WHERE '$ven_id' = Vendor_id;";
$ven_result = mysqli_query($connect, $ven_sql);
$ven_row = mysqli_fetch_assoc($ven_result);

$first_name = $ven_row['first_name'];
$last_name = $ven_row['last_name'];
$email= $ven_row['email'];
$company_name = $ven_row['company'];

$review_sql = "SELECT regular.first_name, review.content, review.rating FROM review, regular 
WHERE regular.Regular_id = review.Regular_id AND review.Space_id = '$spaceid';";

$review_result = mysqli_query($connect, $review_sql);
$review_count = mysqli_num_rows($review_result);


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

echo "
    
    <div class=\"jumbotron text-center\" style=\"background-color: #074e49;color: white; margin-bottom: 0px;\">
    <h1>$currentname at $current_city</h1>      
  
    </div>
    
<div class=\"container\" style='padding: 50px 50px;'>
  <div class=\"row\">
 
     <div class='col-lg-6'>
        <div class='thumbnail'>
        <img src='$imgsrc'>
        </div>
       
        
     </div>
     <div class='col-lg-6'>
        <div class='search-result'>
        <ul class=\"meta-search\" style='padding-top: 20px;font-size: 20px; font-weight: 300;'>
					<i class=\"glyphicon glyphicon-chevron-down\" style='margin-bottom: 30px;'></i> <span>From: $starttiem</span><br>
					<i class=\"glyphicon glyphicon-chevron-up\"style='margin-bottom: 30px;'></i> <span>To: $endtime</span><br>
					<i class=\"glyphicon glyphicon-check\"style='margin-bottom: 30px;'></i> <span> $currentser</span><br>
					<i class=\"glyphicon glyphicon-globe\"style='margin-bottom: 30px;'></i> <span> Company: $current_company</span><br>
					<i class=\"glyphicon glyphicon-pushpin\"style='margin-bottom: 30px;'></i><span>  Category: $current_state ---> $current_city ---> $category_name</span><br>
					<i class=\"glyphicon glyphicon-book\"style='margin-bottom: 30px;'></i><span>  Capacity Size: $room_size</span><br>
		</ul>
        </div>
        </div>
   </div>
</div>

<div  style='background-color: lightgray; width: 90%; height: 2px; margin: 0 auto;'></div>

<div class='container' style='padding: 50px 50px;'>
    <div class='meta-search row'>
        <div class='col-lg-6'>
         <p style='font-weight: 500; font-size: 23px;'>Conference Room Number: $room</p><br>
        <p style='font-weight: 200; font-size: 23px;'>This space is located in $current_street, $current_city, $current_state.</p><br>
         <p style='font-weight: 200; font-size: 23px;'>$descript</p><br>
        <p style='font-weight: 200; font-size: 23px;'>This $category_name featuring $currentser.</p><br>
        <p style='font-weight: 500; font-size: 25px; color: #074e49; margin-bottom: 0px;'> 
        Current Price: <i class=\"glyphicon glyphicon-flash\"style='margin-bottom: 30px;'></i> $$price/Day.</p><br>
        </div>
        
        <div class='col-lg-6'>
        <p style='font-weight: 500; font-size: 23px;margin-bottom: 0px;'>Click for Avaliability</p><br>
        <div> 
    <object type=\"text/html\" data=\"ava.php\" width=\"580px\" height=\"380px\" style=\"overflow:auto;\">
    </object>
 </div>";

if (isset($_SESSION['company'])){
    echo "<a class='btn btn-primary'>Vendor Cannot Reserve</a>";
}
else {
    echo "
 
       <a href='newest_time.php' class='btn btn-danger'>Reserve Now</a>

";
}
echo"
        
     </div>   
    </div>


</div>

<div  style='background-color: lightgray; width: 90%; height: 2px; margin: 20px auto;'></div>

   <div class='container' style='padding: 50px 50px;'>
   <style>
       #map {
        height: 300px;
        width: 100%;
       }
    </style>
        <div class='meta-search row'>
           <div class='col-lg-6'>
           <p style='font-weight: 500; font-size: 23px; margin-bottom: 30px;'>Hosted by: </p>
           <p style='font-weight: 300; font-size: 21px;'><i class=\"glyphicon glyphicon-user\"style='margin-bottom: 20px;'></i>
            $first_name $last_name </p><br>
            <p style='font-weight: 300; font-size: 21px;'><i class=\"glyphicon glyphicon-envelope\"style='margin-bottom: 30px;'></i>
            $email </p><br>
           <p style='font-weight: 300; font-size: 21px;'><i class=\"glyphicon glyphicon-lock\"style='margin-bottom: 30px;'></i>
            $company_name </p><br>
</div>
        <div class = 'col-lg-6'>
        <div id=\"map\"></div>
    <script>
       
      
      function initMap() {
          
          var result = $.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address=$current_zipcode');
          var lat = 0;
          var long = 0;
          
          result.done(function(response) {
          lat = response.results[0].geometry.location.lat;
          long = response.results[0].geometry.location.lng;
          
          document.cookie = 'longtitude = ' + long;
          document.cookie = 'latitude = ' + lat;
          
          var uluru = {lat: lat, lng: long};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 7,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
            });
          //result =  JSON.parse(result);
          //console.log(result);
        
      }
    </script>
    <script async defer
    src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyC2m-krt27TF_1_7p86OOphYJynACwvL70&callback=initMap\">
    </script>
    ";


$longtitude = $_COOKIE['longtitude'];
$latitude = $_COOKIE['latitude'];

echo"
        
</div>
         


</div>



   
</div>
    
    <div  style='background-color: lightgray; width: 90%; height: 2px; margin: 20px auto;'></div>
    <div class='container' style='padding: 50px 50px;'>
    <div class='col-lg-6'>
        <p style='font-weight: 500; font-size: 23px;'>Food Service Nearby:</p><br>
        <p>
        <a href='https://www.grubhub.com/search?orderMethod=delivery&locationMode=DELIVERY&%20facetSet=umamiV2&pageSize=20&hideHateos=true&searchMetrics=true&latitude=$latitude&longitude=$longtitude&%20facet=open_now:true&sortSetId=umamiV2&countOmittingTimes=true&variationId=default-impressionScoreViewAdj%20SearchOnlyBuffed-20160607&sponsoredSize=3'>
        <img src='grubhub.png' style='width: 300px; height: 160px;'>
        </a>
        </p>
      </div>
      <div class='col-lg-6'>
        <p style='font-weight: 500; font-size: 23px;'>You May Also Like: </p><br>
       <div class=\"thumbnail\" style='border-radius: 10px;' >
      <a href='search.php?$current_city'>
          <div class=\"caption text-center\" >
          <img  class='img-responsive' src= \"indexpic.jpg\"  style = \"width:100%; height: 300px;\" >
            <h3  class='carousel-caption'>Spaces in $current_city</h3 >
          </div>
       </a>  
      
      </div >
      </div>
      </div>
      
      <div  style='background-color: lightgray; width: 90%; height: 2px; margin: 20px auto;'></div>
      
      <div class='container' style='padding: 50px 50px;'>
       <div class='col-lg-12'>
        <p style='font-weight: 500; font-size: 23px;'>This Space Has $review_count Reviews:</p><br>
        ";
for ($y=1; $y<=$review_count; $y++){

    $review_row = mysqli_fetch_assoc($review_result);
    $review_name = $review_row['first_name'];
    $review_rate = $review_row['rating'];
    $review_content = $review_row['content'];

    echo"
        <div class=\"card-body\" style='padding: 20px 20px;'>
	        <div class=\"row\">
        	    <div class=\"col-md-2\">
        	        <img src=\"https://image.ibb.co/jw55Ex/def_face.jpg\" class=\"img img-rounded img-fluid\" style='height: 130px; width: 90%'/>
        	    </div>
        	    <div class=\"col-md-10\">
        	        <p>
        	            <a class=\"float-left\"><strong>$review_name</strong></a>
        	            ";

                        for($z=1; $z<=$review_rate; $z++)
                        echo"
        	            
        	            <span class=\"float-right\"><i class=\"text-warning fa fa-star\"></i></span>
        	            
        	            ";


                        echo"

        	       </p>
        	       <div class=\"clearfix\"></div>
        	        <p> $review_content <p>
        	            <a class=\"float-right btn btn-outline-primary ml-2\"> <i class=\"fa fa-reply\"></i> Reply</a>
        	            <a class=\"float-right btn text-white btn-danger\"> <i class=\"fa fa-heart\"></i> Like</a>
        	       </p>
        	    </div>
	        </div>
	        
	        
	        </div>
	        ";

}


echo"
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
