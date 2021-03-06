<?php

require_once '../scripts/app_config.php';
require_once '../scripts/database_connection.php';

$upload_dir = "../images/cars/";
$image_fieldname = "cimg";

// array me ta pithana lathi kata to anevasma tis picture
	$php_errors = array(1 => 'Maximum file size in php.ini exceeded',
					2 => 'Maximum file size in HTML form exceeded',
					3 => 'Only part of the file was uploaded',
					4 => 'No file was selected to upload.');

// pianoume tis metavlites apo ti forma
$car_name = $_REQUEST['cname'];				
$car_category = $_REQUEST['ccat'];
$car_description = $_REQUEST['cdescription'];
//$car_location = $_REQUEST['clocation'];
//$plate_number = $_REQUEST['plate'];
$car_price = $_REQUEST['cprice'];

$air_con = $_REQUEST['air_con'];	//epipleon haraktiristika autokinitou
$cccar = $_REQUEST['cccar'];
$airbags = $_REQUEST['airbags'];
$passengers = $_REQUEST['passengers'];
$doors = $_REQUEST['doors'];
$radio = $_REQUEST['radio'];

$active = 1;



// elegxos sfalmatwn gia photo
($_FILES[$image_fieldname]['error'] == 0)
		or handle_error("the server couldn't upload the image you selected.",
                  $php_errors[$_FILES[$image_fieldname]['error']]);

@is_uploaded_file($_FILES[$image_fieldname]['tmp_name'])
		or handle_error("you were trying to do something naughty. Shame on you!",
                        "Uploaded request: file named " .
                        "'{$_FILES[$image_fieldname]['tmp_name']}'");

@getimagesize($_FILES[$image_fieldname]['tmp_name'])
		or handle_error("you selected a file for your picture " .
						"that isn't an image.",
						"{$_FILES[$image_fieldname]['tmp_name']} " .
						"isn't a valid image file.");				  
				  
$now = time();
	while (file_exists($upload_filename = $upload_dir . $now .
                                     '-' .
                                     $_FILES[$image_fieldname]['name'])) {
		$now++;
}				  
				  
				  
@move_uploaded_file($_FILES[$image_fieldname]['tmp_name'], $upload_filename)
		or handle_error("we had a problem saving your image to " .
						"its permanent location.",
						"permissions or related error moving " .
						"file to {$upload_filename}");
				  

  
//upload car query
$insert_car_query = sprintf("INSERT INTO car_types " .
								"(name, car_Category_ID, " .
								"description, price, pic_path, active) " .
							"VALUES ('%s', '%d', '%s', '%d', '%s', '%d');",
							mysql_real_escape_string($car_name),
							mysql_real_escape_string($car_category),
							mysql_real_escape_string($car_description),
							mysql_real_escape_string($car_price),
							mysql_real_escape_string($upload_filename),
							mysql_real_escape_string($active));						

mysqli_query($con, $insert_car_query); 



//upload car characteristics query
$insert_car_characteristics = sprintf("INSERT INTO car_characteristics " .
								         "(air_con, " .
								         "cc, airbags, passengers, doors, radio) " .
							          "VALUES ('%d', '%d', '%d', '%d', '%d', '%d');",
							           mysql_real_escape_string($air_con),
							           mysql_real_escape_string($cccar),
							           mysql_real_escape_string($airbags),
							           mysql_real_escape_string($passengers),
							           mysql_real_escape_string($doors),
							           mysql_real_escape_string($radio));
							
mysqli_query($con, $insert_car_characteristics); 


$success_message = "{$car_name} added successfully!";

header("Location: cartypes.php?" .
         "success_message={$success_message}");
exit();

?>



