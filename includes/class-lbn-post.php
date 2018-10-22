<?php
/**
 * LittleBot Netlifly
 *
 * A class for all plugin metaboxs.
 *
 * @version   0.9.0
 * @category  Class
 * @package   LittleBotNetlifly
 * @author    Justin W Hall
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks saving and updating posts.
 */
class LBN_Post {

	/**
	 * Parent plugin class.
	 *
	 * @var object
	 */
	protected $plugin = null;

	/**
	 * Kick it off.
	 *
	 * @param object $plugin the parent class.
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Attach hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'save_post', array( $this, 'save_post' ), 10, 3 );
		add_action( 'transition_post_status', array( $this, 'maybe_trigger_build' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'save_post' ), 10, 3 );
		add_action( 'wp_insert_post_data', array( $this, 'insert_post' ), 10, 3 );
		add_action( 'manage_posts_columns', array( $this, 'show_publish_status' ), 10, 3 );
		add_action( 'manage_posts_custom_column', array( $this, 'build_column' ), 15, 3 );
	}

	/**
	 * Add publish column
	 *
	 * @param array $columns Post list columns.
	 *
	 * @return array
	 */
	public function show_publish_status( $columns ) {
		return array_merge( $columns,
			array(
				'Published' => __( 'Published', 'lb-netlifly' ),
			)
		);
	}

	/**
	 * Add columns to invoice and estimates
	 *
	 * @param  array $columns post screen columns.
	 * @param  int   $post_id   the post id.
	 * @return void
	 */
	public function build_column( $columns, $post_id ) {

		$prod_status = (bool) get_post_meta( $post_id, 'lbn_published_stage', true );
		$stage_status = (bool) get_post_meta( $post_id, 'lbn_published_production', true );

		if ( $prod_status ) {
			echo sprintf( '<div>%s</div>', esc_html( 'Production', 'lb-netlifly' ) );
		}

		if ( $stage_status ) {
			echo sprintf( '<div>%s</div>', esc_html( 'Stage', 'lb-netlifly' ) );
		}

		if ( ! $stage_status && ! $prod_status ) {
			echo '—';
		}
	}

	/**
	 * Updates "deploy" status on post update
	 *
	 * @param object $data the $_POST request.
	 * @param object $post the post being updated.
	 *
	 * @return object
	 */
	public function insert_post( $data, $post ) {
		if (
			isset( $post['post_status'] ) && 'auto-draft' === $post['post_status'] ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return $data;
		}

		// If it's a deploy, make sure it's set to publish.
		if ( isset( $post['deploy'] ) ) {
			$data['post_status'] = 'publish';
		}

		return $data;
	}

	/**
	 * Maybe trigger a build on post status change.
	 *
	 * @param string $new_status
	 * @param string $old_status
	 *
	 * @return void
	 */
	public function maybe_trigger_build( $new_status, $old_status ) {
		// If status changes and it's now a draft, tigger build to "unpublish".
		if ( $new_status !== $old_status && 'draft' === $new_status ) {
			$netlifly_stage = new LBN_Netlifly( 'stage' );
			$netlifly_stage->call_build_hook();
			$netlifly_stage = new LBN_Netlifly( 'production' );
			$netlifly_stage->call_build_hook();
		}
	}
	/**
	 * Save post callback
	 *
	 * @param int     $post_id The post ID.
	 * @param object  $post    The post object.
	 * @param boolean $update  Is this an update.
	 * @return void
	 */
	public function save_post( $post_id, $post, $update ) {
		// Bail if it's a auto-draft, we're doing auto save or ajax.
		if (
			isset( $post->post_status ) && 'auto-draft' === $post->post_status ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return;
		}

		// Is this post published.
		$published_stage = isset( $_POST['lbn_published_stage'] ) ? true : false;
		$published_production = isset( $_POST['lbn_published_production'] ) ? true : false;

		// Update published status.
		update_post_meta( $post->ID, 'lbn_published_stage', $published_stage );
		update_post_meta( $post->ID, 'lbn_published_production', $published_production );

		// Maybe deploy to stage?
		if ( $published_stage ) {
			$netlifly_stage = new LBN_Netlifly( 'stage' );
			$netlifly_stage->call_build_hook();
		}

		// Maybe deploy to production?
		if ( $published_production ) {
			$netlifly_stage = new LBN_Netlifly( 'production' );
			$netlifly_stage->call_build_hook();
		}

	}

}
