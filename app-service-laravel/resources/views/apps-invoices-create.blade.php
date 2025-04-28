@extends('layouts.master')
@section('title')
@lang('translation.create-invoice')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
<!-- Sweet Alert css-->
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
@endslot
@slot('title')

@endslot
@endcomponent
<div id="invoice-form">
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card">
                <form class="needs-validation" novalidate id="invoice_form">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-4 d-flex justify-content-between">
                                <div>
                                    <img src="{{ URL::asset('build/images/logo.svg') }}" class="card-logo" alt="logo" height="30">
                                    <div class="mt-4">
                                        <h6 class="text-muted text-uppercase fw-semibold">Adresa</h6>
                                        <p class="text-muted mb-1" id="address-details">--</p>
                                        <p class="text-muted mb-0" id="zip-code"><span>Poštanski broj:</span> --</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6><span class="text-muted fw-normal">Email:</span> <span id="email">--</span></h6>
                                    <h6><span class="text-muted fw-normal">Web:</span> <a href="#" class="link-primary" target="_blank" id="website">--</a></h6>
                                    <h6 class="mb-0"><span class="text-muted fw-normal">Telefon:</span> <span id="contact-no">--</span></h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Faktura #</p>
                                        <h5 class="fs-14 mb-0">#<span id="invoice-no">--</span></h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Datum</p>
                                        <h5 class="fs-14 mb-0"><span id="invoice-date">--</span></h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Skenirana</p>
                                        <span class="badge bg-light text-dark fs-11" id="payment-status">--</span>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan iznos</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">--</span> KM</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card-body p-4 border-top border-top-dashed">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Dobavljač</h6>
                                        <p class="fw-medium mb-2" id="billing-name">--</p>
                                        <p class="text-muted mb-1" id="billing-address-line-1">--</p>
                                        <p class="text-muted mb-1"><span>Telefon: </span><span id="billing-phone-no">--</span></p>
                                        <p class="text-muted mb-0"><span>PIB: </span><span id="billing-tax-no">--</span></p>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Zemlja porijekla</h6>
                                        <p class="fw-medium mb-2" id="shipping-country">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="invoice-table table table-borderless table-nowrap mb-0">
                                        <thead class="align-middle">
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col">Product Details</th>
                                                <th scope="col" style="width: 120px;">Rate</th>
                                                <th scope="col" style="width: 120px;">Quantity</th>
                                                <th scope="col" class="text-end" style="width: 150px;">Amount</th>
                                                <th scope="col" class="text-end" style="width: 105px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="newlink"></tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="5">
                                                    <a href="javascript:void(0)" id="add-item" class="btn btn-soft-secondary fw-medium">
                                                        <i class="ri-add-fill me-1 align-bottom"></i> Add Item
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="border-top border-top-dashed mt-2">
                                                <td colspan="3"></td>
                                                <td colspan="2" class="p-0">
                                                    <table class="table table-borderless table-sm table-nowrap align-middle mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Cijena bez dodatnih troškova</th>
                                                                <td style="width:150px;"><input type="text" class="form-control bg-light border-0" id="cart-subtotal" placeholder="$0.00" readonly /></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Procijenjeni porez (12.5%)</th>
                                                                <td><input type="text" class="form-control bg-light border-0" id="cart-tax" placeholder="$0.00" readonly /></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Popust <small class="text-muted"></small></th>
                                                                <td><input type="text" class="form-control bg-light border-0" id="cart-discount" placeholder="$0.00" readonly /></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Troškovi dostave</th>
                                                                <td><input type="text" class="form-control bg-light border-0" id="cart-shipping" placeholder="$0.00" readonly /></td>
                                                            </tr>
                                                            <tr class="border-top border-top-dashed">
                                                                <th scope="row">Ukupan iznos</th>
                                                                <td><input type="text" class="form-control bg-light border-0" id="cart-total" placeholder="$0.00" readonly /></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card-body pt-0">
                                <div class="border-top border-top-dashed mt-2">
                                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                        <tbody>
                                            <tr class="border-top border-top-dashed fs-15">
                                                <th scope="row">Ukupno</th>
                                                <th class="text-end"><span id="modal-total-amount"></span> USD</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Detalji plaćanja:</h6>
                                    <p class="text-muted mb-1">Način plaćanja: <span class="fw-medium">Kartica</span></p>
                                    <p class="text-muted mb-1">Ime vlasnika kartice: <span class="fw-medium">Tin Tomić</span></p>
                                    <p class="text-muted mb-1">Broj kartice: <span class="fw-medium">xxxx xxxx xxxx 1234</span></p>
                                    <p class="text-muted">Ukupno za platiti: <span class="fw-medium"><span id="payment-method-amount">755.96</span> KM</span></p>
                                </div>

                                <div class="mt-4">
                                    <div class="alert alert-info">
                                        <p class="mb-0"><span class="fw-semibold">Napomena:</span> <span id="note">Račun je informativnog karaktera. Provjerite detalje prije plaćanja.</span></p>
                                    </div>
                                </div>
                                <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ri-printer-line align-bottom me-1"></i> Save
                                    </button>
                                    <a href="javascript:void(0);" id="export-pdf" class="btn btn-primary">
                                        <i class="ri-download-2-line align-bottom me-1"></i> Download Invoice
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger">
                                        <i class="ri-send-plane-fill align-bottom me-1"></i> Send Invoice
                                    </a>
                                    <a href="javascript:void(0);" id="export-xml" class="btn btn-success">
                                        <i class="ri-file-code-line align-bottom me-1"></i> Export to XML
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="aiSuggestionModal" tabindex="-1" aria-labelledby="aiSuggestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title d-flex align-items-center gap-2" id="aiSuggestionModalLabel">
                    <i class="fas fa-wand-magic-sparkles"></i> AI Prijedlozi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body" id="aiSuggestionsBody" style="padding: 1.5rem;">
                <div class="text-muted">Učitavanje prijedloga...</div>
                <!-- Prijedlozi će biti umetnuti ovdje putem JavaScript-a -->
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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

<script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/invoicecreate.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



<script>
    let processedTariffData = [];
    let disableAIPills = false;

    const select2Options = {
        placeholder: "Pretraži tarifne stavke...",
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            transport: function(params, success, failure) {
                const term = params.data.q?.toLowerCase() || "";
                const filtered = processedTariffData.filter(item =>
                    item.search.includes(term)
                );
                success({ results: filtered });
            },
            delay: 200
        },
        templateResult: function(item) {
            if (!item.id && !item.text) return null;
            const icon = item.isLeaf ? "•" : "▶";
            const label = item.display || item.text;
            return $(`<div style="padding-left:${item.depth * 20}px;">${icon} ${label}</div>`);
        },
        templateSelection: function(item) {
            return item.id ? `${item.id} – ${item.text}` : "";
        }
    };

    function initializeTariffSelects() {
        $('.select2-tariff').select2(select2Options);
    }

    function showAISuggestionsSwal(onSelect) {
        const aiItemsRaw = localStorage.getItem("ai_scan_result");
        if (!aiItemsRaw) {
            Swal.fire("Nema prijedloga", "Nema dostupnih AI prijedloga u lokalnoj memoriji.", "info");
            return;
        }

        const aiItems = JSON.parse(aiItemsRaw);
        if (!Array.isArray(aiItems) || aiItems.length === 0) {
            Swal.fire("Nema prijedloga", "Lista prijedloga je prazna.", "info");
            return;
        }

        let html = `<div class='d-flex flex-column'>`;
        aiItems.slice(0, 10).forEach((item, index) => {
            html += `<button type='button' data-index='${index}' class='btn btn-outline-info mb-2 ai-pill'>
                    ${item["Tarifna oznaka"]} – ${item["Naziv"]}
                </button>`;
        });
        html += `</div>`;

        Swal.fire({
            title: "AI prijedlozi",
            html: html,
            showConfirmButton: false,
            didOpen: () => {
                document.querySelectorAll('.ai-pill').forEach(btn => {
                    btn.addEventListener('click', e => {
                        const index = parseInt(e.target.getAttribute('data-index'));
                        onSelect(aiItems[index]);
                        Swal.close();
                    });
                });
            }
        });
    }

    function calculateInvoiceTotals() {
        const productRows = document.querySelectorAll("#newlink tr.product");
        let subtotal = 0;
        productRows.forEach(row => {
            const price = parseFloat(row.querySelector(".product-price")?.value || 0);
            const qty = parseInt(row.querySelector(".product-quantity")?.value || 0);
            subtotal += price * qty;
        });

        const taxRate = 0.125;
        const tax = subtotal * taxRate;
        const discount = 0;
        const shipping = 0;
        const total = subtotal + tax + shipping - discount;

        document.getElementById("cart-subtotal").value = `${subtotal.toFixed(2)} KM`;
        document.getElementById("cart-tax").value = `${tax.toFixed(2)} KM`;
        document.getElementById("cart-discount").value = `${discount.toFixed(2)} KM`;
        document.getElementById("cart-shipping").value = `${shipping.toFixed(2)} KM`;
        document.getElementById("cart-total").value = `${total.toFixed(2)} KM`;
    }

    function createInvoiceRow(id, item = {}) {
        const quantity = item.Quantity || 0;
        const unitPrice = item["Unit Price"] || 0;
        const total = (quantity * unitPrice).toFixed(2);

        const row = document.createElement("tr");
        row.classList.add("product");

        row.innerHTML = `
        <th scope="row" class="product-id align-middle">${id}</th>
        <td class="align-middle">
            <div class="d-flex align-items-center gap-2">
                <select class="form-control select2-tariff"></select>
                <button type="button" class="btn btn-info toggle-ai border-0 rounded-0" style="height:38px;">
                    <i class="fas fa-wand-magic-sparkles fs-6 me-2 text-white"></i> <span class="fs-6">Prijedlozi</span>
                </button>
            </div>
        </td>
        <td class="align-middle">
            <input type="number" class="form-control product-price text-end bg-light border-0 rounded-0 fs-6" value="${unitPrice}" step="0.01" style="height:38px;" required />
        </td>
        <td class="align-middle">
            <div class="input-step d-flex align-items-center justify-content-center">
                <button type="button" class="btn btn-light border minus" style="height:24px; width:24px;">–</button>
                <input type="number" class="product-quantity form-control fs-6 text-center mx-1 border-0 rounded-0" value="${quantity}" readonly style="height:38px; width:50px;">
                <button type="button" class="btn btn-light border plus" style="height:24px; width:24px;">+</button>
            </div>
        </td>
        <td class="align-middle text-end">
            <input type="text" class="form-control product-line-price text-end bg-light border-0 rounded-0 fs-6" value="${total} KM" readonly style="height:38px;" />
        </td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-outline-danger remove-row" style="height:38px;">X</button>
        </td>
    `;

        const select = row.querySelector(".select2-tariff");
        const toggleButton = row.querySelector(".toggle-ai");
        const priceInput = row.querySelector(".product-price");
        const quantityInput = row.querySelector(".product-quantity");
        const totalInput = row.querySelector(".product-line-price");

        if (disableAIPills && toggleButton) {
            toggleButton.disabled = true;
            toggleButton.setAttribute("title", "AI prijedlozi su dostupni samo kod automatskog popunjavanja.");
            toggleButton.setAttribute("data-bs-toggle", "tooltip");
            toggleButton.classList.add("disabled");
            bootstrap.Tooltip.getOrCreateInstance(toggleButton);
        }

        row.querySelector(".plus").addEventListener("click", () => {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            totalInput.value = `${(parseFloat(priceInput.value) * parseInt(quantityInput.value)).toFixed(2)} KM`;
            calculateInvoiceTotals();
        });

        row.querySelector(".minus").addEventListener("click", () => {
            const currentQty = parseInt(quantityInput.value);
            if (currentQty > 0) {
                quantityInput.value = currentQty - 1;
                totalInput.value = `${(parseFloat(priceInput.value) * parseInt(quantityInput.value)).toFixed(2)} KM`;
                calculateInvoiceTotals();
            }
        });

        priceInput.addEventListener("input", () => {
            totalInput.value = `${(parseFloat(priceInput.value) * parseInt(quantityInput.value)).toFixed(2)} KM`;
            calculateInvoiceTotals();
        });

        if (item["Tarifna oznaka"]) {
            const match = processedTariffData.find(p => p.id === item["Tarifna oznaka"]);
            if (match) {
                const option = new Option(match.text, match.id, true, true);
                $(select).append(option).trigger("change");
            }
        }

        toggleButton?.addEventListener("click", () => {
            showAISuggestionsSwal((aiItem) => {
                const match = processedTariffData.find(p => p.id === aiItem["Tarifna oznaka"]);
                if (match) {
                    const option = new Option(match.text, match.id, true, true);
                    $(select).append(option).trigger("change");
                    quantityInput.value = aiItem.Quantity || 0;
                    priceInput.value = aiItem["Unit Price"] || 0;
                    totalInput.value = `${(priceInput.value * quantityInput.value).toFixed(2)} KM`;
                    calculateInvoiceTotals();
                }
            });
        });

        row.querySelector(".remove-row").addEventListener("click", () => {
            row.remove();
            calculateInvoiceTotals();
        });

        return row;
    }

    function addRowToInvoice(item = {}) {
        const tbody = document.getElementById("newlink");
        const newId = tbody.children.length + 1;
        const row = createInvoiceRow(newId, item);
        tbody.appendChild(row);
        initializeTariffSelects();
        calculateInvoiceTotals();
    }

    document.addEventListener("DOMContentLoaded", function() {
        fetch('/storage/data/tariff.json')
            .then(res => res.json())
            .then(data => {
                processedTariffData = data
                    .filter(item => item["Puni Naziv"] && item["Tarifna oznaka"])
                    .map(item => {
                        const hierarchy = item["Puni Naziv"];
                        const parts = hierarchy.split(">>>").map(p => p.trim());
                        const leaf = parts[parts.length - 1];
                        const depth = parts.length - 1;
                        const code = item["Tarifna oznaka"];
                        const isLeaf = code && code.replace(/\s/g, '').length === 10;
                        return {
                            id: isLeaf ? code : null,
                            text: leaf,
                            display: `${code} – ${leaf}`,
                            depth: depth,
                            isLeaf: isLeaf,
                            hierarchy: hierarchy,
                            search: [item["Naziv"], hierarchy, code].join(" ").toLowerCase(),
                            full: item
                        };
                    });

                initializeTariffSelects();

                const aiResultRaw = localStorage.getItem("ai_scan_result");
                if (!aiResultRaw) return;

                const aiItems = JSON.parse(aiResultRaw);
                if (!Array.isArray(aiItems) || aiItems.length === 0) return;

                Swal.fire({
                    title: 'Automatski popuniti?',
                    text: 'Podaci o dobavljaču i fakturi će biti popunjeni automatski. Tarifne oznake i pripadajuće elemente možete unijeti ručno.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Koristi AI podatke',
                    cancelButtonText: 'Unos ručno'
                }).then((result) => {
                    document.getElementById("newlink").innerHTML = "";
                    if (result.isConfirmed) {
                        disableAIPills = false;
                        aiItems.forEach((item, i) => addRowToInvoice(item));
                    } else {
                        disableAIPills = true;
                        addRowToInvoice();
                    }
                });
            });

        document.getElementById("add-item").addEventListener("click", () => {
            addRowToInvoice();
        });
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
                subtotal: document.getElementById("cart-subtotal").value,
                tax: document.getElementById("cart-tax").value,
                discount: document.getElementById("cart-discount").value,
                shipping: document.getElementById("cart-shipping").value,
                total: document.getElementById("cart-total").value,
            };

            document.querySelectorAll("#newlink tr.product").forEach(row => {
                invoiceData.items.push({
                    tarif: row.querySelector(".select2-tariff")?.value || "",
                    price: row.querySelector(".product-price")?.value || "",
                    quantity: row.querySelector(".product-quantity")?.value || "",
                    amount: row.querySelector(".product-line-price")?.value || "",
                });
            });

            let xml = `<invoice>`;
            invoiceData.items.forEach(item => {
                xml += `
    <item>
        <tarif>${item.tarif}</tarif>
        <price>${item.price}</price>
        <quantity>${item.quantity}</quantity>
        <amount>${item.amount}</amount>
    </item>`;
            });
            xml += `
    <subtotal>${invoiceData.subtotal}</subtotal>
    <tax>${invoiceData.tax}</tax>
    <discount>${invoiceData.discount}</discount>
    <shipping>${invoiceData.shipping}</shipping>
    <total>${invoiceData.total}</total>
</invoice>`;

            const blob = new Blob([xml.trim()], {
                type: 'application/xml'
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