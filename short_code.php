<?php
if ( ! defined( 'ABSPATH' ) ) {echo 'Too much hurry will bury your goals. Too much haste will make you waste. Too quick race will cripple your pace. Be patient.<br>Kangroos are not allowed to jump here!';  exit;} // Exit if accessed directly
defined( 'PltutioCardsProject_version' ) or die( 'Part of a <i> Plutio Project Cards </i> Wordpress Plugin. Cannot run here.' );
require_once('plutio_sc_headers.php');
require_once('plutio_db.php');

if (!(class_exists('PlutioCardsProjectShortCode'))){
  class PlutioCardsProjectShortCode
  {
public function __construct() 
{
$plutioheaders = new PlutioCardsProjectSCHeaders();
add_action( 'wp_enqueue_scripts', array($plutioheaders, 'plutiocards_sc_check_jquery' ));
add_action ('wp_head', array($plutioheaders, 'plutiocards_sc_required_headers')); 
add_action ('init', array ($this,'plutiocards_sc_load'));
}
function plutiocards_sc_load() { 
 if (get_option('plutio_client_status')=='1111') {
function plutio_shortcodes_insert( $atts ){
	$a = shortcode_atts( array(
		'title' => 'no',
		'project_id' => 'no',
		'counts' => 'no'
		
	), $atts );
      ob_start();
    
  
$title = esc_attr($a['title']);
$counts = esc_attr($a['counts']);
$projectpl = esc_attr($a['project_id']);
return fetch_plutio_data($projectpl, $title, $counts);
}

add_shortcode( 'plutiocard', 'plutio_shortcodes_insert' );


function fetch_plutio_data($projectpl, $title, $counts) {
if (!(get_option('plutiocards_api')=='sandbox')) {
global $plutiocardsdb;
$plutiocardsdb = new PlutioCardsProjectDB();
  $plutio_token_url = "https://api.plutio.com/v1.9/oauth/token";
  $plutio_project_url = "https://api.plutio.com/v1.9/projects";
}
else
{
  $plutio_token_url = "https://api.sandbox.plutio.com/v1.9/oauth/token";
  $plutio_project_url = "https://api.sandbox.plutio.com/v1.9/projects";
}
$plutio_id = get_option('plutio_client_id');
$plutio_secret = get_option('plutio_client_secret');
if (get_option('plutio_result_cache')=='active') { //checks if cache is enabled from settings.

if ($plutiocardsdb->plutiocards_in_db_cache($projectpl))   {
$project_in_db = $plutiocardsdb->fetch_plutiocards_from_db($projectpl);
$project_name = $project_in_db['project_name'];	
$total_tasks = $project_in_db['total_tasks'];
$tasks_completed = $project_in_db['completed_tasks'];

if ($total_tasks==0){ $tasks_percentage = _e('No TASKS assigned!');}else {
$tasks_percentage = round($tasks_completed / $total_tasks * 100); }
?>
<!--- This is cached result for Plutio Projects -->
<div class="plutiocards_wrapper <?php echo md5($projectpl);?>"> <div class="plutiocards_progress-bar"> <?php if ($title=='yes') { ?>
<div class="plutiocards_project_title"><?php _e($project_name); ?></div>
<?php } ?>	<div id="plutiocards_remaining_progress_bar"> <span class="plutiocards_progress-bar-fill <?php echo md5($projectpl);?>" style="width:0%;text-align:center"><span id="plutiocards_bar_perc"><?php _e($tasks_percentage); ?>%</span></span>
</div> <?php
if ($counts=='yes') { ?>
<div class="plutiocards_project_counts"><?php _e($tasks_completed);?> of <?php _e($total_tasks);?><?php _e('TASKS COMPLETED.');?> </div> <?php }
?></div></div>	<script>
	
jQuery( document ).ready( function() {
jQuery('html, body').animate({scrollTop: '+=1px'}, 1);
var plutio_scroll_position = PlutiocardsJSGetScrollBarState();
 if (plutio_scroll_position.vScrollbar) {
window.addEventListener('scroll', function(e) {
if( PlutiocardsJSIsOnScreen( jQuery( '.plutiocards_wrapper .<?php echo md5($projectpl);?>' ) ) ) { 
jQuery(".plutiocards_wrapper").fadeIn("slow");
jQuery("#plutiocards_bar_perc").fadeIn();
jQuery(".plutiocards_progress-bar-fill.<?php echo md5($projectpl);?>").animate({
width: "<?php echo $tasks_percentage;?>%"
},1400, function () { jQuery(this).removeAttr('.plutiocards_progress-bar-fill');});
}});
}else {
jQuery(".plutiocards_wrapper .<?php echo md5($projectpl);?>").fadeIn("slow");
jQuery("#plutiocards_bar_perc").fadeIn();
jQuery(".plutiocards_progress-bar-fill.<?php echo md5($projectpl);?>").animate({
width: "<?php echo $tasks_percentage;?>%"
},1400, function () { jQuery(this).removeAttr('.plutiocards_progress-bar-fill');});
//}

}
});</script>
<?php
        return ob_get_clean();
} } //ends cache!

$response = wp_remote_post ( $plutio_token_url, array (
'headers' => array (
'Content-Type' => 'application/x-www-form-urlencoded'), 
'body' => array( 'client_id' => $plutio_id, 'client_secret' => $plutio_secret,'grant_type'=>'client_credentials' )
)

);
$error_in_fetching = is_wp_error($response); 
if ($error_in_fetching) {
_e('Server cannot fetch data!<br>');
return ob_get_clean();
}
$resp = json_decode($response['body']);
if (isset($resp->{'statusCode'})) {
	_e('Cannot fetch Plutio data, please check your credentials or contact for support!');

return ob_get_clean();
}
else
{
$plutiotoken = $resp->{'accessToken'};

$business_name = $resp->client->businesses[0];
$plutio_api_response = wp_remote_get ($plutio_project_url, array (
'headers' => array (
'Content-Type' => 'application/json',
'Authorization:' => ' Bearer '.$plutiotoken,
'Business' => $business_name)
));
$error_in_fetching = is_wp_error($plutio_api_response); 
if (!($error_in_fetching)) {
$resp = json_decode($plutio_api_response['body'], true);
$results = array_column($resp, 'taskCounts', '_id');

if (array_key_exists($projectpl, $results)) {
	 
$results_n = array_column($resp, 'name','_id');
$results = array_column($resp, 'taskCounts', '_id');
$project_name = $results_n[$projectpl];	
$projectstatus = $results[$projectpl];
foreach ($projectstatus as $project_status) {
$total_tasks = $project_status['all'];
$tasks_completed = $project_status['completed'];
if ($total_tasks==0){ $tasks_percentage = _e('No TASKS assigned!');}else {
$tasks_percentage = round($tasks_completed / $total_tasks * 100);}
}
?>
<div class="plutiocards_wrapper <?php echo md5($projectpl);?>"> <div class="plutiocards_progress-bar"> <?php if ($title=='yes') { ?> <div class="plutiocards_project_title"><?php _e($project_name); ?></div> <?php } ?>	<div id="plutiocards_remaining_progress_bar">
<span class="plutiocards_progress-bar-fill <?php echo md5($projectpl);?>" style="width:0%;text-align:center"><span id="plutiocards_bar_perc"><?php _e($tasks_percentage); ?>%</span></span></div>
<?php if ($counts=='yes') { ?> <div class="plutiocards_project_counts"><?php _e($tasks_completed);?> of <?php _e($total_tasks);?> <?php _e('TASKS COMPLETED');?> </div> <?php } ?> </div> </div>	<script>
jQuery( document ).ready( function() {
jQuery('html, body').animate({scrollTop: '+=1px'}, 1);
	var plutio_scroll_position = PlutiocardsJSGetScrollBarState();
 if (plutio_scroll_position.vScrollbar) {
window.addEventListener('scroll', function(e) {
if( PlutiocardsJSIsOnScreen( jQuery( '.plutiocards_wrapper .<?php echo md5($projectpl);?>' ) ) ) { 
jQuery(".plutiocards_wrapper").fadeIn("slow");
jQuery("#plutiocards_bar_perc").fadeIn();
jQuery(".plutiocards_progress-bar-fill.<?php echo md5($projectpl);?>").animate({
width: "<?php echo $tasks_percentage;?>%"
},1400, function () { jQuery(this).removeAttr('.plutiocards_progress-bar-fill');});
}});
}else {
jQuery(".plutiocards_wrapper .<?php echo md5($projectpl);?>").fadeIn("slow");
jQuery("#plutiocards_bar_perc").fadeIn();
jQuery(".plutiocards_progress-bar-fill.<?php echo md5($projectpl);?>").animate({
width: "<?php echo $tasks_percentage;?>%"
},1400, function () { jQuery(this).removeAttr('.plutiocards_progress-bar-fill');});
//}

}
});
</script>
<?php
$plutiocardsdb->add_plutiocards_in_db_cache ($projectpl, $project_name, $total_tasks, $tasks_completed); 
}
else {
	_e ('Project ID '.$projectpl. ' doesn\'t exist on Plutio. Please verify again.');
}}else { _e ('Cannot fetch data from Plutio, please wait and try again!');}
        return ob_get_clean();
    }}} 
  }
}
$plutioprojects_sc_load = new PlutioCardsProjectShortCode();
}