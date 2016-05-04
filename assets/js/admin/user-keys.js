/**
 * Handles all functionality for the user keys system.
 *
 * @since 1.1.0
 */
(function ($, data) {
    'use strict';

    var $user_keys, $user_keys_container, $key_table, $add_user, $add_user_input, $delete_users, post_ID;

    /**
     * Initialize the user keys system.
     *
     * @since 1.1.0
     */
    function init() {

        get_elements();

        if (!$user_keys.length) {
            return;
        }

        post_ID = $user_keys.data('post-id');

        setup_handlers();
    }

    /**
     * Gets the elements needed.
     *
     * @since 1.1.0
     */
    function get_elements() {

        $user_keys = $('#rbm-field-user-keys[data-ajax]');

        if (!$user_keys.length) {
            return;
        }

        $user_keys_container = $user_keys.find('.rbm-user-keys-container');
        $key_table = $user_keys.find('table.rbm-user-keys');
        $add_user = $user_keys.find('[data-user-key-add]');
        $delete_users = $user_keys.find('[data-user-key-delete-all]');
        $add_user_input = $user_keys.find('[name="_rbm_new_user_email"]');
    }

    /**
     * Sets up any handlers.
     *
     * @since 1.1.0
     */
    function setup_handlers() {

        $add_user.click(add_user);
        $user_keys.on('click', '[data-user-key-delete]', delete_user);
        $delete_users.click(delete_users);
        $add_user_input.keypress(add_user_input);
    }

    /**
     * Adds a new user.
     *
     * @since 1.1.0
     */
    function add_user() {

        var email = $user_keys.find('[name="_rbm_new_user_email"]').val(),
            $error = $user_keys.find('.rbm-user-keys-input-error[data-input="_rbm_new_user_email"]');

        if (!email.length) {
            $error.show();
            return;
        } else {
            $error.hide();
        }

        $.post(
            ajaxurl,
            {
                action: data['userkeys_ajax_add_user_action'],
                rbm_field_helpers_nonce: data['nonce'],
                email: email,
                post_ID: post_ID
            },
            function (response) {

                if (response['status'] == 'success') {

                    add_row(response['user_email'], response['user_key'], response['edit_link']);
                    $add_user_input.val('');

                } else if (response['status'] == 'fail') {
                    if (response['error_msg']) {
                        alert(response['error_msg']);
                    }
                }
            }
        )
    }

    /**
     * Deletes a user.
     *
     * @since 1.1.0
     */
    function delete_user(e) {

        var email = $(this).data('email');

        e.preventDefault();

        if (!confirm('Are you sure you want to delete this user?')) {
            return;
        }

        $.post(
            ajaxurl,
            {
                action: data['userkeys_ajax_delete_user_action'],
                rbm_field_helpers_nonce: data['nonce'],
                post_ID: post_ID,
                email: email
            },
            function (response) {

                if (response['status'] == 'success') {

                    delete_row(response['user_email']);

                } else if (response['status'] == 'fail') {
                    if (response['error_msg']) {
                        alert(response['error_msg']);
                    }
                }
            }
        )
    }

    /**
     * Deletes all users.
     *
     * @since 1.1.0
     */
    function delete_users(e) {

        e.preventDefault();

        if (!confirm('Are you sure you want to delete ALL users?')) {
            return;
        }

        $.post(
            ajaxurl,
            {
                action: data['userkeys_ajax_delete_users_action'],
                rbm_field_helpers_nonce: data['nonce'],
                post_ID: post_ID
            },
            function (response) {

                if (response['status'] == 'success') {

                    $user_keys_container.fadeOut(300,function () {
                        $key_table.find('tbody tr:not([data-template])').remove();
                    });

                } else if (response['status'] == 'fail') {
                    if (response['error_msg']) {
                        alert(response['error_msg']);
                    }
                }
            }
        )
    }

    /**
     * Adds a row to the user keys table.
     *
     * @since 1.1.0
     *
     * @param email
     * @param key
     * @param link
     */
    function add_row(email, key, link) {

        var $row = $key_table.find('tr[data-template]').clone();

        $row.removeAttr('data-template');
        $row.attr('data-email', email);
        $row.find('[data-user-email]').text(email);
        $row.find('[data-user-link]').text(link).attr('href', link);
        $row.find('[data-user-key-delete]').attr('data-email', email);

        if (!$key_table.find('tbody tr:not([data-template])').length) {
            $user_keys_container.fadeIn();
        }

        $key_table.find('tbody').append($row);

        $row.fadeIn();
    }

    /**
     * Deletes a row from the user keys table.
     *
     * @since 1.1.0
     *
     * @param email
     */
    function delete_row(email) {

        $key_table.find('tr[data-email="' + email + '"]').fadeOut(300, function () {

            $(this).remove();

            if (!$key_table.find('tbody tr:not([data-template])').length) {
                $user_keys_container.fadeOut();
            }
        });
    }

    /**
     * Prevents submitting from input.
     *
     * @since 1.1.0
     *
     * @param e
     */
    function add_user_input(e) {

        if (e.charCode == '13') {
            e.preventDefault();
            $add_user.click();
        }
    }

    $(init);
})(jQuery, RBM_FieldHelpers);