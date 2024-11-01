<?php
if(isset($_POST['cart_name_sub'])){
	
	$cart_name= sanitize_text_field($_POST['wcf_cart_name']);

	
	global $woocommerce;

	  // get user details
	  global $current_user;
	  get_currentuserinfo();

		if (is_user_logged_in())
		{
		$user_id = $current_user->ID;
		$cart_contents = $woocommerce->cart->get_cart();
		
		//print_r($cart_contents);
		$curr_date = date('Y-m-d');
		
	
		$meta_value = $cart_contents;
		update_user_meta( $user_id, $cart_name, $meta_value);
	
		 global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_cart = $wpdb->prefix . 'wcf_custom_cart';
		
		 $cartdata = array(
		 
                'user_id' => $user_id,
                'cart_name' => $cart_name,
				'create_date' => $curr_date,
            );
			
            $wpdb->insert($table_cart, $cartdata);
		
		//---save as custom post type
					
					$post_information = array (
					'post_type' => 'Save Cart',
					'post_title' => $cart_name,
					'post_content' => 'check this',
					'post_status' => 'publish',
				 //   'comment_status' => 'closed',   // if you prefer
				   // 'ping_status' => 'closed',      // if you prefer
				);
				
				 wp_insert_post( $post_information );
			
		
	
		}
}

 

?>



<div class="container mt-3">
  
<!--  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
    Save Cart
  </button>
  -->
</div>

<!-- The Modal -->
<div class="modal" id="cartModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
       <h4 class="modal-title">Enter the Cart Title..</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
	   <form method="post" class="form-inline" action="" >
    <div class="mb-6 row">
      <!--<label class="col-sm-2 col-form-label" for="enq_name">Enter the Cart Title..</label>-->
	  <div class="col-sm-10">
      <input type="text" class="form-control" id="cart_name" placeholder="Enter cart name"  name="wcf_cart_name" required>
	  </div>
    </div><br>
	
	<div class="mb-6 row">
	<!--	<label class="col-sm-2 col-form-label"  for="cart_sub"></label>-->
		<div class="col-sm-10">
		 <button type="submit" class="btn btn-primary" name="cart_name_sub" id="cart_sub">Save</button>
		</div>
	</div><br>
  </form>
  
 
      </div>

      <!-- Modal footer -->
   <!--  <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
-->
    </div>
  </div>
</div>


