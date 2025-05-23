@extends('layouts.master')
@section('title')
@lang('translation.create-invoice')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<!-- Sweet Alert css-->
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Ensures the selected text is truncated with ellipsis and tooltip works */
    .select2-container--default .select2-results__options {
        max-width: none !important;
        min-width: 400px !important;
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
        background: transparent !important;
        opacity: 0.9 !important;
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
                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Dobavljač</h6>
                        <input type="text" class="form-control mb-2" id="billing-name" name="supplier_name" placeholder="Naziv dobavljača">
                        <input type="text" class="form-control mb-2" id="billing-address-line-1" name="supplier_address" placeholder="Adresa dobavljača">
                        <input type="text" class="form-control mb-2" id="billing-phone-no" name="supplier_phone" placeholder="Telefon">
                        <input type="text" class="form-control mb-2" id="billing-tax-no" name="supplier_tax" placeholder="PIB">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="col-6 text-end">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3 text-end">Podaci o uvozniku</h6>
                        <input type="text" class="form-control mb-2 text-end" id="carrier-name" name="uvoznikime" placeholder="Uvoznik">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-address" name="uvoznikadresa" placeholder="Adresa">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-tel" name="uvozniktel" placeholder="Telefon">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-tax" name="uvozniktel" placeholder="PIB">
                        <input type="text" class="form-control mb-2 text-end" id="carrier-email" name="uvozniktel" placeholder="Email">
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
                                <th class="text-end" id="modal-total-amount">-- KM</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <div class="col-2 d-print-none" id="sidebar-buttons-container">
        <div id="fixed-buttons" class="d-flex flex-column gap-2">
            <a href="javascript:window.print()" class="btn btn-info">
                <i class="ri-printer-line align-bottom me-1"></i> Isprintaj deklaraciju
            </a>
            <a href="javascript:void(0);" class="btn btn-info">
                <i class="ri-download-2-line align-bottom me-1"></i> Preuzmi
            </a>
            <button type="button" id="save-invoice-btn" class="btn btn-info">
                <i class="ri-printer-line align-bottom me-1"></i> Sačuvaj
            </button>
            <a href="javascript:void(0);" id="export-xml" class="btn btn-info">
                <i class="ri-file-code-line align-bottom me-1"></i> Exportuj u XML
            </a>
            <a href="javascript:void(0);" id="reset-form" class="btn btn-info text-truncate">
                <i class="ri-delete-bin-line align-bottom me-1"></i> Obriši proizvode
            </a>
        </div>
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


<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class=" d-flex align-items-center bg-info text-white " style="height: 40px;">
                <h5 class="d-flex align-items-center justify-content-center w-100 m-0 text-white" id="createSupplierModalLabel">
                    <i class="ri-user-add-line me-2"></i> Novi dobavljač
                </h5>
                <button type="button" class="btn-close btn-close-white d-flex align-items-center me-2 text-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <i class="ri-building-4-line text-info fs-5 ms-1"></i> <label class="form-label"> Naziv firme</label>
                    <input type="text" class="form-control" id="supplier-name" placeholder="Unesite naziv firme">
                </div>
                <div class="mb-3">
                    <i class="ri-user-2-line text-info fs-5 ms-1"></i><label class="form-label">Vlasnik</label>
                    <input type="text" class="form-control" id="supplier-owner" placeholder="Unesite ime vlasnika">
                </div>
                <div class="mb-3">
                    <i class="ri-home-office-line text-info fs-5 ms-1"></i><label class="form-label">Adresa</label>
                    <input type="text" class="form-control" id="supplier-address" placeholder="Unesite adresu">
                </div>
                <div class="mb-3">
                    <i class="ri-discount-percent-line text-info fs-5 ms-1"></i><label class="form-label">PIB</label>
                    <input type="text" class="form-control" id="supplier-tax" placeholder="Unesite porezni broj">
                </div>
                <div class="mb-3">
                    <i class="ri-phone-line text-info fs-5 ms-1"></i><label class="form-label">Telefon</label>
                    <input type="text" class="form-control" id="supplier-phone" placeholder="Unesite broj telefona">
                </div>
                <div class="mb-3">
                    <i class="ri-mail-line text-info fs-5 ms-1"></i><label class="form-label">Email</label>
                    <input type="email" class="form-control" id="supplier-email" placeholder="Unesite email adresu">
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Otkaži</button>
                <button type="button" class="btn btn-info" id="saveSupplierBtn">Sačuvaj</button>
            </div>
        </div>
    </div>
</div>



<!--end row-->
@endsection
@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/invoicecreate.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


<script>
    let _invoice_data = null;
    let processedTariffData = [];
    let globalAISuggestions = [];

    function getInvoiceId() {
        const id = localStorage.getItem("scan_invoice_id");
        console.log(" Invoice ID:", id);
        return id;
    }

    function updateTotalAmount() {
        let total = 0;

        // Loop through all invoice rows
        document.querySelectorAll("#newlink tr.product").forEach(row => {
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
                    Authorization: `Bearer ${localStorage.getItem("auth_token")}`
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
                Authorization: `Bearer ${localStorage.getItem("auth_token")}`
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

    async function waitForAIResult() {
        const invoice_id = getInvoiceId();
        if (!invoice_id) return;

        Swal.fire({
            title: 'Skeniranje...',
            html: `<div class="custom-swal-spinner mb-3"></div><div id="swal-status-message">Čeka na obradu...</div>`,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        const stepTextMap = {
            null: "Čeka na početak obrade...",
            conversion: "Konvertovanje dokumenta...",
            extraction: "Ekstrakcija podataka...",
            enrichment: "Obogaćivanje podataka..."
        };

        while (true) {
            const res = await fetch(`/api/invoices/${invoice_id}/scan`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('auth_token')}`
                }
            });

            const json = await res.json();
            const status = json?.status?.status;
            const step = json?.status?.processing_step;

            console.log(" Scan status:", status, "| Step:", step);

            const el = document.getElementById("swal-status-message");
            if (el) {
                const message = stepTextMap[step] || "Obrađujemo podatke...";
                el.textContent = message;
            }

            if (status === "completed") {
                if (el) el.textContent = "Završeno";

                // Wait a bit to show "Završeno" before closing
                await new Promise(r => setTimeout(r, 1000));
                Swal.close();
                _invoice_data = null;
                break;
            }

            if (status === "error") {
                Swal.close();
                console.error("Scan error:", json?.status?.error_message);
                Swal.fire("Greška", json?.status?.error_message || "Greška pri skeniranju.", "error");
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

        document.getElementById('reset-form').addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Jesi li siguran/na?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Da',
                cancelButtonText: 'Ne',
                reverseButtons: true,
                focusCancel: true,
                confirmButtonColor: '#299dcb', // info color
                cancelButtonColor: '#6c757d' // bootstrap secondary gray
            }).then((result) => {
                if (result.isConfirmed) {
                    const table = document.getElementById('products-table');
                    if (table) {
                        const tbody = table.querySelector('tbody');
                        if (tbody) {
                            tbody.innerHTML = ''; // Remove all product rows
                        }
                    }
                    //location.reload();
                }
                // If cancelled, just closes and does nothing (no reopening)
            });
        });







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
                data-suggestions='${JSON.stringify(suggestions)}'>
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
            >
          </td>

          <td style="width: 70px;">
            <select class="form-select" name="origin[]">
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
                  type="button" 
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
              <div style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
                <button type="button" class="btn btn-danger btn-sm remove-row"   style="width: 30px;" title="Ukloni red" style="padding: 0">
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
            width: 'resolve'
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
    }


    async function promptForSupplierAfterScan() {
        const token = localStorage.getItem("auth_token");

        console.log(" Fetching suppliers...");
        const res = await fetch("/api/suppliers", {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const json = await res.json();
        const suppliers = json.data || [];
        console.log(" Suppliers loaded:", suppliers);

        if (!suppliers.length) {
            Swal.fire("Greška", "Nema dostupnih dobavljača.", "error");
            return;
        }

        let html = `<select id="supplierSelect" class="form-control">`;
        suppliers.forEach(s => {
            html += `<option value="${s.id}" data-name="${s.name}" data-address="${s.address}" data-tax="${s.tax_id}" data-phone="${s.contact_phone}" data-email="${s.contact_email}">
            ${s.name} (${s.owner})
        </option>`;
        });
        html += `</select>`;

        console.log(" Showing supplier selection prompt...");
        const {
            isConfirmed,
            isDismissed,
            value: selectedId
        } = await Swal.fire({
            title: "Odaberite dobavljača",
            html,
            showCancelButton: true,
            cancelButtonText: "Dodaj novog dobavljača",
            confirmButtonText: "Potvrdi",
            focusConfirm: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                const val = document.getElementById("supplierSelect").value;
                console.log(" Pre-confirm value:", val);
                return val;
            },
            customClass: {
                confirmButton: "btn btn-info me-2",
                cancelButton: "btn btn-info me-2"
            },
            buttonsStyling: false
        });

        if (isConfirmed && selectedId) {
            const selected = document.querySelector(`#supplierSelect option[value="${selectedId}"]`);
            if (selected) {
                console.log(" User selected existing supplier:", selected.dataset);
                document.getElementById("billing-name").value = selected.dataset.name || "";
                document.getElementById("billing-address-line-1").value = selected.dataset.address || "";
                document.getElementById("billing-tax-no").value = selected.dataset.tax || "";
                document.getElementById("billing-phone-no").value = selected.dataset.phone || "";
                document.getElementById("email").value = selected.dataset.email || "";
                localStorage.setItem("selected_supplier_id", selectedId);
                console.log(" selected_supplier_id set to:", selectedId);
            } else {
                console.warn(" Could not find selected option in DOM");
            }
            return;
        }

        if (isDismissed) {
            console.log(" User wants to create a new supplier");
            const modal = new bootstrap.Modal(document.getElementById("createSupplierModal"));
            modal.show();

            //  Ensure only one event listener is added
            const saveBtn = document.getElementById("saveSupplierBtn");
            saveBtn.replaceWith(saveBtn.cloneNode(true));
            document.getElementById("saveSupplierBtn").addEventListener("click", async () => {
                const name = document.getElementById("supplier-name").value.trim();
                const owner = document.getElementById("supplier-owner").value.trim();
                const address = document.getElementById("supplier-address").value.trim();
                const tax = document.getElementById("supplier-tax").value.trim();
                const phone = document.getElementById("supplier-phone").value.trim();
                const email = document.getElementById("supplier-email").value.trim();

                const payload = {
                    name,
                    owner,
                    address,
                    tax_id: tax,
                    contact_email: email,
                    contact_phone: phone,
                    avatar: ""
                };

                console.log(" Sending new supplier payload:", payload);

                try {
                    const response = await fetch("/api/suppliers", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${token}`
                        },
                        body: JSON.stringify(payload)
                    });

                    const responseText = await response.text();
                    console.log(" Raw response text:", responseText);

                    if (!response.ok) {
                        const err = JSON.parse(responseText);
                        console.error(" Supplier creation failed:", err);
                        Swal.fire("Greška", err?.error || "Neuspješno kreiranje dobavljača.", "error");
                        return;
                    }

                    const newSupplier = JSON.parse(responseText);
                    console.log(" Supplier created:", newSupplier);

                    // Fill invoice screen
                    document.getElementById("billing-name").value = newSupplier.data.name || "";
                    document.getElementById("billing-address-line-1").value = newSupplier.data.address || "";
                    document.getElementById("billing-tax-no").value = newSupplier.data.tax_id || "";
                    document.getElementById("billing-phone-no").value = newSupplier.data.contact_phone || "";
                    document.getElementById("email").value = newSupplier.data.contact_email || "";

                    //  Save ID to localStorage
                    localStorage.setItem("selected_supplier_id", newSupplier.data.id);

                    console.log(" Saved selected_supplier_id after creation:", newSupplier.data.id);

                    bootstrap.Modal.getInstance(document.getElementById("createSupplierModal"))?.hide();

                    Swal.fire({
                        icon: "success",
                        title: "Dobavljač kreiran",
                        text: "Podaci su automatski popunjeni.",
                        confirmButtonText: "U redu",
                        customClass: {
                            confirmButton: "btn btn-info"
                        },
                        buttonsStyling: false
                    });

                } catch (err) {
                    console.error(" Unexpected error:", err);
                    Swal.fire("Greška", "Neočekivana greška pri slanju podataka.", "error");
                }
            });
        }
    }







    document.addEventListener("DOMContentLoaded", async () => {
        console.log(" Page loaded. Starting init process...");

        const tariffRes = await fetch("{{ URL::asset('build/json/tariff.json') }}");
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

        const invoice = await getInvoice();
        if (invoice.task_id == null && await startAiScan()) {
            await waitForAIResult();
        } else if (!invoice.items?.length) {
            await waitForAIResult();
        }

        _invoice_data = null;

        await fillInvoiceData();
        await promptForSupplierAfterScan();
        $(document).on('click', '.show-ai-btn', function() {
            console.log(" AI button clicked");

            const select = $(this).closest('td').find('select.select2-tariff');
            const rawSuggestions = select.data("suggestions");
            if (!rawSuggestions || !Array.isArray(rawSuggestions)) return;

            const sorted = [...rawSuggestions].sort((a, b) => a.closeness - b.closeness).slice(0, 10);
            const container = document.getElementById("aiSuggestionsBody");

            if (!sorted.length) {
                container.innerHTML = `<div class="text-muted">Nema prijedloga.</div>`;
            } else {
                container.innerHTML = sorted.map((s, i) => `
      <div class="mb-2">
          <div><strong>${i + 1}. Tarifna oznaka:</strong> ${s.entry["Tarifna oznaka"]}</div>
          <div><strong>Naziv:</strong> ${s.entry["Naziv"]}</div>
          
          <button class="btn btn-sm btn-info mt-1 use-tariff" data-value="${s.entry["Tarifna oznaka"]}">Koristi ovu oznaku</button>
          <hr>
      </div>
    `).join("");
            }

            $('#aiSuggestionModal').data('target-select', select);
            const modal = new bootstrap.Modal(document.getElementById("aiSuggestionModal"));
            modal.show();
            console.log(" Bootstrap modal should be showing now");
        });
        $(document).on('click', '.use-tariff', function() {
            const code = $(this).data('value');
            const select = $('#aiSuggestionModal').data('target-select');

            if (select && code) {
                const matched = processedTariffData.find(item => item.id === code);
                if (matched) {
                    const option = new Option(matched.id, matched.id, true, true);
                    select.find('option').remove();
                    select.append(option).trigger('change');
                }
            }




            const modal = bootstrap.Modal.getInstance(document.getElementById("aiSuggestionModal"));
            if (modal) modal.hide();
            console.log(" Oznaka primijenjena i modal zatvoren");
        });
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateTotalAmount();
            // If you're recalculating total after row deletion
        });



        const invNo = getInvoiceId();
        if (invNo) document.getElementById("invoice-no1").value = invNo;

        document.getElementById("invoice-date").value = new Date().toISOString().split("T")[0];
        console.log(" Invoice date and number set.");
        document.getElementById("carrier-name").value = "Motorex";
        document.getElementById("carrier-address").value = "305 S San Gabriel Blvd";
        document.getElementById("carrier-tel").value = "+123 62 845 515"
        document.getElementById("carrier-email").value = "motorex@business.com";
        document.getElementById("carrier-tax").value = "12376597623461";

        document.getElementById("company-address").value = "Vilsonovo, 9, Sarajevo ";
        document.getElementById("company-zip").value = "71000";
        document.getElementById("company-email").value = "business@deklarant.ba";


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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('sidebar-buttons-container');
        const fixedButtons = document.getElementById('fixed-buttons');
        const topBarHeight = 70.8; // exact topbar height

        window.addEventListener('scroll', () => {
            if (window.scrollY >= topBarHeight) {
                fixedButtons.classList.add('detached-fixed-buttons');
            } else {
                fixedButtons.classList.remove('detached-fixed-buttons');
            }
        });

        document.getElementById("add-item")?.addEventListener("click", () => addRowToInvoice())
    });
</script>

<!-- Save logic script final -->
<script>
    document.getElementById("save-invoice-btn").addEventListener("click", async function(e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Spašavanje...`;

        try {
            const token = localStorage.getItem("auth_token");
            const userId = 3; // Or dynamically set it
            const supplierId = parseInt(localStorage.getItem("selected_supplier_id"));

            if (!supplierId || isNaN(supplierId)) {
                Swal.fire("Greška", "Nije odabran dobavljač.", "error");
                btn.disabled = false;
                btn.innerHTML = `<i class="ri-printer-line align-bottom me-1"></i> Sačuvaj`;
                return;
            }

            const items = [];
            document.querySelectorAll("#newlink tr.product").forEach((row, index) => {
                const item_name = row.querySelector('[name="item_name[]"]')?.value?.trim() || "";
                const item_description_original = item_name; // assume same as item_name input
                const item_code = row.querySelector('[name="item_code[]"]')?.value || "";
                const origin = row.querySelector('[name="origin[]"]')?.value || "";
                const base_price = parseFloat(row.querySelector('[name="price[]"]')?.value || "0");
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]')?.value || "0");
                const total_price = parseFloat((base_price * quantity).toFixed(2));
                const item_description = row.querySelector('[name="item_code[]"] option:checked')?.textContent || "";

                items.push({
                    item_name,
                    item_code,
                    item_description,
                    item_description_original,
                    origin,
                    base_price,
                    quantity,
                    total_price,
                    currency: "EUR",
                    version: new Date().getFullYear(),
                    best_customs_code_matches: globalAISuggestions[index]?.map(s => s.entry?.["Tarifna oznaka"])?.slice(0, 10) || []
                });
            });


            const payload = {
                file_name: "invoice_" + Date.now() + ".pdf",
                total_price: parseFloat(document.getElementById("total-amount")?.value || "0"),
                date_of_issue: document.getElementById("invoice-date")?.value,
                country_of_origin: document.getElementById("shipping-country")?.value || "Germany",
                invoice_id: getInvoiceId(),
                items,
                supplier: {
                    name: document.getElementById("billing-name")?.value,
                    address: document.getElementById("billing-address-line-1")?.value,
                    phone: document.getElementById("billing-phone-no")?.value,
                    tax: document.getElementById("billing-tax-no")?.value,
                    email: document.getElementById("email")?.value
                }
            };

            console.log(" Sending payload:", payload);

            const res = await fetch(`/api/invoices/users/${userId}/suppliers/${supplierId}/form`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });

            const resText = await res.text();
            let resJson;
            try {
                resJson = JSON.parse(resText);
            } catch {
                console.warn(" Response is not valid JSON:", resText);
            }

            if (!res.ok) {
                throw new Error(resJson?.error || "Greška pri spašavanju fakture.");
            }

            Swal.fire({
                icon: "success",
                title: "Uspješno",
                text: "Faktura je sačuvana.",
                confirmButtonText: "U redu",
                customClass: {
                    confirmButton: "btn btn-info"
                },
                buttonsStyling: false
            });

        } catch (err) {
            console.error(" Greška:", err);
            Swal.fire("Greška", err.message || "Neočekivana greška.", "error");
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class="ri-printer-line align-bottom me-1"></i> Sačuvaj`;
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

            document.querySelectorAll("#newlink tr.product").forEach(row => {
                invoiceData.items.push({
                    tarif: row.querySelector(".select2-tariff")?.value || "",
                    name: row.querySelector("input[name='item_name[]']")?.value || "",
                    translation: row.querySelector("input[name='item_translation[]']")?.value || "",
                    origin: row.querySelector("input[name='origin[]']")?.value || "",
                    price: row.querySelector("input[name='price[]']")?.value || "",
                    quantity: row.querySelector("input[name='quantity[]']")?.value || "",
                    total: row.querySelector("input[name='total[]']")?.value || "",
                });
            });

            // Build XML string
            let xml = `<invoice>\n`;
            invoiceData.items.forEach(item => {
                xml += `  <item>\n`;
                xml += `    <tarif>${item.tarif}</tarif>\n`;
                xml += `    <name>${item.name}</name>\n`;
                xml += `    <translation>${item.translation}</translation>\n`;
                xml += `    <origin>${item.origin}</origin>\n`;
                xml += `    <price>${item.price}</price>\n`;
                xml += `    <quantity>${item.quantity}</quantity>\n`;
                xml += `    <total>${item.total}</total>\n`;
                xml += `  </item>\n`;
            });
            xml += `  <subtotal>${invoiceData.subtotal}</subtotal>\n`;
            xml += `  <tax>${invoiceData.tax}</tax>\n`;
            xml += `  <discount>${invoiceData.discount}</discount>\n`;
            xml += `  <shipping>${invoiceData.shipping}</shipping>\n`;
            xml += `  <total>${invoiceData.total}</total>\n`;
            xml += `</invoice>`;

            // Create and download file
            const blob = new Blob([xml], {
                type: "application/xml"
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
    document.getElementById("export-pdf").addEventListener("click", function() {
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





@endsection