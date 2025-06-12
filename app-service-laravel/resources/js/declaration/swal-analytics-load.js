Swal.fire({
  title: 'deklarant.ai je još u beta verziji',
  icon: null,
  html: `<div class="custom-swal-spinner mb-3"></div><div id="swal-status-message">Analitika dolazi u narednim verzijama</div>`,
  showConfirmButton: false,
  allowOutsideClick: false,
  timerProgressBar: true,
  timer: 2000,
  didOpen: () => {
    // Remove default icon if SweetAlert2 renders one
    const icon = Swal.getHtmlContainer()?.previousElementSibling;
    if (icon?.classList.contains('swal2-icon')) {
      icon.remove();
    }
  }
});

// Redirect to home after 3 seconds (do not close the modal)
setTimeout(() => {
  window.location.href = "/";
}, 3000);

