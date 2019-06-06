<?php
/**
 * Renders publish metabox
 *
 * @package littlebot_netlifly/views
 */

// Do we have build hooks?
$lb_netlifly     = get_option( 'lb_netlifly' );
$has_prod_hook  = (bool) $lb_netlifly['production_buildhook'];
$has_stage_hook = (bool) $lb_netlifly['stage_buildhook'];

$current_post_type = $post->post_type;
$post_type_obj     = get_post_type_object( $current_post_type );
$can_publish       = current_user_can( $post_type_obj->cap->publish_posts );
// Prev deploy states.
$published_stage      = (bool) get_post_meta( $post->ID, 'lbn_published_stage', true );
$published_production = (bool) get_post_meta( $post->ID, 'lbn_published_production', true );
$is_classic_editor    = is_plugin_active( 'classic-editor/classic-editor.php' );
?>

<div id="submitpost" class="post-box submitbox">
	<div id="lb-publishing-action">

		<?php if ( ! in_array( $post->post_status, array( 'publish', 'future', 'private', 'lbn-update' ) ) || 0 === $post->ID ) : ?>

			<?php if ( $can_publish ) : ?>

				<?php include plugin_dir_path( __FILE__ ) . 'view-metabox-major-publishing.php'; ?>

				<?php if ( $is_classic_editor ) : ?>
					<div id="major-publishing-actions">
						<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish' ); ?>" />
						<?php submit_button( __( 'Publish' ), 'primary large', 'publish', false ); ?>
					</div>
				<?php endif; ?>

			<?php else : ?>

				<?php if ( $is_classic_editor ) : ?>
					<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Submit for Review' ); ?>" />
					<?php submit_button( __( 'Submit for Review' ), 'primary large', 'publish', false ); ?>
				<?php endif; ?>

			<?php endif; ?>

		<?php else : ?>

			<?php if ( $can_publish ) : ?>

				<?php include plugin_dir_path( __FILE__ ) . 'view-metabox-major-publishing.php'; ?>

			<?php endif; ?>

			<?php if ( $is_classic_editor ) : ?>
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
						<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ); ?>" />
						<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Update' ); ?>" />
					</div>

				</div>
			<?php endif; ?>

		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>

