/* =========================
   DROPDOWN TOGGLE
========================= */
document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
    toggle.addEventListener("click", function (e) {
        e.preventDefault();
        this.parentElement.classList.toggle("active");
    });
});


/* =========================
   IMAGE MODAL
========================= */
function openImage(src) {
    const modalImage = document.getElementById("modalImage");
    const imageModal = document.getElementById("imageModal");

    modalImage.src = src;
    imageModal.style.display = "flex";
}

function closeImage() {
    document.getElementById("imageModal").style.display = "none";
}

// Tutup modal saat klik area gelap
document.getElementById("imageModal").addEventListener("click", function (e) {
    if (e.target === this) {
        closeImage();
    }
});




/* =========================
   HAMBURGER MENU
========================= */
const hamburger = document.getElementById("hamburger");
const navMenu = document.getElementById("navMenu");

hamburger.addEventListener("click", () => {
  navMenu.classList.toggle("active");
});

/* =========================
   DROPDOWN TOGGLE (MOBILE)
========================= */
document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
  toggle.addEventListener("click", function (e) {
    e.preventDefault();
    this.parentElement.classList.toggle("active");
  });
});
