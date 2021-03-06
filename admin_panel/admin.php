<?php

require_once '../scripts/app_config.php';
require_once '../scripts/database_connection.php';

// Change status
if (isset($_GET['reserv_id']) && isset($_GET['reserv_id'])) {
  $reservation_status = $_GET['status'];
  $reserv_id = $_GET['reserv_id'];
  
  if ($reservation_status == 1) { //confirm reservation
    $change_status_query = "UPDATE reservations SET status_id = '1' WHERE id = '{$reserv_id}'";
    $change_status_result = mysqli_query($con, $change_status_query);
	
    echo <<<EOD
	<script>
	  alert('Your changes has been saved successfully!');
	</script>
EOD;
	
  } else if ($reservation_status == 2) {  //cancel reservation
    $change_status_query = "UPDATE reservations SET status_id = '2' WHERE id = '{$reserv_id}'";
    $change_status_result = mysqli_query($con, $change_status_query);
	
	echo <<<EOD
	<script>
	  alert('Your changes has been saved successfully!');
	</script>
EOD;
  
  } else if ($reservation_status == 3) {  //delete reservation
    $reserv_query = "SELECT * FROM reservations WHERE id='{$reserv_id}'";
	$reserv_result = mysqli_query($con, $reserv_query);
    $reserv_row = mysqli_fetch_array($reserv_result); //echo $reserv_row['customer_id'];
	
	$delete_query = "DELETE FROM customers WHERE id = '{$reserv_row['customer_id']}'";
	$delete_result = mysqli_query($con, $delete_query);
	
	$delete_query = "DELETE FROM reservations WHERE id = '{$reserv_id}'";
	$delete_result = mysqli_query($con, $delete_query);
	
echo <<<EOD
 	<script>
	  alert('Your changes has been saved successfully!');
	</script>
EOD;

  }
}

?>

<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap-3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../bootstrap-3.3.1/css/jquery.timepicker.css" />  <!--time_picker-->
	<link href="../bootstrap-3.3.1/css/jquery.validate.password.css" rel="stylesheet" type="text/css" />

    <!-- Custom styles for this template -->
    <link href="../bootstrap-3.3.1/css/custom-css.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css">

	  <!-- jtable.org CSS -->
	  <link href="../plugins/jtable/themes/metro/blue/jtable.css" rel="stylesheet" type="text/css" />
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	  <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>

    <div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index.php">Thessaloniki Car Rentals</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class=""><a href="../index.php">View Site</a></li>
			  <li class="active"><a href="admin.php">Dashboard</a></li>
			  <li class=""><a href="../newcar.php">New Car Type</a></li>
			  <li class=""><a href="../new_car_item.php">New Car Item</a></li>
			  <li class=""><a href="dashboard.php">New admin</a></li>
			  <li class=""><a href=""></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <?php
			    if (isset($_COOKIE['user_id'])) {
                  echo "<li><a href='../logout.php'>Log Out</a>";
                } else {
                  echo "<li class=''><a href='../log_in_form.php'>Log In</a></li>";
				  //echo "<li class=''><a href='../sign_up_form.php'>Sign up</a></li>";
                }
			  ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
	</div> <!-- /container -->	
	
 

   <div class="container"> 
	  <div class="jumbotron">
        <div class="row"> 
           <div class="table-responsive">
             <table class="table table-hover table-striped display" id="datatable">
			    <thead>
				<tr>
				  <th>ID</th>
				  <th>Date</th>
				  <th>Purchaser</th>
				  <th>Plate Number</th>
				  <th>Pickup</th>
				  <th>Drop Off</th>
				  <th>Days</th>
				  <th>Total</th>
				  <th>Status</th>
				  <th>Delete</th>
				</tr>
				</thead>
				<tbody>
				<?php
				//evresi kratisis
				 //$reserv_query = "SELECT * FROM reservations LIMIT 10,20";
				 $reserv_query = "SELECT * FROM reservations order by id desc LIMIT 20";
	             $reserv_result = mysqli_query($con, $reserv_query);
				 //echo $reserv_row['pickup_datetime'];
				 
				 while($reserv_row = mysqli_fetch_array($reserv_result)) {
				 
				 //evresi customer
				 $custom_query = "SELECT * FROM customers WHERE id='{$reserv_row['customer_id']}'";
				 $custom_result = mysqli_query($con, $custom_query);
				 $custom_row = mysqli_fetch_array($custom_result);
				 //echo $custom_row['name'];
				 
				 //evresi oximatos
				 $item_query = "SELECT * FROM car_items WHERE id='{$reserv_row['car_id']}'";
				 $item_result = mysqli_query($con, $item_query);
				 $item_row = mysqli_fetch_array($item_result);
				 //echo $item_row['plate_number'];
				 
				 
				 //sinolikes meres kratisis
				 //$pickup_date = date('d-m-Y h:m:s', strtotime($reserv_row['pickup_datetime']));
				 //$dropoff_date = date('d-m-Y h:m:s', strtotime($reserv_row['dropoff_datetime']));
				 $diff = abs(strtotime($reserv_row['dropoff_datetime']) - strtotime($reserv_row['pickup_datetime']));
				 $years = floor($diff / (365*60*60*24));
				 $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				 $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				 $total_days = $years + $months + $days;
				 
				 // Status twn enoikiasewn (confirmed - standby - cancelled)
				 $status = $reserv_row['status_id'];
				
				 if ($status == 0) {
				   $color = 'red';
				   $status_txt = 'Standby';
				 } else if ($status == 1) {
				   $color = 'green';
				   $status_txt = 'Confirmed';
				 } else if ($status == 2) {
				   $color = 'red';
				   $status_txt = 'Canceled';
				 }
				 
				 
				 
				 
				 echo <<<EOD
			     <tr>
				  <td>{$reserv_row['id']}</td>
				  <td><a href="../rental_details.php?reserv_id={$reserv_row['id']}&total_days={$total_days}&status={$status}">{$reserv_row['reserv_date']}</a></td>
				  <td>Name:{$custom_row['name']} Last Name:{$custom_row['lastname']} email:{$custom_row['email']}</td>
				  <td>{$item_row['plate_number']}</td>
				  <td>{$reserv_row['pickup_datetime']}</td>
				  <td>{$reserv_row['dropoff_datetime']}</td>
				  <td>{$total_days}</td>
				  <td>{$reserv_row['amount']}</td>
				  <td style="color:{$color};">{$status_txt}</td>
				  <td style="text-align:center;"><img src="../images/other/delete.png" width="15" /></a></td>
				 </tr>
EOD;
				}
				?>
			   </tbody>
			 </table>
		  </div>
        </div>



	<div id="carLocationsTableContainer" style="width: 600px;"></div>

		<script type="text/javascript">

		$(document).ready(function () {

			//Prepare jTable
			$('#carLocationsTableContainer').jtable({
				title: 'Car Locations',
				actions: {
					listAction: 'carTypesActions.php?action=list',
					createAction: 'carTypesActions.php?action=create',
					updateAction: 'carTypesActions.php?action=update',
					deleteAction: 'carTypesActions.php?action=delete'
				},
				fields: {
					id: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					name: {
						title: 'Location Name',
						width: '30%'
					},
					description: {
						title: 'Description',
						width: '30%',
						defaultValue: null // defaultvalue: null simainei oti to pedio den einai ypoxrewtiko
					},
					lat: {
						title: 'Latitude',
						width: '20%',
						defaultValue: 0

					},
					long: {
						title: 'Longitude',
						width: '20%',
						defaultValue: 0
					}
				}
			});

			//Load person list from server
			$('#carLocationsTableContainer').jtable('load');

		});

	</script>
	  </div> <!-- / jumbotron-->
	</div> <!-- /container -->

	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<link href="http://code.jquery.com/ui/1.11.2/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
	
   
	<script src="../bootstrap-3.3.1/js/jquery.validate.min.js"></script>
    <script src="../bootstrap-3.3.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="../bootstrap-3.3.1/js/jquery.timepicker.js"></script>  <!--time_picker-->
    <script src="../bootstrap-3.3.1/js/modernizr.custom.52675.js"></script>

	<!-- jtable.org jquery plugin -->
	<script src="../plugins/jtable/jquery.jtable.min.js" type="text/javascript"></script>

	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.js"></script>
	<script>
	$(document).ready( function () {
    $('#datatable').DataTable();
	  
    });
    </script>
  </body>
</html>
