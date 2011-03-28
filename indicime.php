<?php
/*
 * Plugin Name: Indic IME
 * Plugin URI: http://www.vishalon.net/IndicResources/IndicIME.aspx
 * Version: 2.5.1
 * Description: Now easily type in your favourite Indian language with "The way you speak, the way you type" rule with this plugin. Supported Scripts: Bengali(Assamese, Bengali, Manipuri), Devanagari(Hindi, Marathi, Nepali, Sanskrit), Gujarati, Gurmukhi(Punjabi), Kannada, Malaylam, Oriya, Tamil, Telugu
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
			$this->settings = array('scriptlist' => 'defaultlist','link' => 'on');
			$opt = get_option('indicime_options');
			if(is_array($opt))
				$this->settings = $opt;
			if($this->settings['link'] != 'off')
				$this->settings['link'] = 'on';
			if($this->settings['scriptlist'] == 'All')
				$this->settings['scriptlist'] = 'defaultlist';

			if ( $wp_db_version > 6124 ) 
				$this->wpversion = 2.5;
			elseif ( class_exists('WP_Scripts') )
				$this->wpversion = 2.1;
			else
				$this->wpversion = 2.0;
			
			$this->baseURL = get_option('siteurl')."/" . PLUGINDIR . "/" . str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) .  "indicime/";
			add_action('init', array(&$this,'load_plugin'));
			add_action('wp_footer', array(&$this,'add_indicime'));
			add_action('admin_footer', array(&$this,'add_indicime'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_head', array(&$this, 'add_css'));
			add_action('wp_head', array(&$this, 'add_css'));
    	}
    	function add_scripts($init)
    	{
    		$lst = $this->settings['scriptlist'];
    		if($lst != 'defaultlist')
    			$lst .= ";English (F12):English";
    		if($this->wpversion < 2.5)
			{
    			echo "initArray['indicime_scripts'] = '$lst';";
  				echo "tinyMCE.loadPlugin('indicime', '" . $this->baseURL . "');\n";
  				return;
			}
			else
			{
				$init['indicime_scripts'] = $lst;
    			return $init;
			}
    	}
    	function add_css()
    	{
    		echo '<style type="text/css"> #indicimelayer {border: 1px solid black; padding: 3px; font-size:10px;font-family: verdana, arial, helvetica, sans-serif; position:fixed; background-color:white; top:2px; right:2px; text-align:right;display:inline; z-index:1002;}
	* html #indicimelayer{position: absolute; top: expression(document.compatMode=="CSS1Compat"? document.documentElement.scrollTop+2+"px" : body.scrollTop+2+"px"); }
	</style>';
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
			?>
			<div id="indicimelayer">
				Powered By <a href="http://www.vishalon.net/IndicResources/IndicIME.aspx" target="_blank">Indic IME</a>
			</div>
			<script language="JavaScript" type="text/javascript">
			var pphText, indicime_bu = "<?php echo $this->baseURL, '", indicime_script="', $this->settings['scriptlist'],'", indicime_lnk = "', $this->settings['link'], '"' ;?>; 
			initIndicIME();
			</script>
			<?php
		}
		function &add_button($buttons) {
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
			add_options_page("IndicIME Settings", "Indic IME", 'manage_options', plugin_basename(__FILE__), array(&$this, 'optionspage'));
		}
		function optionspage() {
		if ( $_POST && plugin_basename(__FILE__) == $_GET['page'] ) {
			// Saving data
			$s = addslashes(htmlspecialchars($_POST['scriptlist']));
			$l = addslashes(htmlspecialchars($_POST['promotion']));
			$c = addslashes(htmlspecialchars($_POST['customname']));
			if($s != 'defaultlist')
				$s =  (($c =='')?$s:$c) . ':'. $s;
			if($l != "on")
				$l = "off";
			$this->settings = array('scriptlist' => $s,'link' => $l);
			update_option('indicime_options', $this->settings);
			if( !empty($_POST) )
				echo "\n" . '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>' . "\n";
		}
		
		list($customname, $script) = explode(":", $this->settings['scriptlist']);
		
		if($script == '')
			$script = $customname;
		
			?>
			<script>
				function changeScript(val)
				{
					var cn = document.getElementById('customname');
					cn.disabled = (val == 'defaultlist');
					cn.value = (val == 'defaultlist')?'':val;
				}
			</script>
			<div class="wrap">
				<h2>Indic IME Settings</h2>
				<p>Here you can change the Indic IME settings.</p>
				<ol>
				<li>You should choose the script from the list which you want to enable for user(admin and visitors)</li>
				<li>If single script is selected, you can give custom name to it. For example, you may choose "Devanagari" from the list but show the user "Hindi" as option by setting "Hindi" as Custom Script Name.</li>
				<li>If you would like to promote Indian languages over Internet, please check the box.</li>
				<li>Click on "Save Changes" button to save your settings.</li>
				</ol>
				<form name="indicimesetting" method="post" action="">
				<table class="<?php echo ( $this->wpversion < 2.5 ) ? 'optiontable' : 'form-table'; ?>">
			<tr valign="top">
				<th scope="row">Enable</th>
				<td>
					<select name="scriptlist" id="scriptlist" onchange='changeScript(this.options[this.selectedIndex].value);'>
						<option value="defaultlist"<?php selected($script, 'defaultlist'); ?>>All Scripts</option>
						<option value="Bengali"<?php selected($script, 'Bengali'); ?>>Bengali</option>
						<option value="Devanagari"<?php selected($script, 'Devanagari'); ?>>Devanagari</option>
						<option value="Gujarati"<?php selected($script, 'Gujarati'); ?>>Gujarati</option>
						<option value="Gurmukhi"<?php selected($script, 'Gurmukhi'); ?>>Gurmukhi</option>
						<option value="Kannada"<?php selected($script, 'Kannada'); ?>>Kannada</option>
						<option value="Malayalam"<?php selected($script, 'Malayalam'); ?>>Malayalam</option>
						<option value="Oriya"<?php selected($script, 'Oriya'); ?>>Oriya</option>
						<option value="Tamil"<?php selected($script, 'Tamil'); ?>>Tamil</option>
						<option value="Telugu"<?php selected($script, 'Telugu'); ?>>Telugu</option>
					</select> <small>Visitor will see either all or a selected script and English in IndicIME bar.</small>
				</td>
			</tr>
			<tr>
				<th scope="row">Custom Script Name</th>
				<td><input name="customname" id="customname" type="text" <?php echo ($script == 'defaultlist')? "disabled": ""?> value="<?php echo ($script == 'defaultlist')?'':$customname?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Show Link?</th>
				<td>
					<label>
						<input name="promotion" type="checkbox" <?php checked($this->settings['link'], 'on') ; ?>/>
						 Show "Powered By IndicIME" link to promote Indian langauges.
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
		function load_plugin()
		{
			wp_register_script("pramukhlib", $this->baseURL . "editor_plugin.js");
			wp_register_script("pramukhlibhelper", $this->baseURL . "indicime.js");
			wp_enqueue_script("pramukhlib");
			wp_enqueue_script("pramukhlibhelper");
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
					return;
				if ( get_user_option('rich_editing') == 'true')
				{
					add_filter($this->wpversion < 2.5?"mce_plugins":"mce_external_plugins", array(&$this, 'add_plugin'));
					add_action($this->wpversion < 2.5?"tinymce_before_init":"tiny_mce_before_init", array(&$this, 'add_scripts'));
					add_filter('mce_buttons', array(&$this,'add_button'));
				}
		}
	} // End class
} // End If
$indicime = new IndicIME();
?>
