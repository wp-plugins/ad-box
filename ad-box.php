<?php
/*
Plugin Name: Ad Box
Plugin URI: http://Medust.com/
Description: Add an advertising widget to your sidebar that gives you lots of customization options.
Version: 1.0
Author: Medust
Author URI: http://Medust.com
*/

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'ms_ad_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'ms_ad_post_meta_boxes_setup' );

/* Meta box setup function. */
function ms_ad_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'ms_ad_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'ms_ad_save_post_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function ms_ad_add_post_meta_boxes() {

  add_meta_box(
    'ad-box-class',      // Unique ID
	'Ad Box',
    //esc_html__( 'Post Class', 'example' ),    // Title
    'ms_ad_meta_box',   // Callback function
    'post',         // Admin page (or post type)
    'side',         // Context
    'low'         // Priority
  );
}

/* Display the post meta box. */
function ms_ad_meta_box( $object, $box ) { ?>

  <?php //wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' ); ?>
  
  <p><a href="#" class="adbox-upload-button" rel="ad_meta_img">Click here to upload a new image.</a> You can also paste in an image URL below.</p>

  <p>
    <label for="ad_meta_img">Ad Image</label>
    <input class="widefat" type="text" name="ad_meta_img" id="ad_meta_img" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ad_meta_img', true ) ); ?>" placeholder="Ad Image URL" />
  </p>
  
  <p>
  	<label for="ad_meta_link">Ad Destination Link</label>
    <input class="widefat" type="text" name="ad_meta_link" id="ad_meta_link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ad_meta_link', true ) ); ?>" placeholder="Ad Destination Link" />
  </p>
  
  <p>
  	<label for="ad_meta_title">Ad Title</label>
    <input class="widefat" type="text" name="ad_meta_title" id="ad_meta_title" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ad_meta_title', true ) ); ?>" placeholder="Ad Title" />
  </p>
  
  <p>
  	<label for="ad_meta_desc">Ad Description</label>
    <textarea class="widefat" type="text" name="ad_meta_desc" id="ad_meta_desc" placeholder="Ad Description" rows="3"><?php echo esc_attr( get_post_meta( $object->ID, 'ad_meta_desc', true ) ); ?></textarea>
  </p>
  
  <p>
  	<label for="ad_meta_width">Ad Box Width (in Pixels)</label>
    <input class="widefat" type="text" name="ad_meta_width" id="ad_meta_width" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ad_meta_width', true ) ); ?>" placeholder="Ad Box Width (Eg. 300)" />
  </p>
  
  <p>
  	<input type="checkbox" id="ad_meta_new_w" name="ad_meta_new_w" value="yes" <?php if(get_post_meta( $object->ID, 'ad_meta_new_w', true )=='yes') echo 'checked'; ?> /> <label for="ad_meta_new_w"> Open in New Window</label>
  </p>
<?php 
}

/* Save the meta box's post metadata. */
function ms_ad_save_post_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  //if ( !isset( $_POST['smashing_post_class_nonce'] ) || !wp_verify_nonce( $_POST['smashing_post_class_nonce'], basename( __FILE__ ) ) )
    //return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_img_value = ( isset( $_POST['ad_meta_img'] ) ?  $_POST['ad_meta_img'] : '' );
  $new_meta_link_value = ( isset( $_POST['ad_meta_link'] ) ?  $_POST['ad_meta_link'] : '' );
  $new_meta_title_value = ( isset( $_POST['ad_meta_title'] ) ?  $_POST['ad_meta_title'] : '' );
  $new_meta_desc_value = ( isset( $_POST['ad_meta_desc'] ) ?  $_POST['ad_meta_desc'] : '' );
  $new_meta_width_value = ( isset( $_POST['ad_meta_width'] ) ?  $_POST['ad_meta_width'] : '' );
  $new_meta_new_w_value = ( isset( $_POST['ad_meta_new_w'] ) ?  $_POST['ad_meta_new_w'] : '' );

  /* Get the meta key. */
  $meta_img_key = 'ad_meta_img';
  $meta_link_key = 'ad_meta_link';
  $meta_title_key = 'ad_meta_title';
  $meta_desc_key = 'ad_meta_desc';
  $meta_width_key = 'ad_meta_width';
  $meta_new_w_key = 'ad_meta_new_w';

  /* Get the meta value of the custom field key. */
  $meta_img_value = get_post_meta( $post_id, $meta_img_key, true );
  $meta_link_value = get_post_meta( $post_id, $meta_link_key, true );
  $meta_title_value = get_post_meta( $post_id, $meta_title_key, true );
  $meta_desc_value = get_post_meta( $post_id, $meta_desc_key, true );
  $meta_width_value = get_post_meta( $post_id, $meta_width_key, true );
  $meta_new_w_value = get_post_meta( $post_id, $meta_new_w_key, true );
	
	/**
	* Meta Img Post Meta Add/Update/Delete performs
	**/
  	/* If a new meta value was added and there was no previous value, add it. */
  	if ( $new_meta_img_value && '' == $meta_img_value )
    	add_post_meta( $post_id, $meta_img_key, $new_meta_img_value, true );

  	/* If the new meta value does not match the old value, update it. */
  	elseif ( $new_meta_img_value && $new_meta_img_value != $meta_img_value )
    	update_post_meta( $post_id, $meta_img_key, $new_meta_img_value );

  	/* If there is no new meta value but an old value exists, delete it. */
  	elseif ( '' == $new_meta_img_value && $meta_img_value )
    	delete_post_meta( $post_id, $meta_img_key, $meta_img_value );
	
	/**
	* Meta Link Post Meta Add/Update/Delete performs
	**/
	/* If a new meta value was added and there was no previous value, add it. */
  	if ( $new_meta_link_value && '' == $meta_link_value )
    	add_post_meta( $post_id, $meta_link_key, $new_meta_link_value, true );
	
	/* If the new meta value does not match the old value, update it. */
  	elseif ( $new_meta_link_value && $new_meta_link_value != $meta_link_value )
    	update_post_meta( $post_id, $meta_link_key, $new_meta_link_value );
	
	/* If there is no new meta value but an old value exists, delete it. */
  	elseif ( '' == $new_meta_link_value && $meta_link_value )
    	delete_post_meta( $post_id, $meta_link_key, $meta_link_value );
	
	/**
	* Meta Title Post Meta Add/Update/Delete performs
	**/
	/* If a new meta value was added and there was no previous value, add it. */
  	if ( $new_meta_title_value && '' == $meta_title_value )
    	add_post_meta( $post_id, $meta_title_key, $new_meta_title_value, true );
	
	/* If the new meta value does not match the old value, update it. */
  	elseif ( $new_meta_title_value && $new_meta_title_value != $meta_title_value )
    	update_post_meta( $post_id, $meta_title_key, $new_meta_title_value );
	
	/* If there is no new meta value but an old value exists, delete it. */
  	elseif ( '' == $new_meta_title_value && $meta_title_value )
    	delete_post_meta( $post_id, $meta_title_key, $meta_title_value );
	
	/**
	* Meta Description Post Meta Add/Update/Delete performs
	**/
	/* If a new meta value was added and there was no previous value, add it. */
  	if ( $new_meta_desc_value && '' == $meta_desc_value )
    	add_post_meta( $post_id, $meta_desc_key, $new_meta_desc_value, true );
	
	/* If the new meta value does not match the old value, update it. */
  	elseif ( $new_meta_desc_value && $new_meta_desc_value != $meta_desc_value )
    	update_post_meta( $post_id, $meta_desc_key, $new_meta_desc_value );
	
	/* If there is no new meta value but an old value exists, delete it. */
  	elseif ( '' == $new_meta_desc_value && $meta_desc_value )
    	delete_post_meta( $post_id, $meta_desc_key, $meta_desc_value );
	
	/**
	* Meta Width Post Meta Add/Update/Delete performs
	**/
	/* If a new meta value was added and there was no previous value, add it. */
  	if ( $new_meta_width_value && '' == $meta_width_value )
    	add_post_meta( $post_id, $meta_width_key, $new_meta_width_value, true );
	
	/* If the new meta value does not match the old value, update it. */
  	elseif ( $new_meta_width_value && $new_meta_width_value != $meta_width_value )
    	update_post_meta( $post_id, $meta_width_key, $new_meta_width_value );
	
	/* If there is no new meta value but an old value exists, delete it. */
  	elseif ( '' == $new_meta_width_value && $meta_width_value )
    	delete_post_meta( $post_id, $meta_width_key, $meta_width_value );
	
	/**
	* Meta New Window Post Meta Add/Update/Delete performs
	**/
	/* If a new meta value was added and there was no previous value, add it. */
  	if ( $new_meta_new_w_value && '' == $meta_new_w_value )
    	add_post_meta( $post_id, $meta_new_w_key, $new_meta_new_w_value, true );
	
	/* If the new meta value does not match the old value, update it. */
  	elseif ( $new_meta_new_w_value && $new_meta_new_w_value != $meta_new_w_value )
    	update_post_meta( $post_id, $meta_new_w_key, $new_meta_new_w_value );
	
	/* If there is no new meta value but an old value exists, delete it. */
  	elseif ( '' == $new_meta_new_w_value && $meta_new_w_value )
    	delete_post_meta( $post_id, $meta_new_w_key, $meta_new_w_value );
}

class AdBox_Core {
	
	/**
     * The callback used to register the scripts
     */
    static function registerScripts()
    {
        # Include thickbox on widgets page
        if($GLOBALS['pagenow'] == 'widgets.php' || $GLOBALS['pagenow'] == 'post.php' || $GLOBALS['pagenow'] == 'post-new.php')
        {
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
            wp_enqueue_script('adwidget-main',  plugin_dir_url(__FILE__).'assets/js/adbox-widgets.js');
        }
		
		wp_enqueue_script('jquery');                    // Enque Default jQuery
		wp_enqueue_script('jquery-ui-core');            // Enque Default jQuery UI Core
		wp_enqueue_script('jquery-ui-tabs');            // Enque Default jQuery UI Tabs
	
		wp_register_style('ad-box-ui-plugin-css', plugins_url('/assets/css/jquery-ui.css', __FILE__));
		wp_enqueue_style('ad-box-ui-plugin-css');
	
		wp_register_style('ad-box-plugin-css', plugins_url('/assets/css/ad-box.css', __FILE__));
		wp_enqueue_style('ad-box-plugin-css');
    }
}

/**
* Class to add a widget
*/
class AdBoxWidget extends WP_Widget {
	
	function __construct() {
		$params = array(
			'description' => 'Display an image ad with link, title & description',
			'name' => 'Ad Box'
		);
		
		parent::__construct('AdBoxWidget', '', $params);
	}
	
	public function form($instance) {
		extract($instance);
		?>
        <p><a href="#" class="adbox-upload-button" rel="<?php echo $this->get_field_id('ad_img'); ?>">Click here to upload a new image.</a> You can also paste in an image URL below.</p>
        
        <p>
        	<label for="<?php echo $this->get_field_id('ad_img'); ?>">Ad Image</label>
            <input
            	class="widefat"
                type="text"
                placeholder="Ad Image URL"
                id="<?php echo $this->get_field_id('ad_img'); ?>"
                name="<?php echo $this->get_field_name('ad_img'); ?>"
                value="<?php if(isset($ad_img)) echo esc_attr($ad_img); ?>"/>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id('ad_link'); ?>">Ad Destination Link</label>
            <input
            	class="widefat"
                type="text"
                placeholder="Ad Link URL"
                id="<?php echo $this->get_field_id('ad_link'); ?>"
                name="<?php echo $this->get_field_name('ad_link'); ?>"
                value="<?php if(isset($ad_link)) echo esc_attr($ad_link); ?>"/>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id('ad_title'); ?>">Ad Title</label>
            <input
            	class="widefat"
                type="text"
                placeholder="Ad Title"
                id="<?php echo $this->get_field_id('ad_title'); ?>"
                name="<?php echo $this->get_field_name('ad_title'); ?>"
                value="<?php if(isset($ad_title)) echo esc_attr($ad_title); ?>"/>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id('ad_description'); ?>">Ad Description</label>
            <textarea class="widefat" placeholder="Ad Description" id="<?php echo $this->get_field_id('ad_description'); ?>"name="<?php echo $this->get_field_name('ad_description'); ?>" rows="3"><?php if(isset($ad_description)) echo esc_attr($ad_description); ?></textarea>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id('ad_width'); ?>">Ad Box Width (in Pixel)</label>
            <input
            	class="widefat"
                type="text"
                placeholder="Ad Box Width (Eg. 300)"
                id="<?php echo $this->get_field_id('ad_width'); ?>"
                name="<?php echo $this->get_field_name('ad_width'); ?>"
                value="<?php if(isset($ad_width)) echo esc_attr($ad_width); ?>"/>
        </p>
        
        <p>
        	<input
            	type="checkbox"
                id="<?php echo $this->get_field_id('ad_new_w'); ?>"
                name="<?php echo $this->get_field_name('ad_new_w'); ?>"
                value="yes"
                <?php if($ad_new_w=='yes') echo 'checked'; ?> />
            <label for="<?php echo $this->get_field_id('ad_new_w'); ?>"> Open in New Window</label>
        </p>
       <?php
	}
	
	public function widget($args, $instance) {
		extract($args);
		extract($instance);
		
		// Default value set for ad
		
		$ad_main_title = '';
		$ad_main_description = '';
		$ad_sponsor = '<div style="float:right;font-weight:normal; font-size:12px; color:#999">Powered by <a href="http://wordpress.org/plugins/ad-box/" target="_blank">Ad Box</a> from <a href="http://www.medust.com/" target="_blank">Medust</a></div>
		<div style="clear:both"></div>
		</div>';
		
		// CHECKING FOR POST META BOX DATA
		/* Get the current post ID. */
		  $post_id = get_the_ID();
		
		  /* If we have a post ID, proceed. */
		  //if ( !empty( $post_id ) && is_single() ) {
		
			/* Get the custom post class. */
			$ad_box_meta_img = get_post_meta( $post_id, 'ad_meta_img', true );
			$ad_box_meta_link = get_post_meta( $post_id, 'ad_meta_link', true );
			$ad_box_meta_title = get_post_meta( $post_id, 'ad_meta_title', true );
			$ad_box_meta_desc = get_post_meta( $post_id, 'ad_meta_desc', true );
			$ad_box_meta_width = get_post_meta( $post_id, 'ad_meta_width', true );
			$ad_box_meta_new_w = get_post_meta( $post_id, 'ad_meta_new_w', true );
		
			/* If a post class was input, sanitize it and add it to the post class array. */
			if ( !empty( $ad_box_meta_img ) && is_single() ) {
				if($ad_box_meta_new_w!='')
					$meta_target = 'target="_blank"';
				else
					$meta_target = '';
				
				if($ad_box_meta_width!='')
					$meta_width = $ad_box_meta_width;
				else
					$meta_width = 300;
				
				$ad_main_img = '<div id="ms_ad_box" style="padding:3px;background:#fff;border:1px solid #ccc; width:'.$meta_width.'px"><a href="'.$ad_box_meta_link.'" '.$meta_target.'><img src="'.$ad_box_meta_img.'" width="100%"></a>';
				
				if($ad_box_meta_title!='')
					$ad_main_title = '<a href="'.$ad_box_meta_link.'" '.$meta_target.'><div style="font-weight:bold; font-size:14px; text-align:center; margin:5px 0">'.$ad_box_meta_title.'</div></a>';
				
				if($ad_box_meta_desc!='')
					$ad_main_description = '<div style="font-weight:normal; font-size:14px">'.$ad_box_meta_desc.'</div>';
				
			  $ad = $ad_main_img . $ad_main_title . $ad_main_description . $ad_sponsor;
			}
		 //}
		else { //Widget Data
		if(isset($ad_new_w))
			$target = 'target="_blank"';
		else
			$target = '';
		
		if(isset($ad_width)) 
			$width = $ad_width;
		else
			$width = 300;
		
		if($ad_img=='')
			$ad_main_img = '<div id="ms_ad_box" style="padding:3px;background:#fff;border:1px solid #ccc; width:300px"><a href="http://www.medust.com" target="_blank"><img src="'.plugin_dir_url(__FILE__).'assets/sample-ad.jpg'.'" width="100%"></a>';
		else
			$ad_main_img = '<div id="ms_ad_box" hk style="padding:3px;background:#fff;border:1px solid #ccc; width:'.$width.'px"><a href="'.$ad_link.'" '.$target.'><img src="'.$ad_img.'" width="100%"></a>';
				
				if($ad_title!='')
					$ad_main_title = '<a href="'.$ad_link.'" '.$target.'><div style="font-weight:bold; font-size:14px; text-align:center; margin:5px 0">'.$ad_title.'</div></a>';
				
				if($ad_description!='')
					$ad_main_description = '<div style="font-weight:normal; font-size:14px">'.$ad_description.'</div>';
		
		$ad = $ad_main_img . $ad_main_title . $ad_main_description . $ad_sponsor;
		}
		
		echo $before_widget;
			echo $ad;
		echo $after_widget;
	}
}

add_action('admin_init',array('AdBox_Core','registerScripts'));
add_action('widgets_init','adbox_reg_widget');
add_action('wp_enqueue_scripts','ms_ad_box');
add_action('admin_menu', 'ms_ad_box_add_option_page');

function ms_ad_box() {
	wp_enqueue_script(
        'ad-box-script', // name your script so that you can attach other scripts and de-register, etc.
        //get_template_directory_uri() . '/js/your-script.js', // this is the location of your script file
		plugin_dir_url(__FILE__).'assets/js/adbox-front.js',
        array('jquery'), // this array lists the scripts upon which your script depends
		false,
		true
    );
}

function adbox_reg_widget() {
	register_widget('AdBoxWidget');
}

// Displays Wordpress Blog ad box Options menu
function ms_ad_box_add_option_page()
{
    if (function_exists('add_options_page')) {
        add_options_page('Ad Box', 'Ad Box', 8, __FILE__, 'ms_ad_box_options_page');
    }
}

function ms_ad_box_options_page() {
	require_once (dirname(__FILE__) . '/includes/settings-page.php');
}

?>