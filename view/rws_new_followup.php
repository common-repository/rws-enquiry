<form action="admin.php?page=rws_followup&enquiry=<?php echo $_GET['enquiry']; ?>&action=save" method="post">
<div class="wrap">
	<h1 class="wp-heading-inline">Add New Follow Up For <?php echo $ename; ?></h1>
</div>
<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content" style="position: relative;">
			<div id="postbox-container-2" class="postbox-container">
				<div id="enq_flw_up_1" class="postbox">
						<button type="button" class="handlediv" aria-expanded="true">
							<span class="screen-reader-text">Note</span>
						</button>
						<h2 class="hndle ui-sortable-handle"><span>Note</span></h2>
						<div class="inside">
							<textarea  name="enq_flw_up" style="width: 600px;"><?php if ( isset ( $_POST['enq_flw_up'] ) ) { 
echo $_POST['$enq_flw_up'];
}?></textarea>
						</div>
				</div>
				<div id="enq_flwup_dt_1" class="postbox ">
						<button type="button" class="handlediv" aria-expanded="true">
							<span class="screen-reader-text">Next Follow Up date</span>
						</button>
						<h2 class="hndle ui-sortable-handle"><span>Next Follow Up date</span></h2>
						<div class="inside">
 							<input type="date" name="enq_flw_up_dt" value="<?php if ( isset ( $_POST['enq_flw_up_dt'] ) ) { 
echo date( 'm/d/Y', strtotime( $_POST['$enq_flw_up_dt'] ) );
}?>" style="width: 600px;">
						</div>
				</div>
 <?php wp_nonce_field('add_followup_clicked'); ?>
<input type="hidden" value="true" name="add_followup" /> 
<?php  submit_button('Add');?>
			</div>
		</div>
	</div>
	</div>
</form>