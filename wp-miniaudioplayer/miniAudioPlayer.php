<?php
/*___________________________________________________________________________________________________________________________________________________
 _ jquery.mb.components                                                                                                                             _
 _                                                                                                                                                  _
 _ file: miniAudioPlayer.php                                                                                                                        _
 _ last modified: 4/3/21 1:05 AM                                                                                                                    _
 _                                                                                                                                                  _
 _ Open Lab s.r.l., Florence - Italy                                                                                                                _
 _                                                                                                                                                  _
 _ email: matteo@open-lab.com                                                                                                                       _
 _ site: http://pupunzi.com                                                                                                                         _
 _       http://open-lab.com                                                                                                                        _
 _ blog: http://pupunzi.open-lab.com                                                                                                                _
 _ Q&A:  http://jquery.pupunzi.com                                                                                                                  _
 _                                                                                                                                                  _
 _ Licences: MIT, GPL                                                                                                                               _
 _    http://www.opensource.org/licenses/mit-license.php                                                                                            _
 _    http://www.gnu.org/licenses/gpl.html                                                                                                          _
 _                                                                                                                                                  _
 _ Copyright (c) 2001-2021. Matteo Bicocchi (Pupunzi);                                                                                              _
 ___________________________________________________________________________________________________________________________________________________*/

/*___________________________________________________________________________________________________________________________________________________
Plugin Name: mb.miniAudioPlayer
Plugin URI: http://wordpress.org/extend/plugins/wp-miniaudioplayer/
Description: Transform your mp3 audio file link into a nice, small light player. ! IMPORTANT - if you customized the skin for the previous version you need to regenerate it from <a href="http://pupunzi.com/mb.components/mb.miniAudioPlayer/demo/skinMaker.html" target="_blank">here</a>.
Author: Pupunzi (Matteo Bicocchi)
Version: 1.9.7
Author URI: http://pupunzi.com
Text Domain: wp-miniaudioplayer
*/

define("MINIAUDIOPLAYER_VERSION", "1.9.7");
register_activation_hook(__FILE__, 'miniAudioPlayer_install');

$isGutenberg = (version_compare($GLOBALS['wp_version'], '5.0-beta', '>') ? 'true' : 'false');

function miniAudioPlayer_install()
{
// add and update our default options upon activation
    update_option('miniAudioPlayer_version', MINIAUDIOPLAYER_VERSION);
    add_option('miniAudioPlayer_donate', 'false');
    add_option('miniAudioPlayer_getMetadata', 'false');
    add_option('miniAudioPlayer_width', '350');
    add_option('miniAudioPlayer_skin', 'black');
    add_option('miniAudioPlayer_animate', 'true');
    add_option('miniAudioPlayer_volume', '.5');
    add_option('miniAudioPlayer_autoplay', 'false');
    add_option('miniAudioPlayer_showVolumeLevel', 'true');
    add_option('miniAudioPlayer_showTime', 'true');
    add_option('miniAudioPlayer_allowMute', 'true');
    add_option('miniAudioPlayer_showRew', 'true');
    add_option('miniAudioPlayer_excluded', 'map_excluded');
    add_option('miniAudioPlayer_download', 'false');
    add_option('miniAudioPlayer_use_html5_download', 'false');
    add_option('miniAudioPlayer_download_security', 'false');
    add_option('miniAudioPlayer_customizer', 'true');

    add_option('miniAudioPlayer_custom_skin_name', 'mySkin');
    add_option('miniAudioPlayer_add_gradient', 'false');
    add_option('miniAudioPlayer_active_all', 'true');
    add_option('miniAudioPlayer_replaceDefault', 'false');
    add_option('miniAudioPlayer_replaceDefault_show_title', 'false');

    add_option('miniAudioPlayer_custom_skin_css', "

/* DO NOT REMOVE OR MODIFY */
/*{'skinName': 'mySkin', 'borderRadius': 5, 'main': 'rgb(255, 217, 102)', 'secondary': 'rgb(68, 68, 68)', 'playerPadding': 0}*/
/* END - DO NOT REMOVE OR MODIFY */
/*++++++++++++++++++++++++++++++++++++++++++++++++++
Copyright (c) 2001-2014. Matteo Bicocchi (Pupunzi);
http://pupunzi.com/mb.components/mb.miniAudioPlayer/demo/skinMaker.html

Skin name: mySkin
borderRadius: 5
background: rgb(255, 217, 102)
icons: rgb(68, 68, 68)
border: rgb(55, 55, 55)
borderLeft: rgb(255, 230, 153)
borderRight: rgb(255, 204, 51)
mute: rgba(68, 68, 68, 0.4)
download: rgba(255, 217, 102, 0.4)
downloadHover: rgb(255, 217, 102)
++++++++++++++++++++++++++++++++++++++++++++++++++*/

/* Older browser (IE8) - not supporting rgba() */
.mbMiniPlayer.mySkin .playerTable span{background-color:#ffd966}
.mbMiniPlayer.mySkin .playerTable span.map_play{border-left:1px solid #ffd966;}
.mbMiniPlayer.mySkin .playerTable span.map_volume{border-right:1px solid #ffd966;}
.mbMiniPlayer.mySkin .playerTable span.map_volume.mute{color: #444444;}
.mbMiniPlayer.mySkin .map_download{color: #444444;}
.mbMiniPlayer.mySkin .map_download:hover{color: #444444;}
.mbMiniPlayer.mySkin .playerTable span{color: #444444;}
.mbMiniPlayer.mySkin .playerTable {border: 1px solid #444444 !important;}

/*++++++++++++++++++++++++++++++++++++++++++++++++*/

.mbMiniPlayer.mySkin .playerTable{background-color:transparent; border-radius:5px !important;}
.mbMiniPlayer.mySkin .playerTable span{background-color:rgb(255, 217, 102); padding:3px !important; font-size: 20px;}
.mbMiniPlayer.mySkin .playerTable span.map_time{ font-size: 12px !important; width: 50px !important}
.mbMiniPlayer.mySkin .playerTable span.map_title{ padding:4px !important}
.mbMiniPlayer.mySkin .playerTable span.map_play{border-left:1px solid rgb(255, 204, 51); border-radius:0 4px 4px 0 !important;}
.mbMiniPlayer.mySkin .playerTable span.map_volume{padding-left:6px !important}
.mbMiniPlayer.mySkin .playerTable span.map_volume{border-right:1px solid rgb(255, 230, 153); border-radius:4px 0 0 4px !important;}
.mbMiniPlayer.mySkin .playerTable span.map_volume.mute{color: rgba(68, 68, 68, 0.4);}
.mbMiniPlayer.mySkin .map_download{color: rgba(255, 217, 102, 0.4);}
.mbMiniPlayer.mySkin .map_download:hover{color: rgb(255, 217, 102);}
.mbMiniPlayer.mySkin .playerTable span{color: rgb(68, 68, 68);text-shadow: none!important;}
.mbMiniPlayer.mySkin .playerTable span{color: rgb(68, 68, 68);}
.mbMiniPlayer.mySkin .playerTable {border: 1px solid rgb(55, 55, 55) !important;}
.mbMiniPlayer.mySkin .playerTable span.map_title{color: #000; text-shadow:none!important}
.mbMiniPlayer.mySkin .playerTable .jp-load-bar{background-color:rgba(255, 217, 102, 0.3);}
.mbMiniPlayer.mySkin .playerTable .jp-play-bar{background-color:#ffd966;}
.mbMiniPlayer.mySkin .playerTable span.map_volumeLevel a{background-color:rgb(94, 94, 94); height:80%!important }
.mbMiniPlayer.mySkin .playerTable span.map_volumeLevel a.sel{background-color:#444444;}
.mbMiniPlayer.mySkin  span.map_download{font-size:50px !important;}
/* Wordpress playlist select */
.map_pl_container .pl_item.sel{background-color:#ffd966 !important; color: #444444}
/*++++++++++++++++++++++++++++++++++++++++++++++++*/
");

}

$miniAudioPlayer_donate = get_option('miniAudioPlayer_donate');
$miniAudioPlayer_version = get_option('miniAudioPlayer_version');
$miniAudioPlayer_width = get_option('miniAudioPlayer_width');
$miniAudioPlayer_getMetadata = get_option('miniAudioPlayer_getMetadata');
$miniAudioPlayer_skin = get_option('miniAudioPlayer_skin');
$miniAudioPlayer_animate = get_option('miniAudioPlayer_animate');
$miniAudioPlayer_volume = get_option('miniAudioPlayer_volume');
$miniAudioPlayer_autoplay = get_option('miniAudioPlayer_autoplay');
$miniAudioPlayer_showVolumeLevel = get_option('miniAudioPlayer_showVolumeLevel');
$miniAudioPlayer_allowMute = get_option('miniAudioPlayer_allowMute');
$miniAudioPlayer_showTime = get_option('miniAudioPlayer_showTime');
$miniAudioPlayer_showRew = get_option('miniAudioPlayer_showRew');
$miniAudioPlayer_excluded = get_option('miniAudioPlayer_excluded');
$miniAudioPlayer_download = get_option('miniAudioPlayer_download');
//$miniAudioPlayer_use_html5_download = get_option('miniAudioPlayer_use_html5_download');
$miniAudioPlayer_download_security = get_option('miniAudioPlayer_download_security');
$miniAudioPlayer_customizer = get_option('miniAudioPlayer_customizer');
$miniAudioPlayer_custom_skin_css = get_option('miniAudioPlayer_custom_skin_css');
$miniAudioPlayer_custom_skin_name = get_option('miniAudioPlayer_custom_skin_name');
$miniAudioPlayer_add_gradient = get_option('miniAudioPlayer_add_gradient');
$miniAudioPlayer_active_all = get_option('miniAudioPlayer_active_all');
//$miniAudioPlayer_replaceDefault = get_option('miniAudioPlayer_replaceDefault');
//$miniAudioPlayer_replaceDefault_show_title = get_option('miniAudioPlayer_replaceDefault_show_title');

//set up defaults if these fields are empty
if ($miniAudioPlayer_version != MINIAUDIOPLAYER_VERSION) {
    $miniAudioPlayer_version = MINIAUDIOPLAYER_VERSION;
}
if (empty($miniAudioPlayer_donate)) {
    $miniAudioPlayer_donate = "false";
}
if (empty($miniAudioPlayer_getMetadata)) {
    $miniAudioPlayer_getMetadata = "false";
}
if (empty($miniAudioPlayer_width)) {
    $miniAudioPlayer_width = "250";
}
if (empty($miniAudioPlayer_skin)) {
    $miniAudioPlayer_skin = "black";
}
if (empty($miniAudioPlayer_animate)) {
    $miniAudioPlayer_animate = "false";
}
if (empty($miniAudioPlayer_volume)) {
    $miniAudioPlayer_volume = ".5";
}
if (empty($miniAudioPlayer_autoplay)) {
    $miniAudioPlayer_autoplay = "false";
}
if (empty($miniAudioPlayer_showVolumeLevel)) {
    $miniAudioPlayer_showVolumeLevel = "false";
}
if (empty($miniAudioPlayer_allowMute)) {
    $miniAudioPlayer_allowMute = "false";
}
if (empty($miniAudioPlayer_showTime)) {
    $miniAudioPlayer_showTime = "false";
}
if (empty($miniAudioPlayer_showRew)) {
    $miniAudioPlayer_showRew = "false";
}
if (empty($miniAudioPlayer_excluded)) {
    $miniAudioPlayer_excluded = "map_excluded";
}
if (empty($miniAudioPlayer_download)) {
    $miniAudioPlayer_download = "false";
}

/*if (empty($miniAudioPlayer_use_html5_download)) {
    $miniAudioPlayer_use_html5_download = false;
}*/

if (empty($miniAudioPlayer_download_security)) {
    $miniAudioPlayer_download_security = "false";
}
if (empty($miniAudioPlayer_customizer)) {
    $miniAudioPlayer_customizer = "false";
}
if (empty($miniAudioPlayer_add_gradient)) {
    $miniAudioPlayer_add_gradient = "true";
}
if (empty($miniAudioPlayer_custom_skin_name)) {
    $miniAudioPlayer_custom_skin_name = "mySkin";
}

if (!$isGutenberg) {
    if (empty($miniAudioPlayer_active_all)) {
        $miniAudioPlayer_active_all = "false";
    }
    if (empty($miniAudioPlayer_replaceDefault)) {
        $miniAudioPlayer_replaceDefault = "false";
    }
    if (empty($miniAudioPlayer_replaceDefault_show_title)) {
        $miniAudioPlayer_replaceDefault_show_title = "false";
    }
} else {
    $miniAudioPlayer_active_all = true;
    $miniAudioPlayer_replaceDefault = true;
    $miniAudioPlayer_replaceDefault_show_title = true;
}

if (empty($miniAudioPlayer_custom_skin_css)) {
    $miniAudioPlayer_custom_skin_css = "

/* DO NOT REMOVE OR MODIFY */
/*{'skinName': 'mySkin', 'borderRadius': 5, 'main': 'rgb(255, 217, 102)', 'secondary': 'rgb(68, 68, 68)', 'playerPadding': 0}*/
/* END - DO NOT REMOVE OR MODIFY */
/*++++++++++++++++++++++++++++++++++++++++++++++++++
Copyright (c) 2001-2014. Matteo Bicocchi (Pupunzi);
http://pupunzi.com/mb.components/mb.miniAudioPlayer/demo/skinMaker.html

Skin name: mySkin
borderRadius: 5
background: rgb(255, 217, 102)
icons: rgb(68, 68, 68)
border: rgb(55, 55, 55)
borderLeft: rgb(255, 230, 153)
borderRight: rgb(255, 204, 51)
mute: rgba(68, 68, 68, 0.4)
download: rgba(255, 217, 102, 0.4)
downloadHover: rgb(255, 217, 102)
++++++++++++++++++++++++++++++++++++++++++++++++++*/

/* Older browser (IE8) - not supporting rgba() */
.mbMiniPlayer.mySkin .playerTable span{background-color:#ffd966}
.mbMiniPlayer.mySkin .playerTable span.map_play{border-left:1px solid #ffd966;}
.mbMiniPlayer.mySkin .playerTable span.map_volume{border-right:1px solid #ffd966;}
.mbMiniPlayer.mySkin .playerTable span.map_volume.mute{color: #444444;}
.mbMiniPlayer.mySkin .map_download{color: #444444;}
.mbMiniPlayer.mySkin .map_download:hover{color: #444444;}
.mbMiniPlayer.mySkin .playerTable span{color: #444444;}
.mbMiniPlayer.mySkin .playerTable {border: 1px solid #444444 !important;}

/*++++++++++++++++++++++++++++++++++++++++++++++++*/

.mbMiniPlayer.mySkin .playerTable{background-color:transparent; border-radius:5px !important;}
.mbMiniPlayer.mySkin .playerTable span{background-color:rgb(255, 217, 102); padding:3px !important; font-size: 20px;}
.mbMiniPlayer.mySkin .playerTable span.map_time{ font-size: 12px !important; width: 50px !important}
.mbMiniPlayer.mySkin .playerTable span.map_title{ padding:4px !important}
.mbMiniPlayer.mySkin .playerTable span.map_play{border-left:1px solid rgb(255, 204, 51); border-radius:0 4px 4px 0 !important;}
.mbMiniPlayer.mySkin .playerTable span.map_volume{padding-left:6px !important}
.mbMiniPlayer.mySkin .playerTable span.map_volume{border-right:1px solid rgb(255, 230, 153); border-radius:4px 0 0 4px !important;}
.mbMiniPlayer.mySkin .playerTable span.map_volume.mute{color: rgba(68, 68, 68, 0.4);}
.mbMiniPlayer.mySkin .map_download{color: rgba(255, 217, 102, 0.4);}
.mbMiniPlayer.mySkin .map_download:hover{color: rgb(255, 217, 102);}
.mbMiniPlayer.mySkin .playerTable span{color: rgb(68, 68, 68);text-shadow: none!important;}
.mbMiniPlayer.mySkin .playerTable span{color: rgb(68, 68, 68);}
.mbMiniPlayer.mySkin .playerTable {border: 1px solid rgb(55, 55, 55) !important;}
.mbMiniPlayer.mySkin .playerTable span.map_title{color: #000; text-shadow:none!important}
.mbMiniPlayer.mySkin .playerTable .jp-load-bar{background-color:rgba(255, 217, 102, 0.3);}
.mbMiniPlayer.mySkin .playerTable .jp-play-bar{background-color:#ffd966;}
.mbMiniPlayer.mySkin .playerTable span.map_volumeLevel a{background-color:rgb(94, 94, 94); height:80%!important }
.mbMiniPlayer.mySkin .playerTable span.map_volumeLevel a.sel{background-color:#444444;}
.mbMiniPlayer.mySkin  span.map_download{font-size:50px !important;}
/* Wordpress playlist select */
.map_pl_container .pl_item.sel{background-color:#ffd966 !important; color: #444444}
/*++++++++++++++++++++++++++++++++++++++++++++++++*/
";
}

//if ($isGutenberg)
//    $miniAudioPlayer_custom_skin_css .= 'figure.wp-block-audio{opacity: 0;}';

update_option('miniAudioPlayer_version', $miniAudioPlayer_version);

add_filter('plugin_action_links', 'miniAudioPlayer_action_links', 10, 2);
function miniAudioPlayer_action_links($links, $file)
{
    static $this_plugin;
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {
        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=wp-miniaudioplayer/miniAudioPlayer-admin.php">Settings</a>';
        // add the link to the list
        array_unshift($links, $settings_link);
    }
    return $links;
}

// scripts to go in the header and/or footer
add_action('init', 'miniAudioPlayer_init');
function miniAudioPlayer_init()
{
    global $miniAudioPlayer_version;
    load_plugin_textdomain('mbMiniAudioPlayer', false, basename(dirname(__FILE__)) . '/languages/');
    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('mb.miniAudioPlayer', plugins_url('/js/jquery.mb.miniAudioPlayer.js', __FILE__), false, $miniAudioPlayer_version, false);
        wp_enqueue_script('map_overwrite_default_me', plugins_url('/js/map_overwrite_default_me.js', __FILE__), false, $miniAudioPlayer_version, false);
        wp_enqueue_style('mb.miniAudioPlayer.css', plugins_url('/css/miniplayer.css', __FILE__), false, $miniAudioPlayer_version, 'screen');
    }
}

function miniAudioPlayer_player_head()
{
    global $miniAudioPlayer_excluded,
           $miniAudioPlayer_getMetadata,
           $miniAudioPlayer_width,
           $miniAudioPlayer_skin,
           $miniAudioPlayer_animate,
           $miniAudioPlayer_volume,
           $miniAudioPlayer_autoplay,
           $miniAudioPlayer_showVolumeLevel,
           $miniAudioPlayer_allowMute,
           $miniAudioPlayer_showTime,
           $miniAudioPlayer_showRew,
           $miniAudioPlayer_active_all,
           $miniAudioPlayer_replaceDefault,
           $miniAudioPlayer_replaceDefault_show_title,
           $isGutenberg;

    echo '
	<!-- start miniAudioPlayer initializer -->
	<script type="text/javascript">

	let isGutenberg = ' . $isGutenberg . ';
	let miniAudioPlayer_replaceDefault = ' . $miniAudioPlayer_replaceDefault . ';
	let miniAudioPlayer_excluded = "' . $miniAudioPlayer_excluded . '";
	let miniAudioPlayer_replaceDefault_show_title = ' . $miniAudioPlayer_replaceDefault_show_title . ';

	let miniAudioPlayer_defaults = {
				inLine:true,
                width:"' . $miniAudioPlayer_width . '",
				skin:"' . $miniAudioPlayer_skin . '",
				animate:' . $miniAudioPlayer_animate . ',
				volume:' . $miniAudioPlayer_volume . ',
				autoplay:' . $miniAudioPlayer_autoplay . ',
				showVolumeLevel:' . $miniAudioPlayer_showVolumeLevel . ',
				allowMute: ' . $miniAudioPlayer_allowMute . ',
				showTime:' . $miniAudioPlayer_showTime . ',
				id3:' . $miniAudioPlayer_getMetadata . ',
				showRew:' . $miniAudioPlayer_showRew . ',
				addShadow: false,
				downloadable:' . miniAudioPlayer_canDownload() . ',
				downloadPage: null,
				swfPath:"' . plugins_url('/js/', __FILE__) . '",
				onReady: function(player, $controlsBox){
				   if(player.opt.downloadable && player.opt.downloadablesecurity && !' . miniAudioPlayer_userCanRead() . '){
				        jQuery(".map_download", $controlsBox).remove();
				   }
				}
		};

    function initializeMiniAudioPlayer(){
         jQuery(".mejs-container a").addClass(miniAudioPlayer_excluded);
         jQuery("a' . ($miniAudioPlayer_active_all != 'true' ? '.mb_map' : '') . '[href *= \'.mp3\']' . ($miniAudioPlayer_active_all != 'true' ? '.mb_map' : '') . '")' . miniAudioPlayer_getExcluded() . 'mb_miniPlayer(miniAudioPlayer_defaults);
    }

    //if(' . $miniAudioPlayer_replaceDefault . ')
        jQuery("body").addClass("map_replaceDefault");

	jQuery(function(){
     // if(' . $miniAudioPlayer_replaceDefault . ')
         setTimeout(function(){replaceDefault();},10);
      
      initializeMiniAudioPlayer();
      jQuery(document).ajaxSuccess(function(event, xhr, settings) {
        initializeMiniAudioPlayer();
      });
	});
	</script>
	<!-- end miniAudioPlayer initializer -->

	';
};

function miniAudioPlayer_custom_css()
{
    global $miniAudioPlayer_custom_skin_css;

    echo '
<!-- start miniAudioPlayer custom CSS -->

<style id="map_custom_css">
       ' . $miniAudioPlayer_custom_skin_css . '
       </style>
	
<!-- end miniAudioPlayer custom CSS -->	
';

}

;

add_action('wp_head', 'miniAudioPlayer_custom_css');
add_action('wp_footer', 'miniAudioPlayer_player_head', 20);

function miniAudioPlayer_getExcluded()
{
    global $miniAudioPlayer_excluded;
    if (!empty($miniAudioPlayer_excluded)) {
        return '.not(".' . $miniAudioPlayer_excluded . '").not(".wp-playlist-caption").not("[download]").';
    } else {
        return '.';
    }
}

function miniAudioPlayer_canDownload()
{
    global $miniAudioPlayer_download, $miniAudioPlayer_download_security;
    if (($miniAudioPlayer_download == "true" && $miniAudioPlayer_download_security == "false")
        || ($miniAudioPlayer_download == "true" && ($miniAudioPlayer_download_security == "true" && current_user_can('read') == 1))) {
        return 'true';
    } else {
        return 'false';
    }
}

function miniAudioPlayer_userCanRead()
{
    if (current_user_can('read') == 1)
        return 'true';
    else
        return 'false';
}

// TinyMCE stylesheet ***************************************************

function miniAudioPlayer_add_editor_styles()
{
    global $miniAudioPlayer_active_all;
    if ($miniAudioPlayer_active_all == "true")
        add_editor_style(plugins_url('css/TinyMCE_player.css', __FILE__));
    else
        add_editor_style(plugins_url('css/TinyMCE_player_notAll.css', __FILE__));
}

add_action('admin_init', 'miniAudioPlayer_add_editor_styles');

if (is_admin()) {
    require('miniAudioPlayer-admin.php');
    require_once("inc/popup.php");
}
