<?php
if (!defined('WPINC')) die('Go hack your mama!'); //tx ByREV ;)

add_filter('title_save_pre', 'vd_clean_titles', 0);

function vd_clean_titles($title) {
	$wsymbols = array('„','”','“','‚','’','‘','–','—','_','…','«','»');
	$newsymbols = array('"','"','"','\'','\'','\'','-','-','-','...','"','"');

	$sedile = array('ş','Ş','ţ','Ţ');
	$virgule = array('ș','Ș','ț','Ț');

	$vd_clean_title = $title;

	$vdctoptions = vd_clean_titles_get_options(); //verificam setarile din optiunile pluginului
	
	if($vdctoptions['replace_symbols'] == 'y'){ //verificam daca inlocuim simbolurile
		$vd_clean_title = str_replace($wsymbols, $newsymbols, $vd_clean_title);
	}

	if($vdctoptions['replace_ro_chars'] == 'y'){ //verificam daca inlocuim caracterele romanesti
		if($vdctoptions['ro_chars'] == 'virgula'){ //transformam sedilele in virgule
			$vd_clean_title = str_replace($sedile, $virgule, $vd_clean_title);
		}
		if($vdctoptions['ro_chars'] == 'sedile'){ //transformam virgulele in sedile
			$vd_clean_title = str_replace($virgule, $sedile, $vd_clean_title);
		}
	}

	return $vd_clean_title;
}

add_action('admin_menu', 'vd_clean_titles_admin_menu');

	function vd_clean_titles_admin_menu(){
		add_options_page('Clean Titles', 'Clean Titles', 9, basename(__FILE__), 'vd_clean_titles_options_page');
	}

	function vd_clean_titles_get_options(){
		$defaults = array();
		$defaults['replace_symbols'] = 'y';
		$defaults['replace_ro_chars'] = 'y';
		$defaults['ro_chars'] = 'virgula';
					
		$options = get_option('vd_clean_titles_settings');
		if (!is_array($options)){
			$options = $defaults;
			update_option('vd_clean_titles_settings', $options);
		}
	    
		return $options;
	}


	function vd_clean_titles_options_page(){
		if ($_POST['vd_clean_titles']){
			update_option('vd_clean_titles_settings', $_POST['vd_clean_titles']);
			$message = '<div class="updated"><p><strong>Options saved</strong></p></div>';
		}

		$vdctoptions = vd_clean_titles_get_options();
		$resyes = ($vdctoptions['replace_symbols'] == 'y') ? ' checked="checked"' : '';
		$resno = ($vdctoptions['replace_symbols'] == 'n') ? ' checked="checked"' : '';
		$rrcyes = ($vdctoptions['replace_ro_chars'] == 'y') ? ' checked="checked"' : '';
		$rrcno = ($vdctoptions['replace_ro_chars'] == 'n') ? ' checked="checked"' : '';
		$virgulayes = ($vdctoptions['ro_chars'] == 'virgula') ? ' checked="checked"' : '';
		$sedileyes = ($vdctoptions['ro_chars'] == 'sedile') ? ' checked="checked"' : '';

		echo <<<EOT
		<div class="wrap">
			<h2>Clean Titles Options</h2>
<br>
			{$message}
<br>
			<form name="form1" method="post" action="options-general.php?page=vd-clean-titles-admin.php">
			<fieldset>
				<p><strong>Replace symbols in titles:</strong> <input type="radio" value="y" name="vd_clean_titles[replace_symbols]"{$resyes} /> ON <input type="radio" value="n" name="vd_clean_titles[replace_symbols]"{$resno} /> OFF</p>
<p>If this setting is ON the following symbols will be replaced in titles during the save action:</p>
<table style="border:1px solid #444444; padding:3px;">
  <tr>
    <td style="background:#cccccc;"><em>Symbol</em></td>
    <td style="background:#cccccc;"><em>Explanation of the symbol</em></td>
    <td style="background:#cccccc;"><em>Replaced with</em></td>
  </tr>
  <tr>
    <td align="center">„</td>
    <td>quotation mark - double opening down</td>
    <td align="center">&quot;</td>
  </tr>
  <tr>
    <td align="center">”</td>
    <td>quotation mark - double closing</td>
    <td align="center">&quot;</td>
  </tr>
  <tr>
    <td align="center">“</td>
    <td>quotation mark - double opening up</td>
    <td align="center">&quot;</td>
  </tr>
  <tr>
    <td align="center">«</td>
    <td>angle quotes (guillemets) opening</td>
    <td align="center">&quot;</td>
  </tr>
  <tr>
    <td align="center">»</td>
    <td>angle quotes (guillemets) closing</td>
    <td align="center">&quot;</td>
  </tr>
  <tr>
    <td align="center">‚</td>
    <td>quotation mark - single opening down</td>
    <td align="center">'</td>
  </tr>
  <tr>
    <td align="center">’</td>
    <td>quotation mark - single closing (&amp; apostrophe)</td>
    <td align="center">'</td>
  </tr>
  <tr>
    <td align="center">‘</td>
    <td>quotation mark - single opening up</td>
    <td align="center">'</td>
  </tr>
  <tr>
    <td align="center">–</td>
    <td>en dash</td>
    <td align="center">-</td>
  </tr>
  <tr>
    <td align="center">—</td>
    <td>em dash</td>
    <td align="center">-</td>
  </tr>
  <tr>
    <td align="center">_</td>
    <td>underscore</td>
    <td align="center">-</td>
  </tr>
  <tr>
    <td align="center">…</td>
    <td>ellipsis</td>
    <td align="center">...</td>
  </tr>
</table>
<br><br>
				<p><strong>Replace Romanian characters in titles:</strong> <input type="radio" value="y" name="vd_clean_titles[replace_ro_chars]"{$rrcyes} /> YES <input type="radio" value="n" name="vd_clean_titles[replace_ro_chars]"{$rrcno} /> NO</p>
				<blockquote><blockquote>
					If YES what type of Romanian characters would you like to use?<br><br>
<input type="radio" value="virgula" name="vd_clean_titles[ro_chars]"{$virgulayes} /> Ș Ț - characters with comma accents <em>(these are the CORRECT glyphs according to the Romanian Academy - fully compatible with newer versions of Windows (Vista, Windows 7))</em><br><em>If you decide to use Romanian characters with comma accents in the titles we strongly recommend using <a href="http://wordpress.org/extend/plugins/ro-permalinks/" targe="_blank">RO Permalinks</a> plugin!</em>
<br><br><input type="radio" value="sedile" name="vd_clean_titles[ro_chars]"{$sedileyes} /> Ş Ţ - characters with cedillas <em>(incorrect glyphs but compatible with more versions of Windows (XP, Vista, 7))</em>					
				</blockquote></blockquote>
				</fieldset>
<br><input type="submit" name="Submit" value="Save settings &raquo;" />
			</form>
		</div>
		<div>
<br><br><br>	
  <p>More details about this plugin (in Romanian):<br><a href="http://vlad.dulea.ro/2010/12/15/plugin-wordpress-titluri-curate/" target="_blank">http://vlad.dulea.ro/2010/12/15/plugin-wordpress-titluri-curate/</a></p>
	</div>
EOT;
	}
?>