@extends('layouts.master')
@section('title')
@lang('translation.create-invoice')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<!-- Sweet Alert css-->
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
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

    .select2-container--default .select2-selection--single {
        background-color: #f4f4fc !important;
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

<div class="row justify-content-center mt-0 mb-3">
    <div class="col-xxl-10">
        <div class="card" id="invoice-form">
            <div class="card-header border-0 p-4 d-flex justify-content-between align-items-start">
                <div>
                    <img src="{{ URL::asset('build/images/logo-dek.png') }}" class="card-logo" alt="logo" height="34">
                    <div class="mt-4">
                        <h6 class="text-muted text-uppercase fw-semibold">Osnovni podaci</h6>
                        <input type="text" class="form-control mb-2" id="company-address" name="address" placeholder="Unesite adresu">
                        <input type="text" class="form-control mb-2" id="company-zip" name="zip" placeholder="Poštanski broj">
                        <input type="email" class="form-control" id="company-email" name="email" placeholder="Email">
                    </div>

                    <!-- Incoterm Dropdown Section -->

                </div>

                <div style="min-width: 200px;">
                    <h6 class="text-muted text-uppercase fw-semibold mt-1 ">Incoterm</h6>
                    <select class="form-select mb-2 custom-select-icon" style="background: #f4f4fc;" name="incoterm" id="incoterm" placeholder="Odaberite incoterm">

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
                    <div style="min-width: 220px;">
                        <h6 class="text-muted text-uppercase fw-semibold mt-1">Broj fakture</h6>
                        <input type="text" class="form-control" id="invoice-no" name="invoice_no" placeholder="Unesite broj fakture">
                    </div>
                </div>

            </div>
            <div class="card-body p-4 border-top border-top-dashed">
                <div class="row g-4">
                    <div class="col-6 text-start">

                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Podaci o dobavljaču</h6>

                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-info mb-2" id="add-new-supplier">Obriši AI podatke i unesi ručno</button>
                            <button type="button" class="btn btn-info mb-2" id="refill-supplier-ai">Populiši ponovo s AI</button>
                            <select id="supplier-select2" class="form-select"></select>
                        </div>
                        <input type="text" class="form-control mb-2" id="billing-name" name="supplier_name" placeholder="Naziv dobavljača">

                        <input type="text" class="form-control mb-2" id="billing-address-line-1" name="supplier_address" placeholder="Adresa dobavljača">
                        <input type="text" class="form-control mb-2" id="billing-phone-no" name="supplier_phone" placeholder="Telefon">
                        <input type="text" class="form-control mb-2" id="billing-tax-no" name="supplier_tax" placeholder="VAT">
                        <input type="email" class="form-control mb-2" id="email" name="email" placeholder="Email">
                        <input type="email" class="form-control" id="supplier-owner" name="supplierOwner" placeholder="Vlasnik">
                    </div>
                    <div class="col-6 text-end">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3 text-end">Podaci o uvozniku</h6>

                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-info mb-2" id="add-new-importer">Obriši AI podatke i unesi ručno</button>
                            <button type="button" class="btn btn-info mb-2" id="refill-importer-ai">Populiši ponovo s AI</button>
                            <select id="importer-select2" class="form-select"></select>
                        </div>

                        <input type="text" class="form-control mb-2 text-end" id="carrier-name" name="uvoznikime" placeholder="Uvoznik">

                        <input type="text" class="form-control mb-2 text-end" id="carrier-address" name="uvoznikadresa" placeholder="Adresa">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-tel" name="uvozniktel" placeholder="Telefon">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-tax" name="uvozniktel" placeholder="JIB">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-email" name="uvozniktel" placeholder="Email">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-owner" name="carrierOwner" placeholder="Vlasnik">

                    </div>
                </div>
            </div>

            <div class="card-body p-4 border-top border-top-dashed">
                <div class="row g-4 mb-3">
                    <div class="col-lg-4 text-start">
                        <label class="text-muted text-uppercase fw-semibold mb-1">ID Deklaracije</label>
                        <input type="text" class="form-control" id="invoice-no1" name="invoice_no" placeholder="Broj fakture">
                    </div>
                    <div class="col-lg-4 text-center">
                        <label class="d-flex justify-content-center text-muted text-uppercase fw-semibold mb-1">Datum</label>
                        <input type="date" class="form-control" id="invoice-date" name="invoice_date">
                    </div>
                    <div class="col-lg-4 text-end">
                        <label class="text-muted text-uppercase fw-semibold mb-1">Ukupan iznos</label>
                        <input type="text" class="form-control text-end" id="total-amount" name="total_amount" placeholder="0.00 KM">
                    </div>
                </div>
            </div>



            <div class="card-body p-4 border-top border-top-dashed">
                <div class="table-responsive">
                    <table class="table table-borderless text-center table-nowrap align-middle mb-0" id="products-table">
                        <thead class="table-active">
                            <tr>
                                <th style="width: 50px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">#</th>
                                <th style="width: 200px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Proizvodi </th>
                                <th style="width: 140px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Opis </th>
                                <th style="width: 140px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Tarifna oznaka</th>
                                <th style="width: 60px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Tip kvantiteta</th>
                                <th style="width:70px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Zemlja porijekla</th>
                                <th style="width:60px; text-align: center;vertical-align: middle; text-align: left; padding-bottom: 1rem;">Cijena</th>
                                <th style="width: 60px; text-align: center;vertical-align: middle;">
                                    Količina<br>
                                    <small style="font-weight: normal; font-size: 0.75rem; color: #666;">
                                        Broj paketa
                                    </small>
                                </th>

                                <th style="width:70px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Ukupno</th>
                                <th style="width:20px;vertical-align: middle; text-align: middle; padding-bottom: 1rem;">Akcija</th>
                            </tr>
                        </thead>
                        <tbody id="newlink">

                        </tbody>
                        <tbody>
                            <tr class="text-end mt-3">
                                <td colspan="10" class="text-end mt-3">
                                    <a href="javascript:void(0)" id="add-item" class="btn btn-info fw-medium text-end mt-2">
                                        <i class="ri-add-fill me-1 align-bottom"></i> Dodaj proizvod
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-top border-top-dashed mt-2">
                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                        <tbody class="border-bottom-dashed">
                            <tr class="border-top border-top-dashed fs-15">
                                <th>Ukupan iznos</th>
                                <th class="text-end" id="modal-total-amount"> </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    @include('components.fixed-sidebar')
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/invoicecreate.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/bs.js"></script>
<!-- Scan and other logic script -->
<script>
    const editModeMatch = window.location.pathname.match(/\/deklaracija\/(\d+)/);
    const isEditMode = !!editModeMatch;

    if (isEditMode) {
        console.warn("🛑 Edit mode detected – skipping scan/autofill script.");
        // Exit the script entirely
        // Note: Wrap the entire content below inside an IIFE or block
        // Or better – put all scan logic inside a condition
    } else {
        console.log(' Custom invoice JS loaded');
        let _invoice_data = null;
        let processedTariffData = [];
        let globalAISuggestions = [];

        // Add global flags
        window.forceNewSupplier = false;
        window.forceNewImporter = false;
        window.skipPrefillParties = false; // NEW: skip prefill after manual clear

        function getInvoiceId() {
            const id = localStorage.getItem("scan_invoice_id");
            console.log(" Invoice ID:", id);
            return id;
        }

        function updateTotalAmount() {
            let total = 0;

            // Loop through all invoice rows
            document.querySelectorAll("#newlink tr.product").forEach(function(row) {
                const price = parseFloat(row.querySelector('input[name="price[]"]')?.value || 0);
                const quantity = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);
                total += price * quantity;
            });

            // Format as currency
            const formatted = `${total.toFixed(2)} KM`;

            // Set values in both places
            document.getElementById("total-amount").value = formatted;
            document.getElementById("modal-total-amount").textContent = formatted;
        }


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
                console.warn(" No task ID found in localStorage.");
                return false;
            }

            console.log(" Starting AI scan for task ID:", taskId);
            const response = await fetch(`/api/invoices/${taskId}/scan`, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                }
            });

            if (!response.ok) {
                const err = await response.json();
                console.error(" AI scan error:", err);
                Swal.fire("Greška", err?.error || "Nepoznata greška", "error");
                return false;
            }

            console.log(" AI scan started successfully");
            return true;
        }

        async function waitForAIResult(showLoader = true) {
            const invoice_id = getInvoiceId();
            if (!invoice_id) return;

            if (showLoader) {
                Swal.fire({
                    title: 'Skeniranje ',
                    html: `<div class="custom-swal-spinner mb-3"></div><div id="swal-status-message">Čeka na obradu</div>`,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            }

            const stepTextMap = {
                null: "Čeka na početak obrade",
                conversion: "Konvertovanje dokumenta",
                extraction: "Ekstrakcija podataka",
                enrichment: "Obogaćivanje podataka"
            };

            while (true) {
                const res = await fetch(`/api/invoices/${invoice_id}/scan`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });

                const json = await res.json();
                const status = json?.status?.status;
                const step = json?.status?.processing_step;
                const errorMsg = json?.status?.error_message;

                console.log(" Scan status:", status, "| Step:", step, "| Error:", errorMsg);

                const el = document.getElementById("swal-status-message");
                if (el) {
                    if (status === "failed" || status === "error") {
                        el.innerHTML = `<span class='text-danger'>Greška: ${errorMsg || 'Nepoznata greška'}</span><br><span class='text-muted'>Korak: ${stepTextMap[step] || step || ''}</span>`;
                    } else {
                        const message = stepTextMap[step] || "Obrađujemo podatke";
                        el.textContent = message;
                    }
                }

                if (status === "completed") {
                    if (el) el.textContent = "Završeno";
                    await new Promise(r => setTimeout(r, 1000));
                    Swal.close();
                    _invoice_data = null;
                    break;
                }

                if (status === "failed" || status === "error") {
                    await new Promise(r => setTimeout(r, 1000));
                    Swal.close();
                    console.error("Scan error:", errorMsg);
                    Swal.fire("Greška", errorMsg || "Greška pri skeniranju", "error");
                    break;
                }

                await new Promise(r => setTimeout(r, 2000));
            }
        }



        function initializeTariffSelects() {
            $('.select2-tariff').each(function() {
                const select = $(this);
                const prefillValue = select.data("prefill");

                select.select2({
                    placeholder: "Pretraži tarifne stavke...",
                    width: '100%',
                    minimumInputLength: 1,
                    ajax: {
                        transport: function(params, success, failure) {
                            const term = params.data.q?.toLowerCase() || "";
                            const filtered = processedTariffData.filter(item => item.search.includes(term));
                            success({
                                results: filtered
                            });
                        },
                        delay: 200
                    },
                    templateResult: function(item) {
                        if (!item.id && !item.text) return null;
                        const icon = item.isLeaf ? "•" : "▶";
                        return $(`<div style="padding-left:${item.depth * 20}px;" title="${item.display}">${icon} ${item.display}</div>`);
                    },
                    templateSelection: function(item) {
                        return item.id || "";
                    }
                });

                // Programmatically set prefill value, only with Tarifna oznaka
                if (prefillValue) {
                    const matched = processedTariffData.find(item => item.id === prefillValue);
                    if (matched) {
                        const option = new Option(matched.id, matched.id, true, true);
                        select.append(option).trigger('change');
                    }
                }
            });
        }



        function addRowToInvoice(item = {}, suggestions = []) {
            const tbody = document.getElementById("newlink");
            const index = tbody.children.length;

            globalAISuggestions.push(suggestions);

            const name = item.name || item.item_description_original || "";
            const tariff = item.tariff_code || "";
            const price = item.base_price || 0;
            const quantity = item.quantity || 0;
            const origin = item.country_of_origin || "DE";
            const total = (price * quantity).toFixed(2);
            const desc = item.item_description;
            const package_num = item.num_packages || 0;
            const qtype = item.quantity_type || "";

            console.log(` Adding row ${index + 1}:`, item, suggestions);

            const row = document.createElement("tr");
            row.classList.add("product");



            function generateCountryOptions(selectedCode = "") {
                // ISO country codes + names (lowercase for flag URL)
                const countries = [{
                        code: "af",
                        name: "Afghanistan"
                    }, {
                        code: "al",
                        name: "Albania"
                    }, {
                        code: "dz",
                        name: "Algeria"
                    },
                    {
                        code: "ad",
                        name: "Andorra"
                    }, {
                        code: "ao",
                        name: "Angola"
                    }, {
                        code: "ag",
                        name: "Antigua and Barbuda"
                    },
                    {
                        code: "ar",
                        name: "Argentina"
                    }, {
                        code: "am",
                        name: "Armenia"
                    }, {
                        code: "au",
                        name: "Australia"
                    },
                    {
                        code: "at",
                        name: "Austria"
                    }, {
                        code: "az",
                        name: "Azerbaijan"
                    }, {
                        code: "bs",
                        name: "Bahamas"
                    },
                    {
                        code: "bh",
                        name: "Bahrain"
                    }, {
                        code: "bd",
                        name: "Bangladesh"
                    }, {
                        code: "bb",
                        name: "Barbados"
                    },
                    {
                        code: "by",
                        name: "Belarus"
                    }, {
                        code: "be",
                        name: "Belgium"
                    }, {
                        code: "bz",
                        name: "Belize"
                    },
                    {
                        code: "ba",
                        name: "Bosnia and Herzegovina"
                    }, {
                        code: "hr",
                        name: "Croatia"
                    }, {
                        code: "rs",
                        name: "Serbia"
                    },
                    {
                        code: "me",
                        name: "Montenegro"
                    }, {
                        code: "si",
                        name: "Slovenia"
                    }, {
                        code: "mk",
                        name: "North Macedonia"
                    },
                    {
                        code: "de",
                        name: "Germany"
                    }, {
                        code: "fr",
                        name: "France"
                    }, {
                        code: "us",
                        name: "United States"
                    },
                    {
                        code: "gb",
                        name: "United Kingdom"
                    }, {
                        code: "it",
                        name: "Italy"
                    }, {
                        code: "es",
                        name: "Spain"
                    },
                    {
                        code: "cn",
                        name: "China"
                    }, {
                        code: "jp",
                        name: "Japan"
                    }, {
                        code: "in",
                        name: "India"
                    }
                    // Add more if needed (or I can give you all 195 full set)
                ];
                return countries.map(({
                    code,
                    name
                }) => {
                    const flagUrl = `https://flagcdn.com/w40/${code}.png`;
                    const isSelected = selectedCode?.toLowerCase() === code ? "selected" : "";
                    return `<option value="${code.toUpperCase()}" ${isSelected} data-flag="${flagUrl}">${code.toUpperCase()}</option>`;
                }).join("");

            }



            row.innerHTML = `
          <td style="width: 50px;">${index + 1}</td>

          <td colspan="2" style="width: 340px;">
            <div class="input-group" style="display: flex; gap: 0.25rem;">
              <input type="text" class="form-control item-name" name="item_name[]" placeholder="Naziv proizvoda" value="${name}" style="flex:1;">
              <button class="btn btn-outline-info rounded" onmouseover="updateTooltip(this)" type="button" onclick="searchFromInputs(this)" data-bs-toggle="tooltip" data-bs-placement="top"   title="">
                 <i class="fa-brands fa-google"></i>
            </button>
              <input type="text" class="form-control item-desc" name="item_desc[]" placeholder="Opis proizvoda" value="${desc}" style="flex:1;">
            </div>
            <input 
              type="text" 
              class="form-control form-control-sm mt-1" 
              style="font-size: 0.85rem;" 
              name="item_prev[]" 
              placeholder="Prevod"
            >
          </td>

          <td class="text-start" style="width: 150px;">
            <div style="position: relative; width: 100%;">
              <select
                class="form-control select2-tariff "
                style="width: 100%; padding-right: 45px;"
                name="item_code[]"
                data-prefill="${tariff}"
                data-suggestions='${JSON.stringify(suggestions)
                .replace(/&/g, '&amp;')
                .replace(/'/g, '&#39;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')}'>
              </select>

              <button
                type="button"
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

          <td style="width: 60px;">
            <input 
              type="text" 
              class="form-control" 
              name="quantity_type[]" 
              placeholder="AD, AE.." 
              value="${qtype}"
              
            >
          </td>

          <td style="width: 70px;">
            <select class="form-select" name="origin[]" style="width: 100%;">
              ${generateCountryOptions(origin)}
            </select>
          </td>

          <td style="width: 60px;">
            <input 
              type="number" 
              class="form-control text-start-truncate" 
              name="price[]" 
              value="${price}" 
              style="width: 100%;"
              
            >
          </td>

          <td style="width: 80px;">
            <div style="display: flex; flex-direction: column; gap: 2px; width: 100%;">
              <div class="input-group input-group-sm" style="width: 100%;">
                <button 
                  class="btn btn-outline-info btn-sm decrement-qty" 
                  style="background: #f4f4fc !important; width: 20px; padding: 0;" 
                  type="button"
                >−</button>
                <input 
                  type="number" 
                  class="form-control text-center" 
                  name="quantity[]" 
                  value="${quantity}" 
                  step="1" 
                  min="0"
                  style="padding: 0 5px; height: 30px;"
                >
                <button 
                  class="btn btn-outline-info btn-sm increment-qty" 
                  style="background: #f4f4fc !important; width: 20px; padding: 0;" 
                  type="button"
                >+</button>
              </div>
              
             <div class="input-group input-group-sm" style="height: 30px;">
                <button 
                class="btn btn-outline-info btn-sm decrement-kolata"
                  style="padding: 0; width: 20px;"
                >−</button>

                <input
                  type="number"
                  class="form-control text-center"
                  name="kolata[]"
                  placeholder="Broj paketa"
                  min="0"
                  step="1"
                  style="height: 30px; padding: 0 5px; font-size: 10px;"
                  value="${package_num}"
                >

                <button 
                  class="btn btn-outline-info btn-sm increment-kolata" 
                  type="button" 
                  style="padding: 0; width: 20px;"
                >+</button>
                </div>
            </div>
          </td>

          <td style="width: 70px;">
            <input 
              type="text" 
              class="form-control text-start" 
              name="total[]" 
              value="${total}"
              style="width: 100%;"
            >
          </td>

          <td style="width: 20px; text-align: center;">
              <div style="display: flex; flex-direction: column; align-items: end; gap: 2px;">
                <button type="button" class="btn btn-danger btn-sm remove-row text-center "   style="width: 30px;" title="Ukloni red"  >
                  <i class="fas fa-times"></i>
                </button>
                
                <input type="checkbox" class="form-check-input " data-bs-toggle="tooltip"  style="width: 30px; height: 26.66px;" title="Povlastica DA/NE" />
              </div>
            </td>

        `;


            $(row).find('select[name="origin[]"]').select2({
                templateResult: formatFlag,
                templateSelection: formatFlag,
                placeholder: "Select a country",
                width: 'style',
                language: {
                    noResults: function() {
                        return "Nisu pronađeni rezultati";
                    },
                    searching: function() {
                        return "Pretraga…";
                    },
                    inputTooShort: function() {
                        return "Unesite još znakova…";
                    }
                }
            });

            function formatFlag(state) {
                if (!state.id) return state.text;
                const flagUrl = $(state.element).data('flag');
                return $(`<span class="flag-option"><img src="${flagUrl}" width="20" style="margin-right: 10px;" /> ${state.text}</span>`);
            }

            tbody.appendChild(row);
            initializeTariffSelects();

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
        $(document).on('input', 'input[name="price[]"], input[name="quantity[]"]', function() {
            const row = $(this).closest('tr');
            const price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            const quantity = parseInt(row.find('input[name="quantity[]"]').val()) || 0;
            const total = (price * quantity).toFixed(2);
            row.find('input[name="total[]"]').val(total);

            // Optional: update global total as well
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

            // Initialize all tooltips once
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) { // avoid re-init
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover',
                        delay: {
                            show: 100,
                            hide: 100
                        }
                    });
                }
            });

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
            waitForEl("#invoice-date-text", el => el.textContent = invoice.date_of_issue || "—");
            waitForEl("#pregled", el => {
                el.addEventListener("click", () => {
                    window.location.href = `/detalji-deklaracije/${invoice.id}`;
                });
            });



            waitForEl("#total-edit", el => {
                const currency = invoice.items?.[0]?.currency || "EUR";
                el.textContent = `${invoice.total_price} ${currency}`;

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



        }


        async function fetchAndPrefillParties() {
            const taskId = localStorage.getItem("scan_invoice_id");
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
                    // Always remove any previous 'Novi dobavljač' option
                    $("#supplier-select2 option[value='new']").remove();
                    // Add and select 'Novi dobavljač'
                    const newOption = new Option('Novi dobavljač', 'new', true, true);
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
                                // Not found in DB, add 'Novi dobavljač' to select2
                                const newOption = new Option('Novi dobavljač', 'new', true, true);
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
                    // Not found in DB, add 'Novi dobavljač' to select2
                    const newOption = new Option('Novi dobavljač', 'new', true, true);
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
                    const newOption = new Option('Novi uvoznik', 'new', true, true);
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
                                // Not found in DB, add 'Novi uvoznik' to select2
                                const newOption = new Option('Novi uvoznik', 'new', true, true);
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
                    // Always remove any previous 'Novi uvoznik' option
                    $("#importer-select2 option[value='new']").remove();
                    // Add and select 'Novi uvoznik'
                    const newOption = new Option('Novi uvoznik', 'new', true, true);
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
                Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
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
                Swal.fire({
                    title: 'Skeniranje',
                    html: `<div class=\"custom-swal-spinner mb-3\"></div><div id=\"swal-status-message\">Čeka na obradu</div>`,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                if (invoice.task_id == null && await startAiScan()) {
                    await waitForAIResult();
                } else if (!invoice.items?.length) {
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
                placeholder: "Pretraži uvoznika",
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


            // await promptForSupplierAfterScan();
            $(document).on('click', '.show-ai-btn', function() {
                console.log("✅ AI button clicked");

                const select = $(this).closest('td').find('select.select2-tariff');
                console.log("🔎 Found select element:", select);

                let rawSuggestions = select.data("suggestions");
                try {
                    if (typeof rawSuggestions === "string") {
                        rawSuggestions = JSON.parse(rawSuggestions);
                    }
                } catch (err) {
                    console.error("❌ Failed to parse suggestions JSON:", err);
                    return;
                }

                console.log("📦 Raw suggestions:", rawSuggestions);

                if (!rawSuggestions) {
                    console.warn(" No suggestions found on data attribute.");
                    return;
                }

                if (!Array.isArray(rawSuggestions)) {
                    console.warn(" Suggestions are not an array:", typeof rawSuggestions);
                    return;
                }

                const sorted = [...rawSuggestions].sort((a, b) => a.closeness - b.closeness).slice(0, 10);
                console.log(" Sorted suggestions:", sorted);

                const container = document.getElementById("aiSuggestionsBody");
                if (!container) {
                    console.error(" aiSuggestionsBody not found in DOM");
                    return;
                }

                if (!sorted.length) {
                    container.innerHTML = `<div class="text-muted">Nema prijedloga.</div>`;
                    console.log("ℹ️ No sorted suggestions to show.");
                } else {
                    container.innerHTML = sorted.map((s, i) => `
            <div class="mb-2">
                <div><strong>${i + 1}. Tarifna oznaka:</strong> ${s.entry["Tarifna oznaka"]}</div>
                <div><strong>Naziv:</strong> ${s.entry["Naziv"]}</div>
                <button class="btn btn-sm btn-info mt-1 use-tariff" data-value="${s.entry["Tarifna oznaka"]}">
                    Koristi ovu oznaku
                </button>
                <hr>
            </div>
        `).join("");
                    console.log(" Inserted suggestions into modal body.");
                }

                $('#aiSuggestionModal').data('target-select', select);
                console.log(" Set data-target-select on modal.");

                const modalEl = document.getElementById("aiSuggestionModal");
                if (!modalEl) {
                    console.error(" Modal element not found with ID aiSuggestionModal");
                    return;
                }

                let modal = bootstrap.Modal.getInstance(modalEl);
                console.log(" Existing modal instance:", modal);

                if (!modal) {
                    modal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static',
                        keyboard: true
                    });
                    console.log(" Modal instance created.");
                }

                modal.show();
                console.log(" Bootstrap modal should be showing now.");
            });

            $(document).on('click', '.use-tariff', function() {
                const code = $(this).data('value');
                console.log(" User selected code:", code);

                const select = $('#aiSuggestionModal').data('target-select');
                console.log(" Target select:", select);

                if (select && code) {
                    const matched = processedTariffData.find(item => item.id === code);
                    console.log("🔍 Matched tariff code:", matched);

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

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateTotalAmount();
            });

            function formattedToDDMMYYYY(date = new Date()) {
                const day = String(date.getDate()).padStart(2, "0");
                const month = String(date.getMonth() + 1).padStart(2, "0");
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }




            const invNo = getInvoiceId();
            if (invNo) document.getElementById("invoice-no1").value = invNo;

            document.getElementById("invoice-date").value = new Date().toISOString().split("T")[0];
            console.log(" Invoice date and number set.");

            document.getElementById("company-address").value = "Vilsonovo, 9, Sarajevo ";
            document.getElementById("company-zip").value = "71000";
            document.getElementById("company-email").value = "business@deklarant.ba";


            document.getElementById("billing-name")?.addEventListener("input", () => {
                const label = document.getElementById("billing-name-ai-label");
                if (label) label.classList.add("d-none");
            });

            // Hide AI label when user types in importer name
            document.getElementById("carrier-name")?.addEventListener("input", () => {
                const label = document.getElementById("carrier-name-ai-label");
                if (label) label.classList.add("d-none");
            });


        });


        // Add buttons above supplier and importer fields
        $(document).ready(function() {

            // Handler for new supplier
            $(document).on('click', '#add-new-supplier', function() {
                if (window.isResetConfirmed) return;

                Swal.fire({
                    title: 'Jesi li siguran/na?',
                    text: 'Ova radnja će izbrisati sve podatke za dobavljača i omogućiti ručni unos.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    confirmButtonColor: '#299dcb',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.skipPrefillParties = true;

                        // Clear all supplier input fields and unlock them
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner")
                            .val("")
                            .prop('readonly', false)
                            .removeClass("is-invalid");

                        // Clear and reset supplier-select2, keeping only 'Novi dobavljač'
                        const select = $("#supplier-select2");
                        select.empty(); // remove all options

                        const newOption = new Option('Novi dobavljač', 'new', true, true);
                        select.append(newOption).trigger('change'); // add and select it
                    }
                });
            });




            // Handler for new importer
            $(document).on('click', '#add-new-importer', function() {
                if (window.isResetConfirmed) return;

                Swal.fire({
                    title: 'Jesi li siguran/na?',
                    text: 'Ova radnja će izbrisati sve podatke za uvoznika i omogućiti ručni unos.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    confirmButtonColor: '#299dcb',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.skipPrefillParties = true;

                        // Clear all importer input fields and unlock them
                        $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner")
                            .val("")
                            .prop('readonly', false)
                            .removeClass("is-invalid");

                        // Clear and reset importer-select2, keeping only 'Novi uvoznik'
                        const select = $("#importer-select2");
                        select.empty(); // remove all options

                        const newOption = new Option('Novi uvoznik', 'new', true, true);
                        select.append(newOption).trigger('change'); // add and select it
                    }
                });
            });

        });


        $(document).ready(function() {
            // Add SweetAlert confirmation for supplier manual entry
            $('#add-new-supplier').off('click').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Jesi li siguran/na?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    confirmButtonColor: '#299dcb',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.skipPrefillParties = true;
                        // Only clear and unlock supplier fields
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false).removeClass("is-invalid");
                        // Remove and add 'Novi dobavljač' option, then select it
                        $("#supplier-select2 option[value='new']").remove();
                        var newOption = new Option('Novi dobavljač', 'new', true, true);
                        $("#supplier-select2").append(newOption).val('new').trigger('change');
                    }
                });
            });
            // Add SweetAlert confirmation for importer manual entry
            $('#add-new-importer').off('click').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Jesi li siguran/na?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    confirmButtonColor: '#299dcb',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.skipPrefillParties = true;
                        // Only clear and unlock importer fields
                        $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false).removeClass("is-invalid");
                        // Remove and add 'Novi uvoznik' option, then select it
                        $("#importer-select2 option[value='new']").remove();
                        var newOption = new Option('Novi uvoznik', 'new', true, true);
                        $("#importer-select2").append(newOption).val('new').trigger('change');
                    }
                });
            });
        });

        // 1. Add buttons in the DOM (jQuery, after DOMContentLoaded)
        $(document).ready(function() {
            // Add 'Popuni ponovo s AI' button next to 'Obriši' for supplier
            if ($('#add-new-supplier').length && !$('#refill-supplier-ai').length) {
                $('<button type="button" id="refill-supplier-ai" class="btn btn-info btn-sm ms-2">Populiši ponovo s AI</button>')
                    .insertAfter('#add-new-supplier');
            }
            // Add 'Popuni ponovo s AI' button next to 'Obriši' for importer
            if ($('#add-new-importer').length && !$('#refill-importer-ai').length) {
                $('<button type="button" id="refill-importer-ai" class="btn btn-info btn-sm ms-2">Populiši ponovo s AI</button>')
                    .insertAfter('#add-new-importer');
            }

            // Handler for supplier AI refill
            $(document).on('click', '#refill-supplier-ai', async function() {
                const taskId = localStorage.getItem("scan_invoice_id");
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
                        // Set select2 to 'Novi dobavljač'
                        $("#supplier-select2 option[value='new']").remove();
                        var newOption = new Option('Novi dobavljač', 'new', true, true);
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
                const taskId = localStorage.getItem("scan_invoice_id");
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
                        // Set select2 to 'Novi uvoznik'
                        $("#importer-select2 option[value='new']").remove();
                        var newOption = new Option('Novi uvoznik', 'new', true, true);
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
                        Swal.fire("Greška", "Nema AI podataka za uvoznika", "error");
                    }
                } catch (err) {
                    Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
                }
            });
        });




    }
</script>


<!-- btn add item logic -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const addItemBtn = document.getElementById("add-item");
        if (addItemBtn) {
            addItemBtn.addEventListener("click", () => addRowToInvoice());
        } else {
            console.warn("[add-item] button not found.");
        }
    });
</script>






<!-- Google search -->

<script>
    function searchFromInputs(button) {
        const nameInput = button.closest('.input-group').querySelector('.item-name');
        const descInput = button.closest('.input-group').parentElement.querySelector('.item-desc');

        const name = nameInput.value.trim();
        const desc = descInput.value.trim();
        const query = encodeURIComponent(`${name} ${desc}`);


        if (name || desc) {
            window.open(`https://www.google.com/search?q=${query}`, '_blank');
        }
    }

    function updateTooltip(button) {
        const nameInput = document.querySelector('.item-name');
        const descInput = document.querySelector('.item-desc');

        const name = nameInput?.value.trim() || '';
        const desc = descInput?.value.trim() || '';
        const label = (name || desc) ?
            `Klikni za pretragu: ${name} ${desc}` :
            'Klikni za Google pretragu';

        // Update title attribute for fallback
        button.setAttribute('title', label);
        button.setAttribute('data-bs-original-title', label); // for Bootstrap

        // Update Bootstrap tooltip instance if it exists
        const tooltip = bootstrap.Tooltip.getInstance(button);
        if (tooltip) {
            tooltip.setContent({
                '.tooltip-inner': label
            });
        }
    }
</script>




<!-- Fixed side buttons -->


<!-- Save logic script final -->
<script>
    function getInvoiceId() {
        const scanId = localStorage.getItem("scan_invoice_id");
        if (scanId) {
            console.log("Using scanned invoice ID:", scanId);
            return scanId;
        }
        const match = window.location.pathname.match(/\/deklaracija\/(\d+)/);
        const urlId = match ? match[1] : null;
        console.log("Using URL invoice ID:", urlId);
        return urlId;
    }
    async function getInvoice() {
        const id = getInvoiceId();
        if (!id) return {};
        const res = await fetch(`/api/invoices/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        return await res.json();
    }

    document.getElementById("save-invoice-btn").addEventListener("click", async function(e) {
        e.preventDefault();
        e.stopPropagation();
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Spašavanje...`;

        let missingFields = [];

        function checkRequired(id, label) {
            const input = document.getElementById(id);
            if (!input || !input.value.trim()) {
                input.classList.add("is-invalid");
                missingFields.push(label);
            } else {
                input.classList.remove("is-invalid");
            }
        }

        // Check all required fields
        checkRequired("billing-name", "Naziv dobavljača");
        checkRequired("billing-address-line-1", "Adresa dobavljača");
        checkRequired("billing-tax-no", "PIB dobavljača");
        checkRequired("billing-phone-no", "Telefon dobavljača");
        checkRequired("email", "Email dobavljača");
        checkRequired("supplier-owner", "Vlasnik");

        checkRequired("carrier-name", "Naziv uvoznika");
        checkRequired("carrier-address", "Adresa uvoznika");
        checkRequired("carrier-tax", "PIB uvoznika");
        checkRequired("carrier-tel", "Telefon uvoznika");
        checkRequired("carrier-email", "Email uvoznika");
        checkRequired("carrier-owner", "Vlasnik");

        if (missingFields.length > 0) {
            Swal.fire("Greška", "Obavezna polja nisu popunjena:\n" + missingFields.join(", "), "error");
            btn.disabled = false;
            btn.innerHTML = `<i class="ri-save-line align-bottom me-1"></i> Sačuvaj`;
            return;
        }


        const userId = user?.id;
        if (!userId) {
            Swal.fire("Greška", "Korisnički ID nije pronađen", "error");
            return;
        }

        async function ensureEntity(endpoint, data, select2Id) {
            const selectedId = $(select2Id).val();
            // If 'Novi dobavljač' or 'Novi uvoznik' is selected, create new
            if (selectedId === 'new') {
                const res = await fetch(`/api/${endpoint}`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json?.error || `Greška kod spremanja ${endpoint}`);
                return json.data.id;
            } else {
                // Use existing ID
                return selectedId;
            }
        }

        try {
            // Build supplier and importer data
            const supplierData = {
                name: document.getElementById("billing-name").value.trim(),
                address: document.getElementById("billing-address-line-1").value.trim(),
                tax_id: document.getElementById("billing-tax-no").value.trim(),
                contact_phone: document.getElementById("billing-phone-no").value.trim(),
                contact_email: document.getElementById("email").value.trim(),
                owner: document.getElementById("supplier-owner").value.trim() || null,
                avatar: null,
                synonyms: []
            };

            const importerData = {
                name: document.getElementById("carrier-name").value.trim(),
                address: document.getElementById("carrier-address").value.trim(),
                tax_id: document.getElementById("carrier-tax").value.trim(),
                contact_phone: document.getElementById("carrier-tel").value.trim(),
                contact_email: document.getElementById("carrier-email").value.trim(),
                owner: document.getElementById("carrier-owner").value.trim() || null,
                avatar: null,
                synonyms: []
            };

            // --- Ensure valid integer IDs for supplier and importer ---
            function isValidId(id) {
                return id && !isNaN(id) && Number.isInteger(Number(id)) && Number(id) > 0;
            }

            // Only create if 'Novi ...' is selected, otherwise just use ID
            let supplierId = await ensureEntity("suppliers", supplierData, "#supplier-select2");
            let importerId = await ensureEntity("importers", importerData, "#importer-select2");

            // If still not valid, show error and abort
            if (!isValidId(supplierId)) {
                Swal.fire("Greška", "Molimo odaberite ili unesite validnog dobavljača.", "error");
                btn.disabled = false;
                btn.innerHTML = `<i class=\"ri-save-line align-bottom me-1\"></i> Sačuvaj`;
                return;
            }
            if (!isValidId(importerId)) {
                Swal.fire("Greška", "Molimo odaberite ili unesite validnog uvoznika.", "error");
                btn.disabled = false;
                btn.innerHTML = `<i class=\"ri-save-line align-bottom me-1\"></i> Sačuvaj`;
                return;
            }

            supplierId = Number(supplierId);
            importerId = Number(importerId);

            // Build invoice items
            const items = [];
            document.querySelectorAll("#newlink tr.product").forEach((row, index) => {
                const item_name = row.querySelector('[name="item_name[]"]')?.value?.trim() || "";
                const item_description_original = item_name;
                const item_code = $(row).find('[name="item_code[]"]').val() || "";

                const origin = row.querySelector('[name="origin[]"]')?.value || "";
                const base_price = parseFloat(row.querySelector('[name="price[]"]')?.value || "0");
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]')?.value || "0");
                const total_price = parseFloat((base_price * quantity).toFixed(2));
                const item_description = row.querySelector('[name="item_code[]"] option:checked')?.textContent || "";
                const quantity_type = row.querySelector('[name="quantity_type[]"]')?.value || "";
                const package_num = row.querySelector('[name="kolata[]"]')?.value || "";

                items.push({
                    item_name,
                    item_code,
                    item_description,
                    item_description_original,
                    origin,
                    base_price,
                    quantity,
                    quantity_type,
                    package_num,
                    total_price,
                    currency: "EUR",
                    version: new Date().getFullYear(),

                });
            });

            function toISODate(dmy) {
                const [day, month, year] = dmy.split("-");
                return `${year}-${month}-${day}`;
            }

            // Use the existing invoice file name and ID from upload
            const invoiceId = getInvoiceId();
            console.log("💾 Saving to invoice ID:", invoiceId);
            const invoiceData = await getInvoice();
            const fileName = invoiceData.file_name || "invoice.pdf";

            const payload = {
                file_name: fileName, // use the file name from the uploaded invoice
                total_price: parseFloat(document.getElementById("total-amount")?.value || "0"),
                date_of_issue: toISODate(document.getElementById("invoice-date")?.value),
                country_of_origin: document.getElementById("shipping-country")?.value || "Germany",
                items,
                supplier_id: supplierId,
                importer_id: importerId // always send both
            };

            console.log(" Sending payload (update):", payload);

            // Update the existing invoice instead of creating a new one
            const res = await fetch(`/api/invoices/${invoiceId}`, {
                method: "PUT",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });

            const resJson = await res.json();
            if (!res.ok) throw new Error(resJson?.error || "Greška pri spašavanju fakture");

            Swal.fire({
                icon: "success",
                title: "Uspješno",
                text: "Faktura je sačuvana",
                confirmButtonText: "U redu",
                customClass: {
                    confirmButton: "btn btn-info"
                },
                buttonsStyling: false
            });

        } catch (err) {
            console.error(" Greška:", err);
            Swal.fire("Greška", err.message || "Neočekivana greška", "error");
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class="ri-save-line align-bottom me-1"></i> Sačuvaj`;
        }
    });
</script>

<!-- Export to XML -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const exportBtn = document.getElementById("export-xml");
        if (!exportBtn) return;

        exportBtn.addEventListener("click", () => {
            const invoiceData = {
                items: [],
                subtotal: document.getElementById("cart-subtotal")?.value || "",
                tax: document.getElementById("cart-tax")?.value || "",
                discount: document.getElementById("cart-discount")?.value || "",
                shipping: document.getElementById("cart-shipping")?.value || "",
                total: document.getElementById("cart-total")?.value || "",
            };

            document.querySelectorAll("#newlink tr.product").forEach((row) => {
                invoiceData.items.push({
                    tarif: row.querySelector(".select2-tariff")?.value || "",
                    name: row.querySelector("input[name='item_name[]']")?.value || "",
                    translation: row.querySelector("input[name='item_prev[]']")?.value || "",
                    origin: row.querySelector("select[name='origin[]']")?.value || "",
                    price: row.querySelector("input[name='price[]']")?.value || "",
                    quantity: row.querySelector("input[name='quantity[]']")?.value || "",
                    total: row.querySelector("input[name='total[]']")?.value || "",
                });
            });

            // Escape function for XML safety
            const escapeXml = (str) =>
                String(str).replace(/[<>&'"]/g, (c) => ({
                    "<": "&lt;",
                    ">": "&gt;",
                    "&": "&amp;",
                    "'": "&apos;",
                    '"': "&quot;",
                }));

            // Build XML string
            let xml = `<invoice>\n`;
            invoiceData.items.forEach((item) => {

                xml += `  <item>\n`;
                xml += `    <tarif>${escapeXml(item.tarif)}</tarif>\n`;
                xml += `    <name>${escapeXml(item.name)}</name>\n`;
                xml += `    <translation>${escapeXml(item.translation)}</translation>\n`;
                xml += `    <origin>${escapeXml(item.origin)}</origin>\n`;
                xml += `    <price>${escapeXml(item.price)}</price>\n`;
                xml += `    <quantity>${escapeXml(item.quantity)}</quantity>\n`;
                xml += `    <total>${escapeXml(item.total)}</total>\n`;
                xml += `  </item>\n`;
            });
            xml += `  <subtotal>${escapeXml(invoiceData.subtotal)}</subtotal>\n`;
            xml += `  <tax>${escapeXml(invoiceData.tax)}</tax>\n`;
            xml += `  <discount>${escapeXml(invoiceData.discount)}</discount>\n`;
            xml += `  <shipping>${escapeXml(invoiceData.shipping)}</shipping>\n`;
            xml += `  <total>${escapeXml(invoiceData.total)}</total>\n`;
            xml += `</invoice>`;

            // Create blob and download
            const blob = new Blob([xml], {
                type: 'text/xml'
            });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "invoice.xml";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
</script>


<!-- Export to PDF -->
<script>
    document.getElementById("export-pdf").addEventEventListener("click", function() {
        const element = document.getElementById("invoice_form"); // or wrap the main content
        const opt = {
            margin: 0.5,
            filename: 'faktura.pdf',
            image: {
                type: 'jpeg',

                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(element).save();
    });
</script>


<!-- edit fill -->

<script>
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

    document.addEventListener("DOMContentLoaded", async () => {
        const match = window.location.pathname.match(/\/deklaracija\/(\d+)/);
        const invoiceId = match ? match[1] : null;
        if (!invoiceId) return console.log("No ID in URL — skipping load-invoice script.");
        const scanId = localStorage.getItem("scan_invoice_id");
        if (scanId && scanId !== invoiceId) {
            console.warn(`Clearing scan_invoice_id (${scanId}) because it does not match invoiceId (${invoiceId})`);
            localStorage.removeItem("scan_invoice_id");
        }

        Swal.fire({
            title: 'Učitavanje fakture...',
            icon: null, // Disable any built-in icon
            html: `<div class="custom-swal-spinner mb-3"></div><div id="swal-status-message">Molimo sačekajte</div>`,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                const spinner = document.querySelector(".custom-swal-spinner");


                // 🔥 Also remove SweetAlert2’s default icon area if still rendered
                const icon = Swal.getHtmlContainer()?.previousElementSibling;
                if (icon?.classList.contains('swal2-icon')) {
                    icon.remove();
                }
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

            const processedTariffData = tariffJson
                .filter(item => item["Tarifna oznaka"])
                .map(item => ({
                    id: item["Tarifna oznaka"],
                    text: item["Tarifna oznaka"]
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
                placeholder: "Pretraži uvoznika",
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
                const [year, month, day] = dateString.split('-'); // expects YYYY-MM-DD
                return `${day}-${month}-${year}`;
            }

            // --- Prefill invoice fields
            setField("#invoice_number", invoice.invoice_number);
            setField("#date", invoice.date_of_issue);
            setField("#total-amount", `${invoice.total_price} `);
            setText("#modal-total-amount", `${invoice.total_price} ${invoice.items?.[0]?.currency || "EUR"}`);
            setText("#invoice-id1", invoice.id);
            setText("#invoice-date-text", formatDateToDDMMYYYY(invoice.date_of_issue));
            setText("#total-edit", `${invoice.total_price} ${invoice.items?.[0]?.currency || "EUR"}`);
            setField("#invoice-no", invoice.invoice_number);
            setField("#invoice-no1", invoice.id);
            setField("#incoterm", (invoice.incoterm || "").split(" ")[0]);
            setField("#invoice-date", formatDateToDDMMYYYY(invoice.date_of_issue));

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

            invoice.items.forEach((item, index) => {
                const tarifnaOznaka = item.item_code?.trim() || item.best_customs_code_matches?.[0]?.entry?.["Tarifna oznaka"]?.trim() || "";
                const row = document.createElement("tr");
                row.classList.add("product");
                row.innerHTML = `
<td>${index + 1}</td>
<td colspan="2">
    <div class="input-group" style="display: flex; gap: 0.25rem;">
        <input type="text" class="form-control item-name" name="item_name[]" placeholder="Naziv proizvoda" value="${item.item_description_original || ''}" style="flex:1;">
        <button class="btn btn-outline-info rounded" type="button" onclick="searchFromInputs(this)"><i class="fa-brands fa-google"></i></button>
        <input type="text" class="form-control item-desc" name="item_desc[]" placeholder="Opis proizvoda" value="${item.item_description || ''}" style="flex:1;">
    </div>
    <input type="text" class="form-control form-control-sm mt-1" name="item_prev[]" placeholder="Prevod" value="${item.translation || ''}">
</td>
<td>
    <select class="form-control select2-tariff" name="item_code[]">
        <option value="${tarifnaOznaka}" selected>${tarifnaOznaka}</option>
    </select>
</td>
<td><input type="text" class="form-control" name="quantity_type[]" value="${item.quantity_type || ''}"></td>
<td>
    <select class="form-select select2-country" name="origin[]">${generateCountryOptions(item.country_of_origin)}</select>
</td>
<td><input type="number" class="form-control" name="price[]" value="${item.base_price || ''}"></td>
<td style="width: 60px;">
    <div class="input-group input-group-sm">
        <button class="btn btn-outline-info btn-sm" type="button" style="height:30px;padding:0 5px;font-size:10px;">−</button>
        <input type="number" class="form-control text-center" name="quantity[]" value="${item.quantity || 0}" min="0" style="padding: 0 5px;">
        <button class="btn btn-outline-info btn-sm" type="button" style="height:30px;padding:0 5px;font-size:10px;">+</button>
    </div>
    <div class="input-group input-group-sm mt-1">
        <button class="btn btn-outline-info btn-sm" type="button" style="height:30px;padding:0 5px;font-size:10px;">−</button>
        <input type="number" class="form-control text-center" name="kolata[]" value="${item.num_packages || 0}" min="0">
        <button class="btn btn-outline-info btn-sm" type="button" style="height:30px;padding:0 5px;font-size:10px;">+</button>
    </div>
</td>
<td><input type="text" class="form-control" name="total[]" value="${item.total_price || (item.base_price * item.quantity).toFixed(2)}"></td>
<td style="width: 20px; text-align: center;">
    <div style="display: flex; flex-direction: column; align-items: end; gap: 2px;">
        <button type="button" class="btn btn-danger btn-sm remove-row text-center" style="width: 30px;" title="Ukloni red"><i class="fas fa-times"></i></button>
        <input type="checkbox" class="form-check-input" style="width: 30px; height: 26.66px;" title="Povlastica DA/NE" />
    </div>
</td>`;
                tbody.appendChild(row);
            });

            // --- Select2 init
            queueMicrotask(() => {
                $('.select2-country').select2({
                    templateResult: formatCountryWithFlag,
                    templateSelection: formatCountryWithFlag,
                    width: 'resolve',
                    minimumResultsForSearch: Infinity
                });
                $('.select2-tariff').select2({
                    data: processedTariffData,
                    width: 'resolve'
                });
            });

            // Done loading
            Swal.close();
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

    function generateCountryOptions(selectedCode = "") {
        const countries = [
            "af", "al", "dz", "as", "ad", "ao", "ai", "aq", "ag", "ar", "am", "aw", "au", "at", "az",
            "bs", "bh", "bd", "bb", "by", "be", "bz", "bj", "bm", "bt", "bo", "ba", "bw", "bv", "br", "io", "bn", "bg", "bf", "bi",
            "kh", "cm", "ca", "cv", "ky", "cf", "td", "cl", "cn", "cx", "cc", "co", "km", "cg", "cd", "ck", "cr", "ci", "hr", "cu", "cy", "cz",
            "dk", "dj", "dm", "do", "ec", "eg", "sv", "gq", "er", "ee", "et",
            "fk", "fo", "fj", "fi", "fr", "gf", "pf", "tf", "ga", "gm", "ge", "de", "gh", "gi", "gr", "gl", "gd", "gp", "gu", "gt", "gg", "gn", "gw", "gy",
            "ht", "hm", "va", "hn", "hk", "hu",
            "is", "in", "id", "ir", "iq", "ie", "im", "il", "it",
            "jm", "jp", "je", "jo",
            "kz", "ke", "ki", "kp", "kr", "kw", "kg",
            "la", "lv", "lb", "ls", "lr", "ly", "li", "lt", "lu",
            "mo", "mk", "mg", "mw", "my", "mv", "ml", "mt", "mh", "mq", "mr", "mu", "yt", "mx", "fm", "md", "mc", "mn", "me", "ms", "ma", "mz", "mm",
            "na", "nr", "np", "nl", "nc", "nz", "ni", "ne", "ng", "nu", "nf", "mp", "no",
            "om", "pk", "pw", "ps", "pa", "pg", "py", "pe", "ph", "pn", "pl", "pt", "pr",
            "qa", "re", "ro", "ru", "rw", "bl", "sh", "kn", "lc", "mf", "pm", "vc", "ws", "sm", "st", "sa", "sn", "rs", "sc", "sl", "sg", "sx", "sk", "si", "sb", "so", "za", "gs", "ss", "es", "lk", "sd", "sr", "sj", "se", "ch", "sy",
            "tw", "tj", "tz", "th", "tl", "tg", "tk", "to", "tt", "tn", "tr", "tm", "tc", "tv",
            "ug", "ua", "ae", "gb", "us", "um", "uy", "uz",
            "vu", "ve", "vn", "vg", "vi",
            "wf", "eh",
            "ye",
            "zm", "zw"
        ];

        return countries.map(code => {
            const selected = selectedCode?.toLowerCase() === code ? "selected" : "";
            return `<option value="${code.toUpperCase()}" ${selected}>${code.toUpperCase()}</option>`;
        }).join("");
    }

    function formatCountryWithFlag(state) {
        if (!state.id) return state.text;
        const flagUrl = `https://flagcdn.com/w40/${state.id.toLowerCase()}.png`;
        return $(`<span><img src="${flagUrl}" class="me-2" width="20" /> ${state.text}</span>`);
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
        dateFormat: "d-m-Y"
    });

    flatpickr("#invoice-date", {
        locale: "bs",
        dateFormat: "d-m-Y"
    });


    //Remove button logic 
    document.addEventListener("click", function(e) {
        if (e.target.closest(".remove-row")) {
            const button = e.target.closest(".remove-row");
            const row = button.closest("tr");

            Swal.fire({
                title: "Jesi li siguran/na?",
                text: "Ovaj produkt će biti uklonjen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Da, ukloni",
                cancelButtonText: "Odustani",
                customClass: {
                    confirmButton: "btn btn-danger me-2",
                    cancelButton: "btn btn-secondary"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed && row) {
                    row.remove();
                }
            });
        }
    });
</script>



@endsection