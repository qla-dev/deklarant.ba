const overlay = document.getElementById('pre-ai-overlay');

/*  Pokreći logiku SAMO ako overlay postoji i NIJE već sakriven  */
if (overlay && !overlay.classList.contains('d-none')) {

    setTimeout(() => {
        if (!window.AI_SCAN_STARTED) {

            /*  Sakrij overlay  */
            overlay.classList.add('d-none');
            overlay.style.display = 'none';

            /*  1) Postoji global_invoice_id → prikaži “Uredu / Odustani”  */
            if (window.global_invoice_id) {
                Swal.fire({
                    icon: "error",
                    title: "<div class='text-danger'>Nema pokrenutih procesa za AI obradu</div>",
                    text: "Prikazuje se zadnja obrađena deklaracija",
                    showConfirmButton: true,
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: "Uredu",
                    cancelButtonText: "Odustani",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        confirmButton: "btn btn-info",
                        cancelButton: "btn btn-soft-info me-2"
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isDismissed &&
                        result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = "/";
                    }
                });

            /*  2) Nema ni global_invoice_id → automatski redirect  */
            } else {
                Swal.fire({
                    icon: "error",
                    title: "<div class='text-danger'>Nema pokrenutih procesa za AI obradu niti spremih u lokalnom spremniku</div>",
                    text: "Automatsko prebacivanje na početnu stranicu",
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => window.location.href = "/");
            }
        }
    }, 15000); // provjera nakon 12 s
}

