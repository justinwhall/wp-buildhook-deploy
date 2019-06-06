<?php
/**
 * Renders publish major publishing elements
 *
 * @package littlebot_netlifly/views
 */

if ( $has_prod_hook || $has_stage_hook ) : ?>
	<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Publish to', 'lbn-netlifly' ); ?>:</h4>
	<?php if ( $has_stage_hook ) : ?>
		<div><label><input data-env="stage" type="checkbox" name="lbn_published_stage" <?php if ( $published_stage ) : ?>checked<?php endif; ?>>Stage</label></div>
	<?php endif; ?>
	<?php if ( $has_prod_hook ) : ?>
		<div><label><input data-env="production" type="checkbox" name="lbn_published_production" <?php if ( $published_production ) : ?>checked<?php endif; ?>>Production</label></div>
	<?php endif; ?>
<?php else : ?>
	<div class="no-hooks">
		<?php
			$url = get_site_url() . '/wp-admin/options-general.php?page=lb-netlifly';
			printf(
				/* translators: %1 <a> open tag %2 close tag :  */
				esc_html__( 'Oops, you need to %1$sset a production or stage build hook%2$s for this plugin to work.', 'lb-netlifly' ),
				'<a href=' . esc_attr( $url ) . '>',
				'</a>'
			);
		?>
	</div>
<?php endif; ?>
