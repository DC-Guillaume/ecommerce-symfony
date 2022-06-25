// Système de section filtre pour les catégories qui collapse en javascript

let coll = document.getElementsByClassName("collapse-filter");
let i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function () {
    this.classList.toggle("active");
    let content = this.nextElementSibling;
    if (content.style.maxHeight) {
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
}

//==================================================
// Gestion Slide du menu principal avec Hamburger
//==================================================
const menuSlide = () => {
  const burger = document.querySelector(".burger");
  const close = document.querySelector(".close-menu-slider");
  const menu = document.querySelector(".onglets");
  const body = document.querySelector("body");
  const pageOverlay = document.querySelector(".overlay-menu");

  if (burger != null) {
    // Gestion du menu Mobile
    burger.addEventListener("click", () => {
      menu.classList.toggle("menu-slider-active");
      pageOverlay.classList.toggle("overlay-is-visible");
      body.classList.toggle("body-fixed-position");
    });

    close.addEventListener("click", () => {
      menu.classList.remove("menu-slider-active");
      pageOverlay.classList.remove("overlay-is-visible");
      body.classList.remove("body-fixed-position");
    });

    pageOverlay.addEventListener("click", () => {
      menu.classList.remove("menu-slider-active");
      pageOverlay.classList.remove("overlay-is-visible");
      body.classList.remove("body-fixed-position");
    });
  }
};

menuSlide();

//==================================================
// Gestion Slider header
//==================================================

document.addEventListener("DOMContentLoaded", function () {
  let slider = document.getElementById("slider1");

  if (slider !== null) {
    var splide = new Splide("#slider1", {
      type: "loop",
      perPage: 1,
      autoplay: true,
      arrows: false,
      pauseOnHover: false,
      interval: 4000,
    });

    splide.mount();
  }
});

//==================================================
// Gestion Slider produits nouveautés
//==================================================
document.addEventListener("DOMContentLoaded", function () {
  let slider = document.getElementById("slider1");

  if (slider !== null) {
    var splide = new Splide("#slider2", {
      type: "loop",
      perMove: 1,
      perPage: 3,
      gap: "2rem",
      breakpoints: {
        1024: {
          perPage: 2,
        },
        768: {
          perPage: 1,
        },
      },
      autoplay: true,
      arrows: false,
      pauseOnHover: false,
      interval: 2000,
    });

    splide.mount();
  }
});

//==================================================
// Gestion des accordeons
//==================================================
const accordeonButNotTheInstrument = () => {
  let acc = document.getElementsByClassName("accordeon");

  if (acc.length > 0) {
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function () {
        // ajout de la classe active
        this.classList.toggle("accordeon-active");

        // recherche du prochain element dans la div parente : Panel
        let panel = this.nextElementSibling;

        // Si la maxHeight du panel est null lui ajouter sa valeure minimum en hauteur
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        }
      });
    }

    // permet de lancer le script et de garder l'onglet description ouvert de base.
    // breakpoint à 768px
    if (window.innerWidth > 768) {
      acc[0].click();
    }
  }
};

accordeonButNotTheInstrument();
