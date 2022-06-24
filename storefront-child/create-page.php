<?php
/*
Template Name: Create product
*/

get_header(); ?>

<form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
	<h4>Create product</h4>
    <div>
          <div><label>Product Name</label></div>
          <div><input type="text" name="proname" class="proname"/></div>
    </div>
    <div>
          <div><label>Product Price</label></div>
          <div><input type="text" name="proprice" class="proprice"/></div>
    </div>
    <div>
	    <div><label>Product Type</label></div>
      	<select style="" id="_select" name="_select" class="select custom-select">
			<option value="one"></option>
			<option value="two">rare</option>
			<option value="three">frequent</option>
			<option value="four">unusual</option>		
		</select>
    </div>
    <?php $create_date = date('Y-m-d'); ?>
    <div>
        <div><label>Create date</label></div>
        <div><input type="date" name="_creating_date" id="_creating_date" value='<?php echo $create_date ?>'></div>
    </div>
    <div class="upload_block">
    	<label id="label_upload">Product image</label>
		<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
		<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value='Add item' />
	</div>
</form>

<?php
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	if(isset($_POST['proname']))
	{
	  global $wpdb, $post;
	  $post_data = array(
	    'post_title' => $_POST['proname'],
	    'post_type' => 'product',
	    'post_status' => 'publish',
	    'post_author'   => 1
	  );
	  $post_id = wp_insert_post( $post_data );
	  update_post_meta($post_id,'_price',$_POST['proprice']);
	  update_post_meta($post_id,'_select',$_POST['_select']);
	  update_post_meta($post_id,'_creating_date',$_POST['_creating_date']);
	  $img_upload_id = media_handle_upload( 'my_image_upload', $post_id );
      set_post_thumbnail( $post_id, $img_upload_id );
      echo __('Product added');
	}

do_action( 'storefront_sidebar' );

get_footer();
?>