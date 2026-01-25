/* ======================================
   NAVBAR & DROPDOWN SCRIPT (FIXED)
====================================== */

document.addEventListener("DOMContentLoaded", function () {

  const hamburger = document.getElementById("hamburger");
  const navMenu = document.getElementById("navMenu");
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

  /* =========================
     HAMBURGER MENU
  ========================= */
  hamburger.addEventListener("click", function () {
    navMenu.classList.toggle("active");
    this.classList.toggle("active");
    this.textContent = this.classList.contains("active") ? "✕" : "☰";
  });

    /* =========================
    DROPDOWN TOGGLE (MOBILE)
    ========================= */

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const parent = this.parentElement;

            // Tutup dropdown lain (accordion)
            dropdownToggles.forEach(item => {
            if (item.parentElement !== parent) {
                item.parentElement.classList.remove("active");
            }
            });

            parent.classList.toggle("active");
        });
    });


  /* =========================
     CLOSE MENU (KECUALI DROPDOWN)
  ========================= */
  document.querySelectorAll(".nav-menu a").forEach(link => {
    link.addEventListener("click", function () {

      // ❌ Kalau ini dropdown toggle, jangan tutup menu
      if (this.classList.contains("dropdown-toggle")) return;

      if (window.innerWidth <= 768) {
        navMenu.classList.remove("active");
        hamburger.classList.remove("active");
        hamburger.textContent = "☰";

        // Tutup semua dropdown
        dropdownToggles.forEach(item => {
          item.parentElement.classList.remove("active");
        });
      }
    });
  });

  /* =========================
     RESET SAAT RESIZE
  ========================= */
  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      navMenu.classList.remove("active");
      hamburger.classList.remove("active");
      hamburger.textContent = "☰";

      dropdownToggles.forEach(item => {
        item.parentElement.classList.remove("active");
      });
    }
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