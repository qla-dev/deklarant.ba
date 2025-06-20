<style>

    #vise-opcija-swal {

        padding-bottom: 1rem !important;
    }

    #vise-opcija-swal .swal2-title {padding-top: 20px!important;}
</style> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

 
 <div class="d-print-none" id="sidebar-buttons-container">
     <div id="fixed-buttons" class="d-flex flex-column gap-3">
        @php
         $isDeklaracija = Request::is('deklaracija') || Request::is('deklaracija/*');
         $isPregled = Request::segment(1) === 'detalji-deklaracije';
         @endphp

         @if($isDeklaracija)
        <button type="button" id="save-invoice-btn" class="btn btn-info " style="height: 28px !important; ">
             <i class="ri-save-line align-bottom me-1 fs-5"></i> Spremi promjene
         </button>
                  
         <button type="button" id="pregled" class="btn btn-soft-info  pc-opcije-button" style="height: 28px !important; ">
             <i class="ri-eye-line align-bottom me-1 fs-5"></i> Pregled
         </button>
         @endif
             @if($isPregled)
         <!-- Only for /detalji-deklaracije/* -->
         <button type="button" id="uredi" class="btn btn-soft-info" style="height: 28px !important; ">
             <i class="ri-edit-line align-bottom me-1  fs-5"></i> Uredi
         </button>
         @endif

  <a href="javascript:void(0)" onclick="renderPrintTableAndPrint()" class="btn btn-soft-info pc-opcije-button" style="height: 28px !important;">
    <i class="ri-printer-line align-bottom me-1 fs-5"></i> Isprintaj
</a>



      

         <a href="#" class="btn btn-soft-info  btn-original-doc pc-opcije-button" style="height: 28px !important;">
             <i class="ri-file-3-line align-bottom me-1 fs-5"></i> Originalni dokument
         </a>
            <a href="javascript:void(0);" onclick="autoDownloadPDF()" class="btn btn-soft-info pc-opcije-button" style="height: 28px !important;">
    <i class="ri-file-pdf-2-line align-bottom me-1 fs-5"></i> Export u PDF
</a>

         <button class="btn btn-soft-info  pc-opcije-button" onclick="exportTableToCustomCSV()" style="height: 28px !important; "><i class="ri-file-excel-line align-bottom me-1 fs-5"></i> Export u CSV</button>
         <a class="btn btn-soft-info pc-opcije-button" style="height: 28px !important; ">
             <i class="ri-file-code-line align-bottom me-1 fs-5"></i> Export u XML
         </a>
         

         @if($isDeklaracija)
         <!-- Only for /deklaracija/* -->

         <button type="button" id="brisanje" class="btn btn-soft-info  pc-opcije-button" style="height: 28px !important; ">
             <i class="ri-delete-bin-line align-bottom me-1 fs-5"></i> Obriši proizvode
         </button>
         @endif

     

         <button type="button" id="vise-opcija-btn" class="btn btn-soft-info"  style="height: 28px !important; ">
    <i class="ri-more-line align-bottom me-1 fs-5"></i> Više opcija
</button>

     </div>
 </div>
 <div class="text-start total-iznos">
     <div class="floatbar-nav-item w-100 mb-3">
         <p class="text-info fs-13  mb-0 text-uppercase fw-semibold  me-2">Id:</p>
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

<div class="modal fade" id="originalDocumentModal" style="z-index: 999;" tabindex="-1" aria-labelledby="originalDocLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="originalDocLabel">Originalni dokument</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="originalDocFrame" src="" width="100%" height="100%" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".btn-original-doc")?.addEventListener("click", async function (e) {
        e.preventDefault();

        let invoiceId = window.global_invoice_id;

        // Fallback: Try to extract from pathname
        if (!invoiceId) {
            invoiceId = window.location.pathname.split('/').pop();
        }

        if (!invoiceId) {
            Swal.fire("Greška", "Faktura nije pronađena", "error");
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

            // 👉 OPEN IN NEW TAB INSTEAD OF MODAL
            const fileUrl = `/uploads/original_documents/${fileName}`;
            window.open(fileUrl, '_blank');

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
                     text: "Svi proizvodi u tabeli će biti uklonjeni",
                     icon: "warning",
                     showCancelButton: true,
                     confirmButtonText: "Da, obriši sve",
                     cancelButtonText: "Odustani",
                     customClass: {
                         confirmButton: "btn btn-soft-info me-2",
                         cancelButton: "btn btn-info"
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
                <div class="d-flex flex-column text-start mt-1 mb-1">
            
                    @if($isDeklaracija)
                    <button class="btn btn-soft-info w-100 mb-3 p-0" onclick="document.getElementById('pregled')?.click()" style="height: 28px !important;">
                        <i class="ri-eye-line align-bottom me-1 fs-5"></i>Pregled
                    </button>

        
                    @endif

                    <button class="btn btn-soft-info w-100 mb-3 p-0" onclick="document.querySelector('.pc-opcije-button[href^=\'javascript:void\']')?.click()"  style="height: 28px !important;">
                        <i class="ri-download-2-line align-bottom me-1 fs-5"></i>Preuzmi
                    </button>

                    <button class="btn btn-soft-info w-100 mb-3 p-0" onclick="document.querySelector('.pc-opcije-button[href*=\'file-3\']')?.click()"  style="height: 28px !important;">
                        <i class="ri-file-3-line align-bottom me-1 fs-5"></i>Originalni dokument
                    </button>

                    <button class="btn btn-soft-info w-100 mb-3 p-0" onclick="exportTableToCustomCSV()"  style="height: 28px !important;">
                        <i class="ri-file-excel-line align-bottom me-1 fs-5"></i>Export u CSV
                    </button>

                    <a class="btn btn-soft-info w-100 p-0" style="height: 28px !important;">
                        <i class="ri-file-code-line align-bottom me-1 fs-5"></i>Export u XML
                    </a>

                    @if($isDeklaracija)
                

                    <button class="btn btn-soft-info w-100  mt-3 p-0" onclick="document.getElementById('brisanje')?.click()" style="height: 28px !important;">
                        <i class="ri-delete-bin-line align-bottom me-1 fs-5"></i>Obriši proizvode
                    </button>
                    @endif

                 
                </div>`,
                showCancelButton: false,
                showConfirmButton: false,
                showCloseButton: true,
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (popup) popup.setAttribute("id", "vise-opcija-swal");
                }
            });
        });
    }
});
</script>

