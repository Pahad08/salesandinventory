let check = document.getElementById("checkbox");
let password = document.getElementById("password");
let username = document.getElementById("username");
let button = document.getElementById("submit");
let password_blank = document.getElementById("password-error");
let username_blank = document.getElementById("username-error");
console.log(window.innerWidth)
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

    if (password.value == "") {
        password_blank.style.display = "block";
    } else {
        password_blank.style.display = "none";
    }

    if (username.value == "") {
        username_blank.style.display = "block";
    } else {
        username_blank.style.display = "none";
    }

}


username.addEventListener("input", DisableLogin)
password.addEventListener("input", DisableLogin)
