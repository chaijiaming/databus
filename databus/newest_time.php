<html>
<?php

session_start();

function transform_string($str) {
  $year = substr($str,0, -6);
  $month = substr($str,5, -3);
  $day = substr($str, 8);
  $result = $month . "/" .$day. "/" . $year;
    return $result;
}

$s_id = '';
if (isset($_SESSION['space_id'])) {
    $s_id = $_SESSION['space_id'];
} else {
    // Fallback behaviour goes here
    echo "space id is not set <br><br>";
    $s_id = 'space1';
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
if (!isset($_SESSION['email'])){
    $_SESSION['login_space_id'] = $s_id;
    header("refresh: 1;//localhost/databus/login.html");
}
else{

//sql statement
$get_term_sql = "SELECT start_date, end_date FROM Terms WHERE Space_id = '$s_id';";
$get_avl_sql = "SELECT resv_start_date, resv_end_date FROM Space WHERE Space_id = '$s_id';";

$dis_start =array();
$dis_end = array();

$term_result = mysqli_query($connect, $get_term_sql);
if(!$term_result){
  echo mysqli_error($connect);
}else{
  if (mysqli_num_rows($term_result) == 0){
      array_push($dis_start, "00/00/0000");
      array_push($dis_end, "00/00/0000");
  }else{
    while($row = mysqli_fetch_assoc($term_result)){
      //echo "start :" . $row["start_date"] . "  end :" . $row["end_date"] . "<br><br>";
      array_push($dis_start, transform_string($row["start_date"]));
      array_push($dis_end, transform_string($row["end_date"]));
    }
  }
}
//echo "start :" . $dis_start[0] . "  end :" . $dis_end[0] . "<br><br>";

$resv_result = mysqli_query($connect, $get_avl_sql);
if(!$term_result){
  echo mysqli_error($connect);
}else{
  $row = mysqli_fetch_assoc($resv_result);
  $resv_start = transform_string($row["resv_start_date"]);
  $resv_end = transform_string($row["resv_end_date"]);
  //echo "resv start: " . $resv_start . "end: ". $resv_end .'<br>';
}
$ti = $_SESSION['first_name'];

$sql_get_search_result = "SELECT * FROM space WHERE Space_id = '$s_id';";
$search_result = mysqli_query($connect, $sql_get_search_result);

$currentrow = mysqli_fetch_assoc($search_result);

$currentname = $currentrow['space_name'];
$space_addr_sql = "SELECT * FROM space_addr WHERE '$s_id' = space_addr_id;";
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

$sub_category = "SELECT size, Category_id FROM sub_category WHERE '$s_id' = Sub_category_id;";
$sub_result = mysqli_query($connect, $sub_category);
$sub_category_result = mysqli_fetch_assoc($sub_result);

$room_size = $sub_category_result['size'];
$category = $sub_category_result['Category_id'];

$category_sql = "SELECT category_name FROM category WHERE '$category' = Category_id;";
$category_result = mysqli_query($connect, $category_sql);
$category_row = mysqli_fetch_assoc($category_result);

$category_name = $category_row['category_name'];


$get_term_sql = "SELECT start_date, end_date FROM Terms WHERE Space_id = '$s_id';";
$term_result = mysqli_query($connect, $get_term_sql);

$ven_id = $currentrow['Vendor_id'];

$ven_sql = "SELECT first_name, last_name, email, company FROM vendor WHERE '$ven_id' = Vendor_id;";
$ven_result = mysqli_query($connect, $ven_sql);
$ven_row = mysqli_fetch_assoc($ven_result);

$first_name = $ven_row['first_name'];
$last_name = $ven_row['last_name'];
$email= $ven_row['email'];
$company_name = $ven_row['company'];

mysqli_close($connect);

?>
<!doctype html>
<html lang="en">
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="indexStyle.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Checkout</title>

    <div class="topnav">
        <a class="active" href="index.php">Databus.com</a>
        <a class="right" href='logout.php'>Logout </a>
        <a class="right" style="background-color: #2196F3; color: white;" href="me.php"><?php echo $ti ?></a>
        <form action='search.php' method='post'>
            <input type="search" name='search' placeholder="Search space now...">
        </form>
    </div>

    </div>


    <div class="jumbotron" style="margin-bottom: 0px;background: linear-gradient(141deg, #074e49 0%, #20696a 51%, #196683 75%); border: none;" >

    <div class="container text-center" style = " background: transparent" >
    <h1 style = "color: white;" > It's Almost Done, <?php echo $ti;?>!</h1 >

    </div>
    </div>
    <!-- Bootstrap core CSS -->

    <!-- Custom styles for this template -->
</head>

<body class="bg-light">

<div class="container">
    <div class="py-5 text-center">
        </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your Order Deatail</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Space Name</h6>
                        <small class="text-muted"><?php echo $currentname;?> at <?php echo $current_city;?></small>
                    </div>
                    <span class="text-muted">$<?php echo $price;?> Per Day</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Added Service</h6>
                        <small class="text-muted"><?php echo $currentser;?></small>
                    </div>
                    <span class="text-muted">$0</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Tax</h6>
                        <small class="text-muted">State Tax (6%)</small>
                    </div>
                    <span class="text-muted">$<?php echo $price*0.06;?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">NEW USER AUTO ADDED</h6>
                        <small>NEW5OFF</small>
                    </div>
                    <span class="text-success">-$5</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD) $</span>
                    <input id="price_id" type="text" style="border: none; font-size: 15px;" disabled/>

                </li>
            </ul>

            <form class="card p-2">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Any Promo code">

                </div>
            </form>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Select your reserve date:</h4>
            <form action="makeorder.php" class="needs-validation" novalidate>
                <input id="pass" type="hidden" name="price"/>
                <input type="text" name="daterange" style="width: 200px;" id="resv_time" onchange="change_price()"/>
                <h4>Billing Address Info: </h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" name="firstName" placeholder="<?php echo $ti;?>" value="<?php echo $ti;?>"
                               disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" name="lastName" placeholder="" value="" required>
                        <div class="invalid-feedback">
                            Please eneter for verification.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email <span class="text-muted">(Has to be your account)</span></label>
                    <input type="email" class="form-control" id="email" placeholder="<?php echo $_SESSION['email'];?>"
                           value="<?php echo $_SESSION['email'];?>" disabled>
                    <div class="invalid-feedback">
                        If you have any question, please contact us.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="1234 Main St" required>
                    <div class="invalid-feedback">
                        Please enter your shipping address.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address2">Address 2 <span class="text-muted"></span></label>
                    <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                </div>

                <div class="row">


                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" id="zip" placeholder="" required>
                        <div class="invalid-feedback">
                            Zip code required.
                        </div>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="same-address" required>
                    <label class="custom-control-label" for="same-address">I confirm the address above is correct.</label>
                </div>

                <hr class="mb-4">

                <h3 class="mb-3">Payment</h3>

                <div class="form-group" id="credit_cards">
                    <img src="visa.png" id="visa" style="width: 370px; height: 60px;">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cc-name">Name on card</label>
                        <input type="text" class="form-control" name="cc-name" placeholder="" required>
                        <small class="text-muted">Full name as displayed on card</small>
                        <div class="invalid-feedback">
                            Name on card is required
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cc-number">Credit card number</label>
                        <input type="text" class="form-control" name="cc-number" placeholder="" required>
                        <div class="invalid-feedback">
                            Credit card number is required
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="cc-expiration">Expiration</label>
                        <input type="text" class="form-control" name="cc-expiration" placeholder="" required>
                        <div class="invalid-feedback">
                            Expiration date required
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cc-expiration">CVV</label>
                        <input type="password" class="form-control" name="cc-cvv" placeholder="" required>
                        <div class="invalid-feedback">
                            Security code required
                        </div>
                    </div>
                </div>
                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Confirm Order</button>
            </form>
        </div>
    </div>


</div>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<footer class="footer text-center" style='padding-top: 5rem;
    padding-bottom: 5rem;
    background-color: #2c3e50;
    color: #fff;display: block;'>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-5 mb-lg-0">
                <h4 class="text-uppercase mb-4">Location</h4>
                <p class="lead mb-0">W204 Westgate Building
                    <br>State College, PA, 16803</p>
            </div>
            <div class="col-md-4 mb-5 mb-lg-0">
                <h4 class="text-uppercase mb-4">Around the Databus.com</h4>
                <ul class="list-inline mb-0" >
                    <li class="list-inline-item">
                        <a class="btn btn-outline-light btn-social text-center rounded-circle" href="#">
                            <i class="fa fa-fw fa-facebook" style='color: white;'></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-outline-light btn-social text-center rounded-circle" href="#">
                            <i class="fa fa-fw fa-google-plus"style='color: white;'></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-outline-light btn-social text-center rounded-circle" href="#">
                            <i class="fa fa-fw fa-twitter"style='color: white;'></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-outline-light btn-social text-center rounded-circle" href="#">
                            <i class="fa fa-fw fa-linkedin"style='color: white;'></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-outline-light btn-social text-center rounded-circle" href="#">
                            <i class="fa fa-fw fa-dribbble"style='color: white;'></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4 class="text-uppercase mb-4">About Databus.com</h4>
                <p class="lead mb-0">Databus.com is a student non-profit space finding Website. <br>
                    <a href="mailto:help@databus.com" style='color: white'>Contact Us</a>.</p>
            </div>
        </div>
    </div>
</footer>
<div class="copyright py-4 text-center text-white" style='background-color: black;'>
    <div class="container" style='padding: 30px; color: white;'>
        <small>Copyright © Databus.com 2018</small>
    </div>
</div>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';

        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');

            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
</body>
</html>

<script type="text/javascript">
var price = <?php echo $price; ?>;
function change_price() {
    var x = document.getElementById("resv_time").value;
    var st = x.substring(0,11);
    var end = x.substring(13,);
    var a = moment(st);
    var b = moment(end);
    document.getElementById("price_id").value = (b.diff(a, 'days') * price * 1.06 - 5).toFixed(2);
    document.getElementById("pass").value = (b.diff(a, 'days') * price * 1.06 - 5).toFixed(2);
}

var d_start = <?php echo json_encode($dis_start); ?>;
var d_end = <?php echo json_encode($dis_end); ?>;
var resv_start = "<?php echo "$resv_start"; ?>";
var resv_end = "<?php echo "$resv_end"; ?>";
var today = moment();

function include_day_one(start_day){
  var check = 0;
  var start = start_day.substring(3, 5);
  if (parseInt(start) == 1){
    check = 1;
  }else{
    check = 0;
  }
  return check;
}

$('input[name="daterange"]').daterangepicker({

    startDate: today,
    endDate: resv_end,
    minDate: moment(),
    maxDate: resv_end,

    isInvalidDate: function(date) {
      var disabled_start = d_start[0];
      var disabled_end = d_end[0];
      if (disabled_start == disabled_end && disabled_start == "00/00/000"){
          return;
      }
      var check_start = 0;
      var start_day = disabled_start.substring(3, 5);
      if (include_day_one(disabled_start) == 1){
        check_start = 1;
      }else{
        check_start = 0;
        disabled_start = disabled_start.replace(start_day, (parseInt(start_day) - 1).toString());
      }
      var end_day = disabled_end.substring(3, 5);
      disabled_end = disabled_end.replace(end_day, (parseInt(end_day) + 1).toString());
      var f_date = date && (date.isAfter(moment(disabled_start)) && date.isBefore(moment(disabled_end)));

      for (i = 1; i < d_start.length; i++) {
        start_day = d_start[i].substring(3, 5);
        end_day = d_end[i].substring(3, 5);
        disabled_start = d_start[i].replace(start_day, (parseInt(start_day) - 1).toString());
        disabled_end = d_end[i].replace(end_day, (parseInt(end_day) + 1).toString());
        f_date = f_date || (date.isAfter(moment(disabled_start)) && date.isBefore(moment(disabled_end)));
        //f_date = f_date || !(date.isAfter("05/02/2018") && date.isBefore("04/30/2018"));
      }
      //date.disable("05/01/2018");
      return f_date;
    }

})
</script>
<?php } ?>
</html>
