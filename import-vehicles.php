<?php

if ( !class_exists('aspkImportVehicles')){
	class aspkImportVehicles{
		function __construct(){
			register_activation_hook( __FILE__, array(&$this, 'install') );
			add_action('admin_menu', array(&$this,'adminMenu'));
			add_action('wp_enqueue_scripts', array(&$this, 'wpEnqueueScripts') );
			add_action('admin_enqueue_scripts', array(&$this, 'AdminEnqueueScripts') );
			add_action( 'init', array(&$this, 'fe_init') );
		}
		
		function wp_set_content_type(){
			return "text/html";
		}
		
		function wpEnqueueScripts(){
			wp_enqueue_script('jquery');
			//wp_enqueue_script('import-js-bootstrap',plugins_url('js/bootstrap.min.js', __FILE__));
			wp_enqueue_style( 'import-css-bootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );
		}
		
		function AdminEnqueueScripts(){
			wp_enqueue_script('jquery');
			//wp_enqueue_script('import-js-bootstrap',plugins_url('js/bootstrap.min.js', __FILE__));
			wp_enqueue_style( 'import-css-bootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );
		}
		
		function fe_init(){
			ob_start();
			
			$labels = array(
				'name'                  => _x( 'Vehicles', 'Post Type General Name', 'text_domain' ),
				'singular_name'         => _x( 'Vehicle', 'Post Type Singular Name', 'text_domain' ),
				'menu_name'             => __( 'Vehicles', 'text_domain' ),
				'name_admin_bar'        => __( 'Vehicles', 'text_domain' ),
				'archives'              => __( 'Vehicle Archives', 'text_domain' ),
				'attributes'            => __( 'Vehicle Attributes', 'text_domain' ),
				'parent_item_colon'     => __( 'Parent Vehicle:', 'text_domain' ),
				'all_items'             => __( 'All Vehciles', 'text_domain' ),
				'add_new_item'          => __( 'Add New Vehicle', 'text_domain' ),
				'add_new'               => __( 'Add New', 'text_domain' ),
				'new_item'              => __( 'New Vehicle', 'text_domain' ),
				'edit_item'             => __( 'Edit Vehicle', 'text_domain' ),
				'update_item'           => __( 'Update Vehicle', 'text_domain' ),
				'view_item'             => __( 'View Vehicle', 'text_domain' ),
				'view_items'            => __( 'View Vehicle', 'text_domain' ),
				'search_items'          => __( 'Search Vehicle', 'text_domain' ),
				'not_found'             => __( 'Not found', 'text_domain' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
				'featured_image'        => __( 'Featured Image', 'text_domain' ),
				'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
				'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
				'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
				'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
				'items_list'            => __( 'Items list', 'text_domain' ),
				'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
				'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
			);
			$args = array(
			'label'                 => __( 'Vehciles', 'text_domain' ),
			'description'           => __( 'Post Type Description', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
			'hierarchical'          => false,
			'rewrite'               => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			);
			register_post_type( 'vehicles', $args );
			  
		    register_taxonomy(
				'vehicle-category',
				'vehicles',
				array(
				'label' => __( 'Vehicle Category' ),
				'rewrite' => array('slug' => 'vehicle-category'),
				'hierarchical' => true,
				)
			);
		}
		
		function install () {
			
		}
		
		function importVehicles(){
	
			if(isset($_POST["aspk_import_file"])){
				if ( ! function_exists( 'wp_handle_upload' ) );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				$uploadedfile = $_FILES['file'];
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				if ($movefile){
					$file_path = $movefile['file'];
					if ( ($handle = fopen($file_path, "r")) !== FALSE ) {
						
						$headerColumn = fgetcsv($handle, 1000, ",");
						
						$x = 1;
						$row = 1;
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
							$num = count($data);
							
							if($num != 23 ){
								echo "<p> Incorrect format of uploaded file: $num fields in line $row: <br /></p>\n";
								break;
							}else{

								if($x == 1) //delete_old_data_complete(); 
								$x++;
								$row++;
								
								$title = $data[0];
								$year = $data[1];
								$make = $data[2];
								$model = $data[3];
								$trim =  $data[4];
								$category =  $data[5];
								$stock_num =  $data[6];
								$vin =  $data[7];
								$is_new =  $data[8];
								$odometer =  $data[9];
								$price =  $data[10];
								$sale_price =  $data[11];
								$transmission =  $data[12];
								$ext_color =  $data[13];
								$int_color =  $data[14];
								$description =  $data[15];
								$options =  $data[16];
								$image_urls =  $data[17];
								$cylinders =  $data[18];
								$drive =  $data[19];
								$doors =  $data[20];
								$passengers =  $data[21];
								$fuel =  $data[22];
								
								
								//Insert Posts for Vehicles CPT
								$my_post = array(
									'post_title'    => '',
									'post_content'  => $description,
									'post_status'   => 'publish',
									'post_type'     => 'vehicles' );

								$post_id = wp_insert_post( $my_post );
								
								if(!empty($post_id)){
									if(!empty($year)){
										update_post_meta($post_id , 'year', $year);
									}
									if(!empty($make)){
										update_post_meta($post_id , 'make', $make);
									}
									if(!empty($model)){
										update_post_meta($post_id , 'model', $model);
									}
									if(!empty($trim)){
										update_post_meta($post_id , 'trim', $trim);
									}
									if(!empty($category)){
										update_post_meta($post_id , 'category', $category);
									}
									if(!empty($stock_num)){
										update_post_meta($post_id , 'stock_num', $stock_num);
									}
									if(!empty($vin)){
										update_post_meta($post_id , 'vin', $vin);
									}
									if(!empty($is_new)){
										update_post_meta($post_id , 'is_new', $is_new);
									}
									if(!empty($odometer)){
										update_post_meta($post_id , 'odometer', $odometer);
									}
									if(!empty($price)){
										update_post_meta($post_id , 'price', $price);
									}
									if(!empty($sale_price)){
										update_post_meta($post_id , 'sale_price', $sale_price);
									}
									if(!empty($transmission)){
										update_post_meta($post_id , 'transmission', $transmission);
									}
									if(!empty($ext_color)){
										update_post_meta($post_id , 'ext_color', $ext_color);
									}
									if(!empty($int_color)){
										update_post_meta($post_id , 'int_color', $int_color);
									}
									if(!empty($options)){
										update_post_meta($post_id , 'options', $options);
									}
									if(!empty($cylinders)){
										update_post_meta($post_id , 'cylinders', $cylinders);
									}
									if(!empty($drive)){
										update_post_meta($post_id , 'drive', $drive);
									}
									if(!empty($doors)){
										update_post_meta($post_id , 'doors', $doors);
									}
									if(!empty($passengers)){
										update_post_meta($post_id , 'passengers', $passengers);
									}
									if(!empty($fuel)){
										update_post_meta($post_id , 'fuel', $fuel);
									}

									// Image URLs
									if(!empty($image_urls)){
										update_post_meta($post_id , 'car_images', $image_urls);
									
									/* Set featured image from Image URLs.
										* First URL as a Featured image. */
										
										//$featured_image  = explode(";", $image_urls);
										//$image_url = explode( "?", $featured_image[0]);
                                        //$image_url_gen = $image_url[0].'.jpg';
										//Generate_Featured_Image( $image_url_gen, $post_id );
									
									}

									if(!empty($catgEn)){
										wp_set_object_terms( $post_id, $category, 'vehicle-category' );
									}
								}
								
							}
						}	
						echo "<h2>File Imported Successfully</h2>";
						fclose( $handle );
					}else{
						echo "<h2>Incorrect file format </h2>";
					}
				}
			}
			
			?>
			<div class="tw-bs container">
				<div class="row">
					<div class="col-md-6 justify-center">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="row">
								<label>Upload CSV File: </label>
								<input type="file" name="file" required>
							</div>
							<div class="row">
								<input type="submit" name="aspk_import_file" class="btn btn-primary" value="Import">
							</div>
						</form>
					</div>
				</div>
				
			</div>
			<?php
			
		}
		
		function adminMenu(){
			add_menu_page('Import Vehicles Data', 'Import Vehicles Data', 'manage_options', 'vehicles_import', array(&$this, 'importVehicles') );
		}

		function Generate_Featured_Image( $image_url, $post_id  ){
		    $upload_dir = wp_upload_dir();
		    $image_data = file_get_contents($image_url);
		    $filename = basename($image_url);
		    if(wp_mkdir_p($upload_dir['path']))
		      $file = $upload_dir['path'] . '/' . $filename;
		    else
		      $file = $upload_dir['basedir'] . '/' . $filename;
		    file_put_contents($file, $image_data);

		    $wp_filetype = wp_check_filetype($filename, null );
		    $attachment = array(
		        'post_mime_type' => $wp_filetype['type'],
		        'post_title' => sanitize_file_name($filename),
		        'post_content' => '',
		        'post_status' => 'inherit'
		    );
		    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		    require_once(ABSPATH . 'wp-admin/includes/image.php');
		    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
		    $res2= set_post_thumbnail( $post_id, $attach_id );
		}


		
	}//end class
}//end main class
$aspkImport = new aspkImportVehicles();