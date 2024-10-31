<form id="rws_enquiry_followup" name="rws_enquiry_followup" method="post" action="">
	<p><label for="title">Name</label><br /> 
		<input type="text" id="title" value="" tabindex="1" size="20" name="title" />
	</p>
 	<p><label for="email">Email</label><br /> 
		<input type="email" id="email" value="" tabindex="2" size="20" name="enq_email" />
	</p>
	<p><label for="phone">Contact Number</label><br /> 
		<input type="number" id="phone" value="" tabindex="3" size="20" name="enq_phone" /> 
	</p>
	<p><label for="address">Address</label><br /> 
		<input type="text" id="address" value="" tabindex="4" size="20" name="enq_address" /> 
	</p>
	<p><label for="description">Your Message</label><br /> 
		<textarea id="description" tabindex="5" name="enq_message" cols="50" rows="6"></textarea> 
	</p>  
	<p align="right"><input type="submit" value="Submit" tabindex="6" id="submit" name="submit" /></p>
 	<input type="hidden" name="post-type" id="post-type" value="rws_enquiry" />
 	<input type="hidden" name="action" value="custom_posts" />
	<?php wp_nonce_field( 'name_of_my_action','name_of_nonce_field' ); ?> 
</form>