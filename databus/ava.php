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

mysqli_close($connect);

?>

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <input type="text" name="daterange" style="width: 200px;" />

<script type="text/javascript">

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

</html>
