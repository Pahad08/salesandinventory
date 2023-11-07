let form = document.getElementById("form");
let openform = document.getElementById("expenseadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let description = document.getElementById("description");
let amount = document.getElementById("amount");
let descriptionerr = document.getElementById("descriptionerr");
let amounterr = document.getElementById("amounterr");
let del = document.getElementById("del");
let cancel = document.getElementById("cancel");
let update = document.getElementById("update");
let search = document.getElementById("search");

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
    let modal = document.querySelector(".modal-expense");

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
        element.addEventListener("click", () => {
            event.preventDefault();
            let data_id = element.getAttribute("data-expenseid");
            let description = element.getAttribute("data-description");
            let amount = element.getAttribute("data-amount");
            let exp_description = document.getElementById("expense-description");
            let expense_amount = document.getElementById("expense-amount");
            let expense_id = document.getElementById("expense-id");

            expense_id.value = data_id;
            exp_description.value = description;
            expense_amount.value = amount;

            modal.classList.toggle("modal-expense");
            modal.classList.toggle("modal-expense-show");
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
    xhttp.open("GET", "search/search_expense.php?description=" + search.value);
    xhttp.send();
})

selectall.addEventListener("click", () => {
    checkboxes.forEach((element) => {

        if (element.checked == false) {
            element.checked = true;
        }
    })
})

if (canceldelete) {
    canceldelete.addEventListener("click", () => {
        alertbody.classList.toggle("alert-body-show");
        alertbody.classList.toggle("alert-body");
    })
}

cancel.addEventListener("click", (event) => {
    event.preventDefault();
    modal.classList.toggle("modal-expense-show");
    modal.classList.toggle("modal-expense");
})


deletebtn.addEventListener("click", (event) => {
    event.preventDefault();
    alertbody.classList.toggle("alert-body");
    alertbody.classList.toggle("alert-body-show");
})

del.addEventListener("click", () => {
    const deleteexpense = document.getElementById("deleteexpense");
    deleteexpense.submit();
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
    description.value = "";
    amount.value = "";
    descriptionerr.style.display = "none";
    amounterr.style.display = "none";
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

    if (event.target.id == "alert-body" && alertbbody.classList.contains("alert-body-show")) {
        alertbbody.classList.toggle("alert-body-show");
        alertbbody.classList.toggle("alert-body");
    }

    if (event.target.classList == "modal-expense-show") {
        modal.classList.toggle("modal-expense-show");
        modal.classList.toggle("modal-expense");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (description.value == "" && amount.value == "") {
        event.preventDefault();
        descriptionerr.style.display = "block";
        amounterr.style.display = "block";
    }

    if (description.value == "") {
        event.preventDefault();
        descriptionerr.style.display = "block";
    } else {
        descriptionerr.style.display = "none";
    }

    if (amount.value == "") {
        event.preventDefault();
        amounterr.style.display = "block";
    } else {
        amounterr.style.display = "none";
    }

})

update.addEventListener("click", (event) => {

    let desc_err = document.getElementById("desc-err");
    let amount_err = document.getElementById("amount-err");
    let expense_description = document.getElementById("expense-description");
    let expense_amount = document.getElementById("expense-amount");

    if (expense_description.value == "" && expense_amount.value == "") {
        event.preventDefault();
        desc_err.style.display = "block";
        amount_err.style.display = "block";
    }

    if (expense_description.value == "") {
        event.preventDefault();
        desc_err.style.display = "block";
    } else {
        desc_err.style.display = "none";
    }

    if (expense_amount.value == "") {
        event.preventDefault();
        amount_err.style.display = "block";
    } else {
        amount_err.style.display = "none";
    }

})