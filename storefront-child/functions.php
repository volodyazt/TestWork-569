<?php

/* подключение стилей */
function admin_style() {
  
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}

 	wp_enqueue_script( 'myuploadscript', get_stylesheet_directory_uri() . '/assets/js/admin.js', array('jquery'), null, false );

 	wp_enqueue_style('admin-styles', get_stylesheet_directory_uri() . '/admin.css');
}

add_action('admin_enqueue_scripts', 'admin_style');


/* вывод кастомных поле в админке */
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_fields' );
function add_custom_fields() {
	global $product, $post;

	woocommerce_wp_select( array(
	   'id'      => '_select',
	   'label'   => __( 'Product type'),
	   'desc_tip' => true,
	   'description'=> __( 'Select product type' ),
	   'options' => array(
	      'one'   => __( '', 'woocommerce' ),
	      'two'   => __( 'rare', 'woocommerce' ),
	      'three' => __( 'frequent', 'woocommerce' ),
	      'four' => __('unusual', 'woocommerce')
	   ),
	) ); 

	$custom_date = get_post_meta($post->ID, '_creating_date', true);
	woocommerce_wp_text_input( array(		
			'id'                => '_creating_date',
			'type'				=> 'date',
			'label'             => __( 'Creation date', 'woocommerce' ),
			'placeholder'       => 'Creation date',
			'desc_tip'          => 'true',
			//'custom_attributes' => [ 'required' => 'required' ],
			'description'       => __( 'Enter creation date', 'woocommerce' ),
			'value'				=> empty($custom_date) ? get_the_date('Y-m-d', $post ) : $custom_date,
	) );

	custom_image_uploader_field( array(
			'name' => '_uploader_custom',
			'value' => get_post_meta( $post->ID, '_uploader_custom', true ),
		) );

	echo '
	<div class="custom_button">
		<div class="clear_custom_fields">'.__('Clear custom fields').'</div>
		<a id="button_save">Save</a>
	</div>';

}

/* сохранение кастомных полей */
add_action( 'woocommerce_process_product_meta', 'custom_fields_save', 10 );
function custom_fields_save( $post_id ) {

	$product = wc_get_product( $post_id );

	$woocommerce_select = $_POST['_select'];
	if ( ! empty( $woocommerce_select ) ) {
		update_post_meta( $post_id, '_select', esc_attr( $woocommerce_select ) );
	}

	$woocommerce_img = $_POST['_uploader_custom'];
	if ( ! empty( $woocommerce_img ) ) {
	
		update_post_meta( $post_id, '_uploader_custom', esc_attr( $woocommerce_img ) );
	} else {
		update_post_meta( $post_id, '_uploader_custom', 5 );
	}
		
	$woocommerce_date = $_POST['_creating_date'];
	if ( ! empty( $woocommerce_date ) ) {
		update_post_meta( $post_id, '_creating_date', esc_attr( $woocommerce_date ) );
	}

}


function custom_image_uploader_field( $args ) {

	$value = $args[ 'value' ];
	$upload_dir = wp_upload_dir();
	$default = $upload_dir['baseurl'] . '/woocommerce-placeholder.png';
	global $post;
	$img_id = get_post_meta( $post->ID, '_thumbnail_id', true );

 
	if( $image_attributes = wp_get_attachment_image_src( $img_id, array( 150, 110 ) ) ) {
		$src = $image_attributes[0];
	} else {
		$src = $default;
	}
	var_dump($img_id);
	echo '
	<div class="form-field _select_field" id="custom_img_block">
		<label for="_select">'.__( 'Additional image').'</label>
		<img data-src="' . $default . '" src="' . $src . '" width="150" />
		<div class="add_img_btn">
			<input type="hidden" name="' . $args[ 'name' ] . '" id="' . $args[ 'name' ] . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">'.__('Upload').'</button>
			<button type="submit" class="remove_image_button button">×</button>
		</div>
	</div>';
}

/* установка кастомного изображения основным */	
add_action( 'woocommerce_process_product_meta', 'set_custom_img' );
function set_custom_img() {
	global $post;
	$attachment_id = get_post_meta( $post->ID, '_uploader_custom', true );
	 
    set_post_thumbnail( $post->ID, $attachment_id );
}


/* вывод после кастомных полей в карточке товара */
add_action( 'woocommerce_after_add_to_cart_form', 'view_field_after_add_card' );
function view_field_after_add_card() {
	global $post, $product;
	$select_field = get_post_meta( $post->ID, '_select', true );
	$date_field  = get_post_meta( $post->ID, '_creating_date', true );
	if ( $select_field ) {
		echo '<div><strong>'.__('Product type: ').'</strong>';
		switch ( $select_field ) {
			case 'two':
				echo 'rare';
				break;
			case 'three':
				echo 'frequent';
				break;
			case 'four':
				echo 'unusual';
				break;
		}
		echo '</div>';
	}
	if ( $date_field ) {
		echo '<div><strong>'.__('Creation date: ').'</strong>';
		echo $date_field;
		echo '</div>';
	}
}


