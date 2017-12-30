$("#login_form").submit(function(e) {

    var username = $("#username").val();
    var password = $("#password").val();
    var twoFa    = $("#twoFactor").val();

    $.get(
        "api/login/"+username+"/"+password+"/"+twoFa,
        function(data) {
            console.log(data);
            data = JSON.parse(data);
            if(data.type === 'error') {
                $("#errorContainer").html("<div class='alert alert-danger'>"+data.message+"</div>");
            } else if(data.type === 'success') {
                $("#errorContainer").html("<div class='alert alert-success'>"+data.message+"</div>");
                window.location = 'dashboard'
            }
        }
    );

    e.preventDefault();

});