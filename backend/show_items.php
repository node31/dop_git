/*THIS SCRIPT WILL RETURN THE SERVINGS OF AND THE FOOD ITEMS WHICH HE/SHE HAS CONSUMED
THIS SCRIPT WILL GIVE THE USER THE OPTION OF SEEING
-->YESTERDAYS DATA ----------------1
-->LAST WEEKS DATA ----------------2
-->FORTHNIGHTLY DATA --------------3
-->MONTHLY DATA -------------------4
*/

<?php
require_once('connect.php');
@session_start();

function show_items(){

  $redirect_page_successful='';
  $redirect_page_unsuccessful='';

  /*if(!isset($_SESSION['username'])){
    header('Location:'.$redirect_page_unsuccessful.'?laxman=1');
    die('Error 118:The user is not logged in.Return Status = 1');
  }

  if(count($_POST)){
    header('Location:'.$redirect_page_unsuccessful.'?laxman=2');
    die('Error 119:The post script is empty.Return Status = 2');
  } */

  $db_name = 'dop';
  $dop_link = connect($db_name);

  $db_name2 = 'all_users';
  $user_link = connect($db_name2);

  //$table_name = $_SESSION['username'];
  $table_name = 'f2012077';             //This was used for testing purpose only

  echo '<br><br>The user who is currently logged in is:'.$table_name;

  $days = 7;

  /*if($_POST['days']==2)$days = 7;
  else if($_POST['days']==3)$days=15;
  else if($_POST['days']==4)$days=30;
  else $days = 1;*/

  $current_date = date("Y-m-d");
  echo '<br><br>The date today is:'.$current_date;

  $current_day = date("d");
  echo '<br><br>The day today is:'.$current_day;

  $final_array=array();  //This is the final array which has to be sent to the front end

  for($i=1,$j=$current_day-1;$i<=$days;$i++,$j--){

    $loop1_array = array();

    if($j==0){
      $j=31;  //This is for looping around;
    }

    $get_data_query = "SELECT * FROM `$table_name` WHERE `s_no`='$j'";
    echo '<br><br>GET DATA QUERY:'.$get_data_query;

    if($get_data_query_run = mysqli_query($user_link,$get_data_query)){
      $result_row = mysqli_fetch_row($get_data_query_run);
      echo '<br><br>RESULT ROW:';
      print_r($result_row);

      $loop1_array["sno"]=$i;
      $loop1_array["date"]=$result_row[1];

      $loop2_array = array();
      /************************************BREAKFAST**********************************************/
      $food_stringb = $result_row[2];
      $food_stringb_array = explode(',',$food_stringb);
      $countb = count($food_stringb_array);
      $loop2_array = get_food_item_with_servings($food_stringb_array,$countb,$loop2_array,$dop_link,0,1);

      /************************************LUNCH**********************************************/
      $food_stringl = $result_row[3];
      $food_stringl_array = explode(',',$food_stringl);
      $countl = count($food_stringl_array);
      $loop2_array = get_food_item_with_servings($food_stringl_array,$countl,$loop2_array,$dop_link,$countb,2);

      /************************************DINNER**********************************************/
      $food_stringd = $result_row[4];
      $food_stringd_array = explode(',',$food_stringd);
      $countd = count($food_stringd_array);
      $loop2_array = get_food_item_with_servings($food_stringd_array,$countd,$loop2_array,$dop_link,$countb+$countd,3);

      /***************************************************************************************************************************/

      $loop1_array["food_data"] = $loop2_array;

    }else{
      die('<br><br>Error 120:The Get Data Query Could Not Run Successfully'.mysqli_error($user_link));
    }

    $final_array[$i] = $loop1_array;
  }

  $json_string =  json_encode($final_array);
  echo '<br><br><br><br>FINAL STRING:<br><br>'.$json_string;
}

show_items();

function get_food_item_with_servings($food_stringb_array,$countb,$loop2_array,$dop_link,$offset,$time){

  for($k=0;$k<$countb;$k++){
        $dummy_array = array();

        $fs = explode('.',$food_stringb_array[$k]);
        $serving = $fs[0];
        $food_code = $fs[1];

        $get_food_name_query = "SELECT `food_item_name` FROM `food_items` WHERE `s_no`='$food_code'";
        echo '<br><br>GET FOOD NAME QUERY:'.$get_food_name_query;

        if($get_food_name_query_run = mysqli_query($dop_link,$get_food_name_query)){
          $food_name_row = mysqli_fetch_row($get_food_name_query_run);
          $dummy_array["name"]=$food_name_row[0];
        }else{
          die('<br><br>Error 121:The Get Food Name Query Could Not Run Successfully'.mysqli_error($dop_link));
        }

        $dummy_array["servings"]=$serving;
        $dummy_array["time"]=$time;

        $loop2_array[$k+$offset]=$dummy_array;

      }
      return $loop2_array;

}

?>