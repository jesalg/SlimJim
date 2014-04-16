<?php
	
	/* config.sample.php
	 * 
	 * Copy this file to 'config.php' and manage your custom configuration settings there.
	 */

	class CUSTOM_CONFIG {
		
		/* Paths */
		public static $ROOT_PATH				= '/srv/www/slimjim.yourcompany.com/public_html/';
						
		/* MySQL */
		public static $DB_NAME					= 'slimjim';
		public static $DB_HOST					= 'localhost';
		public static $DB_USER					= 'root';
		public static $DB_PASS					= '';

		/* CURL/SSL settings */
		public static $CURL_SSL_VERIFICATION	= true;			//[true(default), false]. Bypass curl SSL verification, e.g. for a server with a self-signed SSL certificate.
	}
	
?>