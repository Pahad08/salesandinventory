let form = document.getElementById("form");
let openform = document.getElementById("supplieradd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let fname = document.getElementById("fname");
let lname = document.getElementById("lname");
let number = document.getElementById("number");
let company = document.getElementById("company");
let username = document.getElementById("username");
let password = document.getElementById("password");
let fnameerr = document.getElementById("fnameerr");
let lnameerr = document.getElementById("lnameerr");
let numbererr = document.getElementById("numbererr");
let companyerr = document.getElementById("companyerr");
let usernameerr = document.getElementById("usernameerr");
let passworderr = document.getElementById("passworderr");
let del = document.getElementById("del");
let cancel = document.getElementById("cancel");
let search = document.getElementById("search");
let update = document.getElementById("update");
let modal = document.querySelector(".modal-supplier");

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
            let supplierid = element.getAttribute("data-supplierid");
            let f_name = element.getAttribute("data-fname");
            let l_name = element.getAttribute("data-lname");
            let number = element.getAttribute("data-number");
            let company = element.getAttribute("data-company");

            let suppid = document.getElementById("supplier-id");
            let fname = document.getElementById("supplier-fname");
            let lname = document.getElementById("supplier-lname");
            let supplier_num = document.getElementById("supplier-number");
            let supplier_company = document.getElementById("supplier-company");

            suppid.value = supplierid;
            fname.value = f_name;
            lname.value = l_name;
            supplier_num.value = number;
            supplier_company.value = company;

            modal.classList.toggle("modal-supplier");
            modal.classList.toggle("modal-supplier-show");
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
    xhttp.open("GET", "search/search_supplier.php?name=" + search.value);
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
    modal.classList.toggle("modal-supplier-show");
    modal.classList.toggle("modal-supplier");
})

del.addEventListener("click", () => {
    const deletesupplier = document.getElementById("deletesupplier");
    deletesupplier.submit();
})

deletebtn.addEventListener("click", (event) => {
    event.preventDefault();
    alertbody.classList.toggle("alert-body");
    alertbody.classList.toggle("alert-body-show");
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
    fname.value = "";
    lname.value = "";
    number.value = "";
    company.value = "";
    username.value = "";
    password.value = "";
    fnameerr.style.display = "none";
    lnameerr.style.display = "none";
    numbererr.style.display = "none";
    companyerr.style.display = "none";
    usernameerr.style.display = "none";
    passworderr.style.display = "none";
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

    if (event.target.classList == "modal-supplier-show") {
        modal.classList.toggle("modal-supplier-show");
        modal.classList.toggle("modal-supplier");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (fname.value == "" && lname.value == "" && company.value == "" && number.value == ""
        && username.value == "" && password.value == "") {
        event.preventDefault();
        fnameerr.style.display = "block";
        lnameerr.style.display = "block";
        companyerr.style.display = "block";
        numbererr.style.display = "block";
        usernameerr.style.display = "block";
        passworderr.style.display = "block";
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
    } else if (number.value.length <
        11) {
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

    if (company.value == "") {
        event.preventDefault();
        companyerr.style.display = "block";
    } else {
        companyerr.style.display = "none";
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

})

update.addEventListener("click", (event) => {

    let fnameerr = document.getElementById("fnameerror");
    let lnameerr = document.getElementById("lnameerror");
    let numbererr = document.getElementById("numerr");
    let companyerr = document.getElementById("companyerror");

    let fname = document.getElementById("supplier-fname");
    let lname = document.getElementById("supplier-lname");
    let supplier_num = document.getElementById("supplier-number");
    let supplier_company = document.getElementById("supplier-company");

    if (fname.value == "" && lname.value == "" && supplier_num.value == "" && supplier_company.value ==
        "") {
        event.preventDefault();
        fnameerr.style.display = "block";
        lnameerr.style.display = "block";
        companyerr.style.display = "block";
        numbererr.style.display = "block";
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

    if (supplier_num.value == "") {
        event.preventDefault();
        numbererr.innerHTML = "Contact Number cannot be blank";
        numbererr.style.display = "block";
    } else if (supplier_num.value.length < 11) {
        event.preventDefault();
        numbererr.innerHTML = "Number length must not be below 11";
        numbererr.style.display = "block";
    } else if (supplier_num.value.length > 11) {
        event.preventDefault();
        numbererr.innerHTML = "Number length must not exceed to 11";
        numbererr.style.display = "block";
    } else {
        numbererr.style.display = "none";
    }

    if (supplier_company.value == "") {
        event.preventDefault();
        companyerr.style.display = "block";
    } else {
        companyerr.style.display = "none";
    }

})