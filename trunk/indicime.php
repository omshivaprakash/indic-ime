<?php
/*
 * Plugin Name: Indic IME
 * Plugin URI: http://www.vishalon.net/IndicResources/IndicIME/tabid/244/Default.aspx
 * Version: 2.0
 * Description: Now easily type in your favourite Indian language with "The way you speak, the way you type" rule with this plugin.
 * Author: Vishal Monpara
 * Author URI: http://www.vishalon.net
 */
if (!class_exists('IndicIME')) 
{
	class IndicIME
	{
		var $wpversion;
		var $baseURL;
		var $settings = array();
		function IndicIME()
    		{
			// Figure out the WordPress version
			global $wp_db_version;
			$this->settings = array('scriptlist' => 'All','link' => 'on');
			
			if ( $wp_db_version > 6124 ) 
				$this->wpversion = 2.5;
			elseif ( class_exists('WP_Scripts') )
				$this->wpversion = 2.1;
			else
				$this->wpversion = 2.0;
			if ($this->wpversion < 2.5)
			{
				add_filter("mce_plugins", array(&$this, 'add_plugin'));
				add_action("tinymce_before_init", array(&$this, 'load_plugin'));
			}
			else
			{
				add_action('init', array(&$this,'load_plugin'));
			}
			$this->baseURL = get_settings('siteurl'). "/wp-includes/js/tinymce/plugins/indicime/";
			// Common for all versions
	  		add_filter("mce_buttons", array(&$this, 'add_button'));
			add_action('wp_footer', array(&$this,'add_indicime'));
			add_action('admin_footer', array(&$this,'add_indicime'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
    		}

		function add_plugin($plugins) {
			if($this->wpversion < 2.5)
	  			$plugins[] = "indicime"; // the leading "-" means it's an external plugin
			else
				$plugins['indicime'] = $this->baseURL . "editor_plugin.js";
  			 return $plugins;
		}
		function add_indicime()
		{
			$opt = get_option('indicime_options');
			if(is_array($opt))
				$this->settings = $opt;

			if ($this->settings['link'] != 'on')
				echo '<!-- IndicIME http://www.vishalon.net/tabid/244/Default.aspx -->';
			?>
			<script type='text/javascript' src='<?php echo $this->baseURL?>editor_plugin.js'></script>
			<script language="JavaScript" type="text/javascript">
			var y = 2, pphText, bu = "<?php echo $this->baseURL, '", lang="', $this->settings['scriptlist'],'"';?>; 
			function indicChangePos(){document.getElementById("indicimelayer").style.top=(document.all?document.documentElement.scrollTop:window.pageYOffset)+y+"px"}
			
			function tl(e1,e2){
			var elem=document.getElementById(e2);var el=document.getElementById(e1);
			if(elem.style.display=="none"){elem.style.display="";if(e1=="pin")el.src=bu + "img/pin2.gif"}
				else{elem.style.display="none";if(e1=="pin")el.src=bu+"img/pin1.gif"}
			}
			function indicChange(id, script)
			{
				pphText.setGlobalScript(script);
				document.getElementById('icm').src = bu + "img/" + script + 'charmap.gif';
				document.getElementById('language').value = script.toLowerCase();
			}
			function initIndicIME()
			{
				pphText  = new PramukhPhoneticHandler();
				if(lang=='All') lang= "english";
				
				if(pphText.convertAllToIndicIME(lang, indicChange) > 0)
					window.onscroll=indicChangePos;
				else{
					document.getElementById('indicimelayer').style.display = "none";
				}
				document.getElementById('icm').src = bu + "img/" + lang + 'charmap.gif';
				document.getElementById('language').value = lang.toLowerCase();
			}
			
			if(typeof addLoadEvent == 'function')
				addLoadEvent(initIndicIME);
			else
				window.onload = initIndicIME;
			
			</script>
			<div id="indicimelayer" style="position:absolute; right:2px; top:2px; padding:3px; visibility:visible; font-family:verdana, arial, helvetica, sans-serif; font-size:10px; border: 1px solid black; text-align:right; background-color:white;">
		<div style="float:left"><img src="<?php echo $this->baseURL?>img/pin2.gif" id="pin" onclick="tl('pin','indiccontent');" style="cursor: pointer; cursor: hand;" title="Toggle IndicIME visibility"></div><div id="indiccontent">&nbsp;Type in <select name="language" id="language" onchange="indicChange(this.id ,this.options[this.selectedIndex].value);" style="font-family:verdana, arial, helvetica, sans-serif; font-size:10px;"> 
		<?php
		if($this->settings['scriptlist']=='All'){
		?>
		<option value="bengali">Bengali</option><option value="devnagari">Devnagari</option>
		<option value="gujarati">Gujarati</option><option value="gurmukhi">Gurmukhi</option><option value="kannada">Kannada</option>
		<option value="malayalam">Malayalam</option><option value="oriya">Oriya</option><option value="tamil">Tamil</option><option value="telugu">Telugu</option>
		<?php
		}
		else
			echo "<option value='" , strtolower($this->settings['scriptlist']), "' selected>" , $this->settings['scriptlist'] , '</option>';
		?>
		<option value="english" >English (F12)</option></select> <img src="<?php echo $this->baseURL?>img/help.gif" id="hp" onclick="tl('hp','icmc');return false;" style="cursor: pointer; cursor: hand;" title="Toggle help description"><div id="icmc" style="display:none;width:540px;text-align:left;">Select Indian script from the list and type with 'The way you speak, the way you type' rule on this page. Refer to following image for details. Press F12 to toggle between Indic script and English.<br><img src="<?php echo $this->baseURL?>img/englishcharmap.gif" alt="indic script char map" id="icm">
		
			<?php
			if ($this->settings['link'] == 'on')
				echo 'Powered By <a href="http://www.vishalon.net/IndicResources/IndicIME/tabid/244/Default.aspx" target="_blank">Indic IME</a>';
			echo '</div></div></div>';
		}
		function &add_button(&$buttons) {
			$wp_sc= array_search('spellchecker', $buttons);
			if ($wp_sc !== false) {
				array_splice($buttons, $wp_sc +1 , 0, array('indicime','indicimehelp'));
			}
			else
			{
				$buttons[] = "indicime";
				$buttons[] = "indicimehelp";
			}
			return $buttons;

		}
		function admin_menu() {
			add_options_page("IndicIME Settings", "Indic IME", 'manage_options', basename(__FILE__), array(&$this, 'optionspage'));
		}
		function optionspage() {
		if ( $_POST && basename(__FILE__) == $_GET['page'] ) {
			// Saving data
			$this->settings = array('scriptlist' => $_POST['scriptlist'],'link' => $_POST['promotion']);
			update_option('indicime_options', $this->settings);
			if( !empty($_POST) )
				echo "\n" . '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>' . "\n";
		}
		$opt = get_option('indicime_options');
		if(is_array($opt))
			$this->settings = $opt;
			?>
			<div class="wrap">
				<h2>Indic IME Settings</h2>
				<form name="indicimesetting" method="post" action="">
				<table class="<?php echo ( $this->wpversion < 2.5 ) ? 'optiontable' : 'form-table'; ?>">
			<tr valign="top">
				<th scope="row">Script List Includes</th>
				<td>
					<select name="scriptlist">
						<option value="All"<?php selected($this->settings['scriptlist'], 'All'); ?>>All Scripts</option>
						<option value="Bengali"<?php selected($this->settings['scriptlist'], 'Bengali'); ?>>Bengali</option>
						<option value="Devnagari"<?php selected($this->settings['scriptlist'], 'Devnagari'); ?>>Devnagari</option>
						<option value="Gujarati"<?php selected($this->settings['scriptlist'], 'Gujarati'); ?>>Gujarati</option>
						<option value="Gurmukhi"<?php selected($this->settings['scriptlist'], 'Gurmukhi'); ?>>Gurmukhi</option>
						<option value="Kannada"<?php selected($this->settings['scriptlist'], 'Kannada'); ?>>Kannada</option>
						<option value="Malayalam"<?php selected($this->settings['scriptlist'], 'Malayalam'); ?>>Malayalam</option>
						<option value="Oriya"<?php selected($this->settings['scriptlist'], 'Oriya'); ?>>Oriya</option>
						<option value="Tamil"<?php selected($this->settings['scriptlist'], 'Tamil'); ?>>Tamil</option>
						<option value="Telugu"<?php selected($this->settings['scriptlist'], 'Telugu'); ?>>Telugu</option>
					</select> <small>Visitor will see either all or a selected script and English in IndicIME bar.</small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show Link?</th>
				<td>
					<label>
						<input name="promotion" type="checkbox" <?php checked($this->settings['link'], 'on') ; ?>/>
						 "Powered By IndicIME" link will be shown to visitor to promote Indian langauges over internet.
					</label>
				</td>
			</tr>
			</table>
			<p class="submit">
				<input type="submit" name="save" value="Save Changes" />
			</p>

	</form>
			</div>
			<?php
		}
		function load_plugin() {
			if($this->wpversion < 2.5)
  				echo "tinyMCE.loadPlugin('indicime', '" . $this->baseURL . "');\n";
			else
			{
				if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
					return;
				if ( get_user_option('rich_editing') == 'true')
				{
					add_filter("mce_external_plugins", array(&$this,"add_plugin"));
					add_filter('mce_buttons', array(&$this,'add_button'));
				}
			}
		}
	} // End class
} // End If
$indicime = new IndicIME();
?>
