<?php
if ( ! defined( 'ABSPATH' ) ) {echo 'Too much hurry will bury your goals. Too much haste will make you waste. Too quick race will cripple your pace. Be patient.<br>Kangroos are not allowed to jump here!';  exit;} // Exit if accessed directly
defined( 'PltutioCardsProject_version' ) or die( 'Part of a <i> Plutio Project Cards </i> Wordpress Plugin. Cannot run here.' );
require_once('plutio_validation.php');
if (!(class_exists('PlutioCardsProjectOptions'))){
  class PlutioCardsProjectOptions
  {
public function __construct() 
{
add_action ('init', array ($this,'plutiocards_settings_options'));  
}
public function plutiocards_settings_options() {
global $plutiocards_css_validation; //css validator, accepts only numerics
global $plutiocards_main_css_color_validation; // validation for color properties, accepts only color values.
global $plutiocards_alpha_validation; // as per our rule, option values are in alphabet. Therefore it only accepts alpha characters.
if (get_option('plutio_css_wrapper_width')=='' && get_option('plutio_css_wrapper_border_radius')=='' ) { // default options
add_option('plutio_css_wrapper_width','100'); 
add_option('plutio_css_wrapper_border_radius','1');
add_option('plutio_css_wrapper_padding','6');
add_option('plutio_css_bar_height','27');
add_option('plutio_css_bar_radius','0');
add_option('plutio_css_bar_font_size','17');
add_option('plutio_css_wrapper_bg_color','#ffffff');
add_option('plutio_css_bar_bg_color','#7b209f');
add_option('plutio_css_bar_title','no');
add_option('plutio_css_bar_counts','no');
add_option('plutio_css_bar_font_color','#ffffff');
add_option('plutio_css_bar_font_color','#ffffff');
add_option('plutio_css_remaining_progress_bar_color','#000000');
add_option('plutio_css_project_title_color_value','#440e59');
add_option('plutio_css_project_counts_color_value','#929292');
add_option('plutio_result_cache','active');
}
if (isset($_POST['plutio_bar_properties'])) {
if ($_POST['plutio_bar_properties']=='requested') {
if ( ! isset( $_POST['main_plutio_project_bar_settings'] ) || !wp_verify_nonce ($_POST['main_plutio_project_bar_settings'], 'plutio_project_bar_main') ) { wp_die(__('I know only PlutioCards call!')); } //

  
  if (isset($_POST['plutio_css_bar_font_color'])) {
      update_option('plutio_css_bar_font_color',$plutiocards_main_css_color_validation->plutiocards_main_css_color_validation($_POST['plutio_css_bar_font_color']));
    }
      if (isset($_POST['cache_plutiocard_results'])) {
      update_option('plutio_cache_results',$plutiocards_alpha_validation->plutiocards_main_alpha_validation($_POST['cache_plutiocard_results']));
    }

      if (isset($_POST['plutio_css_wrapper_width'])) {
      update_option('plutio_css_wrapper_width', $plutiocards_css_validation->plutiocards_main_css_validation($_POST['plutio_css_wrapper_width']));
    }

   if (isset($_POST['plutiocards_admin_project_title_color'])) {
      update_option('plutio_css_project_title_color_value',$plutiocards_main_css_color_validation->plutiocards_main_css_color_validation($_POST['plutiocards_admin_project_title_color']));
    }

       if (isset($_POST['plutiocards_admin_project_counts_color'])) {
      update_option('plutio_css_project_counts_color_value',$plutiocards_main_css_color_validation->plutiocards_main_css_color_validation($_POST['plutiocards_admin_project_counts_color']));
    }


      if (isset($_POST['plutiocards_admin_remaining_progress_bar_color'])) {
      update_option('plutio_css_remaining_progress_bar_color',$plutiocards_main_css_color_validation->plutiocards_main_css_color_validation($_POST['plutiocards_admin_remaining_progress_bar_color']));
    }

if (isset($_POST['plutio_css_wrapper_padding'])){
      update_option('plutio_css_wrapper_padding',$plutiocards_css_validation->plutiocards_main_css_validation($_POST['plutio_css_wrapper_padding']));
    }
if (isset($_POST['plutio_css_wrapper_border_radius'])){
      update_option('plutio_css_wrapper_border_radius',$plutiocards_css_validation->plutiocards_main_css_validation($_POST['plutio_css_wrapper_border_radius']));
    }
if (isset($_POST['plutio_css_bar_height'])){
      update_option('plutio_css_bar_height',$plutiocards_css_validation->plutiocards_main_css_validation($_POST['plutio_css_bar_height']));
    }
if (isset($_POST['plutio_css_bar_radius']))
{
      update_option('plutio_css_bar_radius',$plutiocards_css_validation->plutiocards_main_css_validation($_POST['plutio_css_bar_radius']));
    }
if (isset($_POST['plutio_css_bar_font_size'])){
      update_option('plutio_css_bar_font_size',$plutiocards_css_validation->plutiocards_main_css_validation($_POST['plutio_css_bar_font_size']));
    }
if (isset($_POST['plutio_css_wrapper_bg_color'])){
      update_option('plutio_css_wrapper_bg_color',$plutiocards_main_css_color_validation->plutiocards_main_css_color_validation ($_POST['plutio_css_wrapper_bg_color']));
    }
    if (isset($_POST['plutio_css_bar_bg_color'])){
      update_option('plutio_css_bar_bg_color',$plutiocards_main_css_color_validation->plutiocards_main_css_color_validation($_POST['plutio_css_bar_bg_color']));
    }
if (isset($_POST['plutio_css_bar_title'])){
      update_option('plutio_css_bar_title',$plutiocards_alpha_validation->plutiocards_main_alpha_validation($_POST['plutio_css_bar_title']));
    }
if (isset($_POST['plutio_css_bar_counts'])){
      update_option('plutio_css_bar_counts',$plutiocards_alpha_validation->plutiocards_main_alpha_validation($_POST['plutio_css_bar_counts']));
    }

  }}
}
} //ends main class
global $plutiocard_options;
$plutiocard_options = new PlutioCardsProjectOptions();
}