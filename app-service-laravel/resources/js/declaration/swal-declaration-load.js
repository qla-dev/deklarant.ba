var thingsToInitialize = 2;

function thingInitialized() {
  thingsToInitialize--;
  if (thingsToInitialize <= 0) {
    Swal.close();
  }
  console.log("Thing was initialized. Things left to initialize:", thingsToInitialize)
}

if (typeof EditingMode !== 'undefined' && EditingMode === true) {
  Swal.fire({
    title: 'Učitavanje deklaracije',
    icon: null,
    html: `<div class="custom-swal-spinner mb-3"></div><div id="swal-status-message">Molimo sačekajte</div>`,
    showConfirmButton: false,
    allowOutsideClick: false,
    didOpen: () => {
      // Remove default icon if SweetAlert2 renders one
      const icon = Swal.getHtmlContainer()?.previousElementSibling;
      if (icon?.classList.contains('swal2-icon')) {
        icon.remove();
      }
    }
  });
}

