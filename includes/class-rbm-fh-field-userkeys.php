<?php
/**
 * Field: User Keys
 *
 * @since 1.6.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes
 */

defined( 'ABSPATH' ) || die();

// Global actions
add_filter( 'rbm_field_helpers_admin_data', array( 'RBM_FH_Field_UserKeys', 'add_data' ) );
add_action( 'wp_ajax_rbm_ajax_add_user_key', array( 'RBM_FH_Field_UserKeys', 'rbm_ajax_add_user_key' ) );
add_action( 'wp_ajax_rbm_ajax_delete_user_key', array( 'RBM_FH_Field_UserKeys', 'rbm_ajax_delete_user_key' ) );
add_action( 'wp_ajax_rbm_ajax_delete_user_key_all', array( 'RBM_FH_Field_UserKeys', 'rbm_ajax_delete_user_key_all' ) );

/**
 * Class RBM_FH_Field_UserKeys
 *
 * @since 1.6.0
 */
class RBM_FH_Field_UserKeys extends RBM_FH_Field {

	private static $did_one = false;

	/**
	 * RBM_FH_Field_UserKeys constructor.
	 *
	 * @since 1.6.0
	 *
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $label = '', $args = array(), $value = false ) {

		// Prevent multiple instances
		if ( self::$did_one ) {
			return;
		} else {
			self::$did_one = true;
		}

		parent::__construct( 'user-keys', $label, $args, $value );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.6.0
	 *
	 * @param mixed $value Value of the field.
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $value, $label = '', $args = array() ) {

		$link_base = isset( $args['link_base'] ) ? $args['link_base'] : get_the_permalink();
		?>
		<div id="rbm-field-user-keys" class="<?php echo $args['wrapper_class']; ?>"
			<?php echo ! $args['no_init'] ? 'data-init' : ''; ?>
			 data-post-id="<?php echo isset( $args['post_ID'] ) ? $args['post_ID'] : get_the_ID(); ?>">
			<p>
				<label>
					<strong>New User Email</strong><br/>
					<input type="text" id="_rbm_new_user_email" name="_rbm_new_user_email"
					       class="regular-text"/>
				</label>

				<input type="button" value="Add User" class="button" data-user-key-add/>
			</p>

			<p class="rbm-user-keys-input-error" style="display: none;" data-input="_rbm_new_user_email">
				This cannot be left blank
			</p>

			<div class="rbm-user-keys-container" <?php echo empty( $value ) ? 'style="display: none;"' : ''; ?>>
				<table class="rbm-user-keys">
					<thead>
					<tr>
						<th>
							User Email
						</th>
						<th>
							User Access Link
						</th>
						<th></th>
					</tr>
					</thead>

					<tbody>

					<tr data-template style="display: none;">
						<td data-user-email></td>
						<td>
							<a href="#" data-user-link></a>
						</td>
						<td>
							<a href="#" class="delete-user-key button" data-email="" data-user-key-delete>
								Delete User Key
							</a>
						</td>
					</tr>

					<?php foreach ( $value as $user_email => $user_key ) : ?>
						<tr data-email="<?php echo esc_attr( $user_email ); ?>">
							<td>
								<?php echo $user_email; ?>
							</td>
							<td>
								<a href="<?php echo  "$link_base?rbm_user_key=$user_key"; ?>">
									<?php echo "$link_base?rbm_user_key=$user_key"; ?>
								</a>
							</td>
							<td>
								<a href="#" class="delete-user-key button"
								   data-email="<?php echo esc_attr( $user_email ); ?>"
								   data-user-key-delete>
									Delete User Key
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<p>
					<a href="#" id="delete-all-user-keys" class="button" data-post-id="<?php the_ID(); ?>"
					   data-user-key-delete-all>
						Delete <strong>ALL</strong> User Keys
					</a>
					<br/>
					<span class="description">WARNING: This cannot be undone.</span>
				</p>
			</div>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</div>
		<?php
	}

	/**
	 * Adds some data to localize.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param array $data Data to localize.
	 *
	 * @return mixed
	 */
	function add_data( $data ) {

		$data['userkeys_ajax_delete_users_action'] = 'rbm_ajax_delete_user_key_all';
		$data['userkeys_ajax_delete_user_action']  = 'rbm_ajax_delete_user_key';
		$data['userkeys_ajax_add_user_action']     = 'rbm_ajax_add_user_key';

		return $data;
	}

	public static function rbm_ajax_add_user_key() {

		if ( ! isset( $_POST['post_ID'] ) ||
		     ! isset( $_POST['email'] ) ||
		     ! check_ajax_referer( 'rbm-field-helpers', 'rbm_field_helpers_nonce' )
		) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'Could not get post ID or user email, or could not verify nonce',
			) );
		}

		if ( ! $post_ID = $_POST['post_ID'] ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'Post ID empty',
			) );
		}

		if ( ! $user_email = $_POST['email'] ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'User email empty',
			) );
		}

		/**
		 * Allows filtering of the post ID.
		 *
		 * @since 1.6.0
		 */
		apply_filters( 'rbm_user_key_post_ID_delete', $post_ID, $user_email );

		/**
		 * Allows filtering of the user email to be deleted.
		 *
		 * @since 1.6.0
		 */
		apply_filters( 'rbm_user_key_email_delete', $user_email, $post_ID );

		if ( ! ( $user_keys = get_post_meta( $post_ID, '_rbm_user_keys', true ) ) ) {
			$user_keys = array();
		}

		if ( isset( $user_keys[ $user_email ] ) ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'User already added',
			) );
		}

		$user_keys[ $user_email ] = $user_key = md5( wp_salt() . $user_email );
		update_post_meta( $post_ID, '_rbm_user_keys', $user_keys );

		$edit_link = get_the_permalink( $post_ID ) . "?rbm_user_key=$user_key";

		/**
		 * Allows filtering ot the edit link sent via email to the new user.
		 *
		 * @since 1.6.0
		 */
		apply_filters( 'rbm_user_key_mail_edit_link', $edit_link, $user_email, $user_key, $post_ID );

		wp_mail(
			$user_email,
			'You\'ve been granted access to edit ' . get_the_title( $post_ID ) . '!',
			"You may edit the rbm at the following link:\n" .
			$edit_link
		);

		wp_send_json( array(
			'status'     => 'success',
			'post_ID'    => $post_ID,
			'user_email' => $user_email,
			'user_key'   => $user_key,
			'edit_link'  => $edit_link,
		) );
	}

	public static function rbm_ajax_delete_user_key() {

		if ( ! isset( $_POST['post_ID'] ) ||
		     ! isset( $_POST['email'] ) ||
		     ! check_ajax_referer( 'rbm-field-helpers', 'rbm_field_helpers_nonce' )
		) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'Could not get post ID or user email, or could not verify nonce',
			) );
		}

		if ( ! $post_ID = $_POST['post_ID'] ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'Post ID empty',
			) );
		}

		if ( ! $user_email = $_POST['email'] ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'User email empty',
			) );
		}

		/**
		 * Allows filtering of the new user post ID.
		 *
		 * @since 1.6.0
		 */
		apply_filters( 'rbm_user_key_post_ID_delete', $post_ID, $user_email );

		/**
		 * Allows filtering of the new user email.
		 *
		 * @since 1.6.0
		 */
		apply_filters( 'rbm_user_key_email_delete', $user_email, $post_ID );

		if ( ! ( $user_keys = get_post_meta( $post_ID, '_rbm_user_keys', true ) ) ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'No user keys added yet',
			) );
		}

		if ( ! isset( $user_keys[ $user_email ] ) ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'User already deleted',
			) );
		}

		unset( $user_keys[ $user_email ] );
		update_post_meta( $post_ID, '_rbm_user_keys', $user_keys );

		wp_send_json( array(
			'status'     => 'success',
			'post_ID'    => $post_ID,
			'user_email' => $user_email,
		) );
	}

	public static function rbm_ajax_delete_user_key_all() {

		if ( ! isset( $_POST['post_ID'] ) ||
		     ! check_ajax_referer( 'rbm-field-helpers', 'rbm_field_helpers_nonce' )
		) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'Could not verify nonce',
			) );
		}

		if ( ! $post_ID = $_POST['post_ID'] ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'Post ID empty',
			) );
		}

		/**
		 * Allows filtering of the post ID.
		 *
		 * @since 1.6.0
		 */
		apply_filters( 'rbm_user_key_post_ID_delete', $post_ID );

		if ( ! ( $user_keys = get_post_meta( $post_ID, '_rbm_user_keys', true ) ) ) {
			wp_send_json( array(
				'status'    => 'fail',
				'error_msg' => 'No user keys added yet',
			) );
		}

		delete_post_meta( $post_ID, '_rbm_user_keys' );

		wp_send_json( array(
			'status'  => 'success',
			'post_ID' => $post_ID,
		) );
	}
}