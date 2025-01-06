const sidebar = document.getElementById("sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");

function toggleSidebar() {
  // Only toggle if screen is larger than 800px
  if (window.innerWidth > 800) {
    sidebar.classList.toggle("close");

    if (sidebar.classList.contains("close")) {
      closeAllSubMenus();
    }
  }
}

// Add this to handle resize
window.addEventListener("resize", () => {
  if (window.innerWidth <= 800) {
    sidebar.classList.add("close");
  }
});

function toggleSubMenu(button) {
  const subMenu = button.nextElementSibling;

  if (!subMenu.classList.contains("show")) {
    closeAllSubMenus();
  }

  subMenu.classList.toggle("show");
  button.classList.toggle("rotate");

  if (sidebar.classList.contains("close")) {
    sidebar.classList.remove("close");
  }
}

function closeAllSubMenus() {
  Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
    ul.classList.remove("show");
    ul.previousElementSibling.classList.remove("rotate");
  });
}
