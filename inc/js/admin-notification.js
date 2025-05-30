jQuery(document).ready(function ($) {
    $('#pho-send-notification').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: phoNotificationData.ajax_url,
            data: {
                action: 'pho_send_email_notification',
                user_email: phoNotificationData.user_email,
                procedure_status: phoNotificationData.procedure_status,
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
});
