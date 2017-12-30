$("#registration_form").submit(function(e) {

    var username = $("#username").val();
    var password = $("#password").val();
    var confirm  = $("#confirmPassword").val();

    $.get(
        "api/register/"+username+"/"+password+"/"+confirm,
        function(data) {
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