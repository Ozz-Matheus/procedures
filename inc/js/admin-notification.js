jQuery(document).ready(function ($) {

    // Email
    $('#pho-send-notification').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: phoNotificationData.ajax_url,
            data: {
                action: 'pho_send_email_notification',
                user_email: phoNotificationData.user_email,
                procedure_status: phoNotificationData.procedure_status_slug,
                procedure_status_label: phoNotificationData.procedure_status_label,
                procedure_observations: phoNotificationData.procedure_observations
            },
            success: function (response) {
                alert(response.data);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('.pho-send-notification #pho-send-notification')
        .addClass('button button-secondary')
        .css('padding', '0 8px');

    // ROL

    $('#pho-add-member-button').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: phoNotificationData.ajax_url,
            data: {
                action: 'pho_add_club_member_role',
                user_email: phoNotificationData.user_email,
                procedure_status: phoNotificationData.procedure_status_slug,
                procedure_id: phoNotificationData.procedure_id
            },
            success: function (response) {
                alert(response.data);
                location.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('.pho-send-notification #pho-add-member-button')
        .addClass('button button-secondary')
        .css('padding', '0 8px');

});
