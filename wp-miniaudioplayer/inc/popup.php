<?php
/*___________________________________________________________________________________________________________________________________________________
 _ jquery.mb.components                                                                                                                             _
 _                                                                                                                                                  _
 _ file: popup.php                                                                                                                                  _
 _ last modified: 8/28/19 11:52 PM                                                                                                                  _
 _                                                                                                                                                  _
 _ Open Lab s.r.l., Florence - Italy                                                                                                                _
 _                                                                                                                                                  _
 _ email: matbicoc@gmail.com                                                                                                                       _
 _ site: http://pupunzi.com                                                                                                                         _
 _       http://open-lab.com                                                                                                                        _
 _ blog: http://pupunzi.open-lab.com                                                                                                                _
 _ Q&A:  http://jquery.pupunzi.com                                                                                                                  _
 _                                                                                                                                                  _
 _ Licences: MIT, GPL                                                                                                                               _
 _    http://www.opensource.org/licenses/mit-license.php                                                                                            _
 _    http://www.gnu.org/licenses/gpl.html                                                                                                          _
 _                                                                                                                                                  _
 _ Copyright (c) 2001-2019. Matteo Bicocchi (Pupunzi);                                                                                              _
 ___________________________________________________________________________________________________________________________________________________*/

// Only add map icon above posts and pages
add_action('admin_head', 'miniaudioplayer_add_button');
function miniaudioplayer_add_button()
{

	if (get_user_option('rich_editing') != 'true')
		return;

	add_action('media_buttons', 'miniaudioplayer_add_icon');
	add_action('admin_footer', 'miniaudioplayer_add_popup');
}

// Add button above editor if not editing map
function miniaudioplayer_add_icon()
{
	echo '<style>
	#add-map .dashicons {
		color: #888;
		margin: 0 4px 0 0;
		vertical-align: text-top;
		height: 18px;
        width: 18px;

		background-image: url(' . plugins_url("maplayerbutton.svg", __FILE__) . ');
		background-repeat: no-repeat;
    }
	#add-map {
		padding-left: 0.4em;
	}

	#add-map.disabled {
	    pointer-events:none;
		padding-left: 0.4em;
	}

	</style>
	<a id="add-map" class="button disabled" title="' . __("miniAudioPlayer", 'wpmbmap') . '" href="#" onclick="miniaudioplayer_show_editor();">
		<div class="dashicons"></div>' . __("miniAudioPlayer", "wpmbmap") . '</a>';
}

class miniaudioplayer_check_href
{
	function __construct()
	{
		add_filter('mce_external_plugins', array(&$this, 'add_map_tinymce_plugin'));
		add_filter('tiny_mce_before_init', array(&$this, 'add_map_TinyMCE_css'));
	}

	//include the tinymce javascript plugin
	function add_map_tinymce_plugin($plugin_array)
	{
		$plugin_array['wpmbmap'] = plugins_url('map_short_code.js?_=' . MINIAUDIOPLAYER_VERSION, __FILE__);
		return $plugin_array;
	}

	//include the css file to style the graphic that replaces the shortcode
	function add_map_TinyMCE_css($in)
	{
		if (!empty($in['content_css']))
			$in['content_css'] .= "," . plugins_url('map_short_code.css?_=' . MINIAUDIOPLAYER_VERSION, __FILE__);
		return $in;
	}
}

//add_action("init", create_function('', 'new miniaudioplayer_check_href();'));
add_action("init", function(){
	new miniaudioplayer_check_href();
});


$custom_player_id = "map_" . rand();

// Displays the lightbox popup to insert a YTPlayer shortcode to a post/page
function miniaudioplayer_add_popup()
{
	$exclude_class = get_option('miniAudioPlayer_excluded');
	$showVolumeLevel = get_option('miniAudioPlayer_showVolumeLevel');
	$allowMute = get_option('miniAudioPlayer_allowMute');
	$showTime = get_option('miniAudioPlayer_showTime');
	$showRew = get_option('miniAudioPlayer_showRew');
	$width = get_option('miniAudioPlayer_width');
	$skin = get_option('miniAudioPlayer_skin');
	$miniAudioPlayer_animate = get_option('miniAudioPlayer_animate');
	$miniAudioPlayer_add_gradient = get_option('miniAudioPlayer_add_gradient');
	$volume = get_option('miniAudioPlayer_volume');
	$downloadable = get_option('miniAudioPlayer_download');
	$custom_skin_name = get_option('miniAudioPlayer_custom_skin_name');
	$downloadable_security = get_option('miniAudioPlayer_download_security');
	?>
  <div id="map-form" style="display: none;">
    <style>

      #map-form {
        position: fixed;
        width: 100%;
        min-width: 500px;
        height: 100%;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        background: rgba(0, 0, 0, 0.7);
        z-index: 100101;
        box-sizing: border-box;
        overflow: hidden;
      }

      #map-form header {
        position: absolute;
        background: #0073aa;
        color: #FFFFFF;
        height: 50px;
        box-sizing: border-box;
        margin: 0;
        top: 0;
        width: 100%;
        padding: 10px;
        box-shadow: 1px 4px 8px 0px rgba(0, 0, 0, 0.3);
        z-index: 1000;
      }

      #map-form header h2 {
        color: #ffffff;
        margin: 0;
        line-height: 40px;
      }

      #map-form #editor {
        position: absolute;
        width: 50%;
        min-width: 700px;
        height: 90%;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        background: #FFFFFF;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        box-sizing: border-box;
      }

      #map-form #editor form {
        position: absolute;
        width: 100%;
        top: 50px;
        left: 0;
        height: calc(100% - 55px);
        overflow: auto;
        padding: 10px;
        box-sizing: border-box;
      }

      #map-form fieldset {
        font-size: 16px;
        border: none;
        font-family: inherit;
        font-family: Helvetica Neue, Arial, Helvetica, sans-serif;
      }

      #map-form fieldset span.label {
        display: inline-block;
        width: 45%;
        font-size: 100%;
        font-weight: 400;
        vertical-align: top;
      }

      #map-form fieldset div {
        margin: 0;
        padding: 9px !important;
        display: block;
        font-size: 16px;
        border-bottom: 1px dotted #cccccc;
      }

      #map-form input, textarea, select {
        font-size: 100%;
      }

      #map-form input[type=text], textarea {
        width: 54%;
      }

      #map-form .sub-set {
        background: #f3f3f3;
      }

      #map-form .media-modal-close .media-modal-icon:before {
        color: #FFFFFF;
      }

      #map-form .actions {
        text-align: right;
        padding: 10px;
        background: rgba(158, 158, 158, 0.19);
      }

      .help-inline {
        font-size: 16px;
        font-weight: 300;
        display: block;
        color: #999;
        padding-left: 0;
        margin: 5px 0;
      }

      .help-inline.inline {
        display: inline-block;
        font-weight: 400;
        padding-left: 10px;
      }


    </style>

    <div id="editor">
      <header>
        <h2><?php _e('mb.miniAudioPlayer editor', 'wpmbmap'); ?></h2>
        <button onclick="miniaudioplayer_hide_editor()" type="button" class="button-link media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close panel</span></span></button>
      </header>

      <form id="map_form" action="#">
        <div class="actions">
          <input type="submit" value="Insert code" class="button-primary"/>
        </div>

        <fieldset>
          <div>
            <span class="label"><?php _e('Don’t render', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="exclude" value="true"/>
            <span class="help-inline"><?php _e('check to exclude this link', 'wp-miniaudioplayer'); ?> (<?php echo $exclude_class ?>)</span>
          </div>
          <div>
            <span class="label"><?php _e('Audio url', 'wp-miniaudioplayer'); ?> <span style="color:red">*</span> : </span>
            <input type="text" name="url" class="span5"/>
            <span class="help-inline"><?php _e('A valid .mp3 url', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Audio title', 'wp-miniaudioplayer'); ?>: </span>
            <input type="text" name="audiotitle" class="span5"/>
            <span class="help-inline"><?php _e('The audio title', 'wp-miniaudioplayer'); ?></span><br>
            <span class="label"> </span>
            <button class="button" id="metadata" onclick="getFromMetatags();jQuery(this).hide(); return false" style="color: gray"><?php _e('Get the title from meta-data', 'wp-miniaudioplayer'); ?></button>
          </div>

          <div>
            <span class="label"><?php _e('Skin', 'wp-miniaudioplayer'); ?>:</span>
            <select name="skin">
              <option value="black"><?php _e('black', 'wp-miniaudioplayer'); ?></option>
              <option value="blue"><?php _e('blue', 'wp-miniaudioplayer'); ?></option>
              <option value="orange"><?php _e('orange', 'wp-miniaudioplayer'); ?></option>
              <option value="red"><?php _e('red', 'wp-miniaudioplayer'); ?></option>
              <option value="gray"><?php _e('gray', 'wp-miniaudioplayer'); ?></option>
              <option value="green"><?php _e('green', 'wp-miniaudioplayer'); ?></option>
              <option value="<?php echo $custom_skin_name ?>"><?php echo $custom_skin_name ?></option>
            </select>
            <span class="help-inline"><?php _e('Set the skin color for the player', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Gradient', 'wp-miniaudioplayer'); ?>:</span>
            <input type="checkbox" name="addGradientOverlay" value="true"/>
            <span class="help-inline"><?php _e('Check to add a gradient to the player skin', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Animate', 'wp-miniaudioplayer'); ?>:</span>
            <input type="checkbox" name="animate" value="true"/>
            <span class="help-inline"><?php _e('Check to activate the opening / closing animation', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Width', 'wp-miniaudioplayer'); ?>: </span>
            <input type="text" name="width" class="span6"/>
            <span class="help-inline"><?php _e('Set the player width', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Volume', 'wp-miniaudioplayer'); ?>: </span>
            <input type="text" name="volume" class="span6"/>
            <span class="help-inline"><?php _e('(from 1 to 10) Set the player initial volume', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Autoplay', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="autoplay" value="true"/>
            <span class="help-inline"><?php _e('Check to start playing on page load', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Loop', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="loop" value="false"/>
            <span class="help-inline"><?php _e('Check to loop the sound', 'wp-miniaudioplayer'); ?></span>
          </div>

          <h2><?php _e('Show/Hide', 'wp-miniaudioplayer'); ?></h2>

          <div>
            <span class="label"><?php _e('Volume control', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="showVolumeLevel" value="true"/>
            <span class="help-inline"><?php _e('Check to show the volume control', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Time control', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="showTime" value="true"/>
            <span class="help-inline"><?php _e('Check to show the time control', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Mute control', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="allowMute" value="true"/>
            <span class="help-inline"><?php _e('Check to activate the mute button', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Rewind control', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="showRew" value="true"/>
            <span class="help-inline"><?php _e('Check to show the rewind control', 'wp-miniaudioplayer'); ?></span>
          </div>

          <div>
            <span class="label"><?php _e('Downloadable', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="downloadable" value="false" onclick="manageSecurity(this)"/>
            <span class="help-inline"><?php _e('Check to show the download button', 'wp-miniaudioplayer'); ?></span><br>
          </div>

          <div>
            <span class="label" style="font-weight: normal; color: gray"><?php _e('Only registered', 'wp-miniaudioplayer'); ?>: </span>
            <input type="checkbox" name="downloadable_security" value="true"/>
            <span class="help-inline"><?php _e('Check to limit downloads to registered users', 'wp-miniaudioplayer'); ?></span>
          </div>

          <script>
			  function manageSecurity(el) {
				  var security = jQuery('[name=downloadablesecurity]');
				  if (jQuery(el).is(":checked")) {
					  security.removeAttr('disabled');
				  } else {
					  security.attr('disabled', 'disabled');
					  security.removeAttr('checked');
				  }
			  }
          </script>

        </fieldset>

        <div class="actions">
          <input type="submit" value="Insert code" class="button-primary"/>
          <input class="button" type="reset" value="Reset settings"/>

        </div>

      </form>
    </div>
  </div>

  <script>

	  var selection = null;
	  var tmpInfo = {};

	  jQuery(function () {
		  jQuery(".wp-editor-tabs button").on("click.map", function () {

			  setTimeout(function () {
				  if (!tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
					  jQuery("#add-map").css("opacity", .5);
				  } else {
					  jQuery("#add-map").css("opacity", 1);
				  }
			  }, 400)
		  })
	  });

	  function getFromMetatags() {
		  if (typeof ID3 == "object") {
			  ID3.loadTags(document.audioURL, function () {
				  var info = {};
				  info.title = ID3.getTag(document.audioURL, "title");
				  info.artist = ID3.getTag(document.audioURL, "artist");
				  info.album = ID3.getTag(document.audioURL, "album");
				  info.track = ID3.getTag(document.audioURL, "track");
				  info.size = ID3.getTag(document.audioURL, "size");
				  if (info.title && info.title != undefined) {
					  jQuery("[name='audiotitle']").val(info.title + " - " + info.artist);

					  tmpInfo = info;
				  } else {
					  jquery("button#metadata").after("no meta-data available for this file");
				  }
			  })
		  }
	  }

	  function miniaudioplayer_show_editor() {

		  if (tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false) {
			  alert("You should switch to the visual editor");
			  return;
		  }

		  var map_editor = tinyMCE.activeEditor;

		  var map_form = jQuery('#map-form form').get(0);

		  var selection = map_editor.selection.getNode();
		  map_editor.isValidURL = false;
		  map_editor.isHref = false;

		  if (jQuery(selection).is("a[href *= '.mp3']") || jQuery(selection).find("a[href *= '.mp3']").lenght > 0 || jQuery(selection).prev().is("a[href *= '.mp3']")) {
			  map_editor.isHref = true;
			  map_editor.isValidURL = true;
		  } else if (jQuery(selection).is("a") || jQuery(selection).find("a").lenght > 0 || jQuery(selection).prev().is("a")) {
			  map_editor.isHref = true;
		  }

		  if (!map_editor.isHref) {
			  alert("Select a link to an mp3 file to customize the player.");
			  return;
		  }

		  if (!map_editor.isValidURL) {
			  var d = confirm("the selected Link doesn't seams a valid MP3 path; do you want to continue anyway?");
			  if (!d)
				  return;

		  }
		  map_form.reset();

		  jQuery("body").css({overflow: "hidden"});
		  jQuery("#map-form").slideDown(300);

		  selection = map_editor.selection.getNode();

		  map_editor.selection.select(selection, true);

		  var $selection = jQuery(selection);

		  var map_element = $selection.find("a[href *= '.mp3']");
		  if (map_element.length) {
			  selection = map_editor.selection.select(map_element.get(0), true);
		  } else if ($selection.prev().is("a[href *= '.mp3']")) {
			  selection = map_editor.selection.select($selection.prev().get(0), true);
		  }

		  $selection = jQuery(selection);

		  var url = document.audioURL = $selection.attr("href");
		  var title = $selection.html();
		  var isExcluded = $selection.hasClass("<?php echo $exclude_class ?>");

		  var metadata = $selection.metadata();

		  if (metadata.volume)
			  metadata.volume = parseFloat(metadata.volume) * 10;

		  if (jQuery.isEmptyObject(metadata)) {
			  var defaultmeta = {
				  showVolumeLevel:<?php echo empty($showVolumeLevel) ? false : $showVolumeLevel ?>,
				  allowMute:<?php echo $allowMute ? "true" : "false"?>,
				  showTime:<?php echo $showTime ? "true" : "false"?>,
				  showRew:<?php echo $showRew ? "true" : "false"?>,
				  width: "<?php echo $width ?>",
				  skin: "<?php echo $skin ?>",
				  animate:<?php echo $miniAudioPlayer_animate ? "true" : "false" ?>,
				  loop: false,
				  addGradientOverlay: <?php echo $miniAudioPlayer_add_gradient ? "true" : "false" ?>,
				  downloadable:<?php echo $downloadable ? "true" : "false" ?>,
				  downloadable_security:<?php echo $downloadable_security ? "true" : "false" ?>,
				  volume: parseFloat(<?php echo $volume ?>) * 10
			  };
			  jQuery.extend(metadata, defaultmeta);
		  }

		  jQuery.extend(metadata, {exclude: isExcluded});

		  jQuery("[name='url']", map_form).val(url);

		  jQuery("[name='audiotitle']", map_form).val(title);

		  for (var i in metadata) {
			  if (typeof metadata[i] == "boolean") {
				  if (eval(metadata[i]) == true)
					  jQuery("[name=" + i + "]").attr("checked", "checked");
				  else
					  jQuery("[name=" + i + "]").removeAttr("checked");
			  } else
				  jQuery("[name=" + i + "]").val(metadata[i]);
		  }

		  var map_form = jQuery('#map-form form').get(0);
		  map_form.onsubmit = miniaudioplayer_insertCode;
	  }

	  function miniaudioplayer_insertCode(e) {

		  var map_editor = tinyMCE.activeEditor;
		  var map_form = jQuery('#map-form form').get(0);

		  var map_params = "{";
		  if (jQuery("[name='skin']", map_form).val().length > 0)
			  map_params += "skin:'" + jQuery("[name='skin']").val() + "', ";
		  map_params += "animate:" + (jQuery("[name='animate']").is(":checked") ? "true" : "false") + ", ";
		  if (jQuery("[name='width']", map_form).val().length > 0)
			  map_params += "width:'" + jQuery("[name='width']", map_form).val() + "', ";
		  if (jQuery("[name='volume']", map_form).val().length > 0)
			  map_params += "volume:" + jQuery("[name='volume']", map_form).val() / 10 + ", ";
		  map_params += "autoplay:" + (jQuery("[name='autoplay']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "loop:" + (jQuery("[name='loop']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "showVolumeLevel:" + (jQuery("[name='showVolumeLevel']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "showTime:" + (jQuery("[name='showTime']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "allowMute:" + (jQuery("[name='allowMute']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "showRew:" + (jQuery("[name='showRew']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "addGradientOverlay:" + (jQuery("[name='addGradientOverlay']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "downloadable:" + (jQuery("[name='downloadable']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "downloadablesecurity:" + (jQuery("[name='downloadablesecurity']", map_form).is(":checked") ? "true" : "false") + ", ";
		  map_params += "id3: false";
		  map_params += "}";
		  map_params = map_params.replace(", }", "}");

		  var isExcluded = jQuery("[name='exclude']", map_form).is(":checked") ? "<?php echo $exclude_class ?> " : "";

		  var map_a = "<a id='mbmaplayer_" + new Date().getTime() + "' class=";
		  map_a += "\"mb_map " + isExcluded + map_params + "\" ";

		  for (var x in tmpInfo) {
			  map_a += "meta-" + x + "=\"" + tmpInfo[x] + "\" ";
		  }
		  map_a += "href=\"" + jQuery("[name='url']", map_form).val() + "\">";
		  map_a += jQuery("[name='audiotitle']", map_form).val();
		  map_a += "</a>";
		  map_editor.execCommand('mceInsertContent', 0, map_a);

		  miniaudioplayer_hide_editor();

		  return false;
	  }

	  function miniaudioplayer_hide_editor() {
		  jQuery("#map-form").slideUp(300);
		  jQuery("body").css({overflow: "auto"});
	  }

	  jQuery("body").on("click", "#map-form", function (e) {
		  var target = e.originalEvent.target;
		  if (jQuery(target).parents().is("#map-form"))
			  return;
		  miniaudioplayer_hide_editor();
	  });


	  /*
   * ******************************************************************************
   *  file: metadata.js
   */


	  jQuery.extend({
		  metadata: {
			  defaults: {type: "class", name: "metadata", cre: /({.*})/, single: "metadata"}, setType: function (b, e) {
				  this.defaults.type = b;
				  this.defaults.name = e
			  }, get: function (b, e) {
				  var c = jQuery.extend({}, this.defaults, e);
				  c.single.length || (c.single = "metadata");
				  var a = jQuery.data(b, c.single);
				  if (a) return a;
				  a = "{}";
				  if ("class" == c.type) {
					  var d = c.cre.exec(b.className);
					  d && (a = d[1])
				  } else if ("elem" == c.type) {
					  if (!b.getElementsByTagName) return;
					  d = b.getElementsByTagName(c.name);
					  d.length && (a = jQuery.trim(d[0].innerHTML))
				  } else void 0 !=
				  b.getAttribute && (d = b.getAttribute(c.name)) && (a = d);
				  0 > a.indexOf("{") && (a = "{" + a + "}");
				  a = eval("(" + a + ")");
				  jQuery.data(b, c.single, a);
				  return a
			  }
		  }
	  });
	  jQuery.fn.metadata = function (b) {
		  return jQuery.metadata.get(this[0], b)
	  };

    /*
   * ******************************************************************************
   *  jquery.mb.components
   *  file: id3.min.js
   *
   *  Copyright (c) 2001-2013. Matteo Bicocchi (Pupunzi);
   *  Open lab srl, Firenze - Italy
   *  email: matbicoc@gmail.com
   *  site: 	http://pupunzi.com
   *  blog:	http://pupunzi.open-lab.com
   * 	http://open-lab.com
   *
   *  Licences: MIT, GPL
   *  http://www.opensource.org/licenses/mit-license.php
   *  http://www.gnu.org/licenses/gpl.html
   *
   *  last modified: 31/01/13 23.44
   *  *****************************************************************************
   */

    var q=null;function y(g,i,d){function f(b,h,e,a,d,f){var j=c();if(j){typeof f==="undefined"&&(f=!0);if(h)typeof j.onload!="undefined"?j.onload=function(){j.status=="200"||j.status=="206"?(j.fileSize=d||j.getResponseHeader("Content-Length"),h(j)):e&&e();j=q}:j.onreadystatechange=function(){if(j.readyState==4)j.status=="200"||j.status=="206"?(j.fileSize=d||j.getResponseHeader("Content-Length"),h(j)):e&&e(),j=q};j.open("GET",b,f);j.overrideMimeType&&j.overrideMimeType("text/plain; charset=x-user-defined");a&&j.setRequestHeader("Range",
	  "bytes="+a[0]+"-"+a[1]);j.setRequestHeader("If-Modified-Since","Sat, 1 Jan 1970 00:00:00 GMT");j.send(q)}else e&&e()}function c(){var b=q;window.XMLHttpRequest?b=new XMLHttpRequest:window.F&&(b=new ActiveXObject("Microsoft.XMLHTTP"));return b}function a(b,h){var e=c();if(e){if(h)typeof e.onload!="undefined"?e.onload=function(){e.status=="200"&&h(this);e=q}:e.onreadystatechange=function(){e.readyState==4&&(e.status=="200"&&h(this),e=q)};e.open("HEAD",b,!0);e.send(q)}}function b(b,h){var e,a;function c(b){var p=
	  ~~(b[0]/e)-a,b=~~(b[1]/e)+1+a;p<0&&(p=0);b>=blockTotal&&(b=blockTotal-1);return[p,b]}function g(a,c){for(;n[a[0]];)if(a[0]++,a[0]>a[1]){c&&c();return}for(;n[a[1]];)if(a[1]--,a[0]>a[1]){c&&c();return}var k=[a[0]*e,(a[1]+1)*e-1];f(b,function(b){parseInt(b.getResponseHeader("Content-Length"),10)==h&&(a[0]=0,a[1]=blockTotal-1,k[0]=0,k[1]=h-1);for(var b={data:b.W||b.responseText,s:k[0]},p=a[0];p<=a[1];p++)n[p]=b;i+=k[1]-k[0]+1;c&&c()},d,k,j,!!c)}var j,i=0,l=new z("",0,h),n=[];e=e||2048;a=typeof a==="undefined"?
	  0:a;blockTotal=~~((h-1)/e)+1;for(var m in l)l.hasOwnProperty(m)&&typeof l[m]==="function"&&(this[m]=l[m]);this.a=function(b){var a;g(c([b,b]));a=n[~~(b/e)];if(typeof a.data=="string")return a.data.charCodeAt(b-a.s)&255;else if(typeof a.data=="unknown")return IEBinary_getByteAt(a.data,b-a.s)};this.N=function(){return i};this.f=function(b,a){g(c(b),a)}}(function(){a(g,function(a){a=parseInt(a.getResponseHeader("Content-Length"),10)||-1;i(new b(g,a))})})()}
    function z(g,i,d){var f=g,c=i||0,a=0;this.P=function(){return f};if(typeof g=="string")a=d||f.length,this.a=function(b){return f.charCodeAt(b+c)&255};else if(typeof g=="unknown")a=d||IEBinary_getLength(f),this.a=function(b){return IEBinary_getByteAt(f,b+c)};this.n=function(b,a){for(var h=Array(a),e=0;e<a;e++)h[e]=this.a(b+e);return h};this.j=function(){return a};this.d=function(b,a){return(this.a(b)&1<<a)!=0};this.Q=function(b){b=this.a(b);return b>127?b-256:b};this.r=function(b,a){var h=a?(this.a(b)<<
	  8)+this.a(b+1):(this.a(b+1)<<8)+this.a(b);h<0&&(h+=65536);return h};this.S=function(b,a){var h=this.r(b,a);return h>32767?h-65536:h};this.h=function(b,a){var h=this.a(b),e=this.a(b+1),c=this.a(b+2),d=this.a(b+3),h=a?(((h<<8)+e<<8)+c<<8)+d:(((d<<8)+c<<8)+e<<8)+h;h<0&&(h+=4294967296);return h};this.R=function(b,a){var c=this.h(b,a);return c>2147483647?c-4294967296:c};this.q=function(b){var a=this.a(b),c=this.a(b+1),b=this.a(b+2),a=((a<<8)+c<<8)+b;a<0&&(a+=16777216);return a};this.c=function(b,a){for(var c=
	  [],e=b,d=0;e<b+a;e++,d++)c[d]=String.fromCharCode(this.a(e));return c.join("")};this.e=function(b,a,c){b=this.n(b,a);switch(c.toLowerCase()){case "utf-16":case "utf-16le":case "utf-16be":var a=c,e,d=0,f=1,c=0;e=Math.min(e||b.length,b.length);b[0]==254&&b[1]==255?(a=!0,d=2):b[0]==255&&b[1]==254&&(a=!1,d=2);a&&(f=0,c=1);for(var a=[],g=0;d<e;g++){var j=b[d+f],i=(j<<8)+b[d+c];d+=2;if(i==0)break;else j<216||j>=224?a[g]=String.fromCharCode(i):(j=(b[d+f]<<8)+b[d+c],d+=2,a[g]=String.fromCharCode(i,j))}b=
	  String(a.join(""));b.g=d;break;case "utf-8":e=0;d=Math.min(d||b.length,b.length);b[0]==239&&b[1]==187&&b[2]==191&&(e=3);f=[];for(c=0;e<d;c++)if(a=b[e++],a==0)break;else a<128?f[c]=String.fromCharCode(a):a>=194&&a<224?(g=b[e++],f[c]=String.fromCharCode(((a&31)<<6)+(g&63))):a>=224&&a<240?(g=b[e++],i=b[e++],f[c]=String.fromCharCode(((a&255)<<12)+((g&63)<<6)+(i&63))):a>=240&&a<245&&(g=b[e++],i=b[e++],j=b[e++],a=((a&7)<<18)+((g&63)<<12)+((i&63)<<6)+(j&63)-65536,f[c]=String.fromCharCode((a>>10)+55296,
	  (a&1023)+56320));b=String(f.join(""));b.g=e;break;default:d=[];f=f||b.length;for(e=0;e<f;){c=b[e++];if(c==0)break;d[e-1]=String.fromCharCode(c)}b=String(d.join(""));b.g=e}return b};this.M=function(a){return String.fromCharCode(this.a(a))};this.Z=function(){return window.btoa(f)};this.L=function(a){f=window.atob(a)};this.f=function(a,c){c()}}document.write("<script type='text/vbscript'>\r\nFunction IEBinary_getByteAt(strBinary, iOffset)\r\n\tIEBinary_getByteAt = AscB(MidB(strBinary,iOffset+1,1))\r\nEnd Function\r\nFunction IEBinary_getLength(strBinary)\r\n\tIEBinary_getLength = LenB(strBinary)\r\nEnd Function\r\n<\/script>\r\n");(function(g){g.FileAPIReader=function(g){return function(d,f){var c=new FileReader;c.onload=function(a){f(new z(a.target.result))};c.readAsBinaryString(g)}}})(this);(function(g){g.k={i:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",z:function(g){for(var d="",f,c,a,b,p,h,e=0;e<g.length;)f=g[e++],c=g[e++],a=g[e++],b=f>>2,f=(f&3)<<4|c>>4,p=(c&15)<<2|a>>6,h=a&63,isNaN(c)?p=h=64:isNaN(a)&&(h=64),d=d+Base64.i.charAt(b)+Base64.i.charAt(f)+Base64.i.charAt(p)+Base64.i.charAt(h);return d}};g.Base64=g.k;g.k.encodeBytes=g.k.z})(this);(function(g){var i=g.t={},d={},f=[0,7];i.C=function(c,a,b){b=b||{};(b.dataReader||y)(c,function(g){g.f(f,function(){var f=g.c(4,7)=="ftypM4A"?ID4:g.c(0,3)=="ID3"?ID3v2:ID3v1;f.o(g,function(){var e=b.tags,i=f.p(g,e),e=d[c]||{},k;for(k in i)i.hasOwnProperty(k)&&(e[k]=i[k]);d[c]=e;a&&a()})})})};i.A=function(c){if(!d[c])return q;var a={},b;for(b in d[c])d[c].hasOwnProperty(b)&&(a[b]=d[c][b]);return a};i.B=function(c,a){if(!d[c])return q;return d[c][a]};g.ID3=g.t;i.loadTags=i.C;i.getAllTags=i.A;i.getTag=
	  i.B})(this);(function(g){var i=g.u={},d=["Blues","Classic Rock","Country","Dance","Disco","Funk","Grunge","Hip-Hop","Jazz","Metal","New Age","Oldies","Other","Pop","R&B","Rap","Reggae","Rock","Techno","Industrial","Alternative","Ska","Death Metal","Pranks","Soundtrack","Euro-Techno","Ambient","Trip-Hop","Vocal","Jazz+Funk","Fusion","Trance","Classical","Instrumental","Acid","House","Game","Sound Clip","Gospel","Noise","AlternRock","Bass","Soul","Punk","Space","Meditative","Instrumental Pop","Instrumental Rock",
	    "Ethnic","Gothic","Darkwave","Techno-Industrial","Electronic","Pop-Folk","Eurodance","Dream","Southern Rock","Comedy","Cult","Gangsta","Top 40","Christian Rap","Pop/Funk","Jungle","Native American","Cabaret","New Wave","Psychadelic","Rave","Showtunes","Trailer","Lo-Fi","Tribal","Acid Punk","Acid Jazz","Polka","Retro","Musical","Rock & Roll","Hard Rock","Folk","Folk-Rock","National Folk","Swing","Fast Fusion","Bebob","Latin","Revival","Celtic","Bluegrass","Avantgarde","Gothic Rock","Progressive Rock",
	    "Psychedelic Rock","Symphonic Rock","Slow Rock","Big Band","Chorus","Easy Listening","Acoustic","Humour","Speech","Chanson","Opera","Chamber Music","Sonata","Symphony","Booty Bass","Primus","Porn Groove","Satire","Slow Jam","Club","Tango","Samba","Folklore","Ballad","Power Ballad","Rhythmic Soul","Freestyle","Duet","Punk Rock","Drum Solo","Acapella","Euro-House","Dance Hall"];i.o=function(d,c){var a=d.j();d.f([a-128-1,a],c)};i.p=function(f){var c=f.j()-128;if(f.c(c,3)=="TAG"){var a=f.c(c+3,30).replace(/\0/g,
	  ""),b=f.c(c+33,30).replace(/\0/g,""),g=f.c(c+63,30).replace(/\0/g,""),h=f.c(c+93,4).replace(/\0/g,"");if(f.a(c+97+28)==0)var e=f.c(c+97,28).replace(/\0/g,""),i=f.a(c+97+29);else e="",i=0;f=f.a(c+97+30);return{version:"1.1",title:a,artist:b,album:g,year:h,comment:e,track:i,genre:f<255?d[f]:""}}else return{}};g.ID3v1=g.u})(this);(function(g){function i(a,b){var c=b.a(a),d=b.a(a+1),e=b.a(a+2);return b.a(a+3)&127|(e&127)<<7|(d&127)<<14|(c&127)<<21}var d=g.G={};d.b={};d.frames={BUF:"Recommended buffer size",CNT:"Play counter",COM:"Comments",CRA:"Audio encryption",CRM:"Encrypted meta frame",ETC:"Event timing codes",EQU:"Equalization",GEO:"General encapsulated object",IPL:"Involved people list",LNK:"Linked information",MCI:"Music CD Identifier",MLL:"MPEG location lookup table",PIC:"Attached picture",POP:"Popularimeter",REV:"Reverb",
	    RVA:"Relative volume adjustment",SLT:"Synchronized lyric/text",STC:"Synced tempo codes",TAL:"Album/Movie/Show title",TBP:"BPM (Beats Per Minute)",TCM:"Composer",TCO:"Content type",TCR:"Copyright message",TDA:"Date",TDY:"Playlist delay",TEN:"Encoded by",TFT:"File type",TIM:"Time",TKE:"Initial key",TLA:"Language(s)",TLE:"Length",TMT:"Media type",TOA:"Original artist(s)/performer(s)",TOF:"Original filename",TOL:"Original Lyricist(s)/text writer(s)",TOR:"Original release year",TOT:"Original album/Movie/Show title",
	    TP1:"Lead artist(s)/Lead performer(s)/Soloist(s)/Performing group",TP2:"Band/Orchestra/Accompaniment",TP3:"Conductor/Performer refinement",TP4:"Interpreted, remixed, or otherwise modified by",TPA:"Part of a set",TPB:"Publisher",TRC:"ISRC (International Standard Recording Code)",TRD:"Recording dates",TRK:"Track number/Position in set",TSI:"Size",TSS:"Software/hardware and settings used for encoding",TT1:"Content group description",TT2:"Title/Songname/Content description",TT3:"Subtitle/Description refinement",
	    TXT:"Lyricist/text writer",TXX:"User defined text information frame",TYE:"Year",UFI:"Unique file identifier",ULT:"Unsychronized lyric/text transcription",WAF:"Official audio file webpage",WAR:"Official artist/performer webpage",WAS:"Official audio source webpage",WCM:"Commercial information",WCP:"Copyright/Legal information",WPB:"Publishers official webpage",WXX:"User defined URL link frame",AENC:"Audio encryption",APIC:"Attached picture",COMM:"Comments",COMR:"Commercial frame",ENCR:"Encryption method registration",
	    EQUA:"Equalization",ETCO:"Event timing codes",GEOB:"General encapsulated object",GRID:"Group identification registration",IPLS:"Involved people list",LINK:"Linked information",MCDI:"Music CD identifier",MLLT:"MPEG location lookup table",OWNE:"Ownership frame",PRIV:"Private frame",PCNT:"Play counter",POPM:"Popularimeter",POSS:"Position synchronisation frame",RBUF:"Recommended buffer size",RVAD:"Relative volume adjustment",RVRB:"Reverb",SYLT:"Synchronized lyric/text",SYTC:"Synchronized tempo codes",
	    TALB:"Album/Movie/Show title",TBPM:"BPM (beats per minute)",TCOM:"Composer",TCON:"Content type",TCOP:"Copyright message",TDAT:"Date",TDLY:"Playlist delay",TENC:"Encoded by",TEXT:"Lyricist/Text writer",TFLT:"File type",TIME:"Time",TIT1:"Content group description",TIT2:"Title/songname/content description",TIT3:"Subtitle/Description refinement",TKEY:"Initial key",TLAN:"Language(s)",TLEN:"Length",TMED:"Media type",TOAL:"Original album/movie/show title",TOFN:"Original filename",TOLY:"Original lyricist(s)/text writer(s)",
	    TOPE:"Original artist(s)/performer(s)",TORY:"Original release year",TOWN:"File owner/licensee",TPE1:"Lead performer(s)/Soloist(s)",TPE2:"Band/orchestra/accompaniment",TPE3:"Conductor/performer refinement",TPE4:"Interpreted, remixed, or otherwise modified by",TPOS:"Part of a set",TPUB:"Publisher",TRCK:"Track number/Position in set",TRDA:"Recording dates",TRSN:"Internet radio station name",TRSO:"Internet radio station owner",TSIZ:"Size",TSRC:"ISRC (international standard recording code)",TSSE:"Software/Hardware and settings used for encoding",
	    TYER:"Year",TXXX:"User defined text information frame",UFID:"Unique file identifier",USER:"Terms of use",USLT:"Unsychronized lyric/text transcription",WCOM:"Commercial information",WCOP:"Copyright/Legal information",WOAF:"Official audio file webpage",WOAR:"Official artist/performer webpage",WOAS:"Official audio source webpage",WORS:"Official internet radio station homepage",WPAY:"Payment",WPUB:"Publishers official webpage",WXXX:"User defined URL link frame"};var f={title:["TIT2","TT2"],artist:["TPE1",
		    "TP1"],album:["TALB","TAL"],year:["TYER","TYE"],comment:["COMM","COM"],track:["TRCK","TRK"],genre:["TCON","TCO"],picture:["APIC","PIC"],lyrics:["USLT","ULT"]},c=["title","artist","album","track"];d.o=function(a,b){a.f([0,i(6,a)],b)};d.p=function(a,b){var g=0,h=a.a(g+3);if(h>4)return{version:">2.4"};var e=a.a(g+4),v=a.d(g+5,7),k=a.d(g+5,6),s=a.d(g+5,5),j=i(g+6,a);g+=10;if(k){var o=a.h(g,!0);g+=o+4}var h={version:"2."+h+"."+e,major:h,revision:e,flags:{unsynchronisation:v,extended_header:k,experimental_indicator:s},
	    size:j},l;if(v)l={};else{j-=10;for(var v=a,e=b,k={},s=h.major,o=[],n=0,m;m=(e||c)[n];n++)o=o.concat(f[m]||[m]);for(e=o;g<j;){o=q;n=v;m=g;var u=q;switch(s){case 2:l=n.c(m,3);var r=n.q(m+3),t=6;break;case 3:l=n.c(m,4);r=n.h(m+4,!0);t=10;break;case 4:l=n.c(m,4),r=i(m+4,n),t=10}if(l=="")break;g+=t+r;if(!(e.indexOf(l)<0)&&(s>2&&(u={message:{Y:n.d(m+8,6),K:n.d(m+8,5),V:n.d(m+8,4)},m:{T:n.d(m+8+1,7),H:n.d(m+8+1,3),J:n.d(m+8+1,2),D:n.d(m+8+1,1),w:n.d(m+8+1,0)}}),m+=t,u&&u.m.w&&(i(m,n),m+=4,r-=4),!u||!u.m.D))l in
    d.b?o=d.b[l]:l[0]=="T"&&(o=d.b["T*"]),o=o?o(m,r,n,u):void 0,o={id:l,size:r,description:l in d.frames?d.frames[l]:"Unknown",data:o},l in k?(k[l].id&&(k[l]=[k[l]]),k[l].push(o)):k[l]=o}l=k}for(var w in f)if(f.hasOwnProperty(w)){a:{r=f[w];typeof r=="string"&&(r=[r]);t=0;for(g=void 0;g=r[t];t++)if(g in l){a=l[g].data;break a}a=void 0}a&&(h[w]=a)}for(var x in l)l.hasOwnProperty(x)&&(h[x]=l[x]);return h};g.ID3v2=d})(this);(function(){function g(d){var f;switch(d){case 0:f="iso-8859-1";break;case 1:f="utf-16";break;case 2:f="utf-16be";break;case 3:f="utf-8"}return f}var i=["32x32 pixels 'file icon' (PNG only)","Other file icon","Cover (front)","Cover (back)","Leaflet page","Media (e.g. lable side of CD)","Lead artist/lead performer/soloist","Artist/performer","Conductor","Band/Orchestra","Composer","Lyricist/text writer","Recording Location","During recording","During performance","Movie/video screen capture","A bright coloured fish",
	    "Illustration","Band/artist logotype","Publisher/Studio logotype"];ID3v2.b.APIC=function(d,f,c,a,b){var b=b||"3",a=d,p=g(c.a(d));switch(b){case "2":var h=c.c(d+1,3);d+=4;break;case "3":case "4":h=c.e(d+1,f-(d-a),p),d+=1+h.g}b=c.a(d,1);b=i[b];p=c.e(d+1,f-(d-a),p);d+=1+p.g;return{format:h.toString(),type:b,description:p.toString(),data:c.n(d,a+f-d)}};ID3v2.b.COMM=function(d,f,c){var a=d,b=g(c.a(d)),i=c.c(d+1,3),h=c.e(d+4,f-4,b);d+=4+h.g;d=c.e(d,a+f-d,b);return{language:i,X:h.toString(),text:d.toString()}};
	    ID3v2.b.COM=ID3v2.b.COMM;ID3v2.b.PIC=function(d,f,c,a){return ID3v2.b.APIC(d,f,c,a,"2")};ID3v2.b.PCNT=function(d,f,c){return c.O(d)};ID3v2.b.CNT=ID3v2.b.PCNT;ID3v2.b["T*"]=function(d,f,c){var a=g(c.a(d));return c.e(d+1,f-1,a).toString()};ID3v2.b.TCON=function(){return ID3v2.b["T*"].apply(this,arguments).replace(/^\(\d+\)/,"")};ID3v2.b.TCO=ID3v2.b.TCON;ID3v2.b.USLT=function(d,f,c){var a=d,b=g(c.a(d)),i=c.c(d+1,3),h=c.e(d+4,f-4,b);d+=4+h.g;d=c.e(d,a+f-d,b);return{language:i,I:h.toString(),U:d.toString()}};
	    ID3v2.b.ULT=ID3v2.b.USLT})();(function(g){function i(c,a,b,d){var g=c.h(a,!0);if(g==0)d();else{var e=c.c(a+4,4);["moov","udta","meta","ilst"].indexOf(e)>-1?(e=="meta"&&(a+=4),c.f([a+8,a+8+8],function(){i(c,a+8,g-8,d)})):c.f([a+(e in f.l?0:g),a+g+8],function(){i(c,a+g,b,d)})}}function d(c,a,b,g,h){for(var h=h===void 0?"":h+"  ",e=b;e<b+g;){var i=a.h(e,!0);if(i==0)break;var k=a.c(e+4,4);if(["moov","udta","meta","ilst"].indexOf(k)>-1){k=="meta"&&(e+=4);d(c,a,e+8,i-8,h);break}if(f.l[k]){var s=a.q(e+16+1),j=f.l[k],s=f.types[s];if(k==
	  "trkn")c[j[0]]=a.a(e+16+11),c.count=a.a(e+16+13);else{var k=e+16+4+4,o=i-16-4-4;switch(s){case "text":c[j[0]]=a.e(k,o,"UTF-8");break;case "uint8":c[j[0]]=a.r(k);break;case "jpeg":case "png":c[j[0]]={m:"image/"+s,data:a.n(k,o)}}}}e+=i}}var f=g.v={};f.types={0:"uint8",1:"text",13:"jpeg",14:"png",21:"uint8"};f.l={"\u00a9alb":["album"],"\u00a9art":["artist"],"\u00a9ART":["artist"],aART:["artist"],"\u00a9day":["year"],"\u00a9nam":["title"],"\u00a9gen":["genre"],trkn:["track"],"\u00a9wrt":["composer"],
	    "\u00a9too":["encoder"],cprt:["copyright"],covr:["picture"],"\u00a9grp":["grouping"],keyw:["keyword"],"\u00a9lyr":["lyrics"],"\u00a9gen":["genre"]};f.o=function(c,a){c.f([0,7],function(){i(c,0,c.j(),a)})};f.p=function(c){var a={};d(a,c,0,c.j());return a};g.ID4=g.v})(this);

  </script>
	<?php
}
