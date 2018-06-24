<div id="lb-major-publishing-actions">
	<div id="lb-delete-action">
		<?php
		$post_type = $post->post_type;
		$post_type_object = get_post_type_object( $post_type );
		$can_publish = current_user_can( $post_type_object->cap->publish_posts );

		if ( current_user_can( 'delete_post', $post->ID ) ) {
			if ( ! EMPTY_TRASH_DAYS ) {
				$delete_text = __( 'Delete Permanently' );
			} else {
				$delete_text = __( 'Move to Trash' );
			}
			?>
		<a class="submitdelete deletion" href="<?php echo get_delete_post_link( $post->ID ); ?>"><?php echo $delete_text; ?></a>
		<?php
		}
		?>
	</div>

	<div id="lb-publishing-action">
		<span class="spinner"></span>
		<input name="deploy" type="hidden"  value="false" />

		<?php if ( ! in_array( $post->post_status, array( 'publish', 'future', 'private' ) ) || 0 === $post->ID ) : ?>

			<?php if ( $can_publish ) : ?>

				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish' ) ?>" />
				<?php submit_button( __( 'Publish' ), 'primary large', 'publish', false ); ?>

				<div><?php submit_button( __( 'Deploy to Stage' ), 'primary large', 'deploy', false ); ?></div>
				<div><?php submit_button( __( 'Deploy to Production' ), 'primary large', 'deploy', false ); ?></div>

			<?php else : ?>

				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Submit for Review' ) ?>" />
				<?php submit_button( __( 'Submit for Review' ), 'primary large', 'publish', false ); ?>

			<?php endif; ?>

		<?php else : ?>

			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ) ?>" />
			<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Update' ) ?>" />


			<?php if ( $can_publish ) : ?>

				<div><?php submit_button( __( 'Deploy to Stage' ), 'primary large', 'deploy', false ); ?></div>
				<div><?php submit_button( __( 'Deploy to Production' ), 'primary large', 'deploy', false ); ?></div>

			<?php endif; ?>


		<?php endif; ?>
	</div>

	<div class="clear"></div>
</div>

<script>
jQuery(document).ready(function(){
	window.onbeforeunload = null;
});
</script>
