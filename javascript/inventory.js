let form = document.getElementById("form");
let openform = document.getElementById("stockadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let selectprod = document.getElementById("selectprod");
let quantities = document.getElementById("quantities");
let proderr = document.getElementById("proderr");
let quantityerr = document.getElementById("quantityerr");
let del = document.getElementById("del");
let cancel = document.getElementById("cancel");

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

    if (selectall) {
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
    }
}

AttachedEvents();

search.addEventListener("input", () => {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.getElementById("table").innerHTML = this.responseText;
        AttachedEvents();
    }
    xhttp.open("GET", "search/search_inventory.php?name=" + search.value);
    xhttp.send();
})

if (canceldelete) {
    canceldelete.addEventListener("click", () => {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    })
}

if (deletebtn) {
    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
    })
}

del.addEventListener("click", () => {
    const deletestocks = document.getElementById("deletestocks");
    deletestocks.submit();
})


if (document.querySelector(".added")) {
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
}


reset.addEventListener("click", (event) => {
    event.preventDefault();
    selectprod.value = "";
    quantities.value = "";
    proderr.style.display = "none";
    quantityerr.style.display = "none";
})

if (openform) {
    openform.addEventListener("click", () => {
        form.classList.toggle("form");
        form.classList.toggle("show-form");
    })

}

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

})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (selectprod.value == "" && quantities.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
        quantityerr.style.display = "block";
    }

    if (selectprod.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
    } else {
        proderr.style.display = "none";
    }

    if (quantities.value == "") {
        event.preventDefault();
        quantityerr.style.display = "block";
    } else {
        quantityerr.style.display = "none";
    }

})