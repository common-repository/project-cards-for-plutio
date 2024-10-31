<?php
if ( ! defined( 'ABSPATH' ) ) {echo 'Too much hurry will bury your goals. Too much haste will make you waste. Too quick race will cripple your pace. Be patient.<br>Kangroos are not allowed to jump here!';  exit;} // Exit if accessed directly
defined( 'PltutioCardsProject_version' ) or die( 'Part of a <i> Plutio Project Cards </i> Wordpress Plugin. Cannot run here.' );
global $plutiocards_table;
global $wpdb;
$plutiocards_table = $wpdb->prefix. "plutiocards";
if (!(class_exists('PlutioCardsProjectDB'))){
 class PlutioCardsProjectDB
  {
public function __construct() {
add_action ('init', array ($this,'plutiocardsdb_create')); 
}
function plutiocardsdb_create() {
global $plutiocards_table;
global $wpdb;
global $charset_collate;
$charset_collate = $wpdb->get_charset_collate();
if($wpdb->get_var("SHOW TABLES LIKE '$plutiocards_table'") != $plutiocards_table) {
$sql = "CREATE TABLE " . $plutiocards_table . " (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            projectid VARCHAR(256) DEFAULT NULL,
            title VARCHAR(256) DEFAULT NULL,
            total VARCHAR(256) DEFAULT NULL, 
            completed VARCHAR(256) DEFAULT NULL,
            lastsync TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id));$charset_collate);";
require_once(ABSPATH . "wp-admin/includes/upgrade.php");
dbDelta($sql);
}
}
public function add_plutiocards_in_db_cache ($idproject, $projecttitle, $totaltasks, $completedtasks){
global $wpdb;
global $plutiocards_table;

$wpdb->query( $wpdb->prepare( 
	"
INSERT INTO $plutiocards_table
		( projectid, title, total, completed )
VALUES (%s, %s, %d, %d )
	", 
       $idproject,
       $projecttitle, 
	$totaltasks, 
	$completedtasks 
) );
}
function plutiocards_in_db_cache ($projectid)
{
	global $wpdb;
global $plutiocards_table;	
$wpdb->query(
		"
                DELETE FROM $plutiocards_table
		  WHERE lastsync < (NOW() - INTERVAL 15 MINUTE)"
		);

	$process = $wpdb->get_row( "SELECT * FROM $plutiocards_table WHERE projectid = '$projectid'");
if ( null !== $process ) {
 return true;
} else {
  return false;
}
}
function fetch_plutiocards_from_db ($projectid='idofproject') {
global $wpdb;
global $plutiocards_table;
	$process = $wpdb->get_row( "SELECT * FROM $plutiocards_table WHERE projectid = '$projectid'", ARRAY_A);
if ( null !== $process ) {
$result['project_name'] = $process ['title'];
$result['total_tasks']= $process['total'];
$result['completed_tasks']= $process['completed'];
return $result;
}else {return false;}
}
}}