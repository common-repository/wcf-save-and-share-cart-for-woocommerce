<?php
/*
Plugin Name: WCF Save & Share Cart for WooCommerce
Plugin URI: http://wecodefuture.com
Description: WCF Save & Share Cart for Woocommerce plugin is used for save your cart as wishlist and share to your known.
Version: 1.0
Author: WeCodeFuture
Author URI: http://wecodefuture.com
*/

register_activation_hook(__FILE__, 'wcf_save_share_cart');
register_deactivation_hook(__FILE__, 'wcf_save_share_cart_deactivate');

function wcf_save_share_cart(){
	
	

  wp_insert_post( $post_information );
	
	 global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_cart = $wpdb->prefix . 'wcf_custom_cart';
	
	 $wcfcartsql = "CREATE TABLE IF NOT EXISTS `$table_cart`(
						
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`user_id` INT(90) NOT NULL,
					`cart_name` varchar(300) NOT NULL UNIQUE,
					`create_date` DATE NOT NULL,

					PRIMARY KEY(id)
					)
					ENGINE=MyISAM DEFAULT CHARSET=utf8";

    $wpdb->query($wcfcartsql);

	
}

function wcf_save_share_cart_deactivate(){
	/*
	global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_cart = $wpdb->prefix . 'wcf_custom_cart';
	$wcfsql = "DROP TABLE IF EXISTS $table_cart";
	$wpdb->query($wcfsql);
	*/
}

	function wcf_save_share() {
			wp_enqueue_style( 'wcf-save-share-style', plugin_dir_url( '_FILE_' ) . 'wcf-save-share-cart/asset/css/bootstrap.min.css', false, '5.2.2'  );
			wp_enqueue_script( 'wcf-save-share-js', plugin_dir_url( '_FILE_' ) . 'wcf-save-share-cart/asset/js/bootstrap.min.js', true, '5.2.2');
			wp_register_style('Font Awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
			//wp_register_style( 'wcf-save-share-style', plugin_dir_url( '_FILE_' ) . 'wcf-save-share-cart/asset/css/fontawesome.css', false, '5.2.2'  );
			wp_enqueue_style( 'Font Awesome');
		}
		add_action( 'wp_enqueue_scripts', 'wcf_save_share' );

add_action( 'woocommerce_cart_contents', 'wcf_save_cart_button', 20,1 );

	function wcf_save_cart_button()
	{
		include('modal.php');
		$home_url=  home_url();
		$curr_cart_name = 'default';
		?>
		
		<button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
		Save Cart
	  </button>
		
		
		<?php
		
	}

add_action( 'woocommerce_after_cart', 'wcf_saved_cart_table', 20,1 );

	function wcf_saved_cart_table(){
		
				
		
		
				if(isset($_POST['wcf_cart_dlt'])){
					
							$current_cart_names= sanitize_text_field($_POST['wcf_cart_dlt']);
							
							global $wpdb;
							$charset_collate = $wpdb->get_charset_collate();
							$user_ID = get_current_user_id(); 
							$table_cart = $wpdb->prefix . 'wcf_custom_cart';
							$delete_cart = "DELETE FROM $table_cart WHERE cart_name='$current_cart_names'";
							$wpdb->query($delete_cart);
				}
			

		
		?>
			<div class="container mt-3">
		  <h2>Saved Cart</h2>
		  <p>you can restore cart here:</p>            
		  <table class="table table-bordered">
			<thead>
			  <tr>
			  <th>Sr.No.</th>
				<th>Cart Name</th>
				<th>Created Date</th>
				<th>Action</th>	
				<th>Share</th>
			  </tr>
			</thead>
			<tbody>
			<?php
			global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$user_ID = get_current_user_id(); 
		$i=1;
		$table_cart = $wpdb->prefix . 'wcf_custom_cart';
		$cart_details = "SELECT * FROM $table_cart WHERE user_id=$user_ID";
		$wcfp_cart_detail = $wpdb->get_results($cart_details);
		
			
		foreach($wcfp_cart_detail as $cart_list){  
			$cart_name= $cart_list->cart_name;
			$cart_id= $cart_list->id;
			$created_date = $cart_list->create_date;
			$home_url=  home_url();
			
			?>
				  <tr>
				  <td><?php echo esc_html($i); ?></td>
					<td><?php esc_html_e($cart_name);?></td>
					<td><?php esc_html_e($created_date); ?></td>
					
					<td><form method="post" action""><button type="submit" class="btn btn-primary" name="wcf_cart_rstr" id="cart_rstr" value="<?php esc_html_e($cart_name); ?>">Restore</button>
					<button type="submit" class="btn btn-primary" name="wcf_cart_dlt" id="cart_dlt" value="<?php esc_html_e($cart_name); ?>">Delete</button></form>
				
					</td>
					<td><a href="https://api.whatsapp.com/send?text=<?php print(urlencode($home_url)); ?>?wcf-cart=<?php esc_html_e($cart_name); ?>" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>&nbsp;&nbsp;
					<a href="http://www.facebook.com/share.php?u=<?php print(urlencode($home_url)); ?>?wcf-cart=<?php esc_html_e($cart_name); ?>" target="_blank"><i class = "fa fa-facebook"></i></a>&nbsp;&nbsp;
					<a href="http://twitter.com/home?status=<?php print(urlencode($home_url)); ?>?wcf-cart=+<?php esc_html_e($cart_name); ?>" target="_blank"><i class="fa fa-twitter"></i></a>
					
					
					</td>
				 </tr>
				  
					<?php $i++; 
					
					} 
					 
					
					
			

			?>
				</tbody>
			  </table>
			</div>
			
			<?php
		
		}
		

add_action('init','wcf_get_cart_url');
	
	function wcf_get_cart_url() { 
								
		if ( $_GET['wcf-cart'] ) {
				
			
					global $woocommerce;
					$share_cart= sanitize_text_field($_GET['wcf-cart']);
					 
					global $wpdb;
					$charset_collate = $wpdb->get_charset_collate();
					$table_cart = $wpdb->prefix . 'wcf_custom_cart';
					$cart_details = "SELECT `user_id` FROM $table_cart WHERE cart_name='$share_cart'";

					$wcfp_cart_detail = $wpdb->get_results($cart_details);

					foreach ($wcfp_cart_detail as $cart_user_id)
					{
						// Here you can access to every object value in the way that you want
						$user_ID = $cart_user_id->user_id;
						$cart_url=  wc_get_cart_url();
						$woocommerce->cart->empty_cart();
						$cart_content=get_user_meta($user_ID,$share_cart,true);
						foreach ( $cart_content as $cart_item_key => $values )
							  {
								$id =$values['product_id'];
								$quant=$values['quantity'];
								$woocommerce->cart->add_to_cart( $id, $quant);
								
								
							  }
							  wp_redirect($cart_url);
							  
							  
					}
		}
	}
	
	
	add_action('init','wcf_cart_restore_manually');
	function wcf_cart_restore_manually(){
		
		if(isset($_POST['wcf_cart_rstr'])){
				 global $woocommerce;
				$user_ID = get_current_user_id(); 
				$current_cart_name= sanitize_text_field($_POST['wcf_cart_rstr']);
				
				  // clear current cart, incase you want to replace cart contents, else skip this step
				  $woocommerce->cart->empty_cart();
				  
				  $cart_content=get_user_meta($user_ID,$current_cart_name,true);

				  // add cart contents
				  foreach ( $cart_content as $cart_item_key => $values )
				  {
					$id =$values['product_id'];
					$quant=$values['quantity'];
					$woocommerce->cart->add_to_cart( $id, $quant);
				  }
			  
					
		}
	
	}
	
	
?>