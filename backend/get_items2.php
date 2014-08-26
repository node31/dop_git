<?php
//This php script is responsible for
/*
1.Get the string of items which the user has eaten along with the servings in comma and dot separated format
2.Access these food items from the database and there nutritional values.
3.Multipy the nutritional value with the servings taken and then store it in the table of the corresponding user of the corresponding date.
4.Also you have to store the string which was given to you in the corresponding column of breakfast,lunch and dinner
*/
require_once('connect.php');

@session_start();
function get_items(){
  /**********************************FEW DECLARATIONS WHICH ARE USED THROUGHOUT*************************************************/
  $redirect_page_unsuccessful='items.html';
  $redirect_page_successful='meals.html';
  $login_page = 'login_page.php';

  /*********************************************************************************************************************************/

  /**************CHECK ID THE USER IS LOGGED IN AND WHETHER SOME STRING HAS BEEN SENT HERE***************************************/

  if(!isset($_SESSION['username'])){
    header('Location:'.$redirect_page_unsuccessful);
    //die('Error 115:The user is not logged in as there is no session which is currently in status');
  }

  if(count($_POST)<=0){
    header('Location:'.$redirect_page_unsuccessful.'?sita=1');
    //die('Error 114:The post script does not contain anything.Return Status =1');
  }

  /*********************************************************************************************************************************/

  /***************** CONNECTING TO BOTH DATABASES. THE DOP DATABASE AND THE ALL_USER DATABASE**************************************/

  $db_name='dop';
  $dop_link = connect($db_name);

  $user_database_name = 'all_users';
  $user_link = connect($user_database_name);

  $table_name = $_SESSION['username'];  //THE TABLE NAME HAS BEEN DYNAMICALLY GENERATED
  echo '<br><br> The user who is currently logged in is:'.$table_name;
  /*********************************************************************************************************************************/

  /*************KNOWING THE FOOD_CODE_STRING AND THE NUMBER OF SERVINGS OF EACH FOOD**********************************************/
  $eaten_food=$_POST['eaten_food']; //This is the dot and the comma separted string of food items which were consumed by the person
  $food_time=$_POST['food_time'];  //This represents what time (i.e. breakfast,lunch or dinner) food it is

  $eaten_food_array = explode(',',$eaten_food);
  $count=count($eaten_food_array);

  $carbo = 0.0;
  $protein = 0.0;
  $fat = 0.0;
  $calories = 0.0;
  /*********************************************************************************************************************************/


  /**************LOOP RESPONSIBLE FOR FETCHING THE NUTRIENT CONTENT OF EACH FOOD ITEM,MULTIPLYING IT WITH NUMBER OF SERVINGS************/
  /******************AND STORING THEM************************************************************************************************/
  for($i=0;$i<$count;$i=$i+1){
    $separate = explode('.',$eaten_food_array[$i]);

    $serving = $separate[0];
    $food_code = $separate[1];

    echo '<br><br> The user has taken '.$serving.' of the food with food code as '.$food_code.'<br><br>';

    $get_food_details = "SELECT * FROM `food_items` WHERE `s_no`='$food_code'";
    echo '<br><br> QUERY:'.$get_food_details;

    if($get_food_details_run = mysqli_query($dop_link,$get_food_details)){

      $result_row = mysqli_fetch_row($get_food_details_run);

      $carbo = $carbo + ($result_row[2] * $serving);
      $protein = $protein + ($result_row[3] * $serving);
      $fat = $fat + ($result_row[4] * $serving);
      $calories = $calories + ($result_row[5] * $serving);

      echo "<br><br>Carbohydrates:$carbo,Proteins:$protein,Fat:$fat,Calories:$calories<br><br>";

    }else{
      die('Error 116:The Food Item Query query was not able to run successfully'.mysqli_error($dop_link));
    }
  }

  /*********************************************************************************************************************************/

  /************************************
                                       NOW CARBO,PROTEIN,FAT AND CALORIES CONTAIN THE
                                       VALUES MULTIPLIED BY THE RESPECTIVE SERVINGS
                                                                                   **************************************************/

  /*********************************************************************************************************************************/


  /**********************SELCECTING THE VALUE OF NUTRIENTS ALREADY PRESENT IN THE TABLE**********************************************/
  $current_date = date("Y-m-d");
  echo '<br><br> CURRENT DATE IS : '.$current_date;

  $old_carbo = 0.0;$old_protein=0.0;$old_fat=0.0;$old_calories=0.0;

  $old_data_query = "SELECT `carbo`,`protein`,`fat`,`calories` FROM `$table_name` WHERE `date`='$current_date'";
  echo '<br><br>OLD DATA QUERY :'.$old_data_query;

  /*********************************************************************************************************************************/

  /***************************STORING THE OLD DATA*********************************************************************************/
  if($old_data_query_run = mysqli_query($user_link,$old_data_query)){

    $old_data_result_row = mysqli_fetch_row($old_data_query_run);
    $old_carbo = $old_data_result_row[0];
    $old_protein = $old_data_result_row[1];
    $old_fat = $old_data_result_row[2];
    $old_calories = $old_data_result_row[3];

    echo "<br><br>OLD Carbohydrates:$old_carbo,OLD Proteins:$old_protein,OLD Fat:$old_fat,OLD Calories:$old_calories<br><br>";

  }else{
    die('Error 117: The Old data query was not able to run successfully'.mysqli_error($user_link));
  }
  /*********************************************************************************************************************************/

  $bld_column_name=null;         //Breakfast,Lunch or Dinner Column Name

  if($food_time==1){
    $bld_column_name='breakfast';
  }else if($food_time==2){
    $bld_column_name='lunch';
  }else{
    $bld_column_name='dinner';
  }

  echo '<br><br> THE BLD COLUMN NAME IS :'.$bld_column_name;

  /*****************CALCULATING THE NEW DATA************************************************************************************/
  $new_carbo = $carbo + $old_carbo;
  $new_protein = $protein + $old_protein;
  $new_fat = $fat + $old_fat;
  $new_calories = $calories + $old_calories;
  /*********************************************************************************************************************************/

  /****************** INSERTING THE NEW DATA IN THE DATABASE**********************************************************************/

  $new_data_insert_query = "INSERT INTO `$table_name` ($bld_column_name,carbo,protein,fat,calories) VALUES ('$eaten_food','$new_carbo','$new_protein','$new_fat','$new_calories')";

  $new_data_update_query = "UPDATE `$table_name` SET
                                   $bld_column_name = '$eaten_food',
                                   carbo = '$new_carbo',
                                   protein = '$new_protein',
                                   fat = '$new_fat',
                                   calories = '$new_calories'
                                   WHERE `date`='$current_date'";



  echo '<br><br> NEW DATA UPDATE QUERY:'.$new_data_update_query;

  if($new_data_update_query_run = mysqli_query($user_link,$new_data_update_query)){
    header('Location:'.$redirect_page_successful);
	echo '<br><br>NEW DATA UPDATE QUERY SUCCESSFUL';
  }else{
    echo '<br><br>NEW DATA UPDATE QUERY UNSUCCESSFUL.'.mysqli_error($user_link);
  }
  /*********************************************************************************************************************************/
}

get_items();
?>