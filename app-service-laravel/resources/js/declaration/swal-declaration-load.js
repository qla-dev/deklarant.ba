
  if (typeof EditingMode !== 'undefined' && EditingMode === true) {
    Swal.fire({
      title: 'Učitavanje fakture',
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

    // Auto-close after 3 seconds
    setTimeout(() => {
      Swal.close();
    }, 4000);
  }

