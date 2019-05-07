<?php

if ( defined( 'WP_CLI' ) && WP_CLI ) {

	class Nodomain_Sites extends WP_CLI_Command {

		protected $db;

		public function __construct() {
			$this->db = $GLOBALS['wpdb'];
		}

				
		/* Custom functions here */

		public function list($args, $assoc_args) {

			//USAGE: wp nodomain_sites list --months=6 will list nodomain sites registered at least 6 months ago
			//default is 3 months

			$this->args       = $args;
			$this->assoc_args = $assoc_args;

			if (isset ($assoc_args['months']) && ($assoc_args['months']) > 0)
				$months = $assoc_args['months'];
			else
				$months = "3";

			$nodomain_sites = $this->db->get_results( "SELECT b.blog_id as blog_id,b.registered as registered FROM wordpressdb3_blogs b
			LEFT JOIN wordpressdb3_domain_mapping dm ON dm.blog_id = b.blog_id
			WHERE dm.blog_id IS NULL AND b.blog_id > 1 AND b.registered < (CURRENT_DATE() - INTERVAL ".$months." MONTH) " );

			foreach($nodomain_sites as $site)
			{
				echo "Site ID: ".$site->blog_id;
				echo "\nRegistered on ".$site->registered."\n\n";
			}

			echo "\n\nTotal: ".count($nodomain_sites)." sites older than ".$months." months have no associated domain.\n\n";

		}

		public function disable($args, $assoc_args) {

			//USAGE: wp nodomain_sites disable --months=6 will disable nodomain sites registered at least 6 months ago
			//default is 3 months

			WP_CLI::confirm( 'BE CAREFUL, a UPDATE statement cannot be undone so please backup your database before proceeding. Are you sure you want to proceed?', $assoc_args = array() );

			$this->args       = $args;
			$this->assoc_args = $assoc_args;

			if (isset ($assoc_args['months']) && ($assoc_args['months']) > 0)
				$months = $assoc_args['months'];
			else
				$months = "3";

			$disable_sites = $this->db->query( "UPDATE wordpressdb3_blogs b
			LEFT JOIN wordpressdb3_domain_mapping dm ON dm.blog_id = b.blog_id
			SET b.deleted = 1
			WHERE dm.blog_id IS NULL AND b.blog_id > 1 AND b.registered < (CURRENT_DATE() - INTERVAL ".$months." MONTH) " );

			echo $disable_sites. " sites were disabled!";

		}
	}

	WP_CLI::add_command( 'nodomain_sites', 'Nodomain_Sites' );
}
