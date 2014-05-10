<?php
	
	/* config.sample.php
	 * 
	 * Copy this file to 'config.php' and manage your custom configuration settings there.
	 */

	class CUSTOM_CONFIG {
		
		/* Paths */
		public static $ROOT_PATH					= '/var/www/your.slimjim.path/';
						
		/* MySQL */
		public static $DB_NAME						= 'slimjim';
		public static $DB_HOST						= 'localhost';
		public static $DB_USER						= 'slimjim';
		public static $DB_PASS						= '';
		
		/* Github settings */
		public static $GITHUB_CIDR_VERIFICATION		= true;			//[true(default), false]. Bypass CIDR(IP) verification of hook requests, e.g. in case of curl/ssl problems (alternative: restrict access via .htaccess). WARNING: Use with caution as this setting value "false" introduces a potential security risk!
		public static $GITHUB_CURL_SSL_VERIFICATION	= true;			//[true(default), false]. Bypass curl SSL verification, e.g. for a server with a self-signed SSL certificate. WARNING: Use with caution as this setting value "false" introduces a potential security risk!
	}
	
?>