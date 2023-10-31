let menubody = document.getElementById("nav-body");
let menuicon = document.getElementById("menu-icon");

window.addEventListener("resize", () => {
    if (window.innerWidth > 1023 && menubody.classList.contains("show-nav")) {
        menubody.classList.toggle("show-nav");
        menubody.classList.toggle("nav");
    }
})

menuicon.addEventListener("click", () => {
    menubody.classList.toggle("nav");
    menubody.classList.toggle("show-nav");
})

