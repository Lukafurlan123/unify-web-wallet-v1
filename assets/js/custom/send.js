$("#send_form").submit(function(e) {

    var wallet = $("#wallet_address").val();
    var amount = $("#amount").val();

    $.get(
        "api/send/"+wallet+"/"+amount,
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