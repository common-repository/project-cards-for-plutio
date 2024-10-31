<?php
/**
 * Plugin Name: Plutio Project Cards
 * Plugin URI:  https://plutioplugins.co.uk/
 * Description: Embed a Plutio project progress bar in any WordPress page, with live and automatic updating as you mark tasks complete in  Plutio. Perfect for client dashboards or to display progress publicly.
 * Version:     1.0.3
 * Author:      Ben Henderson
 * Author URI:  https://reposecreative.co.uk/
 * Text Domain: plutio-cards-project
 *
 *  Copyright (C) 2019 Repositive Creative.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ** Copyright (C) 2019 Repositive Creative.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
██████╗ ███████╗██████╗  ██████╗ ███████╗███████╗ ██████╗██████╗ ███████╗ █████╗ ████████╗██╗██╗   ██╗███████╗
██╔══██╗██╔════╝██╔══██╗██╔═══██╗██╔════╝██╔════╝██╔════╝██╔══██╗██╔════╝██╔══██╗╚══██╔══╝██║██║   ██║██╔════╝
██████╔╝█████╗  ██████╔╝██║   ██║███████╗█████╗  ██║     ██████╔╝█████╗  ███████║   ██║   ██║██║   ██║█████╗
██╔══██╗██╔══╝  ██╔═══╝ ██║   ██║╚════██║██╔══╝  ██║     ██╔══██╗██╔══╝  ██╔══██║   ██║   ██║╚██╗ ██╔╝██╔══╝
██║  ██║███████╗██║     ╚██████╔╝███████║███████╗╚██████╗██║  ██║███████╗██║  ██║   ██║   ██║ ╚████╔╝ ███████╗
╚═╝  ╚═╝╚══════╝╚═╝      ╚═════╝ ╚══════╝╚══════╝ ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝   ╚═╝   ╚═╝  ╚═══╝  ╚══════╝

 */
defined( 'ABSPATH' ) or die( 'Must run within wordpress system.' );
define ("PltutioCardsProject_version","1.0.0");
if (!(class_exists('PlutioCardsProject'))){
	class PlutioCardsProject
	{
		public function __construct()
		{
require_once('project_id.php'); //confirms tokens.
require_once('plutio_db.php');  // for caching
require_once('short_code.php'); // base of the plugin
require_once('plutio_options.php'); // default & saving options
add_action( 'admin_menu',  array($this,'plutio_settings' ));

register_deactivation_hook( __FILE__, array( $this, 'plutiocards_deactivate_plugin' ) );

    } // construct ends
public function plutio_settings() {
	// $page_title, $menu_title, $capability, $menu_slug, $function
	add_options_page( 'Settings for Plutio Project Cards', 'Plutio Project Cards', 'manage_options', 'pluto_cards_settings',array($this, 'pluto_settings_function' ));
}
public function plutiocards_deactivate_plugin()
{
delete_option('plutio_result_cache');
delete_option('plutio_client_id');
delete_option('plutio_client_secret');
delete_option('plutio_css_wrapper_width');
delete_option('plutio_css_wrapper_border_radius');
delete_option('plutio_css_wrapper_padding');
delete_option('plutio_css_bar_height');
delete_option('plutio_css_bar_radius');
delete_option('plutio_css_bar_font_size');
delete_option('plutio_css_wrapper_bg_color');
delete_option('plutio_css_bar_bg_color');
delete_option('plutio_css_bar_title');
delete_option('plutio_css_bar_counts');
delete_option('plutio_css_bar_font_color');
delete_option('plutio_css_bar_font_color');
delete_option('plutio_css_remaining_progress_bar_color');
delete_option('plutio_css_project_title_color_value');
delete_option('plutio_css_project_counts_color_value');
delete_option('plutio_client_status');
}

public function pluto_settings_function() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	?>
	<div>


	<h2><?php _e('Plutio Project Cards Settings','plutio-project-cards');?></h2>
<?php


	?>

<?php if (get_option('plutio_client_id')=='') {
	add_option( 'plutio_client_id', 'Please provide client id');
}
if (get_option('plutio_client_secret')=='') {
	add_option( 'plutio_client_secret', 'Please provide client secret key.');
}
if (get_option('plutio_client_status')=='') {
	add_option( 'plutio_client_status', '0000');
}
?>
<form method="post">
  <?php wp_nonce_field( 'plutio_project_get', 'main_plutio_settings' ); // action, field ?>
	<table>
<?php
if (get_option('plutio_client_status')=='1111'){ //masking
  $plutioidshow = substr(get_option('plutio_client_id') ,0, 5);
  $plutioidshow = $plutioidshow.'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxXxX';
  $plutiosecshow = substr(get_option('plutio_client_secret'),0, 5);
$plutiosecshow = $plutiosecshow.'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxXxX';
}
else {
  $plutioidshow = get_option('plutio_client_id');
  $plutiosecshow = get_option('plutio_client_secret');
}
?>
  <tr valign="top">
  <th scope="row"><label for="plutio_option_client"><?php _e('Please Enter Client ID:','plutio-project-cards');?> </label></th>
  <td><input type="text" id="plutio_client_opt_id" name="plutio_client_id" value="<?php echo esc_attr($plutioidshow); ?>" size="60" required placeholder="<?php echo esc_attr('Type client ID here.');?>"/></td>
   </tr>
  <tr valign="top">
  <th scope="row"><label for="plutio_option_client"><?php _e('Please Enter Secret ID:');?> </label></th>
<td><input type="text" id="plutio_client_opt_sec" name="plutio_client_secret" value="<?php esc_attr_e($plutiosecshow); ?>" size="60" required placeholder="<?php esc_attr_e("Type client secret key here.");?>" /><tr></td>
<?php if (get_option('plutiocards_api')=='sandbox') { ?>
  <th scope="row"><label for="plutio_api_options"><?php _e('Live');?> </label></th><td><input type="checkbox" name="plutio_api_options" value=<?php esc_attr_e("live");?>><span><?php _e('API Settings are on plutio sandbox api, check and update for plutio live api.');?></span></td>

<?php }else { ?> <th scope="row"><label for="plutio_api_options"><?php _e('Sandbox');?> </label></th><td><input type="checkbox" name="plutio_api_options" value="<?php esc_attr_e('sandbox');?>"><span><?php _e('API Settings are on live api, check and update for plutio sandbox api credentials.');?></span></td><?php } ?>
   </tr>
   </tr>

<script>
  jQuery( document ).ready(function() {
  jQuery('#plutio_client_opt_id, #plutio_client_opt_sec').on('click focusin', function() {
    this.value = '';
});
jQuery('#plutio_client_opt_id').focusout(function()
{  if (this.value==''){

   this.value = "<?php esc_attr_e($plutioidshow);?>";
 }
});
jQuery('#plutio_client_opt_sec').focusout(function()
{
  if (this.value==''){
    this.value = "<?php esc_attr_e($plutiosecshow); ?>";
  }
});
});
</script>
<?php if (get_option('plutio_client_status')=='1111'){
 $plutio_client_status = 'ready';
 ?>
 <tr valign="top">
<th scope="row"><label for="plutio_option_client"><?php _e('Use this Shortcode:');?> </label></th>
<td><span class="plutio_client_short"><?php _e('[plutiocard project_id=YourProjectID counts=no title=no] <i>use title=yes or counts=yes if you want to show project name or task counts');?></i></span></td>
   </tr>
   <?php

if (get_option('plutio_result_cache')=='active') {
   ?>

    <tr valign="top">
<th scope="row"><label for="cache_plutiocards"><?php _e('Disable Cache results for 15 minutes');?> </label></th>

<td><input type="checkbox" name="plutiocard_result_cache" id="cache_plutiocards" value="<?php esc_attr_e('inactive');?>">
   <span class="enable_disable_cache"><i><?php _e('Cache is enabled, check mark and update to disable it.');?> </i>
   </span>
   </td>
 </tr>
<?php
}
else
{
  if (!(get_option('plutio_result_cache')=='active')) {
   ?>

    <tr valign="top">
<th scope="row"><label for="cache_plutiocards"><?php _e('Enable Cache results for 15 minutes');?> </label></th>

<td><input type="checkbox" name="plutiocard_result_cache" id="cache_plutiocards" value="<?php esc_attr_e('active');?>">
   <span class="enable_disable_cache"><i><?php _e('Cache is disabled, check mark and update to enable it.');?> </i>
   </span>
   </td>
 </tr>
<?php

}
}
}
?>
  </table>
  <?php if (get_option('plutio_client_status')=='1111')   {
  	$option_save_or_update = 'Update';
  }
  else
  {
  	$option_save_or_update = 'Save';
  }
submit_button(__($option_save_or_update)); ?>
        </form>
<?php
if (!(get_option('plutio_result_cache')=='active')) {
$plutio_result_cache_msg = esc_attr('Cache is DISABLED!'); }
else {
  $plutio_result_cache_msg = esc_attr('Cache is ENABLED!');
}
if (!(get_option('plutiocards_api')=='sandbox')) {
$plutio_api_msg = esc_attr('API settings are on live api.'); }
else {
  $plutio_api_msg = esc_attr('API settings are on sandbox api.');
}

if (isset($_POST['main_plutio_settings'] )
    &&  wp_verify_nonce($_POST['main_plutio_settings'], 'plutio_project_get') ) {

if (get_option('plutiocardsproject_error_show')=='0') {
  global $plutiocards_show_last_error;
  $plutiocards_show_last_error = get_option('plutiocards_show_last_error');


  ?>

<div class="error notice is-dismissible">
   <?php _e( 'Oops! thats not an expected result!<br>'.$plutiocards_show_last_error); ?></p>
</div>

        <?php
}
if (get_option('plutiocardsproject_error_show')=='1') {
  ?>
<div class="updated notice is-dismissible">
   <?php _e( 'API Credentials are ready!<br>' .$plutio_result_cache_msg.'<br>'.$plutio_api_msg); ?></p>
</div>
<?php
}

}
if (isset($plutio_client_status) && ($plutio_client_status=='ready')) {
if (isset($_POST['plutio_bar_properties'])) {
if ($_POST['plutio_bar_properties']=='requested') {
if (isset( $_POST['main_plutio_project_bar_settings'] ) && wp_verify_nonce ($_POST['main_plutio_project_bar_settings'], 'plutio_project_bar_main') ) {
?>
<div class="updated notice is-dismissible">
   <?php _e( 'Progress Bar Settings Changed!'); ?></p>
</div>
<?php

} } }  //


      	?>

<div id="plutiocards_admin_barsettings"><?php _e('Set Progress Bar!');?> </div>

	<style>
    #plutiocards_admin_barsettings {

    text-align: center; font-weight: bold; font-size:1.2em;
    padding: 2%;
  }
			.plutiocards_admin_wrapper {
				width:<?php esc_attr_e(get_option('plutio_css_wrapper_width')); ?>%;
			}
			.plutiocards_admin_progress-bar {
				width: 100%;
				background-color: <?php esc_attr_e(get_option('plutio_css_wrapper_bg_color')); ?>;
				padding: <?php esc_attr_e(get_option('plutio_css_wrapper_padding')); ?>px;
				border-radius: <?php esc_attr_e(get_option('plutio_css_wrapper_border_radius')); ?>px;
				box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
			}

			.plutiocards_admin_progress-bar-fill {
				display: block;
				height: <?php esc_attr_e(get_option('plutio_css_bar_height')); ?>px;
				background-color: <?php esc_attr_e(get_option('plutio_css_bar_bg_color')); ?>;
				border-radius:<?php esc_attr_e(get_option('plutio_css_bar_radius')); ?>px;
				transition: width 500ms ease-in-out;
}
.plutiocards_admin_bar_perc {
  color: <?php esc_attr_e(get_option('plutio_css_bar_font_color')); ?>; ;
  font-size: <?php esc_attr_e(get_option('plutio_css_bar_font_size')); ?>px;
font-weight:bold;
padding: 2px;
}
#plutiocards_admin_remaining_progress_bar {
  background-color:<?php esc_attr_e(get_option('plutio_css_remaining_progress_bar_color')); ?>;

width:100.1%;

}
.plutiocards_admin_project_title
{
padding:3px;
font-weight: bold;
color: <?php esc_attr_e(get_option('plutio_css_project_title_color_value')); ?>;
}
.plutiocards_admin_project_counts
{
padding:3px;
font-weight: bold;
color:<?php esc_attr_e(get_option('plutio_css_project_counts_color_value')); ?>;


}
		</style>
<div id="plutiocards_main_fold" style="width:98%">
<div class="plutiocards_admin_wrapper">
<div class="plutiocards_admin_progress-bar">

<div class="plutiocards_admin_project_title" ><?php _e('My PLUTIO PROJECT');?> </div>
	<input type="hidden" name="plutio_css_project_title_color_value" value="<?php esc_attr_e(get_option('plutio_css_project_title_color_value'));?>" id="plutio_project_title_value">
  <div id="plutiocards_admin_remaining_progress_bar">
  			<span class="plutiocards_admin_progress-bar-fill" style="width: 63%;text-align:center;"><span class="plutiocards_admin_bar_perc" id="plutiocards_admin_bar_perc" name="plutiocards_admin_bar_perc"><?php esc_attr_e(get_option('plutio_css_bar_font_size')); ?>px</span></span>
			</div>
      <div class="plutiocards_admin_project_counts"><?php _e('8 of 11 TASKS COMPLETED');?> </div>
			<input type="hidden" name="plutio_css_project_counts_color_value" value="<?php esc_attr_e(get_option('plutio_css_project_counts_color_value'));?>" id="plutio_project_counts_color_value">
      </div>
		</div>

<form method="post">
<?php wp_nonce_field('plutio_project_bar_main', 'main_plutio_project_bar_settings' ); // action, field ?>
<label for="plutiocards_admin_wrapper_width"><?php _e('Wrapper width');?><span class="plutiocards_admin_wrapper_wid"> <?php esc_attr_e(get_option('plutio_css_wrapper_width'));?>% </span></label>
  <input type="range" id="plutiocards_admin_wrapper_width" value="<?php esc_attr_e(get_option('plutio_css_wrapper_width'));?>" min="20" max="100" step="1" style="width: 100%;overflow: hidden;" name="plutio_css_wrapper_width"/>
  <label for="plutiocards_admin_wrapper_border_radius"><?php _e('Wrapper Border Radius');?><span class="plutiocards_admin_wrapper_border_radius"> ( <?php esc_attr_e(get_option('plutio_css_wrapper_border_radius')); ?>px )</span></label>
  <input type="range" id="plutiocards_admin_wrapper_border_radius" value="<?php esc_attr_e(get_option('plutio_css_wrapper_border_radius')); ?>" min="1" max="50" step="1" style="width: 100%" name="plutio_css_wrapper_border_radius"/>
  <label for="plutiocards_admin_wrapper_padding"><?php _e('Progress Bar Padding');?><span class="plutiocards_admin_wrapper_padding"> ( <?php echo get_option('plutio_css_wrapper_padding') ?>px ) </span></label>
  <input type="range" id="plutiocards_admin_wrapper_padding" value="<?php esc_attr_e(get_option('plutio_css_wrapper_padding')); ?>" min="1" max="200" step="1" style="width: 100%" name="plutio_css_wrapper_padding"/>
  <label for="plutiocards_admin_bar_height"><?php _e('Inner Bar height');?> <span class="plutiocards_admin_bar_height"> ( <?php esc_attr_e(get_option('plutio_css_bar_height')); ?>px ) </span></label>
  <input type="range" id="plutiocards_admin_bar_height" style="width: 100%" name="plutio_css_bar_height" value="<?php esc_attr_e(get_option('plutio_css_bar_height')); ?>" />
  <label for="plutiocards_admin_bar_radius"><?php _e('Inner Bar radius');?><span class="plutiocards_admin_bar_radius"> (<?php esc_attr_e(get_option('plutio_css_bar_radius')); ?>px )</span></label>
  <input type="range" id="plutiocards_admin_bar_radius" style="width: 100%" name="plutio_css_bar_radius" value="<?php esc_attr_e(get_option('plutio_css_bar_radius')); ?>" />
  <label for="plutiocards_admin_bar_font_size"><?php _e('Bar Font Size');?><span class="plutiocards_admin_bar_font_size"> ( <?php esc_attr_e(get_option('plutio_css_bar_font_size')); ?>px ) </span></label>
  <input type="range" id="plutiocards_admin_bar_font_size" style="width: 100%" value="<?php esc_attr_e(get_option('plutio_css_bar_font_size')); ?>" min="12" max="100" step="1" name="plutio_css_bar_font_size"/>

  <label for="plutiocards_admin_wrapper_bg_color"><?php _e('Bar Background');?><span class="plutiocards_admin_wrapper_bg_color"> # </span></label>
  <input type="color" id="plutiocards_admin_wrapper_bg_color" name="plutio_css_wrapper_bg_color" value="<?php esc_attr_e(get_option('plutio_css_wrapper_bg_color')); ?>"  />
  <label for="plutiocards_admin_bar_bg_color"><?php _e('Filled Background');?> <span class="plutiocards_admin_bar_bg_color"> # </span></label>
  <input type="color" id="plutiocards_admin_bar_bg_color" name="plutio_css_bar_bg_color" value ="<?php esc_attr_e(get_option('plutio_css_bar_bg_color')); ?>" />
<label for="plutiocards_admin_bar_bg_color"><?php _e('Remaining Progress Bar');?><span class="plutiocards_admin_remaining_progress_bar_color"> # </span></label>
  <input type="color" id="plutiocards_admin_remaining_progress_bar_color" name="plutiocards_admin_remaining_progress_bar_color" value ="<?php esc_attr_e(get_option('plutio_css_remaining_progress_bar_color')); ?>" />
<br/>
  <label for="plutiocards_admin_project_title_color"><?php _e('Project Title');?><span class="plutiocards_admin_project_title_color"> # </span></label>
<input type="color" id="plutiocards_admin_project_title_color" name="plutiocards_admin_project_title_color" value ="<?php esc_attr_e(get_option('plutio_css_project_title_color_value'));?>" />

  <label for="plutiocards_admin_project_counts_color"><?php _e('Project Counts');?><span class="plutiocards_admin_project_counts_color"> # </span></label>
  <input type="color" id="plutiocards_admin_project_counts_color" name="plutiocards_admin_project_counts_color" value ="<?php esc_attr_e(get_option('plutio_css_project_counts_color_value')); ?>" />


  <label for="plutiocards_admin_bar_bg_color"><?php _e('Percentage font color');?><span class="plutiocards_admin_bar_bg_color"> # </span></label>
  <input type="color" id="plutiocards_admin_bar_font_color" name="plutio_css_bar_font_color" value ="<?php esc_attr_e(get_option('plutio_css_bar_font_color')); ?>" />
  <br/>
  <label for="plutiocards_admin_bar_title"><span class="plutiocards_admin_bar_title"><?php _e('Show Project name:');?> </span></label>
  <input type="checkbox" id="plutiocards_admin_bar_title" name="plutio_css_bar_title" value="<?php esc_attr_e('no');?>" />
  <label for="plutiocards_admin_bar_counts"><span class="plutiocards_admin_bar_counts"><?php _e('Show Counts:');?> </span></label>
  <input type="checkbox" id="plutiocards_admin_bar_counts" name="plutio_css_bar_counts" value="<?php esc_attr_e('no');?>"/>
<input type="hidden" name="plutio_bar_properties" value="requested">
<?php submit_button(__('Save Bar Settings')); ?>

</form>
</div>
<script>
jQuery(document).on('change', '#plutiocards_admin_wrapper_width', function() {
    jQuery('.plutiocards_admin_wrapper').css("width",jQuery(this).val()+"%" );
    jQuery('.plutiocards_admin_wrapper_wid').html ("( " + jQuery (this).val()+"% )");
});
jQuery(document).on('change', '#plutiocards_admin_wrapper_border_radius', function() {
    jQuery('.plutiocards_admin_progress-bar').css("border-radius",jQuery(this).val()+"px" );
    jQuery('.plutiocards_admin_wrapper_border_radius').html (" ( " + jQuery (this).val()+"px )");
});
jQuery(document).on('change', '#plutiocards_admin_bar_bg_color', function() {
    jQuery('.plutiocards_admin_progress-bar-fill').css("background-color",jQuery(this).val() );
    jQuery('.plutiocards_admin_bar_bg_color').html (jQuery (this).val());
});
jQuery(document).on('change', '#plutiocards_admin_bar_font_size', function() {
    jQuery('.plutiocards_admin_bar_perc').css("font-size",jQuery(this).val()+"px" );
    jQuery('.plutiocards_admin_bar_perc').html ( jQuery (this).val()+"px ");
    jQuery('.plutiocards_admin_bar_font_size').html (" ( " + jQuery (this).val()+"px )");
});


jQuery(document).on('change', '#plutiocards_admin_wrapper_bg_color', function() {
    jQuery('.plutiocards_admin_progress-bar').css("background-color",jQuery(this).val() );
    jQuery('.plutiocards_admin_wrapper_bg_color').html (jQuery (this).val());
});
jQuery(document).on('change', '#plutiocards_admin_project_counts_color', function() {
    jQuery('.plutiocards_admin_project_counts').css("color",jQuery(this).val() );
    jQuery('.plutiocards_admin_project_counts_color').html (jQuery (this).val());
    jQuery('#plutio_project_counts_color_value').html (jQuery (this).val());
});

jQuery(document).on('change', '#plutiocards_admin_wrapper_padding', function() {
    jQuery('.plutiocards_admin_progress-bar').css("padding",jQuery(this).val()+"px" );
    jQuery('.plutiocards_admin_wrapper_padding').html (" ( " + jQuery (this).val()+ "px )");

});
jQuery(document).on('change', '#plutiocards_admin_bar_height', function() {
    jQuery('.plutiocards_admin_progress-bar-fill').css("height",jQuery(this).val()+"px" );
    jQuery('.plutiocards_admin_bar_height').html(" ( " +jQuery (this).val()+"px )");
});

jQuery(document).on('change', '#plutiocards_admin_bar_radius', function() {
     jQuery('.plutiocards_admin_progress-bar-fill').css("border-radius",jQuery(this).val()+"px" );
    jQuery('.plutiocards_admin_bar_radius').html (" ( "+ jQuery (this).val()+"px )");
});
jQuery(document).on('change', '#plutiocards_admin_bar_font_color', function() {
     jQuery('.plutiocards_admin_bar_perc').css("color",jQuery(this).val() );
    jQuery('#plutiocards_admin_bar_font_color').html (jQuery (this).val());
  });
jQuery(document).on('change', '#plutiocards_admin_remaining_progress_bar_color', function() {
     jQuery('#plutiocards_admin_remaining_progress_bar').css("background-color",jQuery(this).val() );
    jQuery('.plutiocards_admin_remaining_progress_bar_color').html (jQuery (this).val());
  });

jQuery(document).on('change', '#plutiocards_admin_project_title_color', function() {
     jQuery('.plutiocards_admin_project_title').css("color",jQuery(this).val() );
    jQuery('.plutiocards_admin_project_title_color').html (jQuery (this).val());
  });


jQuery("#plutiocards_admin_bar_title").prop("checked",true);
jQuery('#plutiocards_admin_bar_title').click(function() {
    jQuery(".plutiocards_admin_project_title").toggle(this.checked);
});
jQuery("#plutiocards_admin_bar_counts").prop("checked",true);
jQuery('#plutiocards_admin_bar_counts').click(function() {
    jQuery(".plutiocards_admin_project_counts").toggle(this.checked);
});

jQuery('#plutiocards_admin_bar_counts').click(function() {
if (jQuery('input#plutiocards_admin_bar_counts').is(':checked') ) {
jQuery('#plutiocards_admin_bar_counts').val("yes");}

});

jQuery('#plutiocards_admin_bar_title').click(function() {
if (jQuery('input#plutiocards_admin_bar_title').is(':checked') ) {
jQuery('#plutiocards_admin_bar_title').val("yes");}

});
jQuery('#plutio_client_opt_id, #plutio_client_opt_sec').on('click focusin', function() {
    this.value = '';
});
jQuery('#plutio_client_opt_id').focusout(function()
{  if (this.value==''){

   this.value = "<?php esc_attr_e($plutioidshow); ?>";
 }
});
jQuery('#plutio_client_opt_sec').focusout(function()
{
  if (this.value==''){
    this.value = "<?php esc_attr_e($plutiosecshow); ?>";
  }
});

</script>
<?php
}
}

	} // class PlutioCardsProject ends
}
global $run_pluticardsproject;
$run_pluticardsproject = new PlutioCardsProject();
