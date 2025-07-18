import "./bootstrap";

const sidebarToggle = document.querySelector("#sidebarToggle");
sidebarToggle.addEventListener("click", () => {
    document.querySelector("#sidebarToggleIcon").classList.toggle("bi-list");
    document.querySelector("#sidebarToggleIcon").classList.toggle("bi-x");
    document.querySelector("#sidebar").classList.toggle("-ms-[280px]");
    setTimeout(() => {
        document.querySelector("#sidebar").classList.toggle("hidden");
    }, 100);
});
