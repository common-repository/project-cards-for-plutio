<?php
if ( ! defined( 'ABSPATH' ) ) {echo 'Too much hurry will bury your goals. Too much haste will make you waste. Too quick race will cripple your pace. Be patient.<br>Kangroos are not allowed to jump here!'; exit; } // Exit if accessed directly
defined( 'PltutioCardsProject_version' ) or die( 'Part of a <i> Plutio Project Cards </i> Wordpress Plugin. Cannot run here.' );
require_once('plutio_validation.php'); 
if (!(class_exists('PlutioCardsProjectKeys'))){
  class PlutioCardsProjectKeys
  {
public function __construct() 
{
add_action ('init', array ($this,'plutiocards_main_keys_settings'));  
}
public function plutiocards_main_keys_settings() {
if (isset($_POST['plutio_client_id']) && $_POST['plutio_client_secret']) { 
if ( 
    ! isset( $_POST['main_plutio_settings'] ) 
    || !wp_verify_nonce($_POST['main_plutio_settings'], 'plutio_project_get') ) { die(); }
global $plutiocards_keys_validation;
global $plutiocards_alpha_validation;
$plutio_client_get_id = $plutiocards_keys_validation->plutiocards_main_keys_validation($_POST['plutio_client_id']);
$plutio_client_get_secret = $plutiocards_keys_validation->plutiocards_main_keys_validation($_POST['plutio_client_secret']);
$plutioclientid = substr($plutio_client_get_id, -6); 
$plutioclientsc = substr($plutio_client_get_secret, -6);
if ($plutioclientid=='xxxXxX'){$plutio_client_get_id = get_option('plutio_client_id'); }
if ($plutioclientsc=='xxxXxX'){$plutio_client_get_secret = get_option ('plutio_client_secret');}
global $plutio_token_url;
global $plutio_project_url;
if (get_option('plutio_result_cache')=='') {add_option('plutio_result_cache','inactive');}
if(get_option('plutiocards_api')==''){add_option('plutiocards_api','live');}
if (isset($_POST['plutiocard_result_cache'])) {
      update_option('plutio_result_cache',$plutiocards_alpha_validation->plutiocards_main_alpha_validation($_POST['plutiocard_result_cache'])); }
if (isset($_POST['plutio_api_options'])) {
      update_option('plutiocards_api',$plutiocards_alpha_validation->plutiocards_main_alpha_validation($_POST['plutio_api_options']));
    }
if (!(esc_attr(get_option('plutiocards_api')=='sandbox'))) {
  $plutio_token_url = "https://api.plutio.com/v1/oauth/token";
  $plutio_project_url = "https://api.plutio.com/v1/projects";
}
else
{
  $plutio_token_url = "https://api.sandbox.plutio.com/v1/oauth/token";
  $plutio_project_url = "https://api.sandbox.plutio.com/v1/projects";
}
$response = wp_remote_post ( $plutio_token_url, array (
'headers' => array (
'Content-Type' => 'application/x-www-form-urlencoded'), 
'body' => array( 'client_id' => $plutio_client_get_id, 'client_secret' => $plutio_client_get_secret,'grant_type'=>'client_credentials' )
)

);
  $error_in_fetching = is_wp_error($response); 
if ($error_in_fetching) {
  if (get_option('plutiocardsproject_error_show')==''){add_option('plutiocardsproject_error_show','0');}
  update_option('plutiocardsproject_error_show','0');
  update_option('plutiocards_show_last_error','If your internet connection is working right now...there may be error on server side, please try again later!');
  return ;
}
$resp = json_decode($response['body']);
if (isset($resp->{'statusCode'})) {
update_option('plutio_client_id', 'Invalid Client ID or Key Provided!');
update_option('plutio_client_secret', 'Invalid Client ID or Key Provided!');
update_option('plutio_client_status', '0000');
if (get_option('plutiocardsproject_error_show')==''){add_option('plutiocardsproject_error_show','0');}
  update_option('plutiocardsproject_error_show','0');

} else
{
update_option('plutio_client_id', $plutio_client_get_id);
update_option('plutio_client_secret', $plutio_client_get_secret);
update_option('plutio_client_status', '1111');
if (get_option('plutiocardsproject_error_show')==''){add_option('plutiocardsproject_error_show','1');}
  update_option('plutiocardsproject_error_show','1');

}}}}

global $run_pluticardsprojectkeys;
$run_pluticardsprojectkeys = new PlutioCardsProjectKeys();

}