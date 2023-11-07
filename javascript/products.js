let form = document.getElementById("form");
let openform = document.getElementById("prodadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let name = document.getElementById("product-name");
let kilogram = document.getElementById("kilogram");
let price = document.getElementById("price");
let proderr = document.getElementById("proderr");
let kilogramerr = document.getElementById("kilogramerr");
let priceerr = document.getElementById("priceerr");
let del = document.getElementById("del");
let modal = document.querySelector(".modal-product");
let cancel = document.getElementById("cancel");
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
    let modal = document.querySelector(".modal-product");

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
            let data_id = element.getAttribute("data-productid");
            let data_name = element.getAttribute("data-name");
            let data_kilogram = element.getAttribute("data-kilogram");
            let data_price = element.getAttribute("data-price");
            let name = document.getElementById("prod-name");
            let kilogram = document.getElementById("prod-kilo");
            let price = document.getElementById("prod-price");
            let id = document.getElementById("prod-id");

            id.value = data_id;
            name.value = data_name;
            kilogram.value = data_kilogram;
            price.value = data_price;

            modal.classList.toggle("modal-product");
            modal.classList.toggle("modal-product-show");
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
    xhttp.open("GET", "search/search_product.php?name=" + search.value);
    xhttp.send();
})

cancel.addEventListener("click", (event) => {
    event.preventDefault();
    modal.classList.toggle("modal-product-show");
    modal.classList.toggle("modal-product");
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
    const deleteprod = document.getElementById("deleteproduct");
    deleteprod.submit();
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
    name.value = "";
    kilogram.value = "";
    price.value = "";
    proderr.style.display = "none";
    kilogramerr.style.display = "none";
    priceerr.style.display = "none";
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

    if (event.target.id == "alert-body" && alertbody.classList.contains(
        "alert-body-show")) {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    }

    if (event.target.classList == "modal-product-show") {
        modal.classList.toggle("modal-product-show");
        modal.classList.toggle("modal-product");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (name.value == "" && kilogram.value == "" && price.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
        kilogramerr.style.display = "block";
        kilogramerr.style.display = "block";
    }

    if (name.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
    } else {
        proderr.style.display = "none";
    }

    if (kilogram.value == "") {
        event.preventDefault();
        kilogramerr.style.display = "block";
    } else {
        kilogramerr.style.display = "none";
    }

    if (price.value == "") {
        event.preventDefault();
        priceerr.style.display = "block";
    } else {
        priceerr.style.display = "none";
    }

})

update.addEventListener("click", (event) => {

    let proderr = document.getElementById("nameerr");
    let kilogramerr = document.getElementById("kiloerr");
    let priceerr = document.getElementById("Priceerr");
    let name = document.getElementById("prod-name");
    let kilogram = document.getElementById("prod-kilo");
    let price = document.getElementById("prod-price");

    if (name.value == "" && kilogram.value == "" && price.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
        kilogramerr.style.display = "block";
        priceerr.style.display = "block";
    }

    if (name.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
    } else {
        proderr.style.display = "none";
    }

    if (kilogram.value == "") {
        event.preventDefault();
        kilogramerr.style.display = "block";
    } else {
        kilogramerr.style.display = "none";
    }

    if (price.value == "") {
        event.preventDefault();
        priceerr.style.display = "block";
    } else {
        priceerr.style.display = "none";
    }

})