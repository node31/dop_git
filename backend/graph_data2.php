<?php
/*This script will be used when the user wants the graph data.
This script will be responsible for sending the optimum value of nutrients and the average value consumed by the user
THIS SCRIPT WILL GIVE THE USER THE OPTION OF SEEING
-->YESTERDAYS DATA ----------------1
-->LAST WEEKS DATA ----------------2
-->FORTHNIGHTLY DATA --------------3
-->MONTHLY DATA -------------------4
*/

require_once('connect.php');
@session_start();

function send_graph_data(){
  $redirect_page_successful='';
  $redirect_page_unsuccessful='';

  /*if(!isset($_SESSION['username'])){
    //header('Location:'.$redirect_page_unsuccessful.'?bharat=1');
    die('Error 122:The user is not logged in.Return Status = 1');
  }

  if(count($_POST)){
    //header('Location:'.$redirect_page_unsuccessful.'?bharat=2');
    die('Error 123:The post script is empty.Return Status = 2');
  }*/

  $db_name1 = 'dop';$db_name2='all_users';
  $dop_link = connect($db_name1);
  $user_link = connect($db_name2);

  //$table_name = $_SESSION['username'];
  $table_name = 'f2012077';

  //echo '<br><br>The user who is currently logged in is:'.$table_name;

  $days = 7;

  /*if($_POST['days']==2)$days = 7;
  else if($_POST['days']==3)$days=15;
  else if($_POST['days']==4)$days=30;
  else $days = 1;*/

  $carbo_average = 0.0; $protein_average = 0.0;  $fat_average = 0.0;  $calorie_average = 0.0;
  $carbo_optimum = 0.0; $protein_optimum = 0.0;  $fat_optimum = 0.0;  $calorie_optimum = 0.0;

  $current_day = date("d");
  //echo '<br><br>The day today is:'.$current_day;

  $final_array=array();  //This is the final array which has to be sent to the front end

  for($i=1,$j=$current_day-1;$i<=$days;$i++,$j--){

    if($j==0){
      $j=31;  //This is for looping around;
    }

    $get_data_query = "SELECT `carbo`,`protein`,`fat`,`calories` FROM `$table_name` WHERE `s_no`='$j'";
    //echo '<br><br>GET DATA QUERY:'.$get_data_query;

    if($get_data_query_run = mysqli_query($user_link,$get_data_query)){
            $result_row = mysqli_fetch_row($get_data_query_run);
            //echo '<br><br>RESULT ROW:';
            //print_r($result_row);

            $carbo_average = $carbo_average + $result_row[0];
            $protein_average = $protein_average + $result_row[1];
            $fat_average = $fat_average + $result_row[2];
            $calorie_average = $calorie_average + $result_row[3];
    }else{
      die('<br><br>Error 124:The Get Data Query Could Not Run Successfully'.mysqli_error($user_link));
    }
  }

  $carbo_average = $carbo_average/$days;     $protein_average = $protein_average/$days;
  $fat_average = $fat_average/$days;  $calorie_average = $calorie_average/$days;

  $get_optimum_query = "SELECT `optimum_value` FROM `optimum` WHERE 1";
  if($get_optimum_query_run = mysqli_query($dop_link,$get_optimum_query)){

    $result_row = mysqli_fetch_row($get_optimum_query_run);$carbo_optimum =$result_row[0];
    $result_row = mysqli_fetch_row($get_optimum_query_run);$protein_optimum =$result_row[0];
    $result_row = mysqli_fetch_row($get_optimum_query_run);$fat_optimum =$result_row[0];
    $result_row = mysqli_fetch_row($get_optimum_query_run);$calorie_optimum =$result_row[0];

    //echo "<br><br> Carbo-Optimum:$carbo_optimum,Protein-Optimum:$protein_optimum,Fat-Optimum:$fat_optimum,Calorie_Optimum:$calorie_optimum";


  }else{
    die('<br><br>Error 124:The Get Optimum Query Could Not Run Successfully'.mysqli_error($dop_link));
  }


    $dummy_array=array();

    $dummy_array["nutrient"]="carbo";      $dummy_array["optimum"]=10;          $dummy_array["user"]=$carbo_average;
    $final_array[0]=$dummy_array;
    $dummy_array["nutrient"]="protein";    $dummy_array["optimum"]=$protein_optimum;        $dummy_array["user"]=$protein_average;
    $final_array[1]=$dummy_array;
    $dummy_array["nutrient"]="fat";        $dummy_array["optimum"]=$fat_optimum;            $dummy_array["user"]=$fat_average;
    $final_array[2]=$dummy_array;
    $dummy_array["nutrient"]="calories";   $dummy_array["optimum"]=$calorie_optimum;        $dummy_array["user"]=$calorie_average;
    $final_array[3]=$dummy_array;

    $json_string = json_encode($final_array);

    //echo '<br><br>  FINAL STRING:<br><br>'.$json_string;
	echo $json_string;
}

send_graph_data();

?>