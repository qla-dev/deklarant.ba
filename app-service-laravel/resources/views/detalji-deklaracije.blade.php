@extends('layouts.master')
@section('title')
@lang('translation.details')
@endsection
@section('css')
<style>
    .detached-fixed-buttons {

        position: fixed !important;
        top: calc(70.8px + 40.5px);
        /* 110.91px total offset */
        margin-top: 6px;
        width: 13.19vw;

        z-index: 1050;
    }

    .table> :not(caption)>*>* {
        color: inherit !important;
        background-color: transparent !important;
    }

    table.table {
        border: 1px solid  !important;
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



<div class="row justify-content-center mt-0 mb-3">


    <div class="card" id="demo">
        <div class="row">

            <div class="col-lg-12">
                <div class="card-header border-0  p-4">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <img src="{{ URL::asset('build/images/logo-dek.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="34">
                            <img src="{{ URL::asset('build/images/logo-light.png') }}" class="card-logo card-logo-light" alt="logo light" height="17">

                        </div>
                    </div>
                    <!--end card-header-->


                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4  ">
                        <div class="row g-4 mb-3 ">

                            <!--end col-->
                            <div class="col-12 text-start">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3 mt-2">Osnovni podaci</h6>
                                <p class="text-muted mb-1"><span>E-mail: </span><span id="shipping-phone-no">deklarant@skeniraj.ba</span></p>
                                <p class="text-muted mb-1"><span>Tel: </span><span id="shipping-phone-no">+(387) 63974234</span></p>
                                <p class="text-muted mb-1"><span>VAT: </span><span id="shipping-phone-no">BA12314519</span></p>
                                <p class="text-muted mb-1"><span>Adresa: </span><span id="shipping-phone-no">Vilsonovo 9, Sarajevo</span></p>

                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="row g-4 mb-3 ">
                            <div class="col-6 text-start">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Podaci o dobavljaču</h6>
                                <p class="fw-medium mb-2" id="supplier-name"></p>
                                <p class="text-muted mb-1" id="supplier-address"></p>
                                <p class="text-muted mb-1"><span>Tel: </span><span id="supplier-phone"></span></p>
                                <p class="text-muted mb-0"><span>Tax: </span><span id="supplier-tax"></span> </p>
                            </div>
                            <!--end col-->
                            <div class="col-6 text-end">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Podaci o uvozniku</h6>
                                <p class="fw-medium mb-2" id="carrier-name">SCHENKER</p>
                                <p class="text-muted mb-1" id="carrier-address-line-1">305 S San Gabriel Blvd</p>
                                <p class="text-muted mb-1"><span>Tel: +</span><span id="carrier-phone">(123)
                                        456-7890</span></p>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="row g-4 mb-3 justify-content-between align-items-center ">
                            <div class="col-lg-4 text-start">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Broj fakture:</p>
                                <h5 class="fs-14 mb-0">#<span id="invoice-no"> Učitavanje...</span></h5>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4 text-center">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Datum izdavanja</p>
                                <h5 class="fs-14 mb-0"><span id="invoice-date"></span> </h5>
                            </div>
                            <!--end col-->
                            <!--end col-->
                            <div class="col-lg-4 text-end">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan iznos:</p>
                                <h5 class="fs-14 mb-0" id="currency"><span id="total-1"> </span></h5>
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
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table id="invoiceTable" class="table table-borderless text-center table-nowrap align-middle mb-0">
                                <thead>
                                    <tr class="table-active">
                                        <th scope="col">#</th>
                                        <th scope="col">Proizvodi</th>
                                        <th scope="col">Prevod</th>
                                        <th scope="col">Tarifna oznaka</th>
                                        <th scope="col">Cijena</th>
                                        <th scope="col">Količina</th>
                                        <th scope="col">Zemlja porijekla</th>
                                        <th scope="col" class="text-end">Ukupna cijena</th>
                                    </tr>
                                </thead>
                                <tbody id="products-list">


                                </tbody>
                            </table>
                            <!--end table-->
                        </div>
                        <div class="border-top border-top-dashed mt-2">
                            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                <tbody class="border-bottom-dashed">

                                    <tr class="border-top border-top-dashed fs-15">
                                        <th scope="row">Ukupan iznos</th>
                                        <th class="text-end" id="total-amount"></th>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end table-->

                            </table>
                            <!--end table-->
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
    <div class="col-2 d-print-none" id="sidebar-buttons-container">
        <div id="fixed-buttons" class="d-flex flex-column gap-2">
            <a href="javascript:window.print()" class="btn btn-info">
                <i class="ri-printer-line align-bottom me-1"></i> Isprintaj deklaraciju
            </a>
            <a href="javascript:void(0);" class="btn btn-info">
                <i class="ri-download-2-line align-bottom me-1"></i> Preuzmi
            </a>
            <a href="javascript:void(0);" class="btn btn-info" id="export-xlsx">
                <i class="ri-file-excel-2-line align-bottom me-1"></i> Export tabele u CSV
            </a>
        </div>
    </div>

</div>


<!--end row-->
@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/invoicedetails.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


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
    });
</script>


<!-- Declaration fill logic -->
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const invoiceId = window.location.pathname.split('/').pop();
        const token = localStorage.getItem("auth_token");

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

            // Fill invoice details
            console.log("%c[DEBUG] Filling invoice details", "color: teal");
            document.getElementById("invoice-no").textContent = invoice.id;
            document.getElementById("invoice-date").textContent = new Date(invoice.date_of_issue).toLocaleDateString('hr');
            document.getElementById("total-1").textContent = ` ${symbol}${parseFloat(invoice.total_price).toFixed(2)}`;
            document.getElementById("total-amount").textContent = `${symbol} ${parseFloat(invoice.total_price).toFixed(2)}`;
            


            const productsList = document.getElementById("products-list");
            console.log("%c[DEBUG] productsList exists:", "color: teal", !!productsList);

            // Fill product list
            if (!invoice.items || !invoice.items.length) {
                console.warn("%c[DEBUG] No items found in invoice", "color: red");
                productsList.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Nema unesenih stavki u ovoj deklaraciji.
                        </td>
                    </tr>
                `;
            } else {
                console.log("%c[DEBUG] Rendering items in table...", "color: teal");
                productsList.innerHTML = '';
                invoice.items.forEach((item, index) => {
                    console.log(`%c[DEBUG] Rendering item #${index + 1}`, "color: gray", item);
                    productsList.innerHTML += `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td class="text-start">
                                <span class="fw-medium">${item.item_description_original}</span>
                                <p class="text-muted mb-0">${item.item_description}</p>
                            </td>
                            <td>Nema prevoda</td>
                            <td>${item.item_code}</td>
                            <td>${item.base_price} ${item.currency}</td>
                            <td>${item.quantity}</td>
                            <td>${invoice.country_of_origin || '--'}</td>
                            <td class="text-end">${item.total_price} ${item.currency}</td>
                        </tr>
                    `;
                });
                console.log("%c[DEBUG] Final productsList.innerHTML:", "color: violet", productsList.innerHTML);

            }

            // Fetch supplier
            console.log("%c[DEBUG] Fetching supplier...", "color: orange");
            const supplierRes = await fetch(`/api/suppliers/${invoice.supplier_id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            console.log("%c[DEBUG] supplierRes.ok:", "color: orange", supplierRes.ok);

            if (!supplierRes.ok) throw new Error("Greška pri dohvatu dobavljača.");
            const supplier = await supplierRes.json();

            console.log("%c[DEBUG] Supplier data:", "color: green", supplier);

            document.getElementById("supplier-name").textContent = supplier.name || "--";
            document.getElementById("supplier-address").textContent = supplier.address || "--";
            document.getElementById("supplier-phone").textContent = supplier.contact_phone || "--";
            document.getElementById("supplier-tax").textContent = supplier.tax_id || "--";

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


<!-- Export to xslx -->
<script>
    document.getElementById("export-xlsx").addEventListener("click", function () {
        const table = document.getElementById("invoiceTable");
        if (!table) {
            alert("Tabela nije pronađena!");
            return;
        }

        // 1. Convert table to worksheet
        const ws = XLSX.utils.table_to_sheet(table);

        // 2. Append "Ukupan iznos" row manually
        const totalAmount = document.getElementById("total-amount")?.innerText || "--";

        const sheetData = XLSX.utils.sheet_to_json(ws, { header: 1 }); // get 2D array of data
        sheetData.push([]); // empty spacer row
        sheetData.push(["", "", "", "", "", "Ukupan iznos:", totalAmount]); // add summary row

        const newWs = XLSX.utils.aoa_to_sheet(sheetData);

        // 3. Create workbook and export
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, newWs, "Faktura");

        XLSX.writeFile(wb, "faktura.xlsx");
    });
</script>



@endsection