let form = document.getElementById("form");
let openform = document.getElementById("accadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let username = document.getElementById("username");
let password = document.getElementById("password");
let role = document.getElementById("role");
let usernameerr = document.getElementById("usernameerr");
let passworderr = document.getElementById("passworderr");
let roleerr = document.getElementById("roleerr");
let del = document.getElementById("del");
let cancel = document.getElementById("cancel");
let search = document.getElementById("search");
let update = document.getElementById("update");
let modal = document.querySelector(".modal-account");

function Checkboxes() {
    let checkboxes = document.querySelectorAll(".checkbox");
    for (box of checkboxes) {
        if (box.checked == false) {
            return true;
        }
    }
    return false;
}

function AttachedEvents() {
    let selectall = document.getElementById('selectall');
    let checkboxes = document.querySelectorAll(".checkbox");
    const edit = document.querySelectorAll(".edit");

    selectall.addEventListener("click", () => {
        if (Checkboxes()) {
            checkboxes.forEach((element) => {
                element.checked = true;
            })
        } else {
            checkboxes.forEach((element) => {
                element.checked = false;
            })
        }
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let data_id = element.getAttribute("data-accid");
            let data_username = element.getAttribute("data-username");
            let data_password = element.getAttribute("data-password");
            let data_role = element.getAttribute("data-role");

            let username = document.getElementById("curr_username");
            let password = document.getElementById("curr_password");
            let role = document.getElementById("curr_role");
            let id = document.getElementById("acc-id");
            let role_description;

            if (data_role == 1) {
                role_description = "Admin";
            } else if (data_role == 2) {
                role_description = "Worker";
            } else {
                role_description = "Supplier";
            }

            id.value = data_id;
            username.value = data_username;
            password.value = data_password;
            role.value = data_role;
            role.innerHTML = role_description;

            modal.classList.toggle("modal-account");
            modal.classList.toggle("modal-account-show");
        })
    })

}

AttachedEvents();

search.addEventListener("input", () => {

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.getElementById("table").innerHTML = this.responseText;
        AttachedEvents();
    }
    xhttp.open("GET", "search/search_users.php?username=" + search.value);
    xhttp.send();
})


if (canceldelete) {
    canceldelete.addEventListener("click", () => {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    })
}

cancel.addEventListener("click", (event) => {
    event.preventDefault();
    modal.classList.toggle("modal-account-show");
    modal.classList.toggle("modal-account");
})

deletebtn.addEventListener("click", (event) => {
    event.preventDefault();
    alertbody.classList.toggle("alert-body");
    alertbody.classList.toggle("alert-body-show");
})

del.addEventListener("click", () => {
    const deleteacc = document.getElementById("deleteacc");
    deleteacc.submit();
})

if (document.querySelector(".updated")) {
    document.querySelector(".updated").addEventListener("animationend", () => {
        document.querySelector(".updated").style.display = "none";
    })

    document.querySelector(".updated").addEventListener("click", () => {
        document.querySelector(".updated").style.display = "none";
    })
} else if (document.querySelector(".added")) {
    document.querySelector(".added").addEventListener("animationend", () => {
        document.querySelector(".added").style.display = "none";
    })

    document.querySelector(".added").addEventListener("click", () => {
        document.querySelector(".added").style.display = "none";
    })
} else if (document.querySelector(".deleted")) {
    document.querySelector(".deleted").addEventListener("animationend", () => {
        document.querySelector(".deleted").style.display = "none";
    })

    document.querySelector(".deleted").addEventListener("click", () => {
        document.querySelector(".deleted").style.display = "none";
    })
} else if (document.querySelector(".exist")) {
    document.querySelector(".exist").addEventListener("animationend", () => {
        document.querySelector(".exist").style.display = "none";
    })

    document.querySelector(".exist").addEventListener("click", () => {
        document.querySelector(".exist").style.display = "none";
    })
}

reset.addEventListener("click", (event) => {
    event.preventDefault();
    username.value = "";
    password.value = "";
    role.value = "";
    usernameerr.style.display = "none";
    passworderr.style.display = "none";
    roleerr.style.display = "none";
})


openform.addEventListener("click", () => {
    form.classList.toggle("form");
    form.classList.toggle("show-form");
})

window.addEventListener("resize", () => {
    if (window.innerWidth > 1022 && form.classList.contains("show-form")) {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    }
})

window.addEventListener("click", (event) => {

    if (event.target.id == "form" && form.classList.contains("show-form")) {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    }

    if (event.target.id == "alert-body" && alertbody.classList.contains("alert-body-show")) {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    }

    if (event.target.classList == "modal-account-show") {
        modal.classList.toggle("modal-account-show");
        modal.classList.toggle("modal-account");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (username.value == "" && password.value == "" && role.value == "") {
        event.preventDefault();
        usernameerr.style.display = "block";
        passworderr.style.display = "block";
        roleerr.style.display = "block";
    }

    if (username.value == "") {
        event.preventDefault();
        usernameerr.style.display = "block";
    } else {
        usernameerr.style.display = "none";
    }

    if (password.value == "") {
        event.preventDefault();
        passworderr.style.display = "block";
    } else {
        passworderr.style.display = "none";
    }

    if (role.value == "") {
        event.preventDefault();
        roleerr.style.display = "block";
    } else {
        roleerr.style.display = "none";
    }

})

update.addEventListener("click", (event) => {

    let usernameerr = document.getElementById("usernameerror");
    let passworderror = document.getElementById("passworderror");
    let username = document.getElementById("curr_username");
    let password = document.getElementById("curr_password");

    if (username.value == "" && password.value == "" && role.value == "") {
        event.preventDefault();
        usernameerr.style.display = "block";
        passworderror.style.display = "block";
    }

    if (username.value == "") {
        event.preventDefault();
        usernameerr.style.display = "block";
    } else {
        usernameerr.style.display = "none";
    }

    if (password.value == "") {
        event.preventDefault();
        passworderror.style.display = "block";
    } else {
        passworderror.style.display = "none";
    }

})