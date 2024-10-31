<?php
if ( ! defined( 'ABSPATH' ) ) {echo 'Too much hurry will bury your goals. Too much haste will make you waste. Too quick race will cripple your pace. Be patient.<br>Kangroos are not allowed to jump here!';  exit;} // Exit if accessed directly
defined( 'PltutioCardsProject_version' ) or die( 'Part of a <i> Plutio Project Cards </i> Wordpress Plugin. Cannot run here.' );

if (!(class_exists('PlutioCardsProjectSCHeaders'))){
  class PlutioCardsProjectSCHeaders
  {
public function plutiocards_sc_required_headers() { //css style and javascript
?>
<style>
.plutiocards_wrapper {width:<?php esc_attr_e(get_option('plutio_css_wrapper_width')); ?>%; font-weight: bold;}
.plutiocards_progress-bar {width: 100%; background-color: <?php esc_attr_e(get_option('plutio_css_wrapper_bg_color')); ?>;padding: <?php echo get_option('plutio_css_wrapper_padding') ?>px; border-radius: <?php esc_attr_e(get_option('plutio_css_wrapper_border_radius')); ?>px; }
.plutiocards_progress-bar-fill { display: block; height: <?php esc_attr_e(get_option('plutio_css_bar_height')); ?>px; background-color: <?php echo get_option('plutio_css_bar_bg_color') ?>; border-radius: <?php esc_attr_e(get_option('plutio_css_bar_radius')); ?>px; -webkit-transition: none !important; transition: none !important; }
#plutiocards_bar_perc { color: <?php esc_attr_e(get_option('plutio_css_bar_font_color')); ?>; ; font-size: <?php esc_attr_e(get_option('plutio_css_bar_font_size')); ?>px; font-weight:bold; }
.plutiocards_counts { padding:3px; } 
#plutiocards_remaining_progress_bar { background-color: <?php esc_attr_e(get_option('plutio_css_remaining_progress_bar_color')); ?>; width:100.1%; }
.plutiocards_project_title
{ padding:3px; font-weight: bold; color: <?php esc_attr_e(get_option('plutio_css_project_title_color_value')); ?>; }
.plutiocards_project_counts
{ padding:3px; font-weight: bold; color:<?php esc_attr_e(get_option('plutio_css_project_counts_color_value')); ?>; }
</style>
<script>
 function PlutiocardsJSIsOnScreen(elem) {
if( elem.length == 0 ) {
return;
}var $window = jQuery(window)
var viewport_top = $window.scrollTop()
var viewport_height = $window.height()
var viewport_bottom = viewport_top + viewport_height
var $elem = jQuery(elem)
var top = $elem.offset().top
var height = $elem.height()
var bottom = top + height
return (top >= viewport_top && top < viewport_bottom) ||
(bottom > viewport_top && bottom <= viewport_bottom) ||
(height > viewport_height && top <= viewport_top && bottom >= viewport_bottom)
}
function PlutiocardsJSGetScrollBarState() {
    var result_plutio = {vScrollbar: true, hScrollbar: true};
    var origX = window.pageXOffset;
    var origY = window.pageYOffset;
    if (typeof origX != "undefined") {
        window.scrollBy(1, 1);
        result_plutio.vScrollbar = window.pageYOffset != origY;
        result_plutio.hScrollbar = window.pageXOffset != origX;
        if (!result_plutio.vScrollbar || !result_plutio.hScrollbar) {
            window.scrollBy(-1,-1);  // try scrolling the other direction just in case we were at the limit
            result_plutio.vScrollbar = result_plutio.vScrollbar | (window.pageYOffset != origY);
            result_plutio.hScrollbar = result_plutio.hScrollbar | (window.pageXOffset != origX);
        }

        window.scrollTo(origX, origY); 
    }
    return(result_plutio);
}

</script>
<?php
}
public function plutiocards_sc_check_jquery ()
{
if ( ! wp_script_is( 'jquery', 'enqueued' )) {
 wp_enqueue_script('jquery', WPINC. 'js/jquery/jquery.js', array(), null, true);
}
}

}
}