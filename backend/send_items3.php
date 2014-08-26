<?php
require_once('connect.php');


//echo date("Y-m-d");
function get_string($table_name){
	$dop_link = connect('dop');;
	$food_string = 'food_string';
	$current_date = date("Y-m-d");
	//echo '<br>'.$current_date.'<br>';

	$get_food_item_query = "SELECT * FROM `breakfast` WHERE `date` = '$current_date' ";
	//echo '<br>'.$get_food_item_query;
	if($get_food_item_query_run = mysqli_query($dop_link,$get_food_item_query)){
		$food_string_data = mysqli_fetch_row($get_food_item_query_run);
		//echo $food_string_data[2];

		$food_code_array = explode(',',$food_string_data[2]);

		//print_r($food_code_array);
		$count = count($food_code_array);
		//echo '<br>COUNT:'.$count;

		$food_array =  array();


		for($i=0;$i<$count;$i = $i + 1){
                  
                  $dummy_array = array();

                  $dummy = $food_code_array[$i];
                  $dummy_array["id"]=$dummy;

                  $get_food_item_name_query = "SELECT `food_item_name` FROM `food_items` WHERE `s_no` = '$dummy'";
                  //echo '<br> FOOD QUERY:'.$get_food_item_name_query;

                  if($get_food_item_name_query_run = mysqli_query($dop_link,$get_food_item_name_query)){
                    $data = mysqli_fetch_row($get_food_item_name_query_run);
                    //echo '<br> FOOD ITEM NAME:'.$data[0].'<br>';
                    $dummy_array["name"]=$data[0];
                    //$food_array[$dummy]=$data[0];
                    $food_array[$i] = $dummy_array;
                  }else{
                    //echo 'ERROR 106:Could Not Retrieve the food item.'.$mysqli_error($dop_link);
                  }
                }

                //echo'<br><br>';
                //print_r($food_array);
                $json_string = json_encode($food_array);
                //echo'<br><br>';
                echo $json_string;
				
				 if(isset($_GET['page']) && !empty($_GET['page'])){
				if($_GET['page']=='e'){
				////echo "tuliiiiiiii";
				}
					//echo $json_string;

				} 
	}
	else{
		die('Error 106: Could Not Run Food Get Query<br>'.mysqli_error($dop_link));
	}

}

get_string('breakfast');

?>