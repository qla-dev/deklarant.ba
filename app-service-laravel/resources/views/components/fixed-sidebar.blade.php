<style>

    .swal2-modal {

        padding-bottom: .7rem !important;
    }

    .swal2-title {padding-top: 20px!important;}
</style> 
 
 <div class="d-print-none" id="sidebar-buttons-container">
     <div id="fixed-buttons" class="d-flex flex-column gap-3">
        @php
         $isDeklaracija = Request::is('deklaracija') || Request::is('deklaracija/*');
         $isPregled = Request::segment(1) === 'detalji-deklaracije';
         @endphp

         @if($isDeklaracija)
        <button type="button" id="save-invoice-btn" class="btn btn-info btn-sm" style="height: 28px !important; width: 190 px !important;">
             <i class="ri-save-line align-bottom me-1"></i> Spremi promjene
         </button>
         @endif

         <a href="javascript:window.print()" class="btn btn-soft-info btn-sm" style="height: 28px !important; width: 190 px !important;">
             <i class="ri-printer-line align-bottom me-1"></i> Isprintaj
         </a>
         <a href="javascript:void(0);" class="btn btn-soft-info pc-opcije-button">
             <i class="ri-download-2-line align-bottom me-1"></i> Preuzmi
         </a>
         <a href="" class="btn btn-soft-info pc-opcije-button">
             <i class="ri-file-3-line align-bottom me-1"></i> Originalni dokument
         </a>

         <button class="btn btn-soft-info pc-opcije-button" onclick="exportTableToCustomCSV()"><i class="ri-file-excel-line align-bottom me-1"></i> Export u CSV</button>
         <a href="" class="btn btn-soft-info pc-opcije-button">
             <i class="ri-file-code-line align-bottom me-1"></i> Export u XML
         </a>
         

         @if($isDeklaracija)
         <!-- Only for /deklaracija/* -->
         
         <button type="button" id="pregled" class="btn btn-soft-info pc-opcije-button">
             <i class="ri-eye-line align-bottom me-1"></i> Pregled
         </button>
         <button type="button" id="brisanje" class="btn btn-soft-info pc-opcije-button">
             <i class="ri-delete-bin-line align-bottom me-1"></i> Obriši proizvode
         </button>
         @endif

         @if($isPregled)
         <!-- Only for /detalji-deklaracije/* -->
         <button type="button" id="uredi" class="btn btn-soft-info btn-sm pc-opcije-button" style="height: 28px !important; width: 190 px !important;">
             <i class="ri-edit-line align-bottom me-1"></i> Uredi
         </button>
         @endif

         <button type="button" id="vise-opcija-btn" class="btn btn-soft-info">
    <i class="ri-more-line align-bottom me-1"></i> Više opcija
</button>

     </div>
 </div>
 <div class="text-start total-iznos">
     <div class="floatbar-nav-item w-100 mb-3">
         <p class="text-info fs-13  mb-0 text-uppercase fw-semibold  me-2">Id fakture:</p>
         <h5 class="fs-13 mb-0">
             #
             <span id="{{ $isDeklaracija ? 'invoice-id1' : 'invoice-id' }}">Učitavanje...</span>
         </h5>
     </div>

     <div class="floatbar-nav-item w-100">
         <p class="text-info fs-13 mb-0 text-uppercase fw-semibold me-2">Datum :</p>
         <h5 class="fs-13 mb-0">
             <span id="{{ $isDeklaracija ? 'invoice-date-text' : 'invoice-date' }}">Učitavanje...</span>
         </h5>
     </div>

     <hr style="border-top: 1px solid gray; width: 100%;">

     <div class="floatbar-nav-item w-100">
         <p class="text-info fs-13 text-uppercase fw-semibold mb-0 me-2">Ukupan iznos:</p>
         <h5 class="fs-13 mb-0" id="currency">
             <span id="{{ $isDeklaracija ? 'total-edit' : 'total-1' }}">Učitavanje...</span>
         </h5>
     </div>



 </div>
 

<!-- original document modal -->

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".btn-original-doc")?.addEventListener("click", async function (e) {
        e.preventDefault();

        const invoiceId = localStorage.getItem("scan_invoice_id");
        if (!invoiceId) {
            Swal.fire("Greška", "Fajl nije pronađen", "error");
            return;
        }

        try {
            const res = await fetch(`/api/invoices/${invoiceId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!res.ok) throw new Error("Neuspješno dohvaćanje fajla");

            const data = await res.json();
            const fileName = data?.file_name;

            if (!fileName || !fileName.endsWith(".pdf")) {
                Swal.fire("Greška", "Fajl nije PDF ili nije pronađen", "error");
                return;
            }

            const fileUrl = `/storage/uploads/${fileName}`;
            document.getElementById("originalDocFrame").src = fileUrl;
            const modal = new bootstrap.Modal(document.getElementById("originalDocumentModal"));
            modal.show();

        } catch (err) {
            console.error("Greška pri otvaranju originalnog dokumenta:", err);
            Swal.fire("Greška", err.message || "Nepoznata greška", "error");
        }
    });
});
</script>



 <script>
     document.addEventListener("DOMContentLoaded", function() {
         const totalIznos = document.querySelector(".total-iznos");

         function hasVerticalScrollbar() {
             return document.documentElement.scrollHeight > window.innerHeight;
         }

         function toggleFixed() {
             const scrollBottom = window.scrollY + window.innerHeight;
             const pageHeight = document.documentElement.scrollHeight;

             if (pageHeight - scrollBottom <= 80) {
                 totalIznos.classList.add("static");
             } else {
                 totalIznos.classList.remove("static");
             }

             updateRightMargin();
         }

         function updateRightMargin() {
             const container = document.querySelector(".page-content .container-fluid");

             if (container && totalIznos) {
                 const viewportWidth = window.innerWidth;
                 const containerRight = container.offsetLeft + container.offsetWidth;
                 let rightSpace = Math.max(0, viewportWidth - containerRight);

                 // If scrollbar is present, decrease margin by 5px
                 if (hasVerticalScrollbar()) {
                     rightSpace = Math.max(0, rightSpace - 20);
                 }

                 totalIznos.style.marginRight = `${rightSpace}px`;
                 totalIznos.classList.add("visible");
             }
         }

         // Initial run
         toggleFixed();
         updateRightMargin();

         // Watch scroll and resize
         window.addEventListener("scroll", toggleFixed);
         window.addEventListener("resize", updateRightMargin);
     });
 </script>

 <!-- remove items bnt logic -->

 <script>
     document.addEventListener("DOMContentLoaded", function() {
         const deleteBtn = document.getElementById("brisanje");
         if (deleteBtn) {
             deleteBtn.addEventListener("click", function() {
                 Swal.fire({
                     title: "Jesi li siguran/na?",
                     text: "Svi proizvodi u tabeli će biti uklonjeni.",
                     icon: "warning",
                     showCancelButton: true,
                     confirmButtonText: "Da, obriši sve",
                     cancelButtonText: "Odustani",
                     customClass: {
                         confirmButton: "btn btn-danger me-2",
                         cancelButton: "btn btn-secondary"
                     },
                     buttonsStyling: false
                 }).then((result) => {
                     if (result.isConfirmed) {
                         const tbody = document.querySelector("#newlink");
                         if (tbody) {
                             tbody.innerHTML = ""; // remove all rows
                         }
                     }
                 });
             });
         }
     });
 </script>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const viseOpcijaBtn = document.getElementById("vise-opcija-btn");

    if (viseOpcijaBtn) {
        viseOpcijaBtn.addEventListener("click", function () {
            Swal.fire({
                title: "Više opcija",
                html: `
                <div class="d-flex flex-column text-start">
                    @if($isDeklaracija)
                    <button class="btn btn-info w-100 mb-2" onclick="document.getElementById('save-invoice-btn')?.click()">
                        <i class="ri-save-line align-bottom me-1"></i> Spremi promjene
                    </button>
                    @endif

                    <button class="btn btn-soft-info w-100 mb-2" onclick="window.print()">
                        <i class="ri-printer-line align-bottom me-1"></i> Isprintaj
                    </button>

                    <button class="btn btn-soft-info w-100 mb-2" onclick="document.querySelector('.pc-opcije-button[href^=\'javascript:void\']')?.click()">
                        <i class="ri-download-2-line align-bottom me-1"></i> Preuzmi
                    </button>

                    <button class="btn btn-soft-info w-100 mb-2" onclick="document.querySelector('.pc-opcije-button[href*=\'file-3\']')?.click()">
                        <i class="ri-file-3-line align-bottom me-1"></i> Originalni dokument
                    </button>

                    <button class="btn btn-soft-info w-100 mb-2" onclick="exportTableToCustomCSV()">
                        <i class="ri-file-excel-line align-bottom me-1"></i> Export u CSV
                    </button>

                    <button class="btn btn-soft-info w-100 mb-2" onclick="document.querySelector('.pc-opcije-button[href*=\'file-code\']')?.click()">
                        <i class="ri-file-code-line align-bottom me-1"></i> Export u XML
                    </button>

                    @if($isDeklaracija)
                    <button class="btn btn-soft-info w-100 mb-2" onclick="document.getElementById('pregled')?.click()">
                        <i class="ri-eye-line align-bottom me-1"></i> Pregled
                    </button>

                    <button class="btn btn-soft-info w-100 mb-2" onclick="document.getElementById('brisanje')?.click()">
                        <i class="ri-delete-bin-line align-bottom me-1"></i> Obriši proizvode
                    </button>
                    @endif

                    @if($isPregled)
                    <button class="btn btn-soft-info w-100 mb-2" onclick="document.getElementById('uredi')?.click()">
                        <i class="ri-edit-line align-bottom me-1"></i> Uredi
                    </button>
                    @endif
                </div>`,
                showCancelButton: false,
                showConfirmButton: false,
                showCloseButton: true,
            });
        });
    }
});
</script>

