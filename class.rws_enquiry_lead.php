<?php 
class Rws_enquiry_lead {
    private static $initiated = false;
		
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
   private static function init_hooks(){
	   
      self::$initiated = true;
        
	   // Enqueue Style Function
    add_action( 'admin_init',array( 'Rws_enquiry_lead','rws_css_script' ));
     
	   // Add Meta Fields
    add_action( 'admin_init', array( 'Rws_enquiry_lead','add_meta_for_email') );
      
	   //Disable initial autodraft
    add_action( 'admin_enqueue_scripts', array( 'Rws_enquiry_lead','wps_rws_enquiry_admin_enqueue_scripts') );
   
	   // Remove Editer From Enquiry.
    add_action( 'init', array( 'Rws_enquiry_lead','init_remove_support' ),100 );
   
	   //save rws Enquiry
    add_action( 'save_post', array( 'Rws_enquiry_lead','save_rws_enq_email') );
    
	   // Custom Template for Enquiry
    add_filter( 'single_template', array( 'Rws_enquiry_lead','get_enq_template') ) ;
    
	   // Add New Column Header
    add_filter( 'manage_rws_enquiry_followup_posts_columns', array( 'Rws_enquiry_lead','rws_columns_head') );
    
	   // Add New Column Data
    add_action( 'manage_rws_enquiry_followup_posts_custom_column', array( 'Rws_enquiry_lead','rws_columns_content'), 10, 2 );
	   
	   //Add Shortcode for Fron End Use
	add_shortcode( 'rws_enquiry',array( 'Rws_enquiry_lead','ty_front_end_form') );
}

/*
* Remove classic editor from post
*/
public static function init_remove_support() {
	
    $post_type = 'rws_enquiry_followup';
	
    remove_post_type_support( $post_type, 'editor' );
	
	remove_post_type_support( $post_type, 'author' );
}

/*
 * stop autodraft rws posts
 */
 public static function wps_rws_enquiry_admin_enqueue_scripts() {
	 
    if ( 'rws_enquiry_followup' == get_post_type() )
		
        wp_dequeue_script( 'autosave' );
}

/*
 * Enqueue Style
 */
public static function rws_css_script() {
	
	wp_register_style( 'rws_style', plugins_url( 'css/enq_style.css',__FILE__ ) );
	
	wp_enqueue_style( 'rws_style' );	
}

/*
 * Create RWS Enquiry
 */
public static function enquiry_rws() {
	// set up labels
	$labels = array(
 		'name' => 'Enquries',
    	'singular_name' => 'Enquiry',
    	'add_new' => 'Add New Enquiry',
    	'add_new_item' => 'Add New Enquiry',
    	'edit_item' => 'Edit Enquiry',
    	'new_item' => 'New Enquiry',
    	'all_items' => 'All Enquries',
    	'view_item' => 'View Enquiry',
    	'search_items' => 'Search Enquiry',
    	'not_found' =>  'No Enquiry Found',
    	'not_found_in_trash' => 'No Enquiry found in Trash', 
    	'parent_item_colon' => '',
    	'menu_name' => 'Enquiry',
		);	
	register_post_type( 'rws_enquiry_followup', array( 
	  'labels' => $labels,
	  'public' => true,
	  'has_archive' => true, 
	  'query_var' => 'enquires',
	  'rewrite' => array(
		  'slug' => 'enquiry'
	  ),
	  'supports' => array(
		  'title',
		  'editor',
		  'author',
	  ),
	  'can_export' =>  true, 
	) );
}
	
/* 
* Add Meta box to rws enquiry
*/
public static function add_meta_for_email() {
	
    add_meta_box( "enq_email", "Email", array( 'Rws_enquiry_lead',"meta_email"), "rws_enquiry_followup", "normal", "high" );
	
    add_meta_box( "enq_phone", "Phone", array( 'Rws_enquiry_lead',"meta_phone"), "rws_enquiry_followup", "normal", "high" );
	
    add_meta_box( "enq_adress", "Address", array( 'Rws_enquiry_lead',"meta_address"), "rws_enquiry_followup", "normal", "high" );
	
    add_meta_box( "enq_message", "Message", array( 'Rws_enquiry_lead',"meta_message"), "rws_enquiry_followup", "normal", "high" );
} 

// Add Meta Field Email To Enquiry Form
public static function meta_email() {
	
    global $post;
	
	$custom = get_post_custom($post->ID);
	
	$enq_email = "";
	
    if( count($custom) > 0 ) { 
		
		$enq_email = $custom["enq_email"][0];
		
	}?>

<label>Email:</label> <input type="email" name="enq_email" value="<?php echo $enq_email; ?>" style="width: 600px;"><?php

}

/*
 *  Add Meta Field Phone To Enquiry Form
*/
public static  function meta_phone() {
	
    global $post;
	
    $custom = get_post_custom($post->ID);
	
    $enq_phone ="";
	
	if( count($custom) > 0 ) {
		
		$enq_phone = $custom["enq_phone"][0]; 
		
	} ?>

<label>Phone:</label> <input type="number" name="enq_phone" value="<?php echo $enq_phone; ?>" style="width: 600px;"><?php
}

/*
 *  Add Meta Field Address To Enquiry Form
*/
public static  function meta_address() {
	
    global $post;
	
    $custom = get_post_custom($post->ID);
	
    $enq_address = "";
	
	if( count($custom) > 0 ) {
		
		$enq_address = $custom["enq_address"][0]; 
	}?>

 <label>Address:</label> <textarea name="enq_address" style="width: 600px;" ><?php echo $enq_address; ?></textarea><?php
} 

/*
 *  Add Meta Field Message To Enquiry Form
*/
public static  function meta_message() {
	
    global $post;
	
    $custom = get_post_custom($post->ID);
	
    $enq_msg = "";
	
	if( count($custom) > 0 ) {
		
		$enq_msg = $custom["enq_message"][0];
		
	}?>

 <label>Message:</label> <textarea name="enq_message" style="width: 600px;" ><?php echo $enq_msg; ?></textarea> <?php
}

/*
* Save Meta Field
*/
public static  function save_rws_enq_email() {
    global $post; 
	
	if($post){
		
		if(isset($_POST["enq_email"])) update_post_meta( $post->ID, "enq_email", $_POST["enq_email"] );
		
		if(isset($_POST["enq_phone"])) update_post_meta( $post->ID, "enq_phone", $_POST["enq_phone"] );
		
		if(isset($_POST["enq_address"])) update_post_meta( $post->ID, "enq_address", $_POST["enq_address"] );	
		
		if(isset($_POST["enq_message"])) update_post_meta( $post->ID, "enq_message", $_POST["enq_message"] );
	}
}
/**
 * Custom Template for Enquiry
 * @param $single_template
 * @return string
 */
public static  function get_enq_template( $single_template ) {
	
    global $wp_query, $post;
	
    if ( $post->post_type == 'rws_enquiry_followup' ){
		
        $single_template = plugin_dir_path( __FILE__ ) . 'enq_template.php';
		
    }
	
    return $single_template;
}
	
/**
 * ADD NEW COLUMN HEADER
 * @param $defaults
 * @return array
 */
public static  function rws_columns_head($defaults) {
	
    $defaults['message'] = 'Message';
	
	$defaults['phone'] = 'Phone';
	
	$defaults['address'] = 'Address';
	
	$defaults['email'] = 'Email';
	
	$defaults['followup'] = 'Follow Up';	
	
    unset( $defaults['comments'] );
	unset( $defaults['author'] );
	unset( $defaults['date'] );
	
    return $defaults;
} 

/**
 * ADD NEW COLUMN DATA
 * @param $column_name
 * @param $post_ID
 */
public static  function rws_columns_content( $column_name, $post_ID ) {
	
  	 	$custom = get_post_custom($post_ID);
	
		$enq_phone = $custom["enq_phone"][0];
	
		$enq_email = $custom["enq_email"][0];
	
    	$enq_address = $custom["enq_address"][0];
	
    	$enq_message = $custom["enq_message"][0];
	
		if ($column_name == 'message') {
       	    echo $enq_message;
    	}
		if ($column_name == 'phone') {
       	    echo $enq_phone;
    	}
		if ($column_name == 'address'){
			echo $enq_address;
		}
		if ($column_name == 'email'){
			echo $enq_email;
		}
		if ($column_name == 'followup'){
			 $view = '<a class="button button-primary" href=' . admin_url('admin.php?page=rws_followup&enquiry='.$post_ID.'&action=add') . '>Add</a><a class="button" href=' . admin_url('admin.php?page=rws_followup&enquiry='.$post_ID.'&action=view') . '>View</a>';
 			echo $view;	
		}
}
	
	
// Form For Front End
public static function ty_front_end_form() {
	
 	if ( $_POST ) {
		
		self::save_rws_enquiry();		
	} 
	
	include("view/rws_front_end.php");
}

// Save Enquiry Featch From Front End
public static function save_rws_enquiry() {
	
	if ( isset ( $_POST['title'] ) ) {
		
		$title =  esc_html($_POST['title']);
		
	} else {
		
		echo 'Please enter a your full name';
		exit;
	}
	if ( isset ( $_POST['enq_email'] ) ) {
		
		if( is_email ( $_POST['enq_email'] ) ){
			
			$email =  esc_html($_POST['enq_email']);
			
		} else {
			
		echo 'Please enter valid email';
		exit;
	}
	} else {
		
		echo 'Please enter a email';
		exit;
		
	}
	if ( isset ( $_POST['enq_phone'] ) ) {
		
		$phone =  esc_html($_POST['enq_phone']);
		
	} else {
		
		echo 'Please enter a contact number';
		exit;
		
	}
	if ( isset ( $_POST['enq_message'] ) ) {
		
		$description =esc_html( $_POST['enq_message']);
		
	} else {
		
		echo 'Please enter the message';
		exit;
	}
	$email = sanitize_email($email);
	
	$address = esc_html($_POST['enq_address']);
	
	$post = array(
	'post_title' => wp_strip_all_tags( $title ),
	'post_content' => $description,
	'post_status' => 'publish', 
	'post_type' => 'rws_enquiry_followup',
	'meta_input' => array(
		'enq_email' => $email,
		'enq_phone' => $phone,
		'enq_address' =>$address,
		'enq_message' =>$description,
	), );
	
	wp_insert_post( $post ); 
	
	echo "<div class='confirmation_message'>Thanks for contacting us! We will get in touch with you shortly.</div>";
}

}
?>