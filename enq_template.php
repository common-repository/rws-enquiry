<?php
/**
 * The template for displaying all enquiry single posts
*/
get_header(); ?>
<div class="rwswrap">
	<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
	
				get_template_part( 'template-parts/post/content', get_post_format() );
				$phone = get_post_meta(get_the_ID(), 'enq_phone', true);
	
				echo "<br /><b>Phone : </b>".$phone;
	
				$addr = get_post_meta(get_the_ID(), 'enq_address', true);
	
				echo "<br /><b>Address : </b>".$addr;
	
				$email = get_post_meta(get_the_ID(), 'enq_email', true);
	
				echo "<br /><b>Email : </b>".$email;
	
				$msg = get_post_meta(get_the_ID(), 'enq_message', true);
	
				echo "<br /><b>Message : </b>".$msg;	
	
			endwhile; // End of the loop.
			?>
</div><!-- .wrap -->
<?php get_footer();