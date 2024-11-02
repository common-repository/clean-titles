<?php
/*
Plugin Name: Clean Titles
Plugin URI: http://vlad.dulea.ro/2010/12/15/plugin-wordpress-titluri-curate/
Description: Replacing special characters in titles before saving them
Version: 1.2
Author: Vlad Dulea
Author URI: http://vlad.dulea.ro
*/

if (is_admin()) { include('vd-clean-titles-admin.php'); }

?>