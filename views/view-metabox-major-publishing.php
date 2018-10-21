<?php
/**
 * Renders publish major publishing elements
 *
 * @package littlebot_netlifly/views
 */
?>
<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Status', 'lbn-netlifly' ); ?>:</h4>
<select name="post_status" id="post_status">
	<option <?php if ( 'publish' === $post->post_status ) : echo 'selected'; endif; ?> value="published">Published</option>
	<option <?php if ( 'draft' === $post->post_status ) : echo 'selected'; endif; ?> value="draft">Draft</option>
</select>

<?php if ( $has_prod_hook || $has_stage_hook ) : ?>
	<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Publish to', 'lbn-netlifly' ); ?>:</h4>
	<div><label><input class="lb-pub-check" data-env="stage" type="checkbox" name="lbn_published_stage" >Stage</label></div>
	<div><label><input class="lb-pub-check" data-env="production" type="checkbox" name="lbn_published_production">Production</label></div>
<?php else : ?>
	<div class="no-hooks">
		<?php
			$url = get_site_url() . '/wp-admin/options-general.php?page=lb-netlifly';
			echo sprintf( wp_kses( __( 'Oops, you need to <a href="%s">set a production or stage build hook</a> for this plugin to work.', 'lb-netlifly' ),
				array( 'a' => array( 'href' => array() ) ) ), esc_url( $url )
			);
		?>
	</div>
<?php endif; ?>

<script>
jQuery(document).ready(function(){
	$('#post_status').on('change', updateLittleBotPubChecks);
	updateLittleBotPubChecks();

	function updateLittleBotPubChecks() {
		var status = $('#post_status').find('option:selected').val();

		if (status === 'draft') {
			$('.lb-pub-check').prop('checked', false);
			$('.lb-pub-check').prop('disabled', true);
		} else {
			$('.lb-pub-check').prop('disabled', false);
		}
	}

});
</script>
