<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/1/18
 * Time: 8:37 PM
 */
session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";

if (isset($_SESSION['first_name'])) {
    $ti = $_SESSION['first_name'];
    $email = $_SESSION['email'];
} else {
    // not logged in
    $ti = "User has logged out";
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
    <title>$ti 's Page </title>
</head>
<body>
<div class=\"topnav\">
    <a class=\"normal\" href=\"index.php\">Databus.com</a>
    <a class=\"right\" href='logout.php'>Logout </a>
    <a class=\"right\" style=\"background-color: #2196F3; color: white;\" href=\"me.php\">$ti</a>
    <form action = 'search.php' method = 'post' >
    <input type = \"search\" name = 'search' placeholder = \"Search space now...\" >
    </form >
</div >


</body>
</html>";
$connect = mysqli_connect($server, $username, $password, $database);
if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}
$sql_get_username_password = "SELECT vendor_id, first_name, last_name, email, phone_number, company, balance FROM VENDOR WHERE email = '$email';";

$user_name_result = mysqli_query($connect, $sql_get_username_password);
$row = mysqli_fetch_assoc($user_name_result);
if ($row["company"] == ""){
    $sql_get_username_password = "SELECT regular_id, first_name, last_name, email, phone_number, points, Regular_addr_id FROM Regular WHERE email = '$email';";
    $user_name_result = mysqli_query($connect, $sql_get_username_password);
    $row = mysqli_fetch_assoc($user_name_result);
    $last_name = $row["last_name"];
    $phone = $row["phone_number"];
    $id_reg = $row["regular_id"];
    $points = $row["points"];
    $reg_add = $row['Regular_addr_id'];

    $get_trasaction_sql = "SELECT * FROM transaction WHERE Regular_id = '$id_reg'";
    $order_result = mysqli_query($connect, $get_trasaction_sql);
    $order_num = mysqli_num_rows($order_result);

    function transform_string($str) {
        $year = substr($str,0, -6);
        $month = substr($str,5, -3);
        $day = substr($str, 8);
        $result = $month . "/" .$day. "/" . $year;
        return $result;
    }

    function compare_date($tran_date, $today_date){
        $tran_year = substr($tran_date,6);
        $tran_month = substr($tran_date,0, -8);
        $tran_day = substr($tran_date, 3,-5);
        $today_year = substr($today_date,6);
        $today_month = substr($today_date,0, -8);
        $today_day = substr($today_date, 3,-5);
        if (((int)$tran_year <= (int)$today_year) && ((int)$tran_month <= (int)$today_month) && ((int)$tran_day <= (int)$today_day)){
            return 1;
        }else{
            return 0;
        }
    }
    $tran_arr = array();
    $one_tran = array();

    if(!$order_result){
        echo mysqli_error($connect);
    }else{
        if (mysqli_num_rows($order_result) == 0){
            $tran_arr = array();
        }else{
            while($row = mysqli_fetch_assoc($order_result)){
                $one_tran = array();
                //echo "id ". $row["Space_id"] . "  start: " .$row["start_date"]. "  end: " . $row["end_date"] . "  price: " . $row["price"] . "<br><br>";
                array_push($one_tran, $row["Trans_id"]);
                array_push($one_tran, $row["Space_id"]);
                array_push($one_tran, $row["start_date"]);
                array_push($one_tran, $row["end_date"]);
                array_push($one_tran, $row["price"]);
                array_push($tran_arr, $one_tran);
            }
        }
    }

//get already reviewd space id
    $alr_rev_space_id = array();

    $get_already_rev_sql = "SELECT review.Trans_id FROM review, transaction WHERE review.Trans_id = transaction.Trans_id AND review.Regular_id = '$id_reg';";
    $rev_result = mysqli_query($connect, $get_already_rev_sql);
    if(!$rev_result){
        echo mysqli_error($connect);
    }else{
        if (mysqli_num_rows($rev_result) == 0){
            $alr_rev_space_id= array();

        }else{
            while($row = mysqli_fetch_assoc($rev_result)){
                array_push($alr_rev_space_id, $row["Trans_id"]);
            }
        }
    }



    $today = date("m/d/Y");
//echo "today's date: " . $today . "<br> <br>";
    $final_result = array();
    $review_index = array();
    $count = 0;
    if (empty($tran_arr)){
        $final_result = array();
    }else{
        foreach ($tran_arr as $value){
            //echo "value 2: " .transform_string($value[2]). "<br><br>";
            if (compare_date(transform_string($value[3]),$today) == 1){
                array_push($final_result, $value);
                array_push($review_index, $count);

            }
            $count = $count + 1;
        }
    }

    $count2 = 0;

    echo"
        <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 50px 50px\">
             <a class=\"list-group-item active\">This is $ti's Regular Membership</a>
             <a class=\"list-group-item\">Regular ID: 00000$id_reg</a>
             <a class=\"list-group-item\">Full name: $ti   $last_name</a>
             <a class=\"list-group-item\">Email address: $email</a>
             <a class=\"list-group-item\">Phone Number: $phone </a>
             <a class=\"list-group-item\">Your Points: $points </a>
        </div>
</div>
 
    ";

    if (empty($tran_arr)){
        echo"
        <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 50px 50px\">
             <a class=\"list-group-item active\" style='background-color: hotpink; border-color: hotpink'>Your Orders</a>
             <a class=\"list-group-item\">There is no order currently</a>
             
        </div>
</div>
 
    ";
    }
    else{

        echo "
        <div class=\"container\">
          <div class=\"list-group\" style=\"padding: 30px 50px\">
          <a class=\"list-group-item active\" style='background-color: #2ba0c6; border-color: #2ba0c6'> Your Order </a>
        
        ";

        foreach($tran_arr as $a){
            //echo "<html> <header> $a[0] $a[1] $a[2] $a[3] </header> </html>";
            if ((in_array($count2,$review_index)) && (!in_array($a[0], $alr_rev_space_id))){
                echo "
          <div >
          <a class=\"list-group-item\">Order# $count2 Trans ID: 00000$a[0] Space ID: 00000$a[1] Start: $a[2] End: $a[3] Total Price: $$a[4]  <button
           onclick='showrev()' class=\"btn btn-info\" role='button'> Add review </button> </a>
          

          <div>
          <form action=\"review_page.php?space_id=$a[1]&trans_id=$a[0]\" method=\"post\">
            <label>Review</label> <input type= \"text\" name=\"review\" style='width: 500px;'>
            <label> Ratting: </label>
            <select name=\"ratting\">
              <optgroup label = \"Ratting\">
              <option value= \"0\" > 0 </option>
              <option value= \"1\"> 1 </option>
              <option value= \"2\"> 2 </option>
              <option value= \"3\">3</option>
              <option value= \"4\">4</option>
              <option value= \"5\">5</option>
              </optgroup>
            </select>
            
            <input type=\"submit\">
          </form>
            </div>
          <script>
            function showrev() {
              var x = document.getElementById($count2);
              if (x.style.display === \"none\") {
                x.style.display = \"block\";
              } else {
                x.style.display = \"none\";
              }
            }
          </script>
  ";
            }elseif((in_array($count2,$review_index)) && (in_array($a[0], $alr_rev_space_id))){
                echo "
        
        <a href='spacepage.php?$a[1]' class=\"list-group-item\">
        Order# $count2 Trans ID: 00000$a[0] Space ID: 00000$a[1] Start: $a[2] End: $a[3] Total Price: $$a[4] 
        <button class=\"btn btn-normal\" role='button'> Already Reviewed </button></a>
        
  ";
            }else{
                echo "
        
        <a href='spacepage.php?$a[1]' class=\"list-group-item\">
        Order# $count2 Trans ID: 00000$a[0] Space ID: 00000$a[1] Start: $a[2] End: $a[3] Total Price: $$a[4] 
        <button class=\"btn btn-danger\" role='button'> Review After Finish </button></a>
        
";
            }
            $count2 = $count2 + 1;
        }

    }


    echo"
 </div>
 </div>

          </div>
          </div>
          
        <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 50px 50px\">
             <a class=\"list-group-item active\" style='background-color: #1ec64c; border-color: #1ec64c'>Your Address Info</a>
             
            ";


    if ($reg_add == 0){
        echo"<a class=\"list-group-item\">You did not set up your address</a> ";
        echo "<a onclick='showAddr()' class=\"btn btn-info\" role=\"button\">Add one address</a> 
        
        <div style='display: none' id='addr'>
        
        <form action=\"add_addr.php\" method=\"post\" style=\" width: 49%;height: 500px; float: left;\">

    <br>Street: 
    <input type=\"text\" name=\"street\" placeholder=\"Oakwood Ave\">
    
    <br>Apt: 
    <input type=\"text\" name=\"apt\" placeholder=\"202\">
    
    <br>Zipcode: 
    <input type=\"number\" name=\"zipcode\" placeholder=\"16803\">
    
    <br>State:
    
    <input type=\"text\" name=\"state\" placeholder=\"PA\">
    <br>
    <input type=\"submit\" class=\"btn btn-warning\" value=\"Add to my Address\">

</form>

</div>
        
        <script>
function showAddr() {
    var x = document.getElementById(\"addr\");
    if (x.style.display === \"none\") {
        x.style.display = \"block\";
    } else {
        x.style.display = \"none\";
    }
    
} 
</script>
";

    } else {
        $addr_id = $row["Regular_addr_id"];
        $addr = "SELECT street, apt, zip_code, state FROM user_addr WHERE user_addr_id = '$reg_add';";

        $addr_result = mysqli_query($connect, $addr);

        $row_addr = mysqli_fetch_assoc($addr_result);
        $street = $row_addr["street"];
        $apt = $row_addr["apt"];
        $zipcode = $row_addr["zip_code"];
        $state = $row_addr["state"];

        echo"<a class=\"list-group-item\">Street: $street</a>";
        echo"<a class=\"list-group-item\">Apt: $apt</a>";
        echo"<a class=\"list-group-item\">Zipcode: $zipcode</a>";
        echo"<a class=\"list-group-item\">State: $state</a>";
        echo"<form action=\"del_addr.php\" method=\"post\" style=\" width: 49%;height: 200px; float: left;\">";
        echo "<input type=\"submit\" class=\"btn btn-primary\" value=\"Delete my Address\">";
        echo"</form>";
    }
    echo "
        </div>
</div>
 
    ";

}
else{

    $last_name = $row["last_name"];
    $phone = $row["phone_number"];
    $company = $row["company"];
    $id_ven = $row["vendor_id"];
    $balance = $row["balance"];
    echo"
        <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 30px 50px\">
             <a class=\"list-group-item active\" style='background-color: #e96023; border-color: #e96203'>This is $ti's Vendor Membership</a>
             <a class=\"list-group-item\">Vendor ID: 00000$id_ven</a>
             <a class=\"list-group-item\">Full name: $ti   $last_name</a>
             <a class=\"list-group-item\">Email address: $email</a>
             <a class=\"list-group-item\">Phone Number: $phone </a>
             <a class=\"list-group-item\">Your Vendor Company: $company </a>
             <a class=\"list-group-item\">Balance: $$balance </a>
        </div>
</div>
 
    ";

    echo"
        <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 30px 50px\">
             <a class=\"list-group-item active\" style='background-color: #1ec64c; border-color: #1ec64c'>Your Transaction</a>
             <a class=\"list-group-item\">You currently do not have setup payment</a>
            
        </div>
</div>
 
    ";

    $space = "SELECT * FROM space WHERE vendor_id = '$id_ven'";

    $space_result = mysqli_query($connect, $space);

    $count = mysqli_num_rows($space_result);

    if ($count == 0) {

        echo "
        <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 30px 50px\">
             <a class=\"list-group-item active\" style='background-color: #2ba0c6; border-color: #2ba0c6'>Your Space</a>
             <a class=\"list-group-item\">You currently do not have a space listing</a>
            
        
        <a onclick='showAddr()' class=\"btn btn-info\" role=\"button\">Add One Space</a> 
        
        <div style='display: none' id='addr'>
        
    
    <form action=\"add_space.php\" method=\"post\" style=\" width: 49%;height: 400px; float: left;\">
  Space Name: <input type= \"text\" name=\"space_name\"><br>
<!-- Space Name -->

  Street Address:
  <input type= \"text\" name=\"street\"><br>
<!-- Street Address -->

  Room Number:
  <input type= \"number\" name=\"apt\"><br>
<!-- Apartment Number: -->
   City:
  <input type= \"text\" name=\"city\"><br>
<!-- State -->
  State:
  <input type= \"text\" name=\"state\"><br>
<!-- State -->

  Zip Code:
  <input type= \"number\" name=\"zipcode\" pattern \"[0-9]{5}\"><br>
<!-- Zip Code -->

  Availability <br>
  From:
  <input type= \"date\" name=\"from\"><br>
  Till:
  <input type= \"date\" name=\"till\"><br>
<!-- Availability -->

 This is a 
 <select type='number' name='categories'>
 <option value='0'>Hotel</option>
 <option value='1'>Business Room</option>
 <option value='2'>University</option>
 <option value='3'>Private Property</option>
 </select>
  Property
 <br>
 Accomodation Capacity
<input type=\"number\" name= \"size\">
<!-- Categories -->
</select>
    <br>
  Price Per DAY:
  <input type= \"number\" name=\"price\"><br>
<!-- Price -->


  Small Description:
  <input type= \"text\" name=\"description\"><br>

  Amenities/Feature:
  <input type= \"text\" name=\"amenities\"><br>
  
  Cover Photo(web address only):
  <input type='text' name='img'><br>
  
<!-- Amenities -->

<!--
  Pictures
  <input type=\"file\" name=\"pic\" accept=\"image/*\"> <br>
  <br>
 Images -->
  <input type=\"submit\" class=\"btn btn-warning\" value=\"Add this space\">

</form>
</div>

</div>
        
        <script>
function showAddr() {
    var x = document.getElementById(\"addr\");
    if (x.style.display === \"none\") {
        x.style.display = \"block\";
    } else {
        x.style.display = \"none\";
    }
} 
</script>
        
</div>
 
    ";
    }
    else{

        for ($x = 1; $x <= $count; $x++) {
            $ven_row = mysqli_fetch_assoc($space_result);
            $space_i = $ven_row['Space_id'];
            $sname = $ven_row['space_name'];
            $start_time = $ven_row['resv_start_date'];
            $end_time = $ven_row['resv_end_date'];

            $s_addr_sql = "SELECT * FROM space_addr WHERE space_addr_id = '$space_i';";
            $s_addr_result = mysqli_query($connect, $s_addr_sql);
            $s_addr_row = mysqli_fetch_assoc($s_addr_result);
            $s_street = $s_addr_row['street'];
            $s_room = $s_addr_row['room'];
            $s_state = $s_addr_row['state'];
            $s_zipcode = $s_addr_row['zip_code'];
            $s_company = $s_addr_row['company'];
            $s_city = $s_addr_row['city'];


            if ($s_company == ""){
                echo "
            <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 30px 50px\">
             <a class=\"list-group-item active\" style='background-color: #2ba0c6; border-color: #2ba0c6'>Your Space $x out of $count </a>
             
             <a  class=\"list-group-item\">Space ID: 00000$space_i </a> 
             <a class=\"list-group-item\">Space name: $sname  <button onclick=\"location.href = 'spacepage.php?$space_i'\" class= \" btn btn-danger\" role='button' style='margin-left: 20px;'>Pending</button></a>
            <a class=\"list-group-item\">Renting Time: $start_time  To  $end_time </a>
            <a class=\"list-group-item\">Space Address: Room $s_room, $s_street, $s_city, $s_state, $s_zipcode </a>
             
             ";
            } else {
                echo "
            <div class=\"container\">
            <div class=\"list-group\" style=\"padding: 30px 50px\">
             <a class=\"list-group-item active\" style='background-color: #2ba0c6; border-color: #2ba0c6'>Your Space $x out of $count </a>
             
             <a class=\"list-group-item\">Space ID: 00000$space_i </a> 
             <a class=\"list-group-item\">Space name: $sname  <button onclick=\"location.href = 'spacepage.php?$space_i'\" class= \" btn btn-success\" role='button' style='margin-left: 20px;'>Verified by $s_company</button></a>
            <a class=\"list-group-item\">Renting Time: $start_time  To  $end_time </a>
            <a class=\"list-group-item\">Space Address: Room $s_room, $s_street, $s_city, $s_state, $s_zipcode </a>
             
             ";
            }

            if ($x == $count){
                echo "
        <a onclick='showAddr()' class=\"btn btn-info\" role=\"button\">Add another Space</a> 
        
        <div style='display: none' id='add'>
        
    
    <form action=\"add_space.php\" method=\"post\" style=\" width: 49%;height: 400px; float: left;\">
  Space Name: <input type= \"text\" name=\"space_name\"><br>
<!-- Space Name -->

  Street Address:
  <input type= \"text\" name=\"street\"><br>
<!-- Street Address -->

  Room Number:
  <input type= \"number\" name=\"apt\"><br>
<!-- Apartment Number: -->
  
  City:
  <input type= \"text\" name=\"city\"><br>
<!-- Apartment Number: -->
  
  State:
  <input type= \"text\" name=\"state\"><br>
<!-- State -->

  Zip Code:
  <input type= \"number\" name=\"zipcode\" pattern \"[0-9]{5}\"><br>
<!-- Zip Code -->

  Availability <br>
  From:
  <input type= \"date\" name=\"from\"><br>
  Till:
  <input type= \"date\" name=\"till\"><br>
<!-- Availability -->

 This is a 
 <select type='number' name='categories'>
 <option value='0'>Hotel</option>
 <option value='1'>Business Room</option>
 <option value='2'>University</option>
 <option value='3'>Private Property</option>
 </select>
  Property
 <br>
 Accomodation Capacity
<input type=\"number\" name= \"size\">
<!-- Categories -->
</select>
    <br>
  Price Per DAY:
  <input type= \"number\" name=\"price\"><br>
<!-- Price -->


  Small Description:
  <input type= \"text\" name=\"description\"><br>

  Amenities/Feature
  <input type= \"text\" name=\"amenities\"><br>
<!-- Amenities -->
Cover Photo(web address only):
  <input type='text' name='img'><br>
<!--
  Pictures
  <input type=\"file\" name=\"pic\" accept=\"image/*\"> <br>
  <br>
 Images -->
  <input type=\"submit\" class=\"btn btn-warning\" value=\"Add this space\">

</form>
</div>

</div>
          <script>
function showAddr() {
    var x = document.getElementById(\"add\");
    if (x.style.display === \"none\") {
        x.style.display = \"block\";
    } else {
        x.style.display = \"none\";
    }
    
} 
</script>  
            
            
            
            ";

            }

            else {

                echo "</div>

                            </div>
                            ";
            }

        }

    }


}

echo"
</div>
</div>
<link href=\"//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css\" rel=\"stylesheet\">

<footer class=\"footer text-center\" style='margin-top: 20px;padding-top: 5rem;
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


