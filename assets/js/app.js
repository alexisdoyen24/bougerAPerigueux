var sidenav = document.getElementById("mySidenav");
var openBtn = document.getElementById("openBtn");
var closeBtn = document.getElementById("closeBtn");

// paramétrage au 'click' pour ouvrir et fermé
openBtn.onclick = openNav;
closeBtn.onclick = closeNav;

/* fonction permettant l'ouverture du menu */
function openNav() {
  sidenav.classList.add("active");
}

/* fonction permettant la fermeture du menu */
function closeNav() {
  sidenav.classList.remove("active");
}