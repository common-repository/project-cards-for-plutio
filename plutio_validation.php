<?php
if ( ! defined( 'ABSPATH' ) ) {echo 'Too much hurry will bury your goals. Too much haste will make you waste. Too quick race will cripple your pace. Be patient.<br>Kangroos are not allowed to jump here!';  exit;} // Exit if accessed directly
defined( 'PltutioCardsProject_version' ) or die( 'Part of a <i> Plutio Project Cards </i> Wordpress Plugin. Cannot run here.' );
global $plutiocards_show_last_error;

if (!(class_exists('PlutioCardsProjectValidation'))){
  class PlutioCardsProjectValidation
  {
public function plutiocards_main_keys_validation ($keys)
{
if (get_option('plutiocards_show_last_error')==''){add_option('plutiocards_show_last_error','');}
$key =  preg_match("/^([a-zA-Z0-9-_#])+$/i", $keys, $matches, PREG_OFFSET_CAPTURE);
if (!($key)){update_option('plutiocards_show_last_error','Provided Keys are not accroding to API standards, please check again.'); return; }
if ($key) {
$keys = $matches[0][0];
if (strlen($keys) > 50 || strlen($keys) < 5 ) {update_option('plutiocards_show_last_error','Maximum or Minimum legnths are not allowed, please check again.');return; }
update_option('plutiocards_show_last_error','');
return $keys;
}
}

public function plutiocards_main_css_validation ($values)
{
if (!(is_numeric($values) )) {update_option('plutiocards_show_last_error','Incorrect values for css.'); return; } 
return $values;
}

public function plutiocards_main_css_color_validation ($colorvalues)
{ //html 5 input color value
$values = preg_match('/^#([a-f0-9]{3}){1,2}\b$/i', $colorvalues, $matches, PREG_OFFSET_CAPTURE);
if (!($values)) {update_option('plutiocards_show_last_error','Incorrect values for css color property.'); }
return $colorvalues;
}
public function plutiocards_main_alpha_validation ($alphavalues)
{
	if (!(ctype_alpha($alphavalues))) {update_option('plutiocards_show_last_error','Only Alpha values are welcomed for options update.');return;}
	return $alphavalues;
}
}

global $plutiocards_keys_validation;
global $plutiocards_css_validation;
global $plutiocards_main_css_color_validation;
global $plutiocards_alpha_validation;
$plutiocards_keys_validation = new PlutioCardsProjectValidation();
$plutiocards_css_validation = new PlutioCardsProjectValidation();
$plutiocards_main_css_color_validation =  new PlutioCardsProjectValidation();
$plutiocards_alpha_validation = new PlutioCardsProjectValidation();
}
