<?php
  global $wp;
  global $wpdb;
  $fbshare = $wpdb->prefix . 'facebookshare';
  $media = $wpdb->prefix . 'posts';

  // Get the name
  $name = preg_replace('/name\//', '',$wp->request);

  // Get the image
  $query = "SELECT count(*) FROM $fbshare";
  $max = $wpdb->get_var($query);

  $num = rand(1, $max);

  $query = "SELECT $fbshare.media_id, $media.guid
            FROM $fbshare
            INNER JOIN $media
            ON $fbshare.media_id=$media.ID
            AND $fbshare.ID=$num";
  $img = $wpdb->get_row($query);
  $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) ); ?>
  <?php get_header(); ?>

  <div class="wrap">
  	<div id="primary" class="content-area">
  		<main id="main" class="site-main" role="main">
        <p>Ciao <?php echo $name?></p>
        <img src="<?php echo($img->guid) ?>" alt="">
      </main>
    </div>
  </div>

<?php get_footer();
