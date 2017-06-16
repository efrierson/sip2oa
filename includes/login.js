
function sip2login () {
    $("#results").html('<img src="includes/loading_sm.gif" />');

    var encrypt = new JSEncrypt();
    encrypt.setPublicKey($('#pubkey').val());

    var encrypted_un = encrypt.encrypt($('#login-un').val()); 
    var encrypted_pw = encrypt.encrypt($('#login-pw').val());
    
    var payload = {pw:encrypted_pw,un:encrypted_un};
    
    var pwcheck = "includes/siptest.php";
    
    $.ajax({
        type: "POST",
        url: pwcheck,
        data: JSON.stringify(payload),
        success: function(data) {
            $("#results").html(data);
        },
        error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            console.log(err.Message);
        }
    });
}