$(document).ready(function() {
    $("#loadWallets").load("api/wallets/get");
    new Clipboard('.copy');
});

$("#addWallet").click(function() {

    $.get(
        "api/wallet/add",
        function(data) {
            data = JSON.parse(data);
            if(data.type === 'error') {
                $("#errorContainer").html("<div class='alert alert-danger'>"+data.message+"</div>");
            } else if(data.type === 'success') {
                $("#loadWallets").load("api/wallets/get");
                $("#errorContainer").html("<div class='alert alert-success'>"+data.message+"</div>");
            }
        }
    );

});