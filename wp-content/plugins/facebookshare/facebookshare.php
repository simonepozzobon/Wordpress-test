<?php
   /*
   Plugin Name: Facebook share
   Plugin URI: http://www.simonepozzobon.com
   Description: a random image plugin for facebook
   Version: 1.0
   Author: Simone Pozzobon
   Author URI: http://simonepozzobon.com
   License: GPL2
   */

define('PLUGIN_PATH', plugin_dir_path( __FILE__ ));

/*
 *
 * DB TABLES
 *
 */

function sp_facebookshare_install ()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "facebookshare";
  $charset_collate = $wpdb->get_charset_collate();

  $query = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      media_id mediumint(9) NOT NULL,
      PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $query );
}

register_activation_hook( __FILE__, 'sp_facebookshare_install' );


/*
 *
 * ROUTING
 *
 */

add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
add_filter( 'query_vars','my_insert_query_vars' );
// add_filter( 'template_include','include_template' );
add_action( 'wp_loaded','my_flush_rules' );
add_action('init', function() {
  $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
  // $url_path = home_url();
  $path = wp_parse_url(home_url());
  print_r($path);
  if ( isset($path['path']) ) {
      $path = $path['path'].'\//';
      $url_path = preg_replace($path, '', $url_path);
      preg_match("/name\//", $url_path, $check);
  }

  if ( $check ) {
     add_filter( 'template_include','include_template' );
  }
});

// flush_rules() if our rules are not yet included
function my_flush_rules(){
    $rules = get_option( 'rewrite_rules' );

    if ( ! isset( $rules['name/(.*?)'] ) ) {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}

// Adding a new rule
function my_insert_rewrite_rules( $rules )
{
    $newrules = array();
    $newrules['name/(.*?)'] = 'index.php?sp_name=$matches[1]';


    return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function my_insert_query_vars( $vars )
{
    array_push($vars, 'sp_name');
    return $vars;
}

// create template
function include_template( $template )
{
  return PLUGIN_PATH . 'register.php';
}


/*
 *
 * ADMIN PANEL
 *
 */

add_action( 'admin_menu', 'sp_facebook_add_admin_menu' );

function sp_facebook_add_admin_menu() {
	add_menu_page( 'facebookshare', 'Facebook Share', 'manage_options', 'facebookshare', 'media_selector_settings_page_callback', 'dashicons-facebook');
}

function media_selector_settings_page_callback() {
  // Get medias
  global $wpdb;
  $fbshare = $wpdb->prefix . 'facebookshare';
  $media = $wpdb->prefix . 'posts';
  $query = "SELECT $fbshare.media_id, $media.guid
            FROM $fbshare
            INNER JOIN $media ON $fbshare.media_id=$media.ID";
  $results = $wpdb->get_results( $query, OBJECT );

	wp_enqueue_media(); ?>
  <p></p>
  <form method='post'>
		<div id="images" class='image-preview-wrapper'>
      <?php foreach ($results as $key => $result) { ?>
        <img src="<?php _e($result->guid) ?>" height="100">
      <?php } ?>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
		<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
		<!-- <input type="submit" name="submit_image_selector" value="Save" class="button-primary"> -->
	</form> <?php
}

add_action( 'admin_footer', 'sp_facebookshare_set_image_javascript' );
function sp_facebookshare_set_image_javascript() {
	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
	?><script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
			jQuery('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					// $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );

          var $image = '<img src="'+attachment.url+'" height="100">';
          var data = {
              'action' : 'sp_facebookshare_set_image',
              'media_id' : attachment.id,
          };

          $.post(ajaxurl, data, function( response ) {
              alert('Added to media: ' + response);
          });

          $( '#images' ).append( $image );
					$( '#image_attachment_id' ).val( attachment.id );

          // Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
			});
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});
	</script><?php
}

add_action( 'wp_ajax_sp_facebookshare_set_image', 'sp_facebookshare_set_image' );

function sp_facebookshare_set_image()
{
    global $wpdb;

    $media_id = $_POST['media_id'];
    $post_id = $_POST['post_id'];

    $table_name = $wpdb->prefix . "facebookshare";
    $data = [
        'media_id' => $media_id
    ];
    $wpdb->insert( $table_name, $data );

    echo $media_id;
    wp_die();
}
