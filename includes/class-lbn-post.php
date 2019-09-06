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
		add_action( 'trash_post', array( $this, 'trash_post' ), 10, 1 );
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
		return array_merge(
			$columns,
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

		$stage_status  = (bool) get_post_meta( $post_id, 'lbn_published_stage', true );
		$prod_status = (bool) get_post_meta( $post_id, 'lbn_published_production', true );

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
	 * Save post callback
	 *
	 * @param int     $post_id The post ID.
	 * @param object  $post    The post object.
	 * @param boolean $update  Is this an update.
	 * @return void
	 */
	public function save_post( $post_id, $post, $update ) {
		LBN_POST::update( $post );
	}

	/**
	 * Trash post callback
	 *
	 * @param int $post_id The post id.
	 * @return void
	 */
	public function trash_post( $post_id ) {
		$post = get_post( $post_id );
		LBN_POST::update( $post );
	}

	/**
	 * Update post meta and call build hooks(s)
	 *
	 * @param object $post The post being updated.
	 * @return void
	 */
	public function update( $post ) {
		if (
			isset( $post->post_status ) && 'auto-draft' === $post->post_status ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return;
		}

		$lb_netlifly     = get_option( 'lb_netlifly' );
		$has_stage_hook = (bool) $lb_netlifly['stage_buildhook'];
		$has_prod_hook  = (bool) $lb_netlifly['production_buildhook'];

		// Is this post published.
		$published_stage      = isset( $_POST['lbn_published_stage'] ) ? true : false;
		$published_production = isset( $_POST['lbn_published_production'] ) ? true : false;

		// Stage.
		if ( $has_stage_hook ) {
			update_post_meta( $post->ID, 'lbn_published_stage', $published_stage );
			$netlifly_stage = new LBN_Netlifly( 'stage' );
			$netlifly_stage->call_build_hook();
		}

		// Prod.
		if ( $has_prod_hook ) {
			update_post_meta( $post->ID, 'lbn_published_production', $published_production );
			$netlifly_stage = new LBN_Netlifly( 'production' );
			$netlifly_stage->call_build_hook();
		}
	}

}
