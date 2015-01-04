<?php
  
  require_once ('scripts/database_connection.php');
  require_once ('scripts/db_cars.php');
  require_once ('scripts/views.php');
  
  // Requires the <HEAD></HEAD> part of the page
  display_head("Thessaloniki Car Rentals");
  
  // Requires the navbar
  $tag = "home";
  display_navbar($tag);
  
  //ean mpei o xristis se afti ti selida xwris na exei epileksei amaksi 
  //anakatefthine ton stin kentriki
  if (!isset($_REQUEST['car_id']))
  {
    header("Location: index.php");
    exit();
  }
  
  
  //eksagoume ta xaraktiristika tou sygekrienou amaksiou
  $car_id = $_REQUEST['car_id'];

  $result = getCarByID($con, $car_id);

  $car = mysqli_fetch_array($result);
  
  $car_id		       = $car['id'];
  $car_name 	       = $car['name'];
  $car_category 	   = $car['car_category'];
  $car_description     = $car['description'];
  $car_location 	   = $car['car_location'];
  $car_price 		   = $car['price'];
  $car_pic_path        = $car['pic_path']; 
  
  //oi plirofories pou pira me cookies
  $dropoff_location = $_COOKIE['dropoff_location'];
  $pickup_date = $_COOKIE['pickup_date'];
  $dropoff_date = $_COOKIE['dropoff_date'];
  
  
  //car characteristics
  $char_result = getCarCharacteristics($con, $car_id);
  $char = mysqli_fetch_array($char_result);
		  
  $air_con 		= $char['air_con'];
  $cccar 		= $char['cc'];
  $airbags 		= $char['airbags'];
  $passengers 	= $char['passengers'];
  $doors 		= $char['doors'];
  $radio 		= $char['radio'];
  
  //oi epipleon ypiresies
  if(isset($_REQUEST['gps'])) {
    $gps_choice = $_REQUEST['gps'];
  } else {
    $gps_choice = 0;
  }
  
  if(isset($_REQUEST['baby_seat'])) {
    $baby_seat_choice = $_REQUEST['baby_seat'];
  } else {
    $baby_seat_choice = 0;
  }
  
  if(isset($_REQUEST['snow_chains'])) {
    $snow_chains_choice = $_REQUEST['snow_chains'];
  } else {
    $snow_chains_choice = 0;
  }
  
  if(isset($_REQUEST['car_roof'])) {
    $car_roof_choice = $_REQUEST['car_roof'];
  } else {
    $car_roof_choice = 0;
  }
  
 //Standard or Full Insurance
  if(isset($_REQUEST['priceid'])) {
    $fullOrStan = $_REQUEST['priceid'];
  } else {
    $fullOrStan = 0;
  }
  
  //posa pliromis
  $tax = 17.36;
  $total_cost = $fullOrStan + $tax;
  
  
  // difference in days from american format to european format
  function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
  {
    $d1 = explode("/", $date_1);
    $dTemp = explode(" ", $d1[2]);
	$d1[2] = $dTemp[0];
	$dt1 = $d1[1]."/".$d1[0]."/".$d1[2];
    $datetime1 = date_create($dt1);
    $d2 = explode("/", $date_2);
	$dTemp = explode(" ", $d2[2]);
	$d2[2] = $dTemp[0];
    $dt2 = $d2[1]."/".$d2[0]."/".$d2[2];
    $datetime2 = date_create($dt2);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat);  
    
  }
  
  $diff = dateDifference($pickup_date, $dropoff_date);

?>

    <!-- Main component for a primary marketing message or call to action -->
    <div class="container" style="margin-bottom:10px;"> 
	    
		
		<div class="contsite">
		  <p>Order Summary:</p>
		  <div class="vrcrentforlocs">
		    <p class="vrcrentalfor">
             <span class="vrcrentalforone"><?php echo $car_name; ?></span>
            </p>
		    <div class="vrcrentalfortwo">
			  <p>
                 <span class="vrcrentalfordate">From <span style="color:green;"><?php echo $pickup_date; ?> 00:00</span></span>
			  </p>
			  <p>
                 <span class="vrcrentalfordate">To <span style="color:green;"><?php echo $dropoff_date; ?> 00:00</span></span>
			  </p>
			</div>
			<div class="vrclocsboxsum">
			  <p class="vrcpickuploc">
                Pickup Location:
                <span class="vrcpickuplocname"><?php echo $car_location; ?></span>
             </p>
			 <p class="vrcdropoffloc">
               Drop Off Location:
               <span class="vrcdropofflocname"><?php echo $dropoff_location; ?></span>
             </p>
			</div>
		  </div>
		  <table class="vrctableorder">
		    <tbody>
			  <tr class="vrctableorderfrow">
				<td> </td>
				<td align="center">Days</td>
				<td align="center">Net Price</td>
				<td align="center">Tax</td>
				<td align="center">Total Price</td>
			  </tr>
			  <tr>
			    <td align="left">
			      <?php echo $car_name; ?>
                  <br>
				  <?php 
				    if($car_price < $fullOrStan){
					  echo <<<EOD
					    Full Insurance
                        <br>
                        Km inlcuded: Unlimited
EOD;
					} else {
					  echo <<<EOD
					    Standard Insurance
                        <br>
                        Km inlcuded: 200
EOD;
					}
				  ?>
                </td>
				<td align="center"><?php echo $diff; ?></td>
				<td align="center">
					<span class="vrccurrency">€</span>
					<span class="vrcprice"><?php echo $fullOrStan; ?></span>
               </td>
			   <td align="center">
                    <span class="vrccurrency">€</span>
                    <span class="vrcprice"><?php echo $tax; ?></span>
               </td>
			   <td align="center">
					<span class="vrccurrency">€</span>
					<span class="vrcprice"><?php echo $total_cost; ?></span>
			   </td>
			  </tr>
			  <tr height="20px">
				<td height="20px" colspan="5"> </td>
			  </tr>
			  <tr class="vrcordrowtotal">
			    <td>Total</td>
				<td align="center"> </td>
				<td align="center">
					<span class="vrccurrency">€</span>
					<span class="vrcprice"><?php echo $fullOrStan; ?></span>
				</td>
				<td align="center">
					<span class="vrccurrency">€</span>
					<span class="vrcprice"><?php echo $tax; ?></span>
				</td>
				<td class="vrctotalord" align="center">
					<span class="vrccurrency">€</span>
					<span class="vrcprice"><?php echo $total_cost; ?></span>
				</td>
			  </tr>
			</tbody>
		  </table>
		  <form method="post" action="" role="form" id="conorform">
		  <div class="form-group">
		    <table class="cnorderflds">
			  <tbody>
			    <tr>
				  <td align="right">
				    <span>
                      <sup>*</sup>
                    </span>
                    <span>Name</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="fname">
                  </td>
				</tr>
				<tr>
				  <td align="right">
				    <span>
                      <sup>*</sup>
                    </span>
                    <span>Last Name</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="lname">
                  </td>
				</tr>
				<tr>
				  <td align="right">
				    <span>
                      <sup>*</sup>
                    </span>
                    <span>e-mail</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="email">
                  </td>
				</tr>
				<tr>
				  <td align="right">
                    <span>Phone</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="phone">
                  </td>
				</tr>
				<tr>
				  <td align="right">
                    <span>Address</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="address">
                  </td>
				</tr>
				<tr>
				  <td align="right">
                    <span>Zip Code</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="zipcode">
                  </td>
				</tr>
				<tr>
				  <td align="right">
                    <span>City</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="city">
                  </td>
				</tr>
				<tr>
				  <td align="right">
                    <span>Date of Birth</span>
                  </td>
				  <td>
                    <input class="confinput form-control" type="text" size="40" value="" name="birth">
                  </td>
				</tr>
				<tr>
				<td align="right" valign="top">
				  <span>Notes</span>
				</td>
				<td align="right">
				  <textarea class="form-control" name="message" rows="5" cols="40" style="box-shadow: 0 0 2px #cccccc inset;">
				 </textarea>
				 </td>
				</tr> 
			  </tbody>
			</table>
			<br>
			<p class="paymethod">Payment Method</p>
			 <ul style="list-style-type: none;">
			  <li>
				<input id="gpay1" type="radio" checked="checked" value="1" name="gpayid">
				<label for="gpay1">Bank Transfer</label>
			  </li>
              <li>
				<input id="gpay2" type="radio" value="2" name="gpayid">
				<label for="gpay2">PayPal</label>
              </li>
              <li>
				<input id="gpay5" type="radio" value="5" name="gpayid">
				<label for="gpay5">Stripe - Credit Card</label>
              </li>
            </ul>
			<br>
			<input class="btn btn-default" type="submit" value="Confirm Order" name="saveorder">
		 </div>
		</form>
		  
		</div>
	    

      
   </div> <!-- /container -->


<?php

$jqScript = <<<EOD
   <script>
     $(function() {
  
    // Setup form validation on the #register-form element
    $("#conorform").validate({
    
        // Specify the validation rules
        rules: {
            fname: "required",
            lname: "required",
            email: {
                required: true,
                email: true
            },
            
            agree: "required"
        },
        
        // Specify the validation error messages
        messages: {
            firstname: "Please enter your first name",
            lastname: "Please enter your last name",
            email: "Please enter a valid email address",
            agree: ""
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });  
   </script>
  
  
EOD;


  /*======Kataxwrisi Kratisis=======*/
  if(isset($_REQUEST['fname'])) { $custom_fname = $_REQUEST['fname']; } else { $custom_fname = NULL;}
  if(isset($_REQUEST['lname'])) { $custom_lname = $_REQUEST['lname']; } else { $custom_lname = NULL;}
  if(isset($_REQUEST['email'])) { $custom_email = $_REQUEST['email']; } else { $custom_email = NULL;}
  
  if(isset($_REQUEST['phone'])) { $custom_phone = $_REQUEST['phone']; } else { $custom_phone = NULL;}  
  if(isset($_REQUEST['address'])) { $custom_address = $_REQUEST['address']; } else { $custom_address = NULL;}	
  if(isset($_REQUEST['zipcode'])) { $custom_zipcode = $_REQUEST['zipcode']; } else { $custom_zipcode = NULL;}	
  if(isset($_REQUEST['city'])) { $custom_city = $_REQUEST['city']; } else { $custom_city = NULL;}  
  if(isset($_REQUEST['birth'])) { $custom_birthdate = $_REQUEST['birth']; } else { $custom_birthdate = NULL;}  
  if(isset($_REQUEST['message'])) { $custom_message = $_REQUEST['message']; } else { $custom_message = NULL;}  
    
  $custom_user_id = 2;
  
  $reserv_day = date("Y-m-d h:i:sa");
  $status_id = 1;
  
  $car_location = 1;
  
  echo $pickup_date;
  echo date("Y-m-d h:i:sa",$pickup_date);
  

 /*
$insert_customer = sprintf("INSERT INTO customers " .
								         "(user_id, " .
								         "name, lastname, email, phone, address, zipcode, city, birthdate) " .
							          "VALUES ('%d', '%s', '%s', '%s', '%d', '%s', '%d', '%s', '%d');",
							           mysql_real_escape_string($custom_user_id),
							           mysql_real_escape_string($custom_fname),
							           mysql_real_escape_string($custom_lname),
							           mysql_real_escape_string($custom_email),
									   mysql_real_escape_string($custom_phone),
									   mysql_real_escape_string($custom_address),
									   mysql_real_escape_string($custom_zipcode),
									   mysql_real_escape_string($custom_city),
									   mysql_real_escape_string($custom_birthdate));
							
mysqli_query($con, $insert_customer); 


//upload car characteristics query
$insert_car_reservation = sprintf("INSERT INTO reservations " .
								         "(reserv_date, " .
								         "customer_id, car_id, pickup_location_id, pickup_datetime, dropoff_location_id, dropoff_datetime, amount, status_id, notes) " .
							          "VALUES ('%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s');",
							           mysql_real_escape_string($reserv_day),
							           mysqli_insert_id($con),
							           mysql_real_escape_string($car_id),
							           mysql_real_escape_string($car_location),
							           mysql_real_escape_string($pickup_date),
							           mysql_real_escape_string($dropoff_location),
									   mysql_real_escape_string($dropoff_date),
									   mysql_real_escape_string($total_cost),
									   mysql_real_escape_string($status_id),
									   mysql_real_escape_string($custom_message));
							
mysqli_query($con, $insert_car_reservation); 

*/












  
  
  
  // Requires the footer (JS declarations) part of the page 
  display_footer($jqScript);

?>
