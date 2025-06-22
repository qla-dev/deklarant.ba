@extends('layouts.master')
@section('title')
@lang('translation.create-invoice')
@endsection
@section('css')
<!-- <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">-->
<!-- Sweet Alert css-->
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>

    #total-num-packages::placeholder {
        text-align:right!important;
    }

    #total-weight-gross::placeholder {
        text-align:center!important;
    }
    
    /* Ensures the selected text is truncated with ellipsis and tooltip works */
    .select2-container--default .select2-results__options {
        max-width: none !important;

        /* or use 100% if you want it full-width */
        white-space: normal;

        /* allow wrapping of long lines */
    }

    .form-check-input:checked {
        background-color: #299dcb !important;
        border-color: #299dcb !important;
    }



    /* Optional: make the search input area full width too */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        width: 100% !important;


    }

  

    .select2-container--default .select2-results>.select2-results__options {

        opacity: 1 !important;
        backdrop-filter: blur(4px);
        /* Optional: add blur for better visibility */
        border: 1px solid #ccc;
        /* Optional: for contrast if needed */
    }

    /* Hovered item (highlighted in dropdown with keyboard or mouse) */
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #299cdb !important;
        /* or use your specific info color */
        color: white !important;
        /* text color to maintain contrast */
    }


    .detached-fixed-buttons {

        position: fixed !important;
        top: calc(70.8px + 40.5px);
        /* 110.91px total offset */
        margin-top: 6px;
        width: 13.19vw;

        z-index: 1050;
    }

    .custom-select-icon {
        /* Remove default arrow in some browsers */
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;

        /* Add padding on right for the icon */
        padding-right: 2rem;

        /* Position relative to allow background positioning */
        position: relative;
        background-image: url('data:image/svg+xml;utf8,<svg fill="gray" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        background-color: #f4f4fc;
        /* keep your bg */
    }



    .custom-swal-popup {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .custom-swal-spinner {
        margin: 0 auto;
        width: 32px;
        height: 32px;
        border: 3px solid #0dcaf0;
        /* Bootstrap info color */
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    .custom-select-icon:focus {
        box-shadow: none !important;
        /* Remove Bootstrap purple shadow */
        outline: none !important;
        /* Remove default outline */
        border-color: #299dcb;
        /* Optional: set border color to info blue */
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .table> :not(caption)>*>* {
        color: inherit !important;

        padding: .30rem .2rem !important;
    }

    table.table {

        visibility: visible !important;


    }

    thead th {
        background: #f1f1f1;


    }

    tbody td,
    tbody th {

        color: #333;


    }
</style>


@endsection
@section('content')

<div class="row justify-content-center mt-0 mb-3  content-desktop">
    <div class="col card paper-layout">
        <div id="invoice-form">
            <div class="card-header border-0 p-4 pb-0">
                <img src="{{ URL::asset('build/images/logo-light-ai.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="34">
                            <img src="{{ URL::asset('build/images/logo-dark-ai.png') }}" class="card-logo card-logo-light" alt="logo light" height="17">
                <div class="row g-4 justify-content-between py-4">
       <div class="col-6 col-md-3 col-mob">
        <div class="mt-4">
            <h6 class="text-muted text-uppercase fw-semibold">Moji podaci</h6>
            <input type="text" class="form-control mb-2" id="company-name" name="name" placeholder="Ime kompanije" disabled value="{{ Auth::user()->company['name'] ?? '' }}">
            <input type="text" class="form-control mb-2" id="company-id" name="id" placeholder="ID kompanije" disabled value="{{ Auth::user()->company['id'] ?? '' }}">
            <input type="email" class="form-control mb-2" id="company-address" name="address" placeholder="Adresa" disabled value="{{ Auth::user()->company['address'] ?? '' }}">
            <p class="fs-12 text-muted m-0">
                Ovo su informacije o tvojoj kompaniji. Možete ih uvijek prilagoditi na 
                <a href="/profil" class="text-info">pregledu svog profila.</a>
            </p>
        </div>
    </div>

    <!-- Indentation Column -->
    <div class="d-none d-md-block col-md-6 mobile-landscape-hide"></div>

    <!-- Right Column: Invoice Info -->
    <div class="col-6 col-md-3 col-mob">
        <div class="mt-4">
            <h6 class="text-muted text-uppercase fw-semibold mt-1">Broj fakture</h6>
            <input type="text" class="form-control" id="invoice-no" name="invoice_no" placeholder="Unesite broj fakture">
        </div>

      <div style="margin-top: 1.85rem;">
  <h6 class="text-muted text-uppercase fw-semibold mt-1">Incoterm</h6>
  
  <div class="d-flex gap-2">
    <select class="form-select mb-2 custom-select-icon incoterm2" name="incoterm" id="incoterm">
      <option value="" selected disabled>Izaberite</option>
      <option value="EXW">EXW</option>
      <option value="FCA">FCA</option>
      <option value="CPT">CPT</option>
      <option value="CIP">CIP</option>
      <option value="DAP">DAP</option>
      <option value="DPU">DPU</option>
      <option value="DDP">DDP</option>
      <option value="FAS">FAS</option>
      <option value="FOB">FOB</option>
      <option value="CFR">CFR</option>
      <option value="CIF">CIF</option>
    </select>

    <input type="text" id="incoterm-destination" class="form-control mb-2" placeholder="Destinacija">
  </div>
</div>

    </div>
                </div>

              

            </div>
            <div class="card-body p-4 border-top border-top-dashed">
                <div class="row g-4">
                    <div class="col-6 text-start">

                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Dobavljač</h6>

                        <div class="mb-2">
                            <div style="display: flex;">
                                <button type="button" class="btn btn-sm btn-info mb-2 me-2 deklaracija-action-buttons" id="add-new-supplier"><i class="fas fa-wand-magic-sparkles fs-6 me-0 me-md-1"></i><span class="mobile-landscape-hide">Detektovani dobavljač iz baze</span></button>
                                <button type="button" class="btn btn-sm btn-soft-info mb-2 deklaracija-action-buttons" id="refill-supplier-ai"><i class="fa-regular fa-hand align-top me-0 me-md-1 korpica"></i><span class="mobile-landscape-hide">Ručni unos dobavljača</span></button>
                            </div>
                            <select id="supplier-select2" class="form-select"></select>
                        </div>
                        <input type="text" class="form-control mb-2" id="billing-name" name="supplier_name" placeholder="Naziv dobavljača">

                        <input type="text" class="form-control mb-2" id="billing-address-line-1" name="supplier_address" placeholder="Adresa dobavljača">
                        <input type="text" class="form-control mb-2" id="billing-phone-no" name="supplier_phone" placeholder="Telefon dobavljača">
                        <input type="text" class="form-control mb-2" id="billing-tax-no" name="supplier_tax" placeholder="VAT dobavljača">
                        <input type="email" class="form-control mb-2" id="email" name="email" placeholder="Email dobavljača">
                        <input type="email" class="form-control" id="supplier-owner" name="supplierOwner" placeholder="Vlasnik kompanije">
                    </div>
                    <div class="col-6 text-end">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3 text-end">Klijent</h6>

                        <div class="mb-2">
                            <div style="justify-content: end; display: flex;">
                                <button type="button" class="btn btn-sm btn-soft-info mb-2  me-2  deklaracija-action-buttons" id="refill-importer-ai"><i class="fa-regular fa-hand align-top me-0 me-md-1 korpica"></i><span class="mobile-landscape-hide">Ručni unos klijenta</span></button>
                                <button type="button" class="btn btn-sm btn-info mb-2 deklaracija-action-buttons" id="add-new-importer"><i class="fas fa-wand-magic-sparkles fs-6 me-0 me-md-1"></i><span class="mobile-landscape-hide">Detektovani klijent iz baze</span></button>

                            </div>

                            <select id="importer-select2" class="form-select"></select>
                        </div>

                        <input type="text" class="form-control mb-2 text-end" id="carrier-name" name="dobavljačime" placeholder="Naziv dobavljača">

                        <input type="text" class="form-control mb-2 text-end" id="carrier-address" name="klijentadresa" placeholder="Adresa klijenta">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-tel" name="klijenttel" placeholder="Telefon klijenta">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-tax" name="klijenttel" placeholder="JIB klijenta">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-email" name="klijenttel" placeholder="Email klijenta">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-owner" name="carrierOwner" placeholder="Vlasnik kompanije">

                    </div>
                </div>
            </div>

            <div class="card-body p-4 border-top border-top-dashed">
                <div class="row g-4 mb-3">
                    <div class="col-4 text-start">
                        <label class="text-muted text-uppercase fw-semibold mb-1"># Deklaracije</label>
                        <input type="text" class="form-control" id="invoice-no1" name="invoice_no1" placeholder="Broj fakture" disabled>
                    </div>
                    <div class="col-4 text-center">
                        <label class="d-flex justify-content-center text-muted text-uppercase fw-semibold mb-1">Datum</label>
                        <input type="date" class="form-control text-center" id="invoice-date" name="invoice_date">
                    </div>
                    <div class="col-4 text-end">
                        <label class="text-muted text-uppercase fw-semibold mb-1">Ukupan iznos</label>
                        <input type="text" class="form-control text-end" id="total-amount" name="total_amount" placeholder="0.00 KM" disabled>
                    </div>
                </div>
                   <!-- Added fields -->
    <div class="row g-4">
        <div class="col-4 text-start">
            <label class="text-muted text-uppercase fw-semibold mb-1">Neto težina (kg)</label>
            <input type="number" step="0.01" class="form-control" id="total-weight-net" name="total_weight_net" placeholder="0.00 kg">
        </div>
        <div class="col-4 text-center">
            <label class="d-flex justify-content-center text-muted text-uppercase fw-semibold mb-1">Bruto težina (kg)</label>
            <input type="number" step="0.01" class="form-control text-center" id="total-weight-gross" name="total_weight_gross" placeholder="0.00 kg">
        </div>
        <div class="col-4 text-end">
            <label class="text-muted text-uppercase fw-semibold mb-1">Broj koleta</label>
            <input type="number" class="form-control text-end" id="total-num-packages" name="total_num_packages" placeholder="0">
        </div>
    </div>
<input id="q1-estimate" name="q1" type="hidden">




            </div>



            <div class="card-body p-4 border-top border-top-dashed">
                <div class="table-responsive">
                    <table class="table table-borderless text-center table-nowrap align-middle mb-0" id="products-table">
                        <thead class="table-active">
                            <tr>
                                <th style="width: 50px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">#</th>
                                <th style="width: 200px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; padding-right: 50px!important;">Proizvod </th>
                                <th style="width: 140px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; margin-left: -5px!important;">Opis </th>
                                <th style="width: 350px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Tarifna oznaka</th>
                                <th style="width: 50px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Jed. mjere</th>
                                <th style="width:120px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Porijeklo/Pov..</th>
                              
                                 <th style="width:100px; text-align: center;vertical-align: middle; padding-bottom: 1rem;">Koleta</th>
                                  
                                <th style="width: 100px; text-align: center;vertical-align: middle;">
                                    Bruto (kg)<br>
                                    <small style="font-weight: normal; font-size: 0.75rem; color: #666;">
                                        Neto (kg)
                                    </small>
                                </th>
                                    <th style="width: 100px; text-align: center;vertical-align: middle;">
                                    Količina<br>
                                  
                                </th>
                                  
  <th style="width:100px; text-align: center;vertical-align: middle; padding-bottom: 1rem;">Cijena</th>
                                <th style="width:100px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Ukupno</th>
                                <th style="width:20px;vertical-align: middle; text-align: end;">Ukloni 
    
                                </th>
                            </tr>
                        </thead>
                        <tbody id="newlink">

                        </tbody>
                        <tbody>
                            <tr class="text-end mt-3">
                                <td colspan="12" class="text-end mt-3">
                                 <a href="#" id="add-item" class="btn btn-info fw-medium mt-2" role="button">
    <i class="ri-add-fill me-1 align-bottom"></i> Dodaj proizvod
</a>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-top border-top-dashed mt-2">
                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:200px">
                        <tbody class="border-bottom-dashed">
                            <tr class="border-top border-top-dashed fs-15">
                                <th>Ukupan iznos:</th>
                                <th class="text-end" id="modal-total-amount"> </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <input type="hidden" id="currency-lock" value="">

    @include('components.fixed-sidebar')
</div>


 
<!-- Centered fullscreen warning message for mobile -->
<div class="content-mobile-warning d-none d-flex flex-column align-items-center justify-content-center text-center" style="height: 70vh;">
    <!-- Light theme icon -->
    <lord-icon
        id="rotate-icon-light"
        src="/build/images/rotate-phone.json"
        trigger="loop"
        colors="secondary:#299cdb"
        style="width:80px;height:80px;margin-bottom: 1rem;">
    </lord-icon>

    <!-- Dark theme icon -->
    <lord-icon
        id="rotate-icon-dark"
        src="/build/images/rotate-phone-dark.json"
        trigger="loop"
        colors="secondary:#299cdb"
        style="width:80px;height:80px;margin-bottom: 1rem; display: none;">
    </lord-icon>

    <div>
        <strong class="d-block mb-1">Molimo okreni uređaj horizontalno</strong>
        <span class="text-muted">da bi pristupio prikazu deklaracije</span>
    </div>
</div>

<div class="modal fade" id="aiSuggestionModal" tabindex="-1" aria-labelledby="aiSuggestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white" id="aiSuggestionModalLabel">
                    <i class="fas fa-wand-magic-sparkles text-white"></i> Najbolji AI Prijedlozi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body" id="aiSuggestionsBody" style="padding: 1.5rem;">
                <div class="text-muted">Učitavanje prijedloga...</div>
                <!-- Prijedlozi će biti umetnuti ovdje putem JavaScript-a -->
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">
                    Zatvori
                </button>
            </div>
        </div>
    </div>
</div>






<!--end row-->
@endsection
@section('script')



<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>-->
<script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
<!-- <script src="{{ URL::asset('build/js/pages/invoicecreate.init.js') }}"></script> -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/format-to-decimal.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/fix-sidebar.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/action-buttons.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/swal-declaration-load.js') }}"></script>
<script src="{{ URL::asset('build/js/countries.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/bs.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>




<script>
 

    let EditingMode = {{ isset($id) ? 'true' : 'false' }};

// Define and expose global_invoice_id globally
if (typeof window !== "undefined") {
  if (!EditingMode) {
    window.global_invoice_id = localStorage.getItem("scan_invoice_id");
    window.remainingScans = {!! json_encode(Auth::user()->getRemainingScans()) !!};
  } else {
    window.global_invoice_id = {!! isset($id) ? json_encode($id) : 0 !!};
  }
  
}

 
</script>

<!-- Scan and other logic script -->
<script>
    const isEditMode = false;


    if (isEditMode) {

        console.warn(" Edit mode detected – skipping scan/autofill script.");
        // Exit the script entirely
        // Note: Wrap the entire content below inside an IIFE or block
        // Or better – put all scan logic inside a condition
    } else {
        console.log(' Custom invoice JS loaded');

        function showRetryError(title, message) {
    Swal.fire({
        title: title,
        html: `<div class="text-danger">${message}</div>`,
        icon: "error",
        showCancelButton: true,
        confirmButtonText: "Pokušaj ponovo",
        cancelButtonText: "Odustani",
        reverseButtons: true, // ⬅️ Flip button positions
        customClass: {
            confirmButton: "btn btn-info",
            cancelButton: "btn btn-soft-info me-2"
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload(); // Reload page on "Pokušaj ponovo"
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            location.href = "/"; // Redirect ONLY if user clicked "Odustani"
        }
    });
}



        let _invoice_data = null;
        let processedTariffData = [];
        let globalAISuggestions = [];
        const remaining_scans = @json(Auth::user() -> getRemainingScans());

        // Add global flags
        window.forceNewSupplier = false;
        window.forceNewImporter = false;
        window.skipPrefillParties = false; // NEW: skip prefill after manual clear

        function getInvoiceId() {
            const id = window.global_invoice_id;
            console.log(" Invoice ID:", id);
            return id;
        }
        async function updateRemainingScans() {
            console.log(" updateRemainingScans() called ");

            if (!user?.id || !token) {
                console.warn("Missing user or token in updateRemainingScans");
                return;
            }

            // Use global value and decrease it
            const newRemaining = Math.max(0, remaining_scans - 1); // safe fallback to 0

            try {
                const response = await fetch(`/api/user-packages/users/${user.id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        remaining_scans: newRemaining
                    })
                });

                if (!response.ok) {
                    throw new Error(`PUT failed with status ${response.status}`);
                }

                const data = await response.json();
                console.log(" Scan count updated in backend:", data);

            } catch (err) {
                console.error(" Failed to update scan count:", err);
            }
        }




 function updateTotalAmount() {
    let total = 0;
    const currencySymbols = {
        "EUR": "€",
        "USD": "$",
        "KM": "KM"
    };

    const productRows = document.querySelectorAll("#newlink tr.product");

    // Get locked currency from hidden input
    let lockedCurrency = document.getElementById("currency-lock").value;

   productRows.forEach((row) => {
    const itemName = row.querySelector('input[name="item_name[]"]')?.value?.trim();
    const price = parseFloat((row.querySelector('input[name="price[]"]')?.value || '0').replace(',', '.'));
    const quantity = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);

    // 🚫 Skip rows with no name or zeroed values
    if (!itemName || (price === 0 && quantity === 0)) return;

    total += price * quantity;

    if (!lockedCurrency) {
        const currencyInput = row.querySelector('input[name="currency[]"]');
        if (currencyInput && currencyInput.value.trim()) {
            lockedCurrency = currencyInput.value.trim();
            document.getElementById("currency-lock").value = lockedCurrency;
            console.log("✅ Locked currency:", lockedCurrency);
        }
    }
});


    const currency = lockedCurrency || "EUR";
    const currencySymbol = currencySymbols[currency] || currency;
    const formatted = `${formatDecimal(total, 2)} ${currencySymbol}`;
    document.getElementById("total-amount").value = formatted;
    document.getElementById("modal-total-amount").textContent = formatted;
    document.getElementById("total-edit").textContent = formatted;

    // ⬇️ Also update q1-estimate here
    const numPackages = parseFloat(document.getElementById("total-num-packages")?.value || 0);
    const q1Input = document.getElementById("q1-estimate");
    document.getElementById('q1-estimate')?.addEventListener('input', updateProcjenaEstimates);


    if (numPackages > 0) {
        const q1 =  numPackages/ total;
        q1Input.value = q1.toFixed(6);
q1Input.dispatchEvent(new Event("input")); // ✅ ključni event za procjenu
    } else {
        q1Input.value = "";
q1Input.dispatchEvent(new Event("input")); // isto da očisti procjene

    }
}






function updateProcjenaEstimates() {
  const q1 = parseFloat(document.getElementById("q1-estimate")?.value || 0);
  const rows = document.querySelectorAll("#newlink tr.product");

  rows.forEach(row => {
    const total = parseFloat(row.querySelector('input[name="total[]"]')?.value || 0);
    const procjenaInput = row.querySelector('input[name="procjena[]"]');

    if (procjenaInput) {
      const result = q1 * total;
     procjenaInput.value = formatDecimal(result, 2);
    }
  });
}



        
document.getElementById('total-num-packages')?.addEventListener('input', () => {
    updateTotalAmount(); // This will auto-update q1 as well
});

async function getInvoice() {
            if (!_invoice_data) {
                console.log(" Fetching invoice...");
                const res = await fetch(`/api/invoices/${getInvoiceId()}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });
                _invoice_data = await res.json();
                console.log(" Invoice data fetched:", _invoice_data);
            } else {
                console.log(" Using cached invoice data:", _invoice_data);
            }
            return _invoice_data;
        }



        async function startAiScan() {
            const taskId = getInvoiceId();

            if (!taskId) {
                console.warn("No task ID found in localStorage.");
                return false;
            }

            console.log("Starting AI scan for task ID:", taskId);

            //  Show loader inside scan function
          

            try {
                const response = await fetch(`/api/invoices/${taskId}/scan`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    let errorText = "Nepoznata greška";
                    try {
                        const err = await response.json();
                        errorText = err?.error || errorText;
                    } catch (jsonErr) {
                        console.warn("Response nije u JSON formatu", jsonErr);
                    }

                    console.error("AI scan response greška:", errorText);
                    Swal.close();

                    showRetryError(
                        "Greška pri pokretanju skeniranja",
                        errorText,
                        () => startAiScan()
                    );

                    return false;
                }

                console.log("AI scan started successfully");
                return true;

            } catch (error) {
                console.error("AI scan fetch failed:", error);
                Swal.close();

                showRetryError(
                    "Greška pri komunikaciji",
                    error.message || "Nepoznata greška",
                    () => startAiScan()
                );

                return false;
            }
        }



        async function waitForAIResult(showLoader = true) {
            window.AI_SCAN_STARTED = true;
    const invoice_id = getInvoiceId();
    if (!invoice_id) return;

    let progress = 0; // Start at 0%
    let countdown = 50;
    let progressBar = null;
    let timerText = null;
    let fakeInterval = null;
    let countdownInterval = null;
    let lastStep = null;
    let stuckTimer = 0;

    const stepTextMap = {
        null: "Pokretanje AI tehnologije u pozadini",
        conversion: "Konvertovanje dokumenta u potreban format",
        extraction: "Obrada deklaracije pomoću AI tehnologije",
        enrichment: "Obogaćivanje podataka i generisanje deklaracije"
    };

    // Show loader Swal immediately
    if (showLoader) {
        document.getElementById('pre-ai-overlay')?.classList.add('d-none');

        Swal.fire({
            title: "Skeniranje",
            html: `
                <div class="custom-swal-spinner mb-3"></div>
                <div id="swal-status-message">Čeka na obradu</div>
                <div class="mt-3 w-100">
                    <div class="progress" style="height: 16px;">
                        <div id="scan-progress-bar"
                             class="progress-bar progress-bar-striped progress-bar-animated bg-info fw-bold text-white"
                             role="progressbar"
                             style="width: 0%; font-size: 0.85rem; line-height: 16px; transition: width 0.6s ease;"
                             aria-valuemin="0" aria-valuemax="100">0%
                        </div>
                    </div>
                    <div class="text-muted mt-1" style="font-size: 0.85rem;">
                        Preostalo vrijeme: <span id="scan-timer">50s</span>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        await new Promise(r => setTimeout(r, 0));
        progressBar = document.getElementById("scan-progress-bar");
        timerText = document.getElementById("scan-timer");

        // Start continuous progress movement
        fakeInterval = setInterval(() => {
            if (progress < 95) { // Don't go above 95% until complete
                progress = Math.min(95, progress + 0.5);
                updateProgressBar(progress);
            }
        }, 1000);

        // Start countdown timer
        countdownInterval = setInterval(() => {
            countdown--;
            if (timerText) {
                timerText.textContent = `${countdown}s`;
            }
            // Reset countdown when it hits 5 seconds
            if (countdown <= 5) {
                countdown = 15;
                timerText.textContent = `${countdown}s`;
            }
        }, 1000);
        setTimeout(() => {
    const container = timerText?.parentElement;
    if (container) {
        const notice = document.createElement("div");
        notice.className = "text-muted mt-2";
        notice.style.fontSize = "0.82rem";
        notice.innerHTML = `Ovaj proces može završiti u pozadini. Prati progres kroz pregled <a href="/moje-deklaracije" class="text-info">mojih deklaracija.</a> Kada se završi skeniranje, status će preći u draft, te ćeš moći revizirati podatke i dalje manipulisati sa istim.`;
        container.appendChild(notice);
    }
}, 7000);
    }

    function updateProgressBar(value) {
        if (!progressBar) return;
        const clamped = Math.min(95, Math.max(0, value)); // Allow starting from 0%
        progressBar.style.width = `${clamped}%`;
        progressBar.innerHTML = `${Math.floor(clamped)}%`;
    }

    while (true) {
        let status, step, errorMsg;

        try {
            const res = await fetch(`/api/invoices/${invoice_id}/scan`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                }
            });

            if (!res.ok) throw new Error(`Greška kod API poziva: ${res.status} ${res.statusText}`);
            const json = await res.json();

            status = json?.status?.status;
            step = json?.status?.processing_step;
            errorMsg = json?.status?.error_message;

            // Step progress logic
            const stepProgress = {
                conversion: 30,
                extraction: 50,
                enrichment: 80
            };
            const targetProgress = stepProgress[step] || 10;

            if (step !== lastStep) {
                stuckTimer = 0;
                progress = Math.max(progress, targetProgress);
                updateProgressBar(progress);
                lastStep = step;
            } else {
                stuckTimer++;
                if (stuckTimer >= 3) {
                    progress = Math.max(0, progress - 3); // bounce back but never below 0%
                    updateProgressBar(progress);
                    await new Promise(r => setTimeout(r, 500));
                    progress = Math.max(progress, targetProgress);
                    updateProgressBar(progress);
                    stuckTimer = 0;
                }
            }

        } catch (err) {
            console.error("Greška u waitForAIResult:", err);
            clearInterval(fakeInterval);
            clearInterval(countdownInterval);
            Swal.close();
            showRetryError(
                "Greška pri skeniranju",
                err.message || "Nepoznata greška",
                () => waitForAIResult()
            );
            break;
        }

        // Update status text
        const el = document.getElementById("swal-status-message");
        if (el) {
            if (status === "failed" || status === "error") {
                el.innerHTML = `<span class='text-danger'>Greška: ${errorMsg || 'Nepoznata greška'}</span><br><span class='text-muted'>Korak: ${stepTextMap[step] || step || ''}</span>`;
            } else {
                el.textContent = stepTextMap[step] || "Obrađujemo podatke...";
            }
        }

        // SUCCESS
        if (status === "completed") {
            clearInterval(fakeInterval);
            clearInterval(countdownInterval);
            updateProgressBar(100); // Show 100% on completion

            Swal.close();

            setTimeout(() => {
                Swal.fire({
                    icon: "success",
                    title: "Završeno",
                    text: "Deklaracija je uspješno spremljena u draft",
                    showConfirmButton: false,
                    timer: 3000,
                    allowOutsideClick: false,
                    position: "center"
                });
            }, 300);

            _invoice_data = null;
            break;
        }

        // FAILURE
        if (status === "failed" || status === "error") {
            clearInterval(fakeInterval);
            clearInterval(countdownInterval);
            Swal.close();
            showRetryError(
                "Greška pri skeniranju",
                `${errorMsg || "Nepoznata greška"}<br><span class="text-muted">${stepTextMap[step] || step || ""}</span>`,
                () => waitForAIResult()
            );
            break;
        }

        await new Promise(r => setTimeout(r, 2000));
    }
}

function initializeTariffSelects() {
    $('.select2-tariff').each(function () {
        const $select = $(this);
        const prefillValue = $select.data("prefill");

        // Reset Select2 if already initialized
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            placeholder: "Izaberi oznaku",
            allowClear: false,
            width: '100%',
            dropdownCssClass: 'tariff-selection',
            minimumInputLength: 1,
            language: {
                inputTooShort: () => "Pretraži oznake...",
                searching: () => "Pretraga...",
                noResults: () => "Nema rezultata..",
                loadingMore: () => "Učitavanje još rezultata..."
            },
            ajax: {
  transport: function (params, success, failure) {
    const term = (params.data.q || "").toLowerCase();

    // show spinner…
    const container = document.querySelector('.select2-results__options');
    if (container) {
      container.innerHTML = `
        <li class="select2-results__option" role="alert" aria-live="assertive">
          <i class="fa fa-spinner fa-spin" style="margin-right:6px;"></i>
          Pretraga...
        </li>`;
    }

    // split into matches and non-matches
    const matches = processedTariffData.filter(item =>
      item.id.toLowerCase().includes(term) ||
      item.display.toLowerCase().includes(term)
    );
    const rest = processedTariffData.filter(item =>
      !matches.some(m => m.id === item.id)
    );

    setTimeout(() => {
      // concat matches first, then the rest
      success({ results: [...matches, ...rest] });

      // reset scroll
      const dropdown = document.querySelector('.select2-results__options');
      if (dropdown) dropdown.scrollTop = 0;
    }, 200);
  },
  delay: 200
},

            templateResult: function (item) {
                if (!item || !item.id || !item.display) return null;

                const digits = item.id.replace(/\D+/g, '');
                const is4Digit = /^\d{4}$/.test(digits);
                const is6Digit = /^\d{6}$/.test(digits);

                let isParent = false;
                if (is4Digit) {
                    isParent = true;
                } else if (is6Digit) {
                    const parentId = item.id.slice(0, 4);
                    isParent = !processedTariffData.some(other =>
                        other.id.startsWith(parentId) && /^\d{4}$/.test(other.id.replace(/\D+/g, ''))
                    );
                }

                const icon = isParent ? "›" : "•";
                const padding = isParent ? 0 : item.depth * 5;
                const fontWeight = isParent ? "bold" : "normal";

                return $(`<div style="padding-left:${padding}px; font-weight:${fontWeight};" title="${item.display}">
                    ${icon} ${item.display}
                </div>`);
            },
            templateSelection: function (item) {
                return item?.id || "";
            }
        });

        $select.on('select2:open', function () {
            setTimeout(() => {
                const input = document.querySelector('.select2-container--open .select2-search__field');
                if (input) {
                    input.focus();
                    input.removeEventListener('input', smartMaskHandler);
                    input.addEventListener('input', smartMaskHandler, { passive: true });

                    // Prefill search field with selected value
                    const selected = $select.val();
                    if (selected) {
                        input.value = selected;
                        const evt = new Event('input', { bubbles: true });
                        input.dispatchEvent(evt);
                    }
                }
            }, 0);
        });

        if (prefillValue) {
            const match = processedTariffData.find(item => item.id === prefillValue);
            if (match) {
                const option = new Option(match.id, match.id, true, true);
                $select.append(option).trigger('change');
            }
        }
    });
}

function smartMaskHandler(e) {
    const rawInput = e.target.value;
    const digitsOnly = rawInput.replace(/\D+/g, "");

    if (digitsOnly.length >= 4) {
        const formatted = digitsOnly.replace(
            /^(\d{4})(\d{0,2})(\d{0,2})(\d{0,2})/,
            (_, a, b, c, d) => [a, b, c, d].filter(Boolean).join(' ')
        );

        if (/^\d[\d\s]*$/.test(rawInput)) {
            e.target.value = formatted;
            setTimeout(() => {
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }, 0);
        }
    }
}

function addRowToInvoice(item = {}, suggestions = []) {
            const tbody = document.getElementById("newlink");

            const index = tbody.children.length;

            globalAISuggestions.push(suggestions);
            const itemId = item.id || "";
            const name = item.name || item.item_description_original || "";
            const tariff = item.item_code || item.tariff_code || "";
            const price = item.base_price || 0;
            const quantity = item.quantity || 0;
            const origin = item.country_of_origin || "DE";
            const currency = item.currency || "EUR";
            const total = formatDecimal(price * quantity, 2);
            const desc = (item.item_description ?? "") || "";
            const translate = item.translate || item.item_description_translated || "";
            const package_num = item.num_packages || 0;
            const tariff_privilege = item.tariff_privilege || 0;
            const qtype = item.quantity_type || "";
            const best_customs_code_matches = item.best_customs_code_matches || [];
            const weight_gross = item.weight_gross || 0;
            const weight_net = item.weight_net || 0;

            console.log(` Adding row ${index + 1}:`, item, suggestions);

            const row = document.createElement("tr");
            row.classList.add("product");




            function generateCountryOptions(selectedCode = "") {
    return window.countries.map(({ code, name }) => {
        const flagUrl = `https://flagcdn.com/w40/${code}.png`;
        const isSelected = selectedCode?.toLowerCase() === code ? "selected" : "";
        return `<option value="${code.toUpperCase()}" ${isSelected} data-flag="${flagUrl}">${code.toUpperCase()}</option>`;
    }).join("");
}
row.innerHTML = `
          <td style="width: 50px;">${index + 1}</td>
     
          <td colspan="2" style="width: 340px;">
            <div class="input-group" style="display: flex; gap: 0.25rem;">
              <input type="text" class="form-control item-name" name="item_name[]" placeholder="Naziv" value="${name}" style="flex:1;">
              <input type="text" class="form-control item-desc" name="item_desc[]" placeholder="Opis" value="${desc}" style="flex:1;">
            </div>
          <input 
  type="text" 
  class="form-control form-control-sm mt-1 text-uppercase"
  style="font-size: 0.65rem; padding-left:14.4px; height:37.1px; text-transform: uppercase;"
  name="item_prev[]" 
  placeholder="Prevod"
  value="${translate}"
>

          </td>
<input type="hidden" name="item_id[]" value="${itemId || ''}">
<input 
  type="hidden" 
  name="best_customs_code_matches[]" 
  value='${JSON.stringify(best_customs_code_matches || [])
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/&/g, '&amp;')
    .replace(/'/g, '&#39;')
  }'>

<td class="text-start" style="width: 150px;">
  <div style="position: relative; width: 100%;">
    <select
      class="form-control select2-tariff tariff-selection"
      style="width: 100%; padding-right: 75px;"
      name="item_code[]"
      data-prefill="${tariff || ''}"
      data-suggestions='${JSON.stringify(suggestions || [])
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/&/g, '&amp;')
        .replace(/'/g, '&#39;')
      }'>
    </select>

   <!-- 🔍 Google search tooltip button -->
<button
  type="button"
  class="btn btn-outline-info btn-sm google-search-btn"
  style="
    position: absolute;
    top: 50%;
    right: 40px;
    transform: translateY(-50%);
    height: 30px;
    width: 30px;
    padding: 0;
    border-radius: 3px;
  "
  data-bs-toggle="tooltip"
  data-bs-placement="top"
  data-bs-original-title="Klikni za Google pretragu: ${name} ${desc}"
  onclick="searchFromInputs(this)"
>
  <i class="fab fa-google" style="font-size: 15px;"></i>
</button>



    <!-- ✨ AI suggestion button -->
    <button
      type="button"
      data-bs-toggle="tooltip"
      class="btn btn-outline-info btn-sm show-ai-btn"
      style="
        position: absolute;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        height: 30px;
        width: 30px;
        padding: 0;
        border-radius: 3px;
      "
      title="Prikaži AI prijedloge"
    >
      <i class="fas fa-wand-magic-sparkles" style="font-size: 16px;"></i>
    </button>
  </div>
</td>

  </div>
</td>


         <td style="width: 60px;">
  <input 
    type="text" 
    class="form-control" 
    name="quantity_type[]" 
    placeholder="AD, AE.." 
    value="${qtype || 'KOM'}"
  >
</td>


      <td class="text-start" style="width: 130px;">
  <div style="position: relative; width: 100%;">
    <select class="form-select" name="origin[]" style="width: 100%;">
      ${generateCountryOptions(origin)}
    </select>

    <!-- ✅ Povlastica (checkbox) pozicioniran desno, ne ometa dropdown -->
    <input 
       type="checkbox" 
  class="form-check-input tariff-privilege-toggle"
  name="tariff_privilege_check[]"
  ${tariff_privilege !== 0 && tariff_privilege !== "0" ? 'checked' : ''}
  data-bs-toggle="tooltip"
  data-bs-original-title="${tariff_privilege !== 0 && tariff_privilege !== '0' ? tariff_privilege : 'Odaberi povlasticu'}"
  style="
        position: absolute;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        width: 26px;
        height: 26px;
        cursor: pointer;
        margin-top:0px!important;
        z-index:99!important;
        border: 1px solid #299cdb;
      "
    />
    <!-- 🔒 Lock icon (hidden by default) -->
<span
  style="
    position: absolute;
    top: 50%;
    right: 5px;
    transform: translateY(-50%);
    width: 26px;
    height: 26px;
    border: 1px solid #ccc;
    border-radius: 3px;
    display: inline-block;
  "
  data-bs-toggle="tooltip"
  data-bs-placement="top"
  title="Odabrana država nema nijednu povlasticu"
  class="lock-disabled"
>
  <i class="fa fa-lock" aria-hidden="true" style="
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px;
    color: #888;
  "></i>
</span>




    <!-- hidden field -->
    <input 
      type="hidden" 
      name="tariff_privilege[]" 
      value="${tariff_privilege || 0}"
    >
  </div>
</td>



 <td style="width: 70px;">
  <input 
    type="text" 
    class="form-control text-start procjena-field" 
    name="procjena[]" 
    value="" 
    readonly 
    style="width: 100%; background-color: #f9f9f9;"
  >
</td>
       
                    <td style="width: 80px;">
            <div style="display: flex; flex-direction: column; gap: 2px; width: 100%;">
              <div class="input-group input-group-sm" style="width: 100%;">
                <button 
                  class="btn btn-outline-info btn-sm" 
                  style="width: 20px; padding: 0;" 
                  type="button"
                >−</button>
                <input 
                  type="number" 
                  class="form-control text-center rounded-0" 
                  name="weight_gross[]" 
                  value="${weight_gross}" 
                  step="1" 
                  min="0"
                  style="padding: 0 5px; height: 30px; border-radius:0!important"
                >
                <button 
                  class="btn btn-outline-info btn-sm" 
                  style=" width: 20px; padding: 0;" 
                  type="button"
                >+</button>
              </div>
              
             <div class="input-group input-group-sm" style="height: 30px;">
                <button 
                class="btn btn-outline-info btn-sm"
                  style="padding: 0; width: 20px;"
                >−</button>

                <input
                  type="number"
                  class="form-control text-center rounded-0"
                  name="weight_net[]"
                  min="0"
                  step="1"
                  style="height: 30px; padding: 0 5px; font-size: 10px; border-radius:0!important"
                  value="${weight_net}"
                >

                <button 
                  class="btn btn-outline-info btn-sm increment-kolata" 
                  type="button" 
                  style="padding: 0; width: 20px;"
                >+</button>
                </div>
            </div>
          </td>


          <td style="width: 80px;">
            <div style="display: flex; flex-direction: column; gap: 2px; width: 100%;">
              <div class="input-group input-group-sm" style="width: 100%;">
                <button 
                  class="btn btn-outline-info btn-sm decrement-qty" 
                  style="width: 20px; padding: 0;" 
                  type="button"
                >−</button>
                <input 
                  type="number" 
                  class="form-control text-center rounded-0" 
                  name="quantity[]" 
                  value="${quantity}" 
                  step="1" 
                  min="0"
                  style="padding: 0 5px; height: 37px;border-radius:0!important"
                >
                <button 
                  class="btn btn-outline-info btn-sm increment-qty" 
                  style=" width: 20px; padding: 0; " 
                  type="button"
                >+</button>
              </div>
              
             
            </div>
          </td>
         

  <td style="width: 60px;">
  <input 
    type="text" 
    class="form-control text-start-truncate price-input" 
    name="price[]" 
    value="${formatDecimal(price)}" 
    inputmode="decimal"
    style="width: 100%;" 
  />
</td>


         <td style="width: 70px;">
    <input 
      type="text" 
      class="form-control text-start" 
      name="total[]" 
      value="${total}" disabled
      style="width: 100%;"
    >
    <input 
      type="hidden"
      name="currency[]"
      value="${currency}"
    >
</td>


          <td style="width: 20px; text-align: center;">
              <div style="display: flex; flex-direction: column; align-items: end; gap: 2px;">
                <button type="button" class="btn btn-danger btn-sm remove-row text-center "   style="width: 26px;" title="Ukloni red"  >
                  <i class="fas fa-times"></i>
                </button>
                
  

              </div>
            </td>

        `;
            // 1) Initialize origin Select2 on the new row
            $(row).find('select[name="origin[]"]').select2({
            templateResult: formatFlag,
            templateSelection: formatFlag,
            placeholder: "Select a country",
            width: 'style',
            language: {
                noResults:    () => "Nisu pronađeni rezultati",
                inputTooShort: () => "Unesite još znakova…"
            }
            }).on('select2:open', function() {
  const $search = $('.select2-container--open .select2-search__field');
  if ($search.length) setTimeout(() => $search[0].focus(), 0);
});

            // 2) Global country-change listener
           $(document)
            .off('change','select[name="origin[]"]')
            .on('change','select[name="origin[]"]', function(){
                const code       = $(this).val()?.toUpperCase();
                const allowedCode= allowedCountries[code];
                const $row       = $(this).closest('tr');
                const $cb        = $row.find('.tariff-privilege-toggle');
                const $lock      = $row.find('.lock-icon');
                const $hidden    = $row.find('input[name="tariff_privilege[]"]');
                
                if (!allowedCode) {
                // ← country *not* allowed
                $cb
                    .prop('checked', false)
                    .prop('disabled', true)
                    .hide();
                $lock.show();
                $hidden.val(0);
                } else {
                // ← country *allowed*
                $lock.hide();
                $cb
                    .prop('disabled', false)
                    .show();
                if ($cb.is(':checked')) {
                    $hidden.val(allowedCode);
                }
                }

                // update tooltip text based on checkbox state
                const tipText = $cb.is(':checked')
                ? $hidden.val()                  // show code when checked
                : 'Odaberi povlasticu';         // fallback

                const inst = bootstrap.Tooltip.getInstance($cb[0]);
                if (inst) {
                inst.setContent({ '.tooltip-inner': tipText });
                } else {
                $cb
                    .attr('data-bs-original-title', tipText)
                    .removeAttr('title');
                new bootstrap.Tooltip($cb[0]);
                }
            });

            // 3) Trigger once on row-creation to set the initial state & tooltip
            setTimeout(() => {
            $(row).find('select[name="origin[]"]').trigger('change');
            }, 0);


        
            function formatFlag(state) {
                if (!state.id) return state.text;
                const flagUrl = $(state.element).data('flag');
                return $(`<span class="flag-option"><img src="${flagUrl}" width="20"  /> ${state.text}</span>`);
            }

            tbody.appendChild(row);
            const cb = row.querySelector('.tariff-privilege-toggle');
cb.removeAttribute('title');                 // ensure no native tooltip
bootstrap.Tooltip.getInstance(cb)?.dispose(); 
new bootstrap.Tooltip(cb); 
            // ✅ Re-init all tooltips inside the new row
$(row).find('[data-bs-toggle="tooltip"]').each(function () {
  new bootstrap.Tooltip(this);
});

            
            initializeTariffSelects();
            updateProcjenaEstimates(); 

            updateTotalAmount();
        }
        $(document).on('click', '.increment-qty', function() {
            const input = $(this).siblings('input[name="quantity[]"]');
            input.val(parseInt(input.val() || 0) + 1).trigger('input');
            updateTotalAmount();
        });

        $(document).on('click', '.decrement-qty', function() {
            const input = $(this).siblings('input[name="quantity[]"]');
            const current = parseInt(input.val() || 0);
            if (current > 0) {
                input.val(current - 1).trigger('input');
                updateTotalAmount();
            }
        });
       $(document).on('input', 'input[name="price[]"], input[name="quantity[]"]', function () {
    const row = $(this).closest('tr');

    // Get raw input strings
    let priceRaw = row.find('input[name="price[]"]').val() || "0";
    let quantityRaw = row.find('input[name="quantity[]"]').val() || "0";

    // Normalize price by replacing comma with dot
    const price = parseFloat(priceRaw.replace(',', '.')) || 0;
    const quantity = parseInt(quantityRaw, 10) || 0;

    // Calculate and format total
    const total = formatDecimal(price * quantity, 2);

    // Set formatted total with comma
    row.find('input[name="total[]"]').val(total);

    // Update koleta and global total
    updateProcjenaEstimates();
    updateTotalAmount();
});




        document.addEventListener('click', (event) => {
            // Handle decrement button click
            if (event.target.closest('.decrement-kolata')) {
                const container = event.target.closest('div'); // or closest input group wrapper
                const input = container.querySelector('input[name="kolata[]"]');
                if (input) {
                    let currentValue = parseInt(input.value) || 0;
                    if (currentValue > 0) {
                        input.value = currentValue - 1;
                        input.dispatchEvent(new Event('change')); // if you listen for changes
                    }
                }
            }

            // Handle increment button click
            if (event.target.closest('.increment-kolata')) {
                const container = event.target.closest('div'); // or closest input group wrapper
                const input = container.querySelector('input[name="kolata[]"]');
                if (input) {
                    let currentValue = parseInt(input.value) || 0;
                    input.value = currentValue + 1;
                    input.dispatchEvent(new Event('change'));
                }
            }

        

            // Add a single click listener outside to hide tooltips on outside click
            document.addEventListener('click', function(e) {
                tooltipTriggerList.forEach(function(el) {
                    var tooltip = bootstrap.Tooltip.getInstance(el);
                    if (tooltip && e.target !== el && !el.contains(e.target)) {
                        tooltip.hide();
                    }
                });
            });



        });


        async function fillInvoiceData() {
            const invoice = await getInvoice();
            waitForEl("#invoice-id1", el => el.textContent = invoice.id || "—");
           waitForEl("#invoice-date-text", el => {
    const rawDate = invoice.date_of_issue ? new Date(invoice.date_of_issue) : new Date();
    el.textContent = rawDate.toLocaleDateString('hr'); // e.g. "17. 6. 2025."
    if (invoice.incoterm_destination) {
    const destinationInput = document.getElementById("incoterm-destination");
    if (destinationInput) {
        destinationInput.value = invoice.incoterm_destination;
    }
}

});


            waitForEl("#pregled", el => {
                el.addEventListener("click", () => {
                    window.location.href = `/detalji-deklaracije/${invoice.id}`;
                });
            });

          




            const items = invoice.items || [];
            console.log(" Invoice items:", items);

            items.forEach((item, index) => {
                const matches = item.best_customs_code_matches || [];

                const bestMatch = matches.reduce((best, current) => {
                    return !best || current.closeness < best.closeness ? current : best;
                }, null);

                const bestTariffCode = bestMatch?.entry?.["Tarifna oznaka"] || "";

                const suggestions = matches.map(code => ({
                    entry: {
                        "Tarifna oznaka": code.entry?.["Tarifna oznaka"],
                        "Naziv": code.entry?.["Naziv"]
                    },
                    closeness: code.closeness
                }));

                addRowToInvoice({
                    ...item,
                    tariff_code: bestTariffCode
                }, suggestions);
            });
            if (invoice.incoterm) {
                // Extract only the code (first word) for the select
                const incotermCode = invoice.incoterm.split(' ')[0];
                document.getElementById("incoterm").value = incotermCode;
            }
            if (invoice.invoice_number) {
                const cleaned = invoice.invoice_number.replaceAll("/", "");
                document.getElementById("invoice-no").value = cleaned;
            }

            await updateRemainingScans();




        }


        async function fetchAndPrefillParties() {
            const taskId = window.global_invoice_id;
            if (!taskId || !token) return;

            try {
                const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });

                const data = await res.json();
                if (!res.ok) throw new Error("Greška u AI response");

                const {
                    supplier,
                    importer,
                    supplier_id,
                    importer_id
                } = data;
                // Get invoice data for IDs
                const invoice = await getInvoice();

                // --- SUPPLIER LOGIC ---
                let supplierId = invoice.supplier_id || supplier_id;
                if (window.forceNewSupplier) {
                    // Always remove any previous 'Novi klijent' option
                    $("#supplier-select2 option[value='new']").remove();
                    // Add and select 'Novi klijent'
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    if (supplier) {
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    } else {
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false);
                    }
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                    window.forceNewSupplier = false;
                } else if (supplierId) {
                    // Ensure the option exists before setting value
                    if ($(`#supplier-select2 option[value='${supplierId}']`).length === 0) {
                        const text = supplier?.name ? `${supplier.name} – ${supplier.address || ''}` : supplierId;
                        const newOption = new Option(text, supplierId, true, true);
                        $("#supplier-select2").append(newOption);
                    }
                    console.log("[SUPPLIER] Prefilling from ID:", supplierId, invoice.supplier_id ? 'invoice.supplier_id' : 'parties.supplier_id');
                    $("#supplier-select2").val(supplierId).trigger("change");
                    $.ajax({
                        url: `/api/suppliers/${supplierId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        },
                        success: function(dbSupplier) {
                            console.log("[SUPPLIER] Prefilling textboxes from DB for ID:", supplierId, dbSupplier);
                            $("#billing-name").val(dbSupplier.name || "").prop('readonly', true);
                            $("#billing-address-line-1").val(dbSupplier.address || "").prop('readonly', true);
                            $("#billing-phone-no").val(dbSupplier.contact_phone || "").prop('readonly', true);
                            $("#billing-tax-no").val(dbSupplier.tax_id || "").prop('readonly', true);
                            $("#email").val(dbSupplier.contact_email || "").prop('readonly', true);
                            $("#supplier-owner").val(dbSupplier.owner || "").prop('readonly', true);
                            const label = document.getElementById("billing-name-ai-label");
                            if (label) label.classList.add("d-none");
                        },
                        error: function() {
                            if (supplier) {
                                // Not found in DB, add 'Novi klijent' to select2
                                const newOption = new Option('Novi klijent', 'new', true, true);
                                $("#supplier-select2").append(newOption).trigger('change');
                                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                                $("#email").val(supplier.email || "").prop('readonly', false);
                                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                                const label = document.getElementById("billing-name-ai-label");
                                if (label) label.classList.remove("d-none");
                            }
                        }
                    });
                } else if (supplier) {
                    // Not found in DB, add 'Novi klijent' to select2
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    $("#billing-name").val(supplier.name || "").prop('readonly', false);
                    $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                    $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                    $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                    $("#email").val(supplier.email || "").prop('readonly', false);
                    $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                }

                // --- IMPORTER LOGIC ---
                let importerId = invoice.importer_id || importer_id;
                if (window.forceNewImporter) {
                    $("#importer-select2 option[value='new']").remove();
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#importer-select2").append(newOption).trigger('change');
                    if (importer) {
                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                    } else {
                        $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false);
                    }
                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.remove("d-none");
                    window.forceNewImporter = false;
                } else if (importerId) {
                    // Ensure the option exists before setting value
                    if ($(`#importer-select2 option[value='${importerId}']`).length === 0) {
                        const text = importer?.name ? `${importer.name} – ${importer.address || ''}` : importerId;
                        const newOption = new Option(text, importerId, true, true);
                        $("#importer-select2").append(newOption);
                    }
                    console.log("[IMPORTER] Prefilling from ID:", importerId, invoice.importer_id ? 'invoice.importer_id' : 'parties.importer_id');
                    $("#importer-select2").val(importerId).trigger("change");
                    $.ajax({
                        url: `/api/importers/${importerId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        },
                        success: function(dbImporter) {
                            console.log("[IMPORTER] Prefilling textboxes from DB for ID:", importerId, dbImporter);
                            $("#carrier-name").val(dbImporter.name || "").prop('readonly', true);
                            $("#carrier-address").val(dbImporter.address || "").prop('readonly', true);
                            $("#carrier-tel").val(dbImporter.contact_phone || "").prop('readonly', true);
                            $("#carrier-tax").val(dbImporter.tax_id || "").prop('readonly', true);
                            $("#carrier-email").val(dbImporter.contact_email || "").prop('readonly', true);
                            $("#carrier-owner").val(dbImporter.owner || "").prop('readonly', true);
                            const label = document.getElementById("carrier-name-ai-label");
                            if (label) label.classList.add("d-none");
                        },
                        error: function() {
                            if (importer) {
                                // Not found in DB, add 'Novi klijent' to select2
                                const newOption = new Option('Novi klijent', 'new', true, true);
                                $("#importer-select2").append(newOption).trigger('change');
                                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                                const label = document.getElementById("carrier-name-ai-label");
                                if (label) label.classList.remove("d-none");
                            }
                        }
                    });
                } else if (importer) {
                    // Always remove any previous 'Novi klijent' option
                    $("#importer-select2 option[value='new']").remove();
                    // Add and select 'Novi klijent'
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#importer-select2").append(newOption).trigger('change');
                    $("#carrier-name").val(importer.name || "").prop('readonly', false);
                    $("#carrier-address").val(importer.address || "").prop('readonly', false);
                    $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                    $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                    $("#carrier-email").val(importer.email || "").prop('readonly', false);
                    $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.remove("d-none");
                }

            } catch (err) {
                console.error("Greška u fetchAndPrefillParties:", err);
                showRetryError(
                    "Kredit za skeniranje nije iskorišten",
                    err.message || "Neuspješno dohvaćanje podataka",
                    () => fetchAndPrefillParties()
                );


            }
            


        }

        async function fetchAndPrefillSupplierOnly() {
            const taskId = window.global_invoice_id;
            if (!taskId || !token) return;

            try {
                const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });

                const data = await res.json();
                if (!res.ok) throw new Error("Greška u AI response");

                const {
                    supplier,
                    supplier_id
                } = data;
                const invoice = await getInvoice();

                let supplierId = invoice.supplier_id || supplier_id;
                if (window.forceNewSupplier) {
                    $("#supplier-select2 option[value='new']").remove();
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    if (supplier) {
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    } else {
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false);
                    }
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                    window.forceNewSupplier = false;

                } else if (supplierId) {
                    if ($(`#supplier-select2 option[value='${supplierId}']`).length === 0) {
                        const text = supplier?.name ? `${supplier.name} – ${supplier.address || ''}` : supplierId;
                        const newOption = new Option(text, supplierId, true, true);
                        $("#supplier-select2").append(newOption);
                    }
                    console.log("[SUPPLIER] Prefilling from ID:", supplierId);
                    $("#supplier-select2").val(supplierId).trigger("change");

                    $.ajax({
                        url: `/api/suppliers/${supplierId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        },
                        success: function(dbSupplier) {
                            console.log("[SUPPLIER] Prefilling from DB:", dbSupplier);
                            $("#billing-name").val(dbSupplier.name || "").prop('readonly', true);
                            $("#billing-address-line-1").val(dbSupplier.address || "").prop('readonly', true);
                            $("#billing-phone-no").val(dbSupplier.contact_phone || "").prop('readonly', true);
                            $("#billing-tax-no").val(dbSupplier.tax_id || "").prop('readonly', true);
                            $("#email").val(dbSupplier.contact_email || "").prop('readonly', true);
                            $("#supplier-owner").val(dbSupplier.owner || "").prop('readonly', true);
                            const label = document.getElementById("billing-name-ai-label");
                            if (label) label.classList.add("d-none");
                        },
                        error: function(xhr) {
                            console.warn("Supplier not found in DB. Status:", xhr.status);

                            Swal.fire({
                                title: "Klijent nije pronađen",
                                text: "Podaci za klijenta nisu pronađeni u bazi. Unos će biti omogućen ručno.",
                                icon: "info",
                                confirmButtonText: "U redu",
                                confirmButtonColor: "#299dcb"
                            });

                            if (supplier) {
                                const newOption = new Option('Novi klijent', 'new', true, true);
                                $("#supplier-select2").append(newOption).trigger('change');
                                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                                $("#email").val(supplier.email || "").prop('readonly', false);
                                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                                const label = document.getElementById("billing-name-ai-label");
                                if (label) label.classList.remove("d-none");
                            }
                        }

                    });

                } else if (supplier) {
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    $("#billing-name").val(supplier.name || "").prop('readonly', false);
                    $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                    $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                    $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                    $("#email").val(supplier.email || "").prop('readonly', false);
                    $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                }

            } catch (err) {
                console.error("Greška u fetchAndPrefillSupplierOnly:", err);
                showRetryError(
                    "Greška pri dohvaćanju klijenta",
                    err.message || "Neuspješno dohvaćanje",
                    () => fetchAndPrefillSupplierOnly()
                );
            }
        }

        async function fetchAndPrefillImporterOnly() {
    const taskId = window.global_invoice_id;
    if (!taskId || !token) return;

    try {
        console.log("[IMPORTER] Starting fetchAndPrefillImporterOnly... Task ID:", taskId);

        const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });

        const data = await res.json();
        console.log("[IMPORTER] API response:", data);

        if (!res.ok) throw new Error("Greška u AI response");

        const { importer, importer_id } = data;
        const invoice = await getInvoice();
        console.log("[IMPORTER] Existing invoice data:", invoice);

        let importerId = invoice.importer_id || importer_id;
        console.log("[IMPORTER] Final importerId to use:", importerId);

        if (window.forceNewImporter) {
            console.log("[IMPORTER] Forcing new importer...");
            $("#importerr-select2 option[value='new']").remove();

            const labelText = importer?.name ? `${importer.name} – ${importer.address || ''}` : 'Novi klijent';
            const newOption = new Option(labelText, String(importerId), true, true);
            $("#importer-select2").append(newOption);
            $("#importer-select2").val(String(importerId)).trigger("change");

            console.log("[IMPORTER] Added 'new' option and triggered change");

            if (importer) {
                console.log("[IMPORTER] Prefilling fields with AI importer data");
                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
            } else {
                console.warn("[IMPORTER] No importer data provided, clearing fields");
                $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false);
            }

            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");

            window.forceNewImporter = false;

        } else if (importerId) {
            const stringId = String(importerId);
            console.log("[IMPORTER] importerId exists:", stringId);

            if ($(`#importer-select2 option[value='${stringId}']`).length === 0) {
                const text = importer?.name ? `${importer.name} – ${importer.address || ''}` : stringId;
                console.log("[IMPORTER] Option not found. Adding manually:", text);
                const newOption = new Option(text, stringId, true, true);
                $("#importer-select2").append(newOption);
            } else {
                console.log("[IMPORTER] Option already exists in select2:", stringId);
            }

            console.log("[IMPORTER] Setting carrier-select2 to:", stringId);
            $("#importer-select2").val(stringId).trigger("change");

            $.ajax({
                url: `/api/importers/${stringId}`,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                },
                success: function (dbImporter) {
                    console.log("[IMPORTER] DB importer found:", dbImporter);

                    $("#carrier-name").val(dbImporter.name || "").prop('readonly', true);
                    $("#carrier-address").val(dbImporter.address || "").prop('readonly', true);
                    $("#carrier-tel").val(dbImporter.contact_phone || "").prop('readonly', true);
                    $("#carrier-tax").val(dbImporter.tax_id || "").prop('readonly', true);
                    $("#carrier-email").val(dbImporter.contact_email || "").prop('readonly', true);
                    $("#carrier-owner").val(dbImporter.owner || "").prop('readonly', true);

                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.add("d-none");
                },
                error: function (xhr) {
                    console.warn("Importer not found in DB. Status:", xhr.status);

                    Swal.fire({
                        title: "Uvoznik nije pronađen",
                        text: "Podaci za uvoznika nisu pronađeni u bazi. Unos će biti omogućen ručno.",
                        icon: "info",
                        confirmButtonText: "U redu",
                        confirmButtonColor: "#299dcb"
                    });

                    if (importer) {
                        console.log("[IMPORTER] Falling back to AI importer data");
                        const newOption = new Option('Novi klijent', 'new', true, true);
                        $("#importer-select2").append(newOption).trigger('change');

                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);

                        const label = document.getElementById("carrier-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    }
                }
            });

        } else if (importer) {
            console.log("[IMPORTER] No ID but importer data available. Treating as new.");
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#importer-select2").append(newOption).trigger('change');

            $("#carrier-name").val(importer.name || "").prop('readonly', false);
            $("#carrier-address").val(importer.address || "").prop('readonly', false);
            $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
            $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
            $("#carrier-email").val(importer.email || "").prop('readonly', false);
            $("#carrier-owner").val(importer.owner || "").prop('readonly', false);

            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");
        }

    } catch (err) {
        console.error("Greška u fetchAndPrefillImporterOnly:", err);
        showRetryError(
            "Greška pri dohvaćanju uvoznika",
            err.message || "Neuspješno dohvaćanje",
            () => fetchAndPrefillImporterOnly()
        );
    }
}




        document.addEventListener("DOMContentLoaded", async () => {

            window.skipPrefillParties = false; // Always allow prefill on page load/scan
            console.log(" Page loaded. Starting init process...");

            // Parallelize tariff and invoice fetch for faster load
            const [tariffRes, invoice] = await Promise.all([
                fetch("{{ URL::asset('build/json/tariff.json') }}"),
                getInvoice()
            ]);
            const tariffData = await tariffRes.json();
            console.log(" Tariff data loaded:", tariffData);

            processedTariffData = tariffData
                .filter(item => item["Tarifna oznaka"] && item["Naziv"] && item["Puni Naziv"])
                .map(item => ({
                    id: item["Tarifna oznaka"],
                    text: item["Puni Naziv"].split(">>>").pop().trim(),
                    display: `${item["Tarifna oznaka"]} – ${item["Naziv"]}`,
                    depth: item["Puni Naziv"].split(">>>").length - 1,
                    isLeaf: item["Tarifna oznaka"].replace(/\s/g, '').length === 10,
                    search: [item["Naziv"], item["Puni Naziv"], item["Tarifna oznaka"]].join(" ").toLowerCase()
                }));
            console.log(" Processed tariff data:", processedTariffData);

            // Only show loader and run scan if needed
            let scanNeeded = false;
            if (invoice.task_id == null) scanNeeded = true;
            else if (!invoice.items?.length) scanNeeded = true;

            if (scanNeeded) {
              

                let scanStarted = true;

                // Start scan only if task_id is null
                if (invoice.task_id == null) {
                    scanStarted = await startAiScan();
                }

                // Only continue if scan actually started or items are already being processed
                if (!scanStarted) {
                    // Stop further steps like waitForAIResult/fetchParties
                    return;
                }

                if (!invoice.items?.length) {
                    await waitForAIResult();
                }


                Swal.close();
            }


            _invoice_data = null;

            await fillInvoiceData();
            if (!window.skipPrefillParties) {
                await fetchAndPrefillParties();
            }
            window.skipPrefillParties = false; // reset after use
            $("#supplier-select2").select2({
                placeholder: "Pretraži dobavljača",
                allowClear: true,
                ajax: {
                    url: "/api/suppliers",
                    dataType: "json",
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    },
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data.data.map(s => ({
                            id: s.id,
                            text: `${s.name} – ${s.address}`,
                            full: s
                        }))
                    }),
                    minimumInputLength: 1, // ⬅️ ovo SPRJEČAVA da išta bude prikazano dok ne krene search
tags: false,           // ⬅️ ovo SPRJEČAVA "ručni unos" koji ti ionako ne koristi
allowClear: true,      // ⬅️ po želji – omogućava 'x' za brisanje izbora
placeholder: "Pretraži...", // bolji UX
                    cache: true
                },
                tags: true,
                allowClear: false
            });

            $('#supplier-select2').on('select2:select', function(e) {
                const data = e.params.data.full;
                if (data) {
                    $('#billing-name').val(data.name || "");
                    $('#billing-address-line-1').val(data.address || "");
                    $('#billing-phone-no').val(data.contact_phone || "");
                    $('#billing-tax-no').val(data.tax_id || "");
                    $('#email').val(data.contact_email || "");
                    $('#supplier-owner').val(data.owner || ""); // Fill owner field
                }
            });

            $("#importer-select2").select2({
                placeholder: "Pretraži klijenta",
                allowClear: true,
                ajax: {
                    url: "/api/importers",
                    dataType: "json",
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    },
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data.data.map(s => ({
                            id: s.id,
                            text: `${s.name} – ${s.address}`,
                            full: s
                        }))
                    }),
                    cache: true
                },
                tags: true,
                allowClear: false
            });

            $('#importer-select2').on('select2:select', function(e) {
                const data = e.params.data.full;
                if (data) {

                    $('#carrier-address').val(data.address || "");
                    $('#carrier-name').val(data.name || "");
                    $('#carrier-phone').val(data.contact_phone || "");
                    $('#carrier-tax').val(data.tax_id || "");
                    $('#carrier-email').val(data.contact_email || "");
                    $('#carrier-owner').val(data.owner || ""); // Fill owner field
                }
            });


             // Handle AI suggestions button click
$(document).on('click', '.show-ai-btn', function() {
  console.log("AI button clicked");

  // 1) find the select2 and parse its suggestions
  const $select = $(this).closest('td').find('select.select2-tariff');
  let raw = $select.data('suggestions');
  if (!raw) {
    console.warn("No suggestions data on select");
    return;
  }
  try {
    if (typeof raw === 'string') raw = JSON.parse(raw);
  } catch (e) {
    console.error("Bad JSON in suggestions:", e);
    return;
  }
  if (!Array.isArray(raw)) {
    console.warn("Suggestions not an array:", raw);
    return;
  }

  // 2) sort by closeness, take top 10
  const sorted = raw
    .slice()
    .sort((a, b) => a.closeness - b.closeness)
    .slice(0, 10);

  // 3) render into the modal body
  const $body = $('#aiSuggestionsBody');
  if (sorted.length === 0) {
    $body.html('<div class="text-muted">Nema prijedloga.</div>');
  } else {
    const html = sorted.map((s, i) => {
      const isLast = i === sorted.length - 1;
      const code      = s.entry["Tarifna oznaka"];
      const childName = s.entry["Naziv"];

      // split out the parent code (first segment)
      const parts = code.split(' ');
      let nameLabel;

      if (parts.length > 1) {
        const parentCode = parts[0];
        // look up the parent in your master list
        const parentItem = processedTariffData.find(item => item.id === parentCode);
        const parentName = parentItem
          ? (parentItem.Naziv || parentItem.text || parentItem.label)
          : parentCode;

        // format: Parent – Child (with child in bold)
        nameLabel = `${parentName} – <strong>${childName}</strong>`;
      } else {
        // no parent segment: just bold the whole name
        nameLabel = `<strong>${childName}</strong>`;
      }

      return `
        <div${!isLast ? ' class="mb-3"' : ''}>
          <div>
            <strong>${i+1}. Tarifna oznaka:</strong> ${code}
          </div>
          <div>
            <strong>Naziv:</strong> ${nameLabel}
          </div>
          <button type="button"
                  class="btn btn-sm btn-info mt-1 use-tariff"
                  data-value="${code}">
            Koristi ovu oznaku
          </button>
          ${!isLast ? '<hr>' : ''}
        </div>
      `;
    }).join('');

    $body.html(html);
  }

  // 4) stash the select2 field for the “use-tariff” handler
  $('#aiSuggestionModal').data('target-select', $select);

  // 5) show the Bootstrap modal
  const modalEl = document.getElementById('aiSuggestionModal');
  let modal = bootstrap.Modal.getInstance(modalEl)
           || new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: true });
  modal.show();
});


            $(document).on('click', '.use-tariff', function() {
                const code = $(this).data('value');
                console.log(" User selected code:", code);

                const select = $('#aiSuggestionModal').data('target-select');
                console.log(" Target select:", select);

                if (select && code) {
                    const matched = processedTariffData.find(item => item.id === code);
                    console.log(" Matched tariff code:", matched);

                    if (matched) {
                        const option = new Option(matched.id, matched.id, true, true);
                        select.find('option').remove();
                        select.append(option).trigger('change');
                        console.log(" Code applied to select2 field.");
                    } else {
                        console.warn(" No match found in processedTariffData.");
                    }
                } else {
                    console.warn(" Select or code not defined properly.");
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById("aiSuggestionModal"));
                if (modal) {
                    modal.hide();
                    console.log(" Modal closed.");
                } else {
                    console.warn(" No modal instance to close.");
                }
            });

            document.addEventListener("click", function(e) {
                if (e.target.closest(".remove-row")) {
                    const button = e.target.closest(".remove-row");
                    const row = button.closest("tr");

                    Swal.fire({
                        title: "Oprez!",
                        text: "Odabrani proizvod će biti trajno uklonjen sa popisa trenutne deklaracije. Nakon akcije, deklaraciju morate spasiti!",
                        icon: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Odustani",
                        confirmButtonText: "Da, ukloni",
                        customClass: {
                            confirmButton: "btn btn-soft-info me-2",
                            cancelButton: "btn btn-info"
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed && row) {
                            row.remove();
                            updateTotalAmount();
                        }
                    });
                }
            });


            function formatDateToDDMMYYYY(dateString) {
                if (!dateString) return '';
                if (typeof dateString === 'string') {
                    const [year, month, day] = dateString.split('-');
                    return `${day}.${month}.${year}`;
                } else if (dateString instanceof Date) {
                    const d = dateString;
                    const day = String(d.getDate()).padStart(2, '0');
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const year = d.getFullYear();
                    return `${day}.${month}.${year}`;
                }
                return '';
            }

            flatpickr("#invoice-date", {
                locale: "bs",
                dateFormat: "d.m.Y"
            });





            const invNo = getInvoiceId();
            if (invNo) document.getElementById("invoice-no1").value = invNo;

          const invoiceDateInput = document.getElementById("invoice-date");
if (invoiceDateInput) {
    invoiceDateInput.value = formatDateToDDMMYYYY(invoice.date_of_issue || new Date());
}
            

            console.log(" Invoice date and number set.");




         // Prefill total weights and package count
setField("#total-weight-net", invoice.total_weight_net ?? "");
setField("#total-weight-gross", invoice.total_weight_gross ?? "");
setField("#total-num-packages", invoice.total_num_packages ?? "");

console.log("Weights and package count set:",
    invoice.total_weight_net,
    invoice.total_weight_gross,
    invoice.total_weight_gross,
    invoice.total_num_packages
);

// Prefill q1-estimate (total_value / total_num_packages)
const totalValue = parseFloat(document.getElementById("total-amount")?.value) || 0;
const numPackages = parseFloat(invoice.total_num_packages ?? 0);
const q1Input = document.getElementById("q1-estimate");

if (q1Input) {
    if (numPackages > 0 && !isNaN(totalValue)) {
        const rawAmount = totalValue.toString().replace(/[^\d.-]/g, ""); // Remove currency symbols
        const amount = parseFloat(rawAmount) || 0;
        q1Input.value = (numPackages / amount ).toFixed(6);
    } else {
        q1Input.value = "";
    }
}
updateProcjenaEstimates(); // ✅ prisilno izračunaj odmah nakon postavljanja q1


            

            // Hide AI label when user types in importer name
            document.getElementById("carrier-name")?.addEventListener("input", () => {
                const label = document.getElementById("carrier-name-ai-label");
                if (label) label.classList.add("d-none");
            });
            document.getElementById('q1-estimate')?.addEventListener('input', updateProcjenaEstimates);

            document.addEventListener('input', function (e) {
    if (e.target?.name === "kolata[]") {
        updateProcjenaEstimates();
    }
});



        });


        // Add buttons above supplier and importer fields
        $(document).ready(function() {

            // Handler for new supplier
            $(document).on('click', '#add-new-supplier', function() {
                if (window.isResetConfirmed) return;

                Swal.fire({
                    title: 'Oprez!',
                    text: 'Ova radnja će izbrisati sve podatke za dobavljača i omogućiti ponovno automatsko popunjavanje.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: "btn btn-soft-info me-2",
                        cancelButton: "btn btn-info"
                    },

                    focusCancel: true,

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set flag to trigger refetch
                        window.forceNewSupplier = false;
                        window.skipPrefillParties = false;

                        // Očistimo polja i učitamo ponovo iz baze
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner")
                            .val("")
                            .prop('readonly', true)
                            .removeClass("is-invalid");

                        $("#supplier-select2").empty();

                        // Pokrećemo ponovno popunjavanje samo za supplier
                        fetchAndPrefillSupplierOnly();
                    }
                });
            });





            // Handler for new importer
            $(document).on('click', '#add-new-importer', function() {
                if (window.isResetConfirmed) return;

                Swal.fire({
                    title: 'Oprez!',
                    text: 'Ova radnja će izbrisati sve podatke za klijenta i omogućiti ponovno automatsko popunjavanje.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: "btn btn-soft-info me-2",
                        cancelButton: "btn btn-info"
                    },

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.forceNewImporter = false;
                        window.skipPrefillParties = false;

                        // Očisti inpute i pripremi Select2
                        $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner")
                            .val("")
                            .prop('readonly', true)
                            .removeClass("is-invalid");

                        $("#importer-select2").empty();

                        // Ponovno preuzimanje samo importer podataka
                        fetchAndPrefillImporterOnly();
                    }
                });
            });
        });




        // Add SweetAlert confirmation for importer manual entry

        // 1. Add buttons in the DOM (jQuery, after DOMContentLoaded)
        $(document).ready(function() {
            // Add 'Popuni ponovo s AI' button next to 'Obriši' for supplier


            // Handler for supplier AI refill
            $(document).on('click', '#refill-supplier-ai', async function() {
                const taskId = window.global_invoice_id;
                if (!taskId || !window.token) return;
                try {
                    const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        }
                    });

                    const data = await res.json();
                    if (!res.ok) throw new Error("Greška u AI response");
                    const supplier = data.supplier;
                    if (supplier) {
                        // Set select2 to 'Novi klijent'
                        $("#supplier-select2 option[value='new']").remove();
                        var newOption = new Option('Novi klijent', 'new', true, true);
                        $("#supplier-select2").append(newOption).val('new').trigger('change');
                        // Fill fields
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                        const label = document.getElementById("billing-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    } else {
                        Swal.fire("Greška", "Nema AI podataka za dobavljača", "error");
                    }
                } catch (err) {
                    Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
                }
            });

            // Handler for importer AI refill
            $(document).on('click', '#refill-importer-ai', async function() {
                const taskId = window.global_invoice_id;
                if (!taskId || !window.token) return;
                try {
                    const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        }
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error("Greška u AI response");
                    const importer = data.importer;
                    if (importer) {
                        // Set select2 to 'Novi dobavljač'
                        $("#importer-select2 option[value='new']").remove();
                        var newOption = new Option('Novi klijent', 'new', true, true);
                        $("#importer-select2").append(newOption).val('new').trigger('change');
                        // Fill fields
                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                        const label = document.getElementById("carrier-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    } else {
                        Swal.fire("Greška", "Nema AI podataka za klijenta", "error");
                    }
                } catch (err) {
                    Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
                }
            });
        });




    }
</script>

<!-- edit fill -->

<script>
    $.fn.select2.defaults.set("language", {
  searching: function () {
    return "Pretraga...";
  },
  noResults: function () {
    return "Nema rezultata";
  },
  inputTooShort: function (args) {
    return `Unesite još ${args.minimum - args.input.length} znakova`;
  },
  loadingMore: function () {
    return "Učitavanje još rezultata...";
  },
});

    let processedTariffData = [];
    const tariffJsonPromise = fetch("{{ URL::asset('build/json/tariff.json') }}").then(res => res.json());

    function waitForEl(selector, callback) {
        const el = document.querySelector(selector);
        if (el) return callback(el);

        const observer = new MutationObserver(() => {
            const el = document.querySelector(selector);
            if (el) {
                observer.disconnect();
                callback(el);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }


   


    document.addEventListener("click", function(e) {
        const btn = e.target.closest("button");
        if (!btn) return;

        const group = btn.closest(".input-group");
        const input = group?.querySelector("input");

        if (!input) return;

        const isMinus = btn.textContent.trim() === "−";
        const isPlus = btn.textContent.trim() === "+";

        if (isMinus || isPlus) {
            const val = parseInt(input.value) || 0;
            const min = parseInt(input.min) || 0;
            input.value = isMinus ? Math.max(min, val - 1) : val + 1;
            updateTotalAmount();
        }
    });


    document.addEventListener("click", function(e) {
        const button = e.target.closest(".remove-row");
        if (!button) return;

        const row = button.closest("tr");

        // Short delay to let the browser fully handle prior UI rendering
        setTimeout(() => {
            Swal.fire({
                title: "Oprez!",
                text: "Odabrani proizvod će biti trajno uklonjen sa popisa trenutne deklaracije. Nakon akcije, deklaraciju morate spasiti!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Odustani",
                confirmButtonText: "Da, ukloni",
                customClass: {
                    confirmButton: "btn btn-soft-info me-2",
                    cancelButton: "btn btn-info"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed && row) {
                    row.remove();
                    updateTotalAmount();
                }
            });
        }, 10);
    });




    document.addEventListener("DOMContentLoaded", async () => {

      if (!invoiceId) return console.log("No ID in URL — skipping load-invoice script.");
const scanId = window.global_invoice_id;
if (scanId && scanId !== invoiceId) {
    console.warn(`Clearing scan_invoice_id (${scanId}) because it does not match invoiceId (${invoiceId})`);
    localStorage.removeItem("scan_invoice_id");
}

Swal.fire({
    title: 'Učitavanje deklaracije...',
    icon: null,
    html: `
        <div class="custom-swal-spinner mb-3"></div>
        <div id="swal-status-message">Molimo sačekajte</div>
    `,
    showConfirmButton: false,
    allowOutsideClick: false,
    didOpen: () => {
        const spinner = document.querySelector(".custom-swal-spinner");
        const icon = Swal.getHtmlContainer()?.previousElementSibling;
        if (icon?.classList.contains('swal2-icon')) {
            icon.remove();
        }

        // ➕ Delay 3 seconds before continuing
        setTimeout(() => {
            // Place your next action here, e.g. fetch invoice or close Swal
            console.log("✅ Ready after 3 seconds");

            // Swal.close(); // or any follow-up logic
        }, 4000);
    }
});



        try {
            const [tariffJson, invoiceRes, suppliersRes, importersRes] = await Promise.all([
                tariffJsonPromise,
                fetch(`/api/invoices/${invoiceId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                }),
                fetch("/api/suppliers", {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                }),
                fetch("/api/importers", {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
            ]);

            const invoice = await invoiceRes.json();
            waitForEl("#pregled", (el) => {
                el.addEventListener("click", () => {
                    window.location.href = `/detalji-deklaracije/${invoice.id}`;
                });
            });

            const suppliersJson = await suppliersRes.json();
            const importersJson = await importersRes.json();

            //  CORRECT – this updates the global variable
            processedTariffData = tariffJson
                .filter(item => item["Tarifna oznaka"])
                .map(item => ({
                    id: item["Tarifna oznaka"],
                    text: `${item["Tarifna oznaka"]} – "${item["Naziv"]}"`,
                    display: `${item["Tarifna oznaka"]} – "${item["Naziv"]}"`,
                    search: `${item["Tarifna oznaka"]} ${item["Naziv"]}`.toLowerCase(),
                    isLeaf: item["leaf"] ?? true,
                    depth: item["depth"] ?? 0
                }));


            const supplierOptions = suppliersJson.data.map(s => ({
                id: s.id,
                text: `${s.name} – ${s.address}`,
                full: s
            }));

            const importerOptions = importersJson.data.map(i => ({
                id: i.id,
                text: `${i.name} – ${i.address}`,
                full: i
            }));

            // --- Supplier Select2
            $('#supplier-select2').select2({
                placeholder: "Pretraži dobavljača",
                width: '100%',
                data: supplierOptions
            });
            $('#supplier-select2').on('change', async function() {
                const supplierId = $(this).val();
                if (!supplierId) return;
                try {
                    const res = await fetch(`/api/suppliers/${supplierId}`, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                    const s = await res.json();
                    setField("#billing-name", s.name);
                    setField("#billing-address-line-1", s.address);
                    setField("#billing-phone-no", s.contact_phone);
                    setField("#billing-tax-no", s.tax_id);
                    setField("#email", s.contact_email);
                    setField("#supplier-owner", s.owner);
                } catch (err) {
                    console.warn("Failed to load supplier:", err);
                }
            });


            // --- Importer Select2
            $('#importer-select2').select2({
                placeholder: "Pretraži klijenta",
                width: '100%',
                data: importerOptions
            });
            $('#importer-select2').on('change', async function() {
                const importerId = $(this).val();
                if (!importerId) return;
                try {
                    const res = await fetch(`/api/importers/${importerId}`, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                    const i = await res.json();
                    setField("#carrier-name", i.name);
                    setField("#carrier-address", i.address);
                    setField("#carrier-tel", i.contact_phone);
                    setField("#carrier-tax", i.tax_id);
                    setField("#carrier-email", i.contact_email);
                    setField("#carrier-owner", i.owner);
                } catch (err) {
                    console.warn("Failed to load importer:", err);
                }
            });

            function formatDateToDDMMYYYY(dateString) {
                if (!dateString) return '';
                if (typeof dateString === 'string') {
                    const [year, month, day] = dateString.split('-');
                    return `${day}.${month}.${year}`;
                } else if (dateString instanceof Date) {
                    const d = dateString;
                    const day = String(d.getDate()).padStart(2, '0');
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const year = d.getFullYear();
                    return `${day}.${month}.${year}`;
                }
                return '';
            }


        
         

            // --- Prefill selected supplier/importer
            if (invoice.supplier_id) {
                const selected = supplierOptions.find(s => s.id === invoice.supplier_id);
                if (selected) {
                    $('#supplier-select2').append(new Option(selected.text, selected.id, true, true)).trigger('change');
                }
            }

            if (invoice.importer_id) {
                const selected = importerOptions.find(i => i.id === invoice.importer_id);
                if (selected) {
                    $('#importer-select2').append(new Option(selected.text, selected.id, true, true)).trigger('change');
                }
            }

            // --- Table rendering
            const tbody = document.querySelector("#newlink");
            tbody.innerHTML = "";

            // Clear previous rows
            tbody.innerHTML = "";

            // Use DocumentFragment for fast DOM appending
            const fragment = document.createDocumentFragment();

            // Append all rows in one operation
            tbody.appendChild(fragment);

            // Update totals only once
            updateTotalAmount();

            // Close loading UI ASAP to unblock interaction
            Swal.close();

            // Defer Select2 initialization to next event loop tick
            setTimeout(() => {

                $('.select2-tariff').select2({
                    data: processedTariffData,
                    width: 'resolve'
                });
            }, 0);

        } catch (e) {
            console.error("Error loading invoice:", e);
            Swal.fire("Greška", "Nije moguće učitati deklaraciju.", "error");
        }
    });

    function setField(selector, value) {
        const el = document.querySelector(selector);
        if (el) el.value = value || "";
    }

    function setText(selector, value) {
        const el = document.querySelector(selector);
        if (el) el.textContent = value || "";
    }

   function formatCountryWithFlag(state) {
        if (!state.id) return state.text;
        const flagUrl = `https://flagcdn.com/w40/${state.id.toLowerCase()}.png`;
        return $(`<span><img src="${flagUrl}" class="" width="20" /> ${state.text}</span>`);
    }

    function formatToDMY(isoDate) {
        if (!isoDate) return "";
        const [year, month, day] = isoDate.split("-");
        return `${day}-${month}-${year}`;
    }

    // Reformat existing value BEFORE flatpickr init
    const input1 = document.querySelector("#date");
    if (input1 && input1.value.includes("-") && input1.value.length === 10) {
        input1.value = formatToDMY(input1.value);
    }

    const input2 = document.querySelector("#invoice-date");
    if (input2 && input2.value.includes("-") && input2.value.length === 10) {
        input2.value = formatToDMY(input2.value);
    }

    // Now initialize Flatpickr
    flatpickr("#date", {
        locale: "bs",
        dateFormat: "d.m.Y"
    });

    flatpickr("#invoice-date", {
        locale: "bs",
        dateFormat: "d.m.Y"
    });

  

    document.getElementById("add-item")?.addEventListener("click", (e) => {
    e.preventDefault();
    console.log("Dodaj proizvod clicked");
    addRowToInvoice();
    initializeTariffSelects();
});





    //Remove button logic 
</script>


<div id="pre-ai-overlay" class="{{ isset($id) ? 'd-none' : '' }}">
  <div class="bg-white rounded shadow p-4 text-center" style="width:420px;">
    <h5 class="mb-4" style="font-size: 20px">Pokretanje AI&nbsp;tehnologije</h5>

    <div class="custom-swal-spinner mb-3"></div>

    <div class="text-muted" style="font-size:.9rem;">
      Pripremamo okruženje
    </div>
  </div>
</div>










<script src="{{ URL::asset('build/js/declaration/select-autofocus.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/loading-overlay.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/decimal-regex.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/tariff-privilege.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/export-edit.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/swal-declaration-load.js') }}"></script>
@endsection