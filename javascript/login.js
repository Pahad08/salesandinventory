let check = document.getElementById("checkbox");
let password = document.getElementById("password");
let username = document.getElementById("username");
let button = document.getElementById("submit");
let password_blank = document.getElementById("password-error");
let username_blank = document.getElementById("username-error");

check.addEventListener("input", () => {
    if (check.checked) {
        password.type = "text";
    } else {
        password.type = "password";
    }
})


function DisableLogin() {
    if (username.value == "" || password.value == "") {
        button.disabled = true;
    } else {
        button.disabled = false;
    }
}

function Password() {

    DisableLogin();

    if (password.value == "") {
        password_blank.style.display = "block";
    } else {
        password_blank.style.display = "none";
    }
}

function Username() {
    if (username.value == "") {
        username_blank.style.display = "block";
    } else {
        username_blank.style.display = "none";
    }

    DisableLogin();
}

username.addEventListener("input", Username)
password.addEventListener("input", Password)