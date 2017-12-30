$("#change_password_form").submit(function(e) {

    var password    = $("#password").val();
    var newPassword = $("#newPassword").val();

    $.get(
        "api/settings/changepassword/"+password+"/"+newPassword,
        function(data) {
            data = JSON.parse(data);
            if(data.type === 'error') {
                $("#errorContainer").html("<div class='alert alert-danger'>"+data.message+"</div>");
            } else if(data.type === 'success') {
                $("#errorContainer").html("<div class='alert alert-success'>"+data.message+"</div>");
            }
        }
    );

    e.preventDefault();

});

$("#switchAuthenticator").click(function() {

    var state = $(this).attr('data-state');
    var type = 1;

    if(state === "enabled") {
        type = 0;
    }

    $.get(
        "api/settings/switchauthenticator/"+type,
        function(data) {
            data = JSON.parse(data);
            if(data.type === 'success') {
                if(state === "enabled") {
                    $("#switchAuthenticator").text("Enable authenticator");
                    $("#switchAuthenticator").attr('data-state', "disabled");
                } else {
                    $("#switchAuthenticator").text("Disable authenticator");
                    $("#switchAuthenticator").attr('data-state', "enabled");
                }
            }
        }
    );
});