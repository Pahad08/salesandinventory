let form = document.getElementById("form");
let openform = document.getElementById("saleadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let selectprod = document.getElementById("selectprod");
let quantity = document.getElementById("quantity");
let proderr = document.getElementById("proderr");
let quantityerr = document.getElementById("quantityerr");
let loc = document.getElementById("location");
let locerr = document.getElementById("locerr");
let modal = document.querySelector(".modal-sales");
let cancel = document.getElementById("cancel");
let del = document.getElementById("del");
let search = document.getElementById("search");
let update = document.getElementById("update");

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
    let modal = document.querySelector(".modal-sales");

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
            let id = element.getAttribute("data-id");
            let data_quantity = element.getAttribute("data-quantity");
            let data_currquantity = element.getAttribute("data-currquantity");
            let prodid = element.getAttribute("data-prodid");
            let prodname = element.getAttribute("data-prodname");

            let name = document.getElementById("select-value");
            let quantity = document.getElementById("quantity-value");
            let sale_id = document.getElementById("sale-id");
            let currquantity = document.getElementById("curr-quantity");
            let selected = document.getElementById("selected");

            sale_id.value = id;
            quantity.value = data_quantity;
            currquantity.value = data_currquantity;
            selected.value = prodid
            selected.innerHTML = prodname;

            modal.classList.toggle("modal-sales");
            modal.classList.toggle("modal-sales-show");
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
    xhttp.open("GET", "search/search_sales.php?name=" + search.value);
    xhttp.send();
})

cancel.addEventListener("click", (event) => {
    event.preventDefault();
    modal.classList.toggle("modal-sales-show");
    modal.classList.toggle("modal-sales");
})

if (canceldelete) {
    canceldelete.addEventListener("click", () => {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    })
}

deletebtn.addEventListener("click", (event) => {
    event.preventDefault();
    alertbody.classList.toggle("alert-body");
    alertbody.classList.toggle("alert-body-show");
})

del.addEventListener("click", () => {
    const deletesales = document.getElementById("deletesales");
    deletesales.submit();
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
    selectprod.value = "";
    quantity.value = "";
    proderr.style.display = "none";
    quantityerr.style.display = "none";
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

    if (event.target.classList == "modal-sales-show") {
        modal.classList.toggle("modal-sales-show");
        modal.classList.toggle("modal-sales");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (selectprod.value == "" && quantity.value == "") {
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

    if (quantity.value == "") {
        event.preventDefault();
        quantityerr.style.display = "block";
    } else {
        quantityerr.style.display = "none";
    }

})

update.addEventListener("click", (event) => {

    let iderr = document.getElementById("iderr");
    let quantityerr = document.getElementById("quanterr");

    let name = document.getElementById("prodselect");
    let quantity = document.getElementById("quantity-value");

    if (name.value == "" && quantity.value == "") {
        event.preventDefault();
        iderr.style.display = "block";
        quantityerr.style.display = "block";
    }

    if (name.value == "") {
        event.preventDefault();
        iderr.style.display = "block";
    } else {
        iderr.style.display = "none";
    }

    if (quantity.value == "") {
        event.preventDefault();
        quantityerr.style.display = "block";
    } else {
        quantityerr.style.display = "none";
    }
})