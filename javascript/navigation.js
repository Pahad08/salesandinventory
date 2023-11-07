let menubody = document.getElementById("nav-body");
let menuicon = document.getElementById("menu-icon");
let body = document.querySelector(".body");

window.addEventListener("resize", () => {
    if (window.innerWidth > 1023 && menubody.classList.contains("show-nav")) {
        menubody.classList.toggle("show-nav");
        menubody.classList.toggle("nav");
    }

    if (window.innerWidth < 1023 && menubody.classList.contains("hide-nav")) {
        menubody.classList.toggle("nav");
        menubody.classList.toggle("hide-nav");
    }

    if (window.innerWidth < 1023 && body.classList.contains("body-move")) {
        body.classList.toggle("body");
        body.classList.toggle("body-move");
    }

})

menuicon.addEventListener("click", () => {

    if (window.innerWidth > 1022) {
        body.classList.toggle("body");
        body.classList.toggle("body-move");
        menubody.classList.toggle("nav");
        menubody.classList.toggle("hide-nav");
    } else {
        menubody.classList.toggle("nav");
        menubody.classList.toggle("show-nav");
    }

})

