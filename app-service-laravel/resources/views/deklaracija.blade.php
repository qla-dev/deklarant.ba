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
            text-align: right !important;
        }

        #total-weight-gross::placeholder {
            text-align: center !important;
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
                    <img src="{{ URL::asset('build/images/logo-light-ai.png') }}" class="card-logo card-logo-dark"
                        alt="logo dark" height="34">
                    <img src="{{ URL::asset('build/images/logo-dark-ai.png') }}" class="card-logo card-logo-light"
                        alt="logo light" height="17">
                    <div class="row g-4 justify-content-between py-4">
                        <div class="col-6 col-md-3 col-mob">
                            <div class="mt-4">
                                <h6 class="text-muted text-uppercase fw-semibold">Moji podaci</h6>
                                <input type="text" class="form-control mb-2" id="company-name" name="name"
                                    placeholder="Ime kompanije" disabled value="{{ Auth::user()->company['name'] ?? '' }}">
                                <input type="text" class="form-control mb-2" id="company-id" name="id"
                                    placeholder="ID kompanije" disabled value="{{ Auth::user()->company['id'] ?? '' }}">
                                <input type="email" class="form-control mb-2" id="company-address" name="address"
                                    placeholder="Adresa" disabled value="{{ Auth::user()->company['address'] ?? '' }}">
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
                                <input type="text" class="form-control" id="invoice-no" name="invoice_no"
                                    placeholder="Unesite broj fakture">
                            </div>

                            <div style="margin-top: 1.85rem;">
                                <h6 class="text-muted text-uppercase fw-semibold mt-1">Incoterm</h6>

                                <div class="d-flex gap-2">
                                    <select class="form-select mb-2 custom-select-icon incoterm2" name="incoterm"
                                        id="incoterm">
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
                                    <input type="text" id="incoterm-destination" class="form-control mb-2"
                                        placeholder="Destinacija">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 border-top border-top-dashed">
                    <div class="row g-4">
                        <div class="col-6 text-start">

                            <h6 class="text-muted text-uppercase fw-semibold mb-3">pošiljatelj</h6>

                            <div class="mb-2">
                                <div style="display: flex;">
                                    <button type="button" class="btn btn-sm btn-info mb-2 me-2 deklaracija-action-buttons"
                                        id="add-new-supplier"><i
                                            class="fas fa-wand-magic-sparkles fs-6 me-0 me-lg-1"></i><span
                                            class="mobile-landscape-hide">Detektovani pošiljatelj iz baze</span></button>
                                    <button type="button" class="btn btn-sm btn-soft-info mb-2 deklaracija-action-buttons"
                                        id="refill-supplier-ai"><i
                                            class="fa-regular fa-hand align-top me-0 me-lg-1 korpica"></i><span
                                            class="mobile-landscape-hide">Ručni unos pošiljatelja</span></button>
                                </div>
                                <select id="supplier-select2" class="form-select"></select>
                            </div>
                            <input type="text" class="form-control mb-2" id="billing-name" name="supplier_name"
                                placeholder="Naziv pošiljatelja">

                            <input type="text" class="form-control mb-2" id="billing-address-line-1" name="supplier_address"
                                placeholder="Adresa pošiljatelja">
                            <input type="text" class="form-control mb-2" id="billing-phone-no" name="supplier_phone"
                                placeholder="Telefon pošiljatelja">
                            <input type="text" class="form-control mb-2" id="billing-tax-no" name="supplier_tax"
                                placeholder="VAT pošiljatelja">
                            <input type="email" class="form-control mb-2" id="email" name="email"
                                placeholder="Email dobavljača">
                            <input type="email" class="form-control" id="supplier-owner" name="supplierOwner"
                                placeholder="Vlasnik kompanije">
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="text-muted text-uppercase fw-semibold mb-3 text-end">Primatelj</h6>

                            <div class="mb-2">
                                <div style="justify-content: end; display: flex;">
                                    <button type="button"
                                        class="btn btn-sm btn-soft-info mb-2  me-2  deklaracija-action-buttons"
                                        id="refill-importer-ai"><i
                                            class="fa-regular fa-hand align-top me-0 me-lg-1 korpica"></i><span
                                            class="mobile-landscape-hide">Ručni unos primatelja</span></button>
                                    <button type="button" class="btn btn-sm btn-info mb-2 deklaracija-action-buttons"
                                        id="add-new-importer"><i
                                            class="fas fa-wand-magic-sparkles fs-6 me-0 me-lg-1"></i><span
                                            class="mobile-landscape-hide">Detektovani primatelj iz baze</span></button>

                                </div>

                                <select id="importer-select2" class="form-select"></select>
                            </div>

                            <input type="text" class="form-control mb-2 text-end" id="carrier-name" name="dobavljačime"
                                placeholder="Naziv primatelja">

                            <input type="text" class="form-control mb-2 text-end" id="carrier-address" name="klijentadresa"
                                placeholder="Adresa primatelja">
                            <input type="text" class="form-control mb-2 text-end" id="carrier-tel" name="klijenttel"
                                placeholder="Telefon primatelja">
                            <input type="text" class="form-control mb-2 text-end" id="carrier-tax" name="klijenttel"
                                placeholder="JIB primatelja">
                            <input type="text" class="form-control mb-2 text-end" id="carrier-email" name="klijenttel"
                                placeholder="Email primatelja">
                            <input type="text" class="form-control mb-2 text-end" id="carrier-owner" name="carrierOwner"
                                placeholder="Vlasnik kompanije">

                        </div>
                    </div>
                </div>

                <div class="card-body p-4 border-top border-top-dashed">
                    <div class="row g-4 mb-3">
                        <div class="col-4 text-start">
                            <label class="text-muted text-uppercase fw-semibold mb-1"># Deklaracije</label>
                            <input type="text" class="form-control" id="invoice-no1" name="invoice_no1"
                                placeholder="Broj fakture" disabled>
                        </div>
                        <div class="col-4 text-center">
                            <label
                                class="d-flex justify-content-center text-muted text-uppercase fw-semibold mb-1">Datum</label>
                            <input type="text" class="form-control text-center" id="invoice-date" name="invoice_date">
                        </div>
                        <div class="col-4 text-end">
                            <label class="text-muted text-uppercase fw-semibold mb-1">Ukupan iznos</label>
                            <input type="text" class="form-control text-end" id="total-amount" name="total_amount"
                                placeholder="0.00 KM" disabled oninput="updateEstimates()">
                        </div>
                    </div>
                    <!-- Added fields -->
                    <div class="row g-4">
                        <div class="col-4 text-start">
                            <label class="text-muted text-uppercase fw-semibold mb-1">Neto (kg)</label>
                            <input type="text" step="0.01" class="form-control" id="total-weight-net"
                                name="total_weight_net" placeholder="0,00 kg" oninput="updateEstimates()">
                        </div>
                        <div class="col-4 text-center">
                            <label class="d-flex justify-content-center text-muted text-uppercase fw-semibold mb-1">Bruto
                                (kg)</label>
                            <input type="text" step="0.01" class="form-control text-center" id="total-weight-gross"
                                name="total_weight_gross" placeholder="0,00 kg" oninput="updateEstimates()">
                        </div>
                        <div class="col-4 text-end">
                            <label class="text-muted text-uppercase fw-semibold mb-1">Broj koleta</label>
                            <input type="text" class="form-control text-end decimal-input" id="total-num-packages"
                                name="total_num_packages" placeholder="0,00" oninput="updateEstimates()">
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 border-top border-top-dashed">
                    <div class="table-responsive">
                        <table class="table table-borderless text-center table-nowrap align-middle mb-0"
                            id="products-table">
                            <thead>
                                <tr>
                                    <th class="dark-remove-bg"
                                        style="width: 20px;vertical-align: middle; text-align: left; background: #f3f3f9!">
                                        Poz<br>
                                        <small style="font-weight: normal; font-size: 0.75rem; color: #666;">
                                            BRN
                                        </small>
                                    </th>
                                    <th class="dark-remove-bg"
                                        style="width: 200px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; padding-right: 50px!important; background: #f3f3f9!">
                                        Proizvod </th>
                                    <th class="dark-remove-bg"
                                        style="width: 140px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; margin-left: -5px!important; background: #f3f3f9!">
                                        Opis </th>
                                    <th class="th-tarifa dark-remove-bg"
                                        style="width: 400px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; background: #f3f3f9!">
                                        Tarifna oznaka</th>
                                    <th class="dark-remove-bg"
                                        style="width: 50px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; background: #f3f3f9!">
                                        Jed. mjere</th>
                                    <th class="dark-remove-bg"
                                        style="width:120px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; background: #f3f3f9!">
                                        Porijeklo/Pov..</th>

                                    <th class="dark-remove-bg"
                                        style="width:100px; text-align: center;vertical-align: middle; padding-bottom: 1rem; background: #f3f3f9!">
                                        Koleta</th>

                                    <th class="dark-remove-bg"
                                        style="width: 100px; text-align: center;vertical-align: middle; background: #f3f3f9!">
                                        Bruto (kg)<br>
                                        <small style="font-weight: normal; font-size: 0.75rem; color: #666;">
                                            Neto (kg)
                                        </small>
                                    </th>
                                    <th class="dark-remove-bg"
                                        style="width: 100px; text-align: center;vertical-align: middle; background: #f3f3f9!">
                                        Količina<br>

                                    </th>

                                    <th class="dark-remove-bg"
                                        style="width:100px; text-align: center;vertical-align: middle; padding-bottom: 1rem; background: #f3f3f9!">
                                        Cijena</th>
                                    <th class="dark-remove-bg"
                                        style="width:100px;vertical-align: middle; text-align: middle; padding-bottom: 1rem; background: #f3f3f9!">
                                        Ukupno</th>
                                    <th class="dark-remove-bg"
                                        style="width:20px;vertical-align: middle; text-align: end; background:unset!important; border: 1px solid transparent;">

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
    <div class="content-mobile-warning d-none d-flex flex-column align-items-center justify-content-center text-center"
        style="height: 70vh;">
        <!-- Light theme icon -->
        <lord-icon id="rotate-icon-light" src="/build/images/rotate-phone.json" trigger="loop" colors="secondary:#299cdb"
            style="width:80px;height:80px;margin-bottom: 1rem;">
        </lord-icon>

        <!-- Dark theme icon -->
        <lord-icon id="rotate-icon-dark" src="/build/images/rotate-phone-dark.json" trigger="loop"
            colors="secondary:#299cdb" style="width:80px;height:80px;margin-bottom: 1rem; display: none;">
        </lord-icon>

        <div>
            <strong class="d-block mb-1">Molimo okreni uređaj horizontalno</strong>
            <span class="text-muted">da bi pristupio prikazu deklaracije</span>
        </div>
    </div>

    <div class="modal fade" id="aiSuggestionModal" tabindex="-1" aria-labelledby="aiSuggestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title d-flex align-items-center gap-2 text-white" id="aiSuggestionModalLabel">
                        <i class="fas fa-wand-magic-sparkles text-white"></i> Najbolji AI Prijedlozi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Zatvori"></button>
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
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    <script>
        let EditingMode = {{ isset($id) ? 'true' : 'false' }};
        window.remaining_scans = @json(Auth:: user() -> getRemainingScans());
        // Define and expose global_invoice_id globally
        if (typeof window !== "undefined") {
            if (!EditingMode) {
                window.global_invoice_id = localStorage.getItem("scan_invoice_id");
            } else {
                window.global_invoice_id = {!! isset($id) ? json_encode($id) : 0 !!};
            }
        }
        window.global_tariff_path = "{{ URL::asset('build/json/tariff.json') }}";
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

    <script src="{{ URL::asset('build/js/declaration/declaration-utils.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/country-selector.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/date-picker.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/select-autofocus.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/loading-overlay.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/decimal-regex.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/tariff-privilege.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/export-edit.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/swal-declaration-load.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/slot-number.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/auto-save.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/weight-estimation.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/importer-supplier.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/init.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/runtime.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/row.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/tariff-selector.js') }}"></script>
    <script src="{{ URL::asset('build/js/declaration/validate-weight.js') }}"></script>
@endsection