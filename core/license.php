<?php
//add to menu dashboard
if( is_admin() ) {
  add_action( 'admin_menu', 'wpf_lcns' );
}

function wpf_lcns() {
  // subpage
  add_submenu_page( 'edit.php?post_type=wpf-portfolio', 'Licenses', 'Licenses', 'manage_options','wpf-licencing', 'wpf_license_page' );
}
  
function wpf_license_page() {
  ?>
  <div class="wrap">
    <h2><?php _e('Addons Licenses', 'wpf'); ?></h2>
    <p>Once you install Addon(s) the license fields will be added.</p>
    <form method="post" action="options.php">
    
      <?php settings_fields('wpf_license'); ?>
      <?php settings_fields('wpf_license_status'); ?>
      
      <table class="form-table">
        <tbody>
          <?php do_action( 'wpf_license_row' ); ?>
          
        </tbody>
      </table>  
      <?php //submit_button(); ?>
      <a id="lic-subm" style="display: inline-block;margin-top: 30px;padding: 5px 20px;background: rgb(43, 162, 13);border-radius: 3px;color: #fff;cursor: pointer">Save</a>
    </form>
  <?php
}

function wpf_reg_reg() {
  // creates our settings in the options table
  register_setting('wpf_license', 'wpf_license_key', 'wpf_sant_license' );
  register_setting('wpf_license_status', 'wpf_status_license_key', 'wpf_sant_license' );
}
add_action('admin_init', 'wpf_reg_reg');

function wpf_sant_license( $new ) {
  $old = get_option( 'wpf_license_key' );
  if( $old && $old != $new ) {
    delete_option( 'wpf_license_status' ); 
  }
  return $new;
}

function wpf_reg_activate_license($name, $license, $addon_key) { 

    $api_params = array( 
      'edd_action'=> 'activate_license', 
      'license'   => $license, 
      'item_name' => urlencode( $name ),
      'url'       => home_url()
    );
    
    $response = wp_remote_post( PF_STORE_URL, array(
      'timeout'   => 15,
      'sslverify' => false,
      'body'      => $api_params
    ) ); 

    if ( is_wp_error( $response ) )
      return false;

    $license_data = json_decode( wp_remote_retrieve_body( $response ) ); 
    

    update_option( $addon_key, $license_data->license );

}

function wpf_reg_check_license($addon_key, $name) {
    $store_url = PF_STORE_URL;
    $item_name = $name;
    $license = get_option( $addon_key );
    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode( $item_name )
    );
    $response = wp_remote_get( add_query_arg( $api_params, $store_url ), array( 'timeout' => 15, 'sslverify' => false ) );
    if ( is_wp_error( $response ) )
        return false;
    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
    $addon_key = str_replace( '_key', '_status', $addon_key );
    if( $license_data->license == 'expired' ) { 
        update_option( $addon_key, 'expired' );
    } elseif( $license_data->license == 'invalid' ) {
        update_option( $addon_key, 'invalid' );
    } elseif( $license_data->license == 'inactive' ) {
        update_option( $addon_key, 'inactive' );
    }

    wpf_reg_activate_license($name, $license, $addon_key);
}

function wpf_license_check() {
  $check = false;
  $addons = array();
  $additions = get_option( 'wpf_additions_license_key' );
  $masonry = get_option( 'wpf_masonry_license_key' );
  $bundle = get_option( 'wpf_bundle_license_key' );
  $data = array(
      'additions' => array(
         'name' => 'Winning Portfolio Additions Addon',
         'key' => 'wpf_additions_license_key'
      ),
      'masonry' => array(
         'name' => 'Winning Portfolio Masonry Addon',
         'key' => 'wpf_masonry_license_key'
      ),
      'bundle' => array(
         'name' => 'Winning Portfolio Addon Bundle',
         'key' => 'wpf_bundle_license_key'
      )
  );
  if( get_option( 'wpf_additions_license_key' ) || get_option( 'wpf_masonry_license_key' ) || get_option( 'wpf_bundle_license_key' ) ) {
     $check = true;
     if( $masonry ) {
        array_push( $addons, 'masonry' );
     }
     if( $additions ) {
        array_push( $addons, 'additions' );
     }
     if( $bundle ) {
        array_push( $addons, 'bundle' ); 
     }
  }

  if( $check ) {
    foreach( $addons as $addon ) {
      $addon_key = $data[$addon]['key'];
      $name = $data[$addon]['name'];
      wpf_reg_check_license($addon_key, $name);
    }
  }
  
} 
add_action('admin_init', 'wpf_license_check');

function wpf_save_license() {
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    
    if( $data ) {
      foreach( $data as $addon ) {
        update_option( $addon['key'], $addon['val'] );
      }
    } 

    wp_die();
}
add_action('wp_ajax_wpf_save_license', 'wpf_save_license');

function wpf_license_ajax() {
  ?>
    <script type="text/javascript">
       jQuery(document).ready(function($){
          $data = {};
          $('#lic-subm').on('click', function(){
              $('.regular-text').each(function(){
                 var $this = $(this);
                 var $val = $this.val();
                 var $key = $this.attr('name');
                 $data[$key] = {
                  val: $val,
                  key: $key
                 }
              });
              $('#lic-subm').text('Saving...');
              var data = {
                'action': 'wpf_save_license',
                'data': $data
              }
              jQuery.post(ajaxurl, data, function(response) {
                location.reload(true);
              })
          });
       });
    </script>
  <?php
}
add_action( 'admin_footer', 'wpf_license_ajax' );