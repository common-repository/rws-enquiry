<?php
class RWS_Followup {
    
    private static $initiated = false;
	
    public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
    private static function init_hooks(){
      self::$initiated = true;
      
      	// Pin to dashboorad
    add_action( 'wp_dashboard_setup', array( 'RWS_Followup','add_to_dashboard') );
    
		//Add Followup
    add_action( 'admin_menu', array( 'RWS_Followup','follow_up') );
    
   }
   
/*
*  Add Follow Up Page
*/
public static function follow_up() {
    add_submenu_page(
        'edit.php?post_type=rws_enquiry_followup',
        __( 'Follow Ups', 'rws_followup' ),
        __( 'Follow Ups', 'rws_followup' ),
        'manage_options',
        'rws_followup', 
		array( 'RWS_Followup', 'follow_up_page')
    );	
}
	
/*
* Pin Todays Followups To Dashboard
*/
public static  function add_to_dashboard(){
		
	wp_add_dashboard_widget( 'rws_enquiry_followup', 'RWS Enquiry and Lead Follow-up', array( 'RWS_Followup','todays_followup') );
	
	}
	
/*
*Execute Follow Up Page
*/
public static function follow_up_page() {
	if ( isset( $_POST['add_followup'] ) ) {
		
		self::add_followup_action();
		
	}
	if ( isset( $_POST['close_followup'] ) ) {
		
		self::add_followup_action();
	
	}
	if ( isset( $_GET['action'] ) )	{
		
		if ( $_GET['action'] == 'trash' ) {
		
			self::close_followup();
		
		} elseif ( $_GET['action'] == 'add' ) {
			
			self::add_followup();
		
		} elseif ( $_GET['action'] == 'view' ) {
		
			self::view_followup();
		}
	} else {
		
		self::todays_followup();
	
	}
}
	
/*
*  Delete Follow Up Entry
*/
public static  function close_followup() {
	
	 global $wpdb;
	
	 $enquiry = $_GET['enquiry'];
	
	 $query = "SELECT meta_value FROM {$wpdb->prefix}postmeta where meta_id=".$_GET['flw'];
	
	 $postdata = $wpdb->get_results( $query );
	
	 $resultflw =  delete_post_meta( $enquiry, 'enq_flw_up', $postdata[0]->meta_value);
	
	 $resultdt =  delete_post_meta( $enquiry, 'enq_flw_up_dt', date( 'Y-m-d', strtotime( $_GET['flwd'] ) ) );
	
	 if ( $resultflw || $resultdt ){
		 
		 echo '<div id="message" class="updated fade"><p> Follow up successfully deleted. </p></div>';
		 
	 } else {
		 
		echo '<div id="error" class="error"><p> Failed to delete follow up. </p></div>';
		 
	 }
	
	 self::view_followup();
}

/*
*  Show All Follow-ups With Respect To Enquiry 
*/
public static function view_followup() {
	
	$flw = get_post_meta( $_GET['enquiry'],'enq_flw_up' );
	
	$flwdt = get_post_meta( $_GET['enquiry'],'enq_flw_up_dt' );
	
	$ename = get_the_title( $_GET['enquiry'] );
	
?>
<div class="wrap">
	
<h1 class="wp-heading-inline">Follow Ups For <?php echo $ename; ?></h1>
	
<a href="<?php echo admin_url('admin.php?page=rws_followup&enquiry='.$_GET['enquiry'].'&action=add');?>" class="page-title-action">Add New Follow Up</a>
	
<hr class="wp-header-end" >	<br /><br />
	
<table class="wp-list-table widefat fixed striped pages" >
	
	<thead>
		<tr>		
		<th scope="col" class="manage-column"> Note</th>
		<th scope="col" class="manage-column">Next Date</th>
		<th scope="col" class="manage-column">Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = -1;
	
	global $wpdb;
	
	if( count ( $flw ) > 0 ){
	
		foreach ( $flw as $val )	{		
			$a = 0;
			$b = 0;
			$mid1 = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id FROM  `{$wpdb->prefix}postmeta` WHERE meta_key='enq_flw_up' AND post_id=%d AND meta_value=%s", $_GET['enquiry'], $val ) );
			
			echo "<tr><td>".esc_html($val)."</td>";
			
			$i++;
			
			foreach ( $flwdt as $key=>$valdt ) {
				
				if ( $key == $i ) {
					
				$mid2 = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id FROM  `{$wpdb->prefix}postmeta` WHERE meta_key='enq_flw_up_dt' AND post_id=%d AND meta_value=%s", $_GET['enquiry'], $valdt ) );
				echo "<td>".esc_html($valdt)."</td>";
					
			}
				
		}
			
		$m_id1 = $mid1[0]->meta_id;
			
		if ( count($mid1) > 1 ) {
			
			$m_id1 = $mid1[$a]->meta_id;
			$a++;
			
		}
		$m_id2 = $mid2[0]->meta_id;
		
		if ( count($mid2) > 1 ) {
		
			$m_id2 = $mid2[$b]->meta_id;
			$b++;
		
		}
		echo "<td><a href='". admin_url('admin.php?page=rws_followup&enquiry='.$_GET['enquiry'].'&flw='.$m_id1.'&flwd='.$m_id2.'&action=trash')."' class='button button-primary'>Delete</a></td></tr>";
	} 
	}
	else {
		echo "<tr><td colspan='3'> No follow ups for ".$ename.".</td></tr>";
	}
?>
	</tbody>
	</table></div>
<?php }

/*
* Add Follow up
*/
public static function add_followup() {
	
	$ename = get_the_title( $_GET['enquiry'] );
	
	include( "view/rws_new_followup.php" );
	
 }

/*
*  Save Follow Up
*/
public static function add_followup_action() {
	
	if( $_POST['enq_flw_up'] == "" ) {
		
		echo '<div class="notice inline notice-error  is-dismissible "><p> Please Enter Note. </p></div>';		
		self::add_followup();
	}
	else if( $_POST['enq_flw_up_dt'] == "" ) {
		
		echo '<div class="notice inline notice-error  is-dismissible"><p> Please Select Next Follow Up Date. </p></div>';
		self::add_followup();
	}
	else {
		
		add_post_meta( $_GET['enquiry'], 'enq_flw_up', sanitize_text_field($_POST['enq_flw_up']) );
	
	 	add_post_meta( $_GET['enquiry'], 'enq_flw_up_dt', date( "Y-m-d", strtotime($_POST['enq_flw_up_dt'] ) ) );
		
		echo '<div id="message" class="updated fade"><p> Follow-up Added Sucessfuly. </p></div>';
		 
		self::view_followup();
	}
	
	
}  

/*
*  Show Todays Or Pending Follow Ups
*/
public static function todays_followup() {
	
	global $wpdb;
	
	$qry = "SELECT pm.post_id, MAX( pm.meta_value ) as 'flw_dt' FROM  `{$wpdb->prefix}postmeta` pm inner join `{$wpdb->prefix}posts` p on pm.post_id = p.ID WHERE pm.meta_key =  'enq_flw_up_dt' AND p.`post_status` LIKE 'publish' AND post_type =  'rws_enquiry_followup' GROUP BY post_id";	
	
	$enq = $wpdb->get_results($qry);
	
	$posts = "SELECT ID FROM `{$wpdb->prefix}posts` WHERE ID NOT IN (SELECT post_id FROM `{$wpdb->prefix}postmeta` WHERE meta_key LIKE  'enq_flw_up_dt') AND post_type =  'rws_enquiry_followup' AND post_title NOT LIKE 'Auto Draft'";
	
	$fpost =  $wpdb->get_results($posts);
	
	$html = '<div class="RWSwrap"><h1 class="wp-heading-inline"> Today\'s Follow Ups</h1>
	<table class="wp-list-table widefat fixed striped pages">
		<thead><tr>
			<th scope="col" class="manage-column">Name</th>
			<th scope="col" class="manage-column">Phone</th>
			<th scope="col" class="manage-column">Email</th>
			<th scope="col" class="manage-column">Action</th>
		</tr></thead>
		<tbody>';

	$i=0;
	
	foreach ( $enq as $val ) {	
		
		if ( strtotime( $val->flw_dt ) <= strtotime( date('Y-m-d') ) ) {
			
			$i++;
			
			$html .= "<tr><td class='title column-title has-row-actions column-primary page-title' data-colname='Name'>".esc_html(get_the_title($val->post_id))."</td>";
			
			$phone = $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE meta_key='enq_phone' AND post_id=".$val->post_id);
			
			$html .= "<td  class='phone column-phone' data-colname='Phone'>".esc_html($phone[0]->meta_value)."</td>";
			$email = $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE meta_key='enq_email' AND post_id=".$val->post_id);
			
			$html .= "<td  class='email column-email' data-colname='Email'>".esc_html($email[0]->meta_value)."</td>";
			
			$html .= '<td  class="action column-action" data-colname="Action"><a class="button button-primary" href=' . admin_url('admin.php?page=rws_followup&enquiry='.esc_html($val->post_id).'&action=add') . '>Add</a><a class="button" href=' . admin_url('admin.php?page=rws_followup&enquiry='.esc_html($val->post_id).'&action=view') . '>View</a></td></tr>';
			
		}
	}
	
	foreach( $fpost as $valp ) {
		
		$i++;
		
		$id=esc_html($valp->ID);
		
		$html .= "<tr><td>".esc_html(get_the_title($valp->ID))."</td>";
		
		$phone = $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE meta_key='enq_phone' AND post_id=".$id);
		
		$html .= "<td>".esc_html($phone[0]->meta_value)."</td>";
	
		$email = $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE meta_key='enq_email' AND post_id=".$id);
		
		$html .= "<td>".esc_html($email[0]->meta_value)."</td>";
		
		$html .= '<td><a class="button button-primary" href=' . admin_url('admin.php?page=rws_followup&enquiry='.$id.'&action=add') . '>Add</a><a class="button" href=' . admin_url('admin.php?page=rws_followup&enquiry='.$id.'&action=view') . '>View</a></td></tr>';
	}
	
	if ( $i==0 ) {
	
		$html .= "<tr><td colspan='4'>No follow up today</td></tr>";

	}
	$html .= '</tbody></table></div>';
	echo $html;
}
}
?>