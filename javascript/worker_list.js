let form = document.getElementById("form");
let openform = document.getElementById("workeradd");
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
let accid = document.getElementById("account-id");
let fnameerr = document.getElementById("fnameerr");
let lnameerr = document.getElementById("lnameerr");
let numbererr = document.getElementById("numbererr");
let del = document.getElementById("del");
let cancel = document.getElementById("cancel");
let search = document.getElementById("search");
let update = document.getElementById("update");
let modal = document.querySelector(".modal-worker");

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
            let workerid = element.getAttribute("data-workerid");
            let f_name = element.getAttribute("data-fname");
            let l_name = element.getAttribute("data-lname");
            let number = element.getAttribute("data-number");
            let username = element.getAttribute("data-username");
            let acc_id = element.getAttribute("data-accid");

            let worker_id = document.getElementById("worker-id");
            let fname = document.getElementById("worker-fname");
            let lname = document.getElementById("worker-lname");
            let worker_num = document.getElementById("worker-number");
            let selected = document.getElementById("selected");

            worker_id.value = workerid;
            fname.value = f_name;
            lname.value = l_name;
            worker_num.value = number;
            selected.value = acc_id;
            selected.innerHTML = username;

            modal.classList.toggle("modal-worker");
            modal.classList.toggle("modal-worker-show");
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
    xhttp.open("GET", "search/search_worker.php?name=" + search.value);
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
    modal.classList.toggle("modal-worker-show");
    modal.classList.toggle("modal-worker");
})

del.addEventListener("click", () => {
    const deleteworker = document.getElementById("deleteworker");
    deleteworker.submit();
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
    accid.value = "";
    fnameerr.style.display = "none";
    lnameerr.style.display = "none";
    numbererr.style.display = "none";
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

    if (event.target.classList == "modal-worker-show") {
        modal.classList.toggle("modal-worker-show");
        modal.classList.toggle("modal-worker");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (fname.value == "" && lname.value == "" && number.value == "") {
        event.preventDefault();
        fnameerr.style.display = "block";
        lnameerr.style.display = "block";
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

})

update.addEventListener("click", (event) => {

    let fnameerr = document.getElementById("fnameerror");
    let lnameerr = document.getElementById("lnameerror");
    let numbererr = document.getElementById("numerr");

    let fname = document.getElementById("worker-fname");
    let lname = document.getElementById("worker-lname");
    let worker_num = document.getElementById("worker-number");

    if (fname.value == "" && lname.value == "" && worker_num.value == "") {
        event.preventDefault();
        fnameerr.style.display = "block";
        lnameerr.style.display = "block";
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

    if (worker_num.value == "") {
        event.preventDefault();
        numbererr.innerHTML = "Contact Number cannot be blank";
        numbererr.style.display = "block";
    } else if (worker_num.value.length <
        11) {
        event.preventDefault();
        numbererr.innerHTML = "Number length must not be below 11";
        numbererr.style.display = "block";
    } else if (worker_num.value.length > 11) {
        event.preventDefault();
        numbererr.innerHTML = "Number length must not exceed to 11";
        numbererr.style.display = "block";
    } else {
        numbererr.style.display = "none";
    }

})