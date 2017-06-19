
function sip2config () {
    $("#results").html('<img src="includes/loading_sm.gif" />');
    var encrypt = new JSEncrypt();
    encrypt.setPublicKey($('#pubkey').val());

    var encrypted_un = encrypt.encrypt($('#app-un').val()); 
    var encrypted_pw = encrypt.encrypt($('#app-pw').val());
    var custid = $('#custid').val();
    var hostname = $('#hostname').val();
    var port = $('#port').val();
    var location = $('#location').val();
    var endpoint = $('#oaapiendpoint').val();
    var apikey = $('#oaapikey').val();
    var connectionid = $('#oaconnectionid').val();
    
    var payload = {pw:encrypted_pw,un:encrypted_un,hostname:hostname,port:port,location:location,custid:custid,oaendpoint:endpoint,oaapikey:apikey,oaconnectionid:connectionid};
    
    var pwcheck = "includes/config.php";

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

function sip2login () {
    $("#warning").html('<img src="includes/loading_sm.gif" />');

    var encrypt = new JSEncrypt();
    encrypt.setPublicKey($('#pubkey').val());

    var encrypted_un = encrypt.encrypt($('#login-un').val()); 
    var encrypted_pw = encrypt.encrypt($('#login-pw').val());
    var rd = $('#returnData').val();
    var custid = $('#custid').val();
    
    var payload = {pw:encrypted_pw,un:encrypted_un,custid:custid,rd:rd};
    
    var pwcheck = "includes/siptest.php";
    
    console.log(payload);
    
    $.ajax({
        type: "POST",
        url: pwcheck,
        data: JSON.stringify(payload),
        dataType: "json",
        success: function(data) {
            if (data.valid == "Y") {
                console.log(data);
                window.location.href="redirect.php";
            } else {
                $("#warning").html('<div class="warningmessage">'+data.message+'</div>');
            }
            $("#results").html(data);
        },
        error: function(xhr, status, error) {
            console.log("Broke");
        }
    });
}