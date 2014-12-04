<?php
  
  require_once ('scripts/database_connection.php');
  require_once ('scripts/views.php');
  
  // Requires the <HEAD></HEAD> part of the page
  display_head("Thessaloniki Car Rentals");
  
  // Requires the navbar
  $tag = "home";
  display_navbar($tag);
  
?>

    <!-- Main component for a primary marketing message or call to action -->
    <div class="container"> 
	  
	 <div class="jumbotron">
       
	  <?php  
	   
      // pianei ta stoixeia apo ti forma	   
	  $category = $_REQUEST['category'];
	  $pickup_location = $_REQUEST['pickup_location'];
	  
	  if ($category == 'Any') {
	    $select_query = "SELECT * FROM cars WHERE car_location = '{$pickup_location}'";
	  } else {
	    $select_query = "SELECT * FROM cars WHERE car_category ='{$category}' " .
	                    "AND car_location = '{$pickup_location}'";  
	    }
     
	  // run the query	  
	  $result = mysqli_query($con, $select_query);  
    
	  
	  //emfanise ola ta amaksia
	  if($result) {
	    while ($car = mysqli_fetch_array($result)) {   //$car['car_category'];
		
		  $car_id		       = $car['car_id'];
		  $car_name 	       = $car['car_name'];
		  $car_category 	   = $car['car_category'];
		  $car_description     = $car['car_description'];
		  $car_location 	   = $car['car_location'];
		  $car_price 		   = $car['car_price'];
		  $car_pic_path        = $car['car_pic_path'];
          
		  echo <<<EOD
           <div class="car_result">
	        <form method="get" action="book_now.php">
		      <table class="car_result_table">
		        <tbody>
			      <tr>
			        <td align="left" width="130px" valign="top"> <!--car icon and price-->
				      <img class="imgresult" src="{$car_pic_path}" alt="" height="" width="">
				      <div class="car-result-price-div">
				        <span class="vrcstartfrom">Price/Day</span>
					    <span class="car_cost">{$car_price}</span>
				      </div>
				    </td> <!--end car icon and price-->
				    <td align="left" width="80%" valign="top">
				      <table>
				        <tbody>
					      <tr>
					        <td class="vrcrowcname"><b>{$car_category} : {$car_name}</b></td>
					      </tr>
					      <tr>
					        <td class="vrcrowdescr">
						      <p>{$car_description}</p>
						    </td>
					      </tr>
					      <tr>
					        <td>
						      <div class="characteristics">
						        <div class="char-icon">
							      <img class="char-img" src="images/char-icons/ac.png">
							      <span>A/C</span>
							    </div>
							    <div class="char-icon">
							      <img class="char-img" src="images/char-icons/engine.png">
							      <span>1000cc</span>
							    </div>	
							    <div class="char-icon">
							      <img class="char-img" src="images/char-icons/airbag.png">
							      <span>x2</span>
							    </div>
							    <div class="char-icon">
							      <img class="char-img" src="images/char-icons/body.png">
							      <span>x4</span>
							    </div>
							    <div class="char-icon">
							      <img class="char-img" src="images/char-icons/door.png">
							      <span>x4</span>
							    </div>
							    <div class="char-icon">
							      <img class="char-img" src="images/char-icons/radio.png">
							      <span>Radio CD</span>
								</div>
							    <div class="char-icon">
							        <img class="char-img" src="images/char-icons/compas.png">
							        <span>{$car_location}</span>
							    </div>
							  </div>
						    </td>
					      </tr>
					      <tr>
					        <td>
						      <input type="submit" value="Continue" class="btn btn-default btn-sm">
						    </td>
					      </tr>
					    </tbody>
				      </table>
				    </td>
			      </tr>
			    </tbody>
		      </table>
		    </form>
	       </div>
EOD;
		 	    
		} //end while
	  
	  } else { 
	    echo "no result";
	    }
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  ?>
        
	    
		
	 </div> <!-- / jumbotron-->

    </div> <!-- /container -->


<?php
    
  // Requires the footer (JS declarations) part of the page 
  display_footer();

?>
