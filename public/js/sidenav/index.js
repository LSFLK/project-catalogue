const sidenav = document.getElementById("sidenav");
const sidenavWidth = "230px";

function openSidenav() {
    sidenav.style.width = sidenavWidth;
}

function closeSidenav() {
    sidenav.style.width = 0;
}

function toggleSidenav() {
    if(!parseInt(sidenav.style.width)) { openSidenav() }
    else { closeSidenav() }
}

sidenav.addEventListener("click", () => closeSidenav());

window.onresize = function() { closeSidenav() }