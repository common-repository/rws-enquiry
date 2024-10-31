<?php
/**
Plugin Name: RWS Enquiry And Lead Follow-up
Plugin URI: http://rhizomaticweb.com/rws-enquiry-wp/
Description: Genrate leads with their Follow ups. Best way manage follow ups with respect to enquies. 
Version: 1.0
Author: Rhizomatic Web Services
Author URI: http://rhizomaticweb.com/rws-enquiry-wp/
License: GPLv2 or later
**/ 

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'RWS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( RWS_PLUGIN_DIR . 'class.rws_enquiry_lead.php' );
require_once( RWS_PLUGIN_DIR . 'class.rws_followup.php' );

add_action( 'init', array( 'Rws_enquiry_lead', 'init' ) );

add_action( 'init', array( 'Rws_enquiry_lead','enquiry_rws') );

add_action( 'init', array( 'RWS_Followup', 'init' ) );
   

?>