<div id="submitpost" class="post-box submitbox">
	<div id="lb-delete-action">
		<?php
		$post_type = $post->post_type;
		$post_type_object = get_post_type_object( $post_type );
		$can_publish = current_user_can( $post_type_object->cap->publish_posts );
		// Prev deploy states.
		$published_stage = (bool) get_post_meta( $post->ID, 'lbn_published_stage', true );
		$published_production = (bool) get_post_meta( $post->ID, 'lbn_published_production', true );
		?>
	</div>

	<div id="lb-publishing-action">

		<?php if ( ! in_array( $post->post_status, array( 'publish', 'future', 'private' ) ) || 0 === $post->ID ) : ?>

			<?php if ( $can_publish ) : ?>

				<h4 style="margin-bottom: 0;">Publish Status</h4>
				<div><label><input class="js-lbn-status-stage" type="checkbox" name="lbn_published_stage" <?php if ( $published_stage ): ?>checked<?php endif; ?>>Stage</label></div>
				<div><label><input class="js-lbn-status-production" type="checkbox" name="lbn_published_production" <?php if ( $published_production ): ?>checked<?php endif; ?>>Production</label></div>

				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish' ) ?>" />
				<?php submit_button( __( 'Publish' ), 'primary large', 'publish', false ); ?>

			<?php else : ?>

				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Submit for Review' ) ?>" />
				<?php submit_button( __( 'Submit for Review' ), 'primary large', 'publish', false ); ?>

			<?php endif; ?>

		<?php else : ?>

			<?php if ( $can_publish ) : ?>

				<h4 style="margin-bottom: 0;">Publish Status</h4>
				<div><label><input class="js-lbn-status-stage" type="checkbox" name="lbn_published_stage" <?php if ( $published_stage ): ?>checked<?php endif; ?>>Stage</label></div>
				<div><label><input class="js-lbn-status-production" type="checkbox" name="lbn_published_production" <?php if ( $published_production ): ?>checked<?php endif; ?>>Production</label></div>

				<h4 style="margin-bottom: 0;">Update</h4>
				<div><label><input class="js-lbn-deploy-stage" type="checkbox" name="lbn_deploy_stage" >Stage</label></div>
				<div><label><input class="js-lbn-deploy-production" type="checkbox" name="lbn_deploy_production" >Production</label></div>

			<?php endif; ?>
			<div id="major-publishing-actions">
				<?php
				if ( current_user_can( 'delete_post', $post->ID ) ) {
					if ( ! EMPTY_TRASH_DAYS ) {
						$delete_text = __( 'Delete Permanently' );
					} else {
						$delete_text = __( 'Move to Trash' );
					}
					?>
					<div id="delete-action">
						<a class="submitdelete deletion delete" href="<?php echo get_delete_post_link( $post->ID ); ?>"><?php echo $delete_text; ?></a>
					</div>
					<?php
				}
				?>
				<div id="publishing-action">
					<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ) ?>" />
					<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Update' ) ?>" />
				</div>
			</div>


		<?php endif; ?>
	</div>


	<div class="clear"></div>
</div>

