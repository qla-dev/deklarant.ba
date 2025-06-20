@extends('layouts.master')
@section('title')
@lang('translation.details')
@endsection
@section('css')
<style>
    .table> :not(caption)>*>* {
        color: inherit !important;
        background-color: transparent !important;
    }

    table.table {
        border: 1px solid !important;
        visibility: visible !important;
    }

    thead th {
        background: #f1f1f1;
        color: #000;
        border: 1px solid #ccc;
    }

    tbody td,
    tbody th {
        border: 1px solid #ccc;
        color: #333;
    }

    
  
</style>

</style>
@endsection
@section('content')

<head>
    <!-- other head content like meta, title, css etc. -->
    <base href="{{ url('/') }}/">
    <!-- rest of head -->
</head>



<div class="row justify-content-center mt-0 mb-3 content-desktop">


    <div class="card col paper-layout">
        <div class="row">

            <div class="col-lg-12">
                <div class="card-header border-0  p-4 pb-0">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <img src="{{ URL::asset('build/images/logo-light-ai.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="34">
                            <img src="{{ URL::asset('build/images/logo-dark-ai.png') }}" class="card-logo card-logo-light" alt="logo light" height="17">

                        </div>
                    </div>
                    <!--end card-header-->


                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4  ">
                        <div class="row ">

                            <!--end col-->
                            <div class="row">
                                <!-- LEFT: Osnovni podaci -->
                                <div class="col-6 col-md-3 col-mob">
                                    <h6 class="text-uppercase fw-semibold mb-3 mt-3">Moji podaci</h6>
                                    <p class="text-muted mb-1"><span>Ime firme: </span><span id="shipping-email">{{ Auth::user()->company['name'] ?? '' }}</span></p>
                                    <p class="text-muted mb-1"><span>JIB: </span><span id="shipping-vat">{{ Auth::user()->company['id'] ?? '' }}</span></p>
                                    <p class="text-muted mb-3"><span>Adresa: </span><span id="shipping-address">{{ Auth::user()->company['address'] ?? '' }}</span></p>
                                 <p class="fs-12 text-muted m-0">
                Ovo su informacije o tvojoj kompaniji. Možete ih uvijek prilagoditi na 
                <a href="/profil" class="text-info">pregledu svog profila.</a>
            </p>
                                </div>
 <!-- Indentation Column -->
    <div class="d-none d-md-block col-md-6 mobile-landscape-hide"></div>

                                <!-- RIGHT: Incoterm and Broj fakture stacked -->
                                <div class="col-6 col-md-3 col-mob text-end" style="padding-right: 0px;">
                                    
                                    <div class="mb-3 mt-3">
                                        <h6 class="text-uppercase fw-semibold">Broj fakture</h6>
                                        <h5 class="fs-14 mb-0 text-muted "><span id="invoice-no"></span></h5>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <h6 class="text-uppercase fw-semibold mt-4">Incoterm</h6>
                                        <h5 class="fs-14 mb-0 text-muted "><span id="incoterm" class=" text-muted" style="background: unset!important;"></span>-<span id="incoterm-destination" class=" text-muted" style="background: unset!important;"></span></h5>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <h6 class="text-uppercase fw-semibold">Bruto/Neto težina</h6>
                                        <h5 class="fs-14 mb-0 text-muted "><span id="total-weight-gross"></span>/<span id="total-weight-net"></span> kg</h5>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <h6 class="text-uppercase fw-semibold">Broj koleta</h6>
                                        <h5 class="fs-14 mb-0 text-muted "><span id="total-num-packages"></span></h5>
                                    </div>
                         <div class="mb-3 mt-3 d-none">
    <h6 class="text-uppercase fw-semibold">Vrijednost po koletu</h6>
    <h5 class="fs-14 mb-0 text-muted">
        <span id="q1-static">--</span> <span class="text-lowercase">KM</span>
    </h5>
</div>


                            
                


                                </div>
                            </div>


                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed ">
                        <div class="row pt-1">
                            <div class="col-6 text-start">
                                <h6 class="text-uppercase fw-semibold mb-3">Dobavljač</h6>
                                <p class="fw-medium mb-2" id="supplier-name"></p>
                                <p class="text-muted mb-1" id="supplier-address"></p>
                                <p class="text-muted mb-1"><span>Tel: </span><span id="supplier-phone"></span></p>
                                <p class="text-muted mb-1"><span>Email: </span><span id="supplier-email"></span></p>
                                <p class="text-muted mb-1"><span>Vlasnik: </span><span id="supplier-owner"></span></p>
                                <p class="text-muted mb-0"><span>JIB/VAT: </span><span id="supplier-tax"></span></p>

                            </div>
                            <!--end col-->
                            <div class="col-6 text-end">
                                <h6 class="text-uppercase fw-semibold mb-3">Klijent</h6>
                                <p class="fw-medium mb-2" id="carrier-name"></p>
                                <p class="text-muted mb-1" id="carrier-address-line-1"></p>
                                <p class="text-muted mb-1"><span>Tel:</span><span id="carrier-phone"></span></p>
                                <p class="text-muted mb-1"><span>Email: </span><span id="carrier-email"></span></p>
                                <p class="text-muted mb-1"><span>Vlasnik: </span><span id="carrier-owner"></span></p>
                                <p class="text-muted mb-1"><span>JIB/VAT: </span><span id="carrier-tax"></span></p>

                                <!-- Add incoterm here, top right -->

                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>

                <!--end col-->

                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="table-responsive pt-2">
                            <table id="invoiceTable" class="table table-borderless text-center table-nowrap align-middle mb-0 tabel-layout">
                                <thead>
                                    <tr class="table-active">
                                        <th>#</th>
                                        <th>Proizvod</th>
                                        <th>Opis</th>
                                        <th>Prevod</th>
                                        <th>Tarifna oznaka</th>
                                        <th>Tip kvantiteta</th>
                                        <th>Zemlja porijekla</th>
                                        <th>Povlastica</th>
                                       
                                        <th>Količina</th>
                                        <th>Bruto/Neto (kg)</th>
                                        <th>Koleta</th>
                                         <th>Cijena</th>

                                        <th style="border-right: 1px solid gray;">Ukupno</th>

                                    </tr>
                                </thead>
                                <tbody id="products-list">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <!--end card-body-->
                </div>

                <!--end col-->
            </div>
            <!--end row-->
        </div>

        <!--end card-->
    </div>

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






<!--end row-->
@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/invoicedetails.js') }}"></script>
<script src="{{ URL::asset('build/js/declaration/format-to-decimal.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ URL::asset('build/js/declaration/fix-sidebar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>






<!-- Declaration fill logic -->
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const invoiceId = window.location.pathname.split('/').pop();

        function waitForEl(selector, callback, interval = 50, timeout = 5000) {
            const startTime = Date.now();

            (function check() {
                const el = document.querySelector(selector);
                if (el) return callback(el);

                if (Date.now() - startTime > timeout) {
                    console.warn(`[waitForEl] Timeout waiting for ${selector}`);
                    return;
                }

                setTimeout(check, interval);
            })();
        }


        console.log("%c[DEBUG] invoiceId:", "color: #1e90ff", invoiceId);
        console.log("%c[DEBUG] token present:", "color: #1e90ff", !!token);

        if (!invoiceId || !token) {
            Swal.fire({
                icon: 'error',
                title: 'Greška',
                text: "Niste prijavljeni ili faktura nije definisana.",
            });
            return;
        }

        try {
            Swal.fire({
                title: 'Učitavanje deklaracije...',
                allowOutsideClick: false,
                html: '<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>',
                showConfirmButton: false,
            });

            console.log("%c[DEBUG] Fetching invoice data...", "color: orange");
            const invoiceRes = await fetch(`/api/invoices/${invoiceId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            waitForEl("#uredi", el => {
                el.disabled = false;
                el.addEventListener("click", () => {
                    window.location.href = `/deklaracija/${invoice.id}`;
                });
            });

            console.log("%c[DEBUG] invoiceRes.ok:", "color: orange", invoiceRes.ok);

            if (!invoiceRes.ok) throw new Error("Greška pri dohvatu deklaracije.");
            const invoice = await invoiceRes.json();

            console.log("%c[DEBUG] Invoice response:", "color: green", invoice);
            console.log("%c[DEBUG] Invoice items:", "color: green", invoice.items);

            // Currency logic
            const currencySymbols = {
                "EUR": "€",
                "USD": "$",
                "KM": "KM",
            };
            const currencies = [...new Set(invoice.items.map(item => item.currency))];
            let symbol = "KM";

            if (currencies.length === 1) {
                const code = currencies[0];
                symbol = currencySymbols[code] || code;
            } else if (currencies.length > 1) {
                symbol = "Multiple";
            }
console.log("%c[DEBUG] Filling invoice details", "color: teal");

// Format helper
function formatValue(val, suffix = '') {
    return (val ?? '') !== '' ? `${val}${suffix}` : '--';
}

document.getElementById("invoice-no").textContent = invoice.invoice_number ?? '--';

if (invoice.date_of_issue) {
    const date = new Date(invoice.date_of_issue);
    document.getElementById("invoice-date").textContent = date.toLocaleDateString('hr');
} else {
    document.getElementById("invoice-date").textContent = '--';
}

if (document.getElementById("total-1")) {
    const rawPrice = invoice.total_price ?? "0";
    const price = parseFloat(rawPrice.toString().replace(',', '.'));

    console.log("[DEBUG] total_price raw:", rawPrice);
    console.log("[DEBUG] total_price parsed:", price);

    const formatted = isNaN(price)
        ? '--'
        : `${price.toFixed(2).replace('.', ',')} ${symbol}`;

    document.getElementById("total-1").textContent = formatted;
}


if (document.getElementById("incoterm")) {
    document.getElementById("incoterm").textContent = invoice.incoterm ?? '--';
}

if (document.getElementById("incoterm-destination")) {
    document.getElementById("incoterm-destination").textContent = invoice.incoterm_destination ?? '--';
}

document.getElementById("total-weight-net").textContent = formatValue(invoice.total_weight_net);
document.getElementById("total-weight-gross").textContent = formatValue(invoice.total_weight_gross);
document.getElementById("total-num-packages").textContent = formatValue(invoice.total_num_packages);
document.getElementById("invoice-id").textContent = invoice.id ?? '--';
// q1-static logic
const q1Span = document.getElementById("q1-static");
const q1Hidden = document.getElementById("q1-estimate");

const totalAmount = parseFloat(invoice.total_price ?? 0);
const numPackages = parseFloat(invoice.total_num_packages ?? 0);
const q1 = numPackages > 0
  ? (numPackages / parseFloat(totalAmount.toString().replace(/[^\d.-]/g, ""))).toFixed(6)
  : "--";

if (q1Span) q1Span.textContent = q1;
if (q1Hidden) q1Hidden.value = numPackages > 0 ? q1 : "";


            const productsList = document.getElementById("products-list");
            console.log("%c[DEBUG] productsList exists:", "color: teal", !!productsList);

            // Fill product list
            if (!invoice.items || !invoice.items.length) {
                productsList.innerHTML = `
                    <tr>
                        <td colspan="12" class="text-center text-muted py-3">
                            Nema unesenih stavki u ovoj deklaraciji.
                        </td>
                    </tr>
                `;
            } else {
    productsList.innerHTML = '';
  const q1Value = parseFloat(
  document.getElementById("q1-static")?.textContent?.replace(',', '.') || "0"
);
    invoice.items.forEach((item, index) => {
        // Find best customs code match for Tarifna oznaka if not present
        let tarifnaOznaka = item.item_code || item.tariff_code || '';
        if (!tarifnaOznaka && Array.isArray(item.best_customs_code_matches) && item.best_customs_code_matches.length > 0) {
            const best = item.best_customs_code_matches.find(e => e.entry?.["Tarifna oznaka"]);
            if (best) {
                tarifnaOznaka = best.entry["Tarifna oznaka"];
            }
        }
    const q1Raw = document.getElementById("q1-static")?.textContent || "0";
const totalRaw = item.base_price * item.quantity || "0";

const q1 = parseFloat(q1Raw.replace(',', '.'));
const totalPrice = parseFloat(totalRaw.toString().replace(',', '.'));


const estimatedTotal = formatDecimal(q1 * totalPrice, 2);



        productsList.innerHTML += `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${item.item_description_original || item.item_description || '<span class="text-muted">Nepoznato</span>'}</td>
                <td>${item.item_description || '<span class="text-muted">Nepoznato</span>'}</td>
                <td>${item.item_description_translated || '<span class="text-muted">Nepoznato</span>'}</td>
                <td>${tarifnaOznaka || '<span class="text-muted">Nepoznato</span>'}</td>
                <td>${item.quantity_type || '<span class="text-muted">Nepoznato</span>'}</td>
                <td>${item.country_of_origin || ''}</td>
              <td>${item.tariff_privilege && item.tariff_privilege !== "0" ? item.tariff_privilege : ''}</td>
                <td>${item.quantity}</td>
                <td>${item.weight_gross || '0'}/${item.weight_net || '0'}</td>
                <td>${String(estimatedTotal).replace('.', ',')}</td>
                <td>${String(item.base_price).replace('.', ',')} ${item.currency || ''}</td>

               <td>${formatDecimal(item.total_price || (item.base_price * item.quantity))} ${item.currency || ''}</td> </tr>
        `;
    });
}


            // Fetch and fill supplier and importer data
            const supplierRes = await fetch(`/api/suppliers/${invoice.supplier_id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            const supplier = await supplierRes.json();
            // Fill supplier fields
            document.getElementById("supplier-name").textContent = supplier.name || "--";
            document.getElementById("supplier-address").textContent = supplier.address || "--";
            document.getElementById("supplier-phone").textContent = supplier.contact_phone || "--";
            document.getElementById("supplier-tax").textContent = supplier.tax_id || supplier.jib || "--";
            document.getElementById("supplier-email").textContent = supplier.email || supplier.contact_email || "--";
            document.getElementById("supplier-owner").textContent = supplier.owner || "--";

            // Fill importer/carrier fields if available
            if (invoice.importer_id) {
                const importerRes = await fetch(`/api/importers/${invoice.importer_id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                if (importerRes.ok) {
                    const importer = await importerRes.json();
                    document.getElementById("carrier-name").textContent = importer.name || "--";
                    document.getElementById("carrier-address-line-1").textContent = importer.address || "--";
                    document.getElementById("carrier-phone").textContent = importer.contact_phone || "--";
                    document.getElementById("carrier-tax").textContent = importer.tax_id || importer.jib || "--";
                    document.getElementById("carrier-email").textContent = importer.email || importer.contact_email || "--";
                    document.getElementById("carrier-owner").textContent = importer.owner || "--";

                }
            }

            Swal.close();

        } catch (err) {
            console.error("%c[ERROR] Exception occurred:", "color: red", err);
            Swal.fire({
                icon: 'error',
                title: 'Greška',
                text: 'Greška pri učitavanju fakture.',
            });
        }
    });
</script>





<!-- Export to csv -->








<script src="{{ URL::asset('build/js/declaration/export-view.js') }}"></script>
@endsection