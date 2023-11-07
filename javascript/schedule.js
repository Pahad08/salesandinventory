let form = document.getElementById("form");
let openform = document.getElementById("schedadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
const deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let delivery_date = document.getElementById("delivery-date");
let quantity = document.getElementById("quantity");
let supplier = document.getElementById("supplier");
let product = document.getElementById("product");
let deliveryerr = document.getElementById("deliveryerr");
let quantityerr = document.getElementById("quantityerr");
let suppliererr = document.getElementById("suppliererr");
let producterr = document.getElementById("producterr");
let del = document.getElementById("del");
let cancel = document.getElementById("cancel");
let update = document.getElementById("update");
let cancelreceive = document.getElementById("close-receive");
let delsched = document.getElementById("del-sched");
let search = document.getElementById("search");
let modal = document.querySelector(".modal-transaction");
let receivebody = document.getElementById("receive-body");

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
    let receivebtns = document.querySelectorAll(".receive");

    if (receivebtns && receivebody) {
        receivebtns.forEach((element) => {
            element.addEventListener("click", (event) => {
                event.preventDefault();
                receivebody.classList.toggle("receive-body");
                receivebody.classList.toggle("receive-body-show");
                let url = element.getAttribute("data-id");
                delsched.href = "receive.php?id=" + url;
            })
        })
    }

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
            let transactionid = element.getAttribute("data-transactionid");
            let f_name = element.getAttribute("data-fname");
            let l_name = element.getAttribute("data-lname");
            let quantity = element.getAttribute("data-quantity");
            let delivery = element.getAttribute("data-delivery");
            let prod_id = element.getAttribute("data-prodid");
            let supplier_id = element.getAttribute("data-supplierid");
            let prod_name = element.getAttribute("data-prodname");

            let transaction_id = document.getElementById("transaction-id");
            let delivery_date = document.getElementById("deliver-date");
            let transac_quantity = document.getElementById("quant");
            let selected_supplier = document.getElementById("selected-supplier");
            let selected_product = document.getElementById("selected-product");

            transaction_id.value = transactionid;
            delivery_date.value = delivery;
            transac_quantity.value = quantity;
            selected_supplier.value = supplier_id;
            selected_product.value = prod_id;

            selected_supplier.innerHTML = f_name + " " + l_name;
            selected_product.innerHTML = prod_name;

            modal.classList.toggle("modal-transaction");
            modal.classList.toggle("modal-transaction-show");
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
    xhttp.open("GET", "search/search_sched.php?name=" + search.value);
    xhttp.send();
})

if (cancelreceive) {
    cancelreceive.addEventListener("click", (event) => {
        event.preventDefault();
        receivebody.classList.toggle("receive-body-show");
        receivebody.classList.toggle("receive-body");
    })
}

if (canceldelete) {
    canceldelete.addEventListener("click", () => {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    })
}

if (cancel) {
    cancel.addEventListener("click", (event) => {
        event.preventDefault();
        modal.classList.toggle("modal-transaction-show");
        modal.classList.toggle("modal-transaction");
    })
}


if (deletebtn) {
    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
    })
}

if (del) {
    del.addEventListener("click", () => {
        const deletesched = document.getElementById("deletesched");
        deletesched.submit();
    })
}

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
} else if (document.querySelector(".prodreceive")) {
    document.querySelector(".prodreceive").addEventListener("animationend", () => {
        document.querySelector(".prodreceive").style.display = "none";
    })

    document.querySelector(".prodreceive").addEventListener("click", () => {
        document.querySelector(".prodreceive").style.display = "none";
    })
}

reset.addEventListener("click", (event) => {
    event.preventDefault();
    delivery_date.value = "";
    quantity.value = "";
    supplier.value = "";
    product.value = "";
    deliveryerr.style.display = "none";
    quantityerr.style.display = "none";
    suppliererr.style.display = "none";
    producterr.style.display = "none";
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

    if (event.target.classList == "modal-transaction-show") {
        modal.classList.toggle("modal-transaction-show");
        modal.classList.toggle("modal-transaction");
    }

    if (event.target.classList == "receive-body-show") {
        receivebody.classList.toggle("receive-body");
        receivebody.classList.toggle("receive-body-show");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (delivery_date.value == "" && quantity.value == "" && supplier
        .value == "" && product.value == "") {
        event.preventDefault();
        deliveryerr.style.display = "block";
        quantityerr.style.display = "block";
        suppliererr.style.display = "block";
        producterr.style.display = "block";
    }

    if (delivery_date.value == "") {
        event.preventDefault();
        deliveryerr.style.display = "block";
    } else {
        deliveryerr.style.display = "none";
    }

    if (quantity.value == "") {
        event.preventDefault();
        quantityerr.style.display = "block";
    } else {
        quantityerr.style.display = "none";
    }

    if (supplier.value == "") {
        event.preventDefault();
        suppliererr.style.display = "block";
    } else {
        suppliererr.style.display = "none";
    }

    if (product.value == "") {
        event.preventDefault();
        producterr.style.display = "block";
    } else {
        producterr.style.display = "none";
    }

})

if (update) {
    update.addEventListener("click", (event) => {

        let deliveryerr = document.getElementById("delivererr");
        let quanterr = document.getElementById("quanterr");

        let deliverdate = document.getElementById("deliver-date");
        let quantity = document.getElementById("quant");

        if (deliverdate.value == "" && quantity.value == "" && supplier_id.value == "" && prod_id.value ==
            "") {
            event.preventDefault();
            deliveryerr.style.display = "block";
            quanterr.style.display = "block";
            supid_err.style.display = "block";
            prodid_err.style.display = "block";
        }

        if (deliverdate.value == "") {
            event.preventDefault();
            deliveryerr.style.display = "block";
        } else {
            deliveryerr.style.display = "none";
        }

        if (quantity.value == "") {
            event.preventDefault();
            quanterr.style.display = "block";
        } else {
            quanterr.style.display = "none";
        }


    })
}