// Manejo del tema
document.getElementById("theme-toggle").addEventListener("change", function () {
  const theme = this.checked ? "dark" : "light";
  document.documentElement.setAttribute("data-bs-theme", theme);
  localStorage.setItem("theme", theme);

  // Actualizar ícono
  const icon = document.querySelector("#theme-toggle + label i");
  icon.className = this.checked ? "bi bi-sun" : "bi bi-moon-stars";
});

// Inicializar tema al cargar
document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "light";
  const toggle = document.getElementById("theme-toggle");

  document.documentElement.setAttribute("data-bs-theme", savedTheme);
  toggle.checked = savedTheme === "dark";

  // Actualizar ícono inicial
  const icon = document.querySelector("#theme-toggle + label i");
  icon.className = savedTheme === "dark" ? "bi bi-sun" : "bi bi-moon-stars";
});

// Manejo de modales para ubicaciones
document
  .querySelectorAll('[data-bs-target="#modalUbicacion"]')
  .forEach((btn) => {
    btn.addEventListener("click", function () {
      const modal = document.getElementById("modalUbicacion");
      const form = modal.querySelector("form");

      if (this.dataset.id) {
        form.action = "actions/update_ubicacion.php";
        modal.querySelector("#nombre").value = this.dataset.nombre;
        modal.querySelector("#descripcion").value = this.dataset.descripcion;
        form.innerHTML += `<input type="hidden" name="id" value="${this.dataset.id}">`;
      } else {
        form.action = "actions/create_ubicacion.php";
        form.reset();
      }
    });
  });
