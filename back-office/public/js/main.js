(() => {
  // Sidebar Dropdown Toggle Button
  const sideDropdown = document.querySelectorAll(".sidebar-dropdown");
  if (sideDropdown) {
    sideDropdown.forEach((dropdown) => {
      dropdown.addEventListener("click", (event) => {
        event.preventDefault();
        const target = dropdown.getAttribute("data-target");
        const expand = document.querySelector(target);
        if (expand.classList.contains("open")) {
          expand.classList.remove("open");
          expand.setAttribute("data-target", false);
          return;
        }
        expand.setAttribute("data-target", true);
        expand.classList.add("open");
        return;
      });
    });
  }
  // Sidebar Toggle Button
  const toggleBidebebar = document.querySelector("#header-hamburger");
  if (toggleBidebebar) {
    toggleBidebebar.addEventListener("click", (event) => {
      event.preventDefault();
      const el = event.target;
      const sidebar = document.querySelector(".side-wrapper");
      const sidebarContainer = document.querySelector(".layout-dashboard");

      sidebarContainer.classList.toggle("one-column");
    });
  }
})();
