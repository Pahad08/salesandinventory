let acc = document.querySelector(".modal-acc");
let profile = document.querySelector(".modal-profile");
let edit_profile = document.getElementById("edit-profile");
let edit_acc = document.getElementById("edit-acc");
let cancel = document.querySelectorAll(".cancel");
let update_profile = document.querySelector("#update-profile");
let update_acc = document.querySelector("#update-acc");

edit_acc.addEventListener("click", () => {

    let data_id = edit_acc.getAttribute("data-accid");
    let data_username = edit_acc.getAttribute("data-username");
    let acc_id = document.getElementById("acc-id");
    let username = document.getElementById("username");

    acc_id.value = data_id;
    username.value = data_username;

    acc.classList.toggle("modal-acc");
    acc.classList.toggle("modal-acc-show");
})

edit_profile.addEventListener("click", () => {

    let data_workerid = edit_profile.getAttribute("data-workerid");
    let data_fname = edit_profile.getAttribute("data-fname");
    let data_lname = edit_profile.getAttribute("data-lname");
    let data_number = edit_profile.getAttribute("data-contactnumber");
    let data_company = edit_profile.getAttribute("data-company");

    let profile_id = document.getElementById("profile-id");
    let fname = document.getElementById("fname");
    let lname = document.getElementById("lname");
    let number = document.getElementById("number");
    let company = document.getElementById("company");

    profile_id.value = data_workerid;
    fname.value = data_fname;
    lname.value = data_lname;
    number.value = data_number;
    company.value = data_company;

    profile.classList.toggle("modal-profile");
    profile.classList.toggle("modal-profile-show");
})

cancel.forEach((element) => {
    element.addEventListener("click", (event) => {
        event.preventDefault();
        if (acc.classList.contains("modal-acc-show")) {
            acc.classList.toggle("modal-acc-show");
            acc.classList.toggle("modal-acc");
        }

        if (profile.classList.contains("modal-profile-show")) {
            profile.classList.toggle("modal-profile-show");
            profile.classList.toggle("modal-profile");
        }

    })
})



window.addEventListener("click", (event) => {
    if (event.target.className == "modal-acc-show" && acc.classList.contains("modal-acc-show")) {
        acc.classList.toggle("modal-acc-show");
        acc.classList.toggle("modal-acc");
    }
})

update_acc.addEventListener("click", (event) => {
    let usernameerr = document.getElementById("usernameerr");
    let curr_passworderr = document.getElementById("curr-passworderr");
    let new_passworderr = document.getElementById("new-passworderr");
    let curr_password = document.getElementById("curr-pass");
    let new_password = document.getElementById("new-pass");

    if (username.value == "" && curr_password.value == "" && new_password.value == "") {
        event.preventDefault();
        usernameerr.style.display = "block";
        curr_passworderr.style.display = "block";
        new_passworderr.style.display = "block";
    }

    if (username.value == "") {
        event.preventDefault();
        usernameerr.style.display = "block";
    } else {
        usernameerr.style.display = "none";
    }

    if (curr_password.value == "") {
        event.preventDefault();
        curr_passworderr.style.display = "block";
    }
    else {
        curr_passworderr.style.display = "none";
    }

    if (new_password.value == "") {
        event.preventDefault();
        new_passworderr.style.display = "block";
    }
    else {
        new_passworderr.style.display = "none";
    }

})

update_profile.addEventListener("click", (event) => {
    let fnameerr = document.getElementById("fnameerr");
    let lnameerr = document.getElementById("lnameerr");
    let numbererr = document.getElementById("numbererr");
    let companyerr = document.getElementById("companyerr");
    let fname = document.getElementById("fname");
    let lname = document.getElementById("lname");
    let number = document.getElementById("number");
    let company = document.getElementById("company");

    if (fname.value == "" && lname.value == "" && number.value == "" && company.value == "") {
        event.preventDefault();
        fnameerr.style.display = "block";
        numbererr.style.display = "block";
        lnameerr.style.display = "block";
        companyerr.style.display = "block";
    }

    if (fname.value == "") {
        event.preventDefault();
        fnameerr.style.display = "block";
    } else {
        fnameerr.style.display = "none";
    }

    if (lname.value == "") {
        event.preventDefault();
        lnameerr.style.display = "block";
    } else {
        lnameerr.style.display = "none";
    }

    if (number.value == "") {
        event.preventDefault();
        numbererr.innerHTML = "Contact Number cannot be blank";
        numbererr.style.display = "block";
    } else if (number.value.length < 11) {
        event.preventDefault();
        numbererr.innerHTML = "Number length must not be below 11";
        numbererr.style.display = "block";
    } else if (number.value.length > 11) {
        event.preventDefault();
        numbererr.innerHTML = "Number length must not exceed to 11";
        numbererr.style.display = "block";
    } else {
        numbererr.style.display = "none";
    }

})

if (document.querySelector(".updated")) {
    document.querySelector(".updated").addEventListener("animationend", () => {
        document.querySelector(".updated").style.display = "none";
    })

    document.querySelector(".updated").addEventListener("click", () => {
        document.querySelector(".updated").style.display = "none";
    })
} else if (document.querySelector(".exist")) {
    document.querySelector(".exist").addEventListener("animationend", () => {
        document.querySelector(".exist").style.display = "none";
    })

    document.querySelector(".exist").addEventListener("click", () => {
        document.querySelector(".exist").style.display = "none";
    })
} else if (document.querySelector(".pass-err")) {
    document.querySelector(".pass-err").addEventListener("animationend", () => {
        document.querySelector(".pass-err").style.display = "none";
    })

    document.querySelector(".pass-err").addEventListener("click", () => {
        document.querySelector(".pass-err").style.display = "none";
    })
}