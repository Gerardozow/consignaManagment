// Tema oscuro/claro
document.getElementById("theme-toggle").addEventListener("click", function () {
  const html = document.documentElement;
  const newTheme =
    html.getAttribute("data-bs-theme") === "dark" ? "light" : "dark";
  html.setAttribute("data-bs-theme", newTheme);
  localStorage.setItem("theme", newTheme);
});

// Funciones para inventario
function editarItem(id) {
  fetch(`get_item.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      // Llenar modal de edición
      document.getElementById("edit-numero_parte").value = data.numero_parte;
      // ... completar otros campos
      new bootstrap.Modal(document.getElementById("editarItem")).show();
    });
}

function eliminarItem(id) {
  if (confirm("¿Estás seguro de eliminar este item?")) {
    window.location = `delete_item.php?id=${id}`;
  }
}
