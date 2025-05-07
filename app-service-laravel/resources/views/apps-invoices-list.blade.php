@extends('layouts.master')
@section('title')

@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    /* Style all pagination buttons */
    #invoiceTable_wrapper .dataTables_paginate .paginate_button {
        background-color: #2793ce;
        /* info background */
        color: #fff !important;
        /* info text */
        border-radius: 0.375rem;
        padding: 5px 12px;
        margin: 2px;
        transition: background-color 0.2s;

    }

    /* Hover state */
    #invoiceTable_wrapper .dataTables_paginate .paginate_button:hover {
        color: #fff;
        background-color: #2385ba;
        border-color: #2385ba;
        cursor: pointer;
    }

    /* Active page button */
    #invoiceTable_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #2385ba !important;
        color: white !important;
        font-weight: bold;
        border-color: #2385ba;
    }

    .ribbon {
        position: absolute;
        top: 12px;
        left: -12px;
        transform: rotate(-45deg);
        background-color: #0dcaf0;
        /* Bootstrap 'info' color */
        color: white;
        font-weight: bold;
        padding: 4px 40px;
        font-size: 12px;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .modal-dialog.modal-xl {
    max-width: 75vw; /* or set fixed px: 1200px, 1400px */
    }
</style>
@endsection
@section('content')





<div class="row">
    <div class="col-lg-12">
        <div class=" card ribbon-box border mb-lg-4 position-relative rounded-0" id="invoiceList">
            <!-- Ribbon -->
            <span class="ribbon-three ribbon-three-info  "><span>Fakture</span></span>

            <div class="card-header border-0">
                <div class="d-flex align-items-center">

                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card ms-1 me-1 mb-2">
                    <table id="invoiceTable" class="table mb-2 w-100">
                        <thead class="table-info">
                            <tr>
                                <th>ID</th>
                                <th>Moje fakture</th>
                                <th>Zemlja porijekla</th>
                                <th>Tip datoteke</th>
                                <th>Cijena</th>
                                <th>Datum</th>
                                <th>Skenirana</th>
                                <th>Dobavljač</th>
                                <th>Vlasnik</th>
                                <th>Akcija</th>
                            </tr>
                        </thead>
                        <tbody class="table-light">
                            <!-- Loaded via DataTables -->
                        </tbody>
                    </table>
                </div>

                <div class="noresult " style="display: none">
                    <div class="text-center py-4">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-3">Nažalost! Nema rezultata</h5>
                        <p class="text-muted mb-0">Nismo pronašli nijednu fakturu prema vašem upitu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pregled fakture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row justify-content-center">
                    <div class="col-xxl-9">
                        <div class="card" id="demo">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div
                                        class="card-header border-bottom-dashed p-4 d-flex justify-content-between">
                                        <div>
                                            <img src="{{ URL::asset('build/images/logo.svg') }}"
                                                class="card-logo" alt="logo" height="30">
                                            <div class="mt-4">
                                                <h6 class="text-muted text-uppercase fw-semibold">Adresa
                                                </h6>
                                                <p class="text-muted mb-1" id="address-details">--</p>
                                                <p class="text-muted mb-0" id="zip-code"><span>Poštanski
                                                        broj:</span> --</p>
                                            </div>
                                        </div>
                                        <div class="text-end">

                                            <h6><span class="text-muted fw-normal">Email:</span> <span
                                                    id="email">--</span></h6>
                                            <h6><span class="text-muted fw-normal">Web:</span> <a href="#"
                                                    class="link-primary" target="_blank" id="website">--</a>
                                            </h6>
                                            <h6 class="mb-0"><span
                                                    class="text-muted fw-normal">Telefon:</span> <span
                                                    id="contact-no">--</span></h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="card-body p-4">
                                        <div class="row g-3">
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">
                                                    Faktura #</p>
                                                <h5 class="fs-14 mb-0">#<span id="invoice-no">--</span></h5>
                                            </div>
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Datum
                                                </p>
                                                <h5 class="fs-14 mb-0"><span id="invoice-date">--</span>
                                                </h5>
                                            </div>
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">
                                                    Skenirana</p>
                                                <span class="badge bg-light text-dark fs-11"
                                                    id="payment-status">--</span>
                                            </div>
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan
                                                    iznos</p>
                                                <h5 class="fs-14 mb-0"><span id="total-amount">--</span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="card-body p-4 border-top border-top-dashed">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <h6 class="text-muted text-uppercase fw-semibold mb-3">
                                                    Dobavljač</h6>
                                                <p class="fw-medium mb-2" id="billing-name">--</p>
                                                <p class="text-muted mb-1" id="billing-address-line-1">--
                                                </p>
                                                <p class="text-muted mb-1"><span>Telefon: </span><span
                                                        id="billing-phone-no">--</span></p>
                                                <p class="text-muted mb-0"><span>PIB: </span><span
                                                        id="billing-tax-no">--</span></p>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="text-muted text-uppercase fw-semibold mb-3">
                                                    Zemlja porijekla</h6>
                                                <p class="fw-medium mb-2" id="shipping-country">--</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Invoice Items -->
                                <div class="col-lg-12">
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr class="table-active">
                                                        <th>#</th>
                                                        <th>Artikal</th>
                                                        <th>Opis</th>
                                                        <th>Cijena</th>
                                                        <th>Količina</th>
                                                        <th>Ukupno</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-list">
                                                    <!-- Dynamic rows will be inserted here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Totals -->
                                <div class="col-lg-12">
                                    <div class="card-body pt-0">
                                        <div class="border-top border-top-dashed mt-2">
                                            <table
                                                class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                                style="width:250px">
                                                <tbody>
                                                    <tr class="border-top border-top-dashed fs-15">
                                                        <th scope="row">Ukupno</th>
                                                        <th class="text-end"><span id="modal-total-amount">
                                                            </span> EUR </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-4">
                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Detalji
                                                plaćanja:</h6>
                                            <p class="text-muted mb-1">Način plaćanja: <span
                                                    class="fw-medium">Kartica</span></p>
                                            <p class="text-muted mb-1">Ime vlasnika kartice: <span
                                                    class="fw-medium">Tin Tomić</span></p>
                                            <p class="text-muted mb-1">Broj kartice: <span
                                                    class="fw-medium">xxxx xxxx xxxx 1234</span></p>
                                            <p class="text-muted">Ukupno za platiti: <span
                                                    class="fw-medium"><span
                                                        id="payment-method-amount">755.96</span> KM</span>
                                            </p>
                                        </div>

                                        <div class="mt-4">
                                            <div class="alert alert-info">
                                                <p class="mb-0"><span class="fw-semibold">Napomena:</span>
                                                    <span id="note">Račun je informativnog karaktera.
                                                        Provjerite detalje prije plaćanja.</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                            <a href="javascript:window.print()" class="btn btn-success">
                                                <i class="ri-printer-line align-bottom me-1"></i> Print
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-primary">
                                                <i class="ri-download-2-line align-bottom me-1"></i>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- row -->
                        </div> <!-- card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--end row-->
@endsection
@section('script')



<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem("auth_token");
        const user = JSON.parse(localStorage.getItem("user"));

        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        const table = $('#invoiceTable').DataTable({
            ajax: {
                url: `/api/invoices/users/${user.id}`,
                dataSrc: "",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", `Bearer ${token}`);
                }
            },
            scrollX: true,
            autoWidth: true,
            lengthChange: false,
            fixedColumns: {
                leftColumns: 1
            },
            columns: [{
                    data: null,
                    title: 'ID',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'file_name',
                    title: 'Moje fakture',
                    render: function(data, type, row) {
                        return `<a href="#" class="text-info view-invoice" data-id="${row.id}">${data}</a>`;
                    }
                },
                {
                    data: 'country_of_origin',
                    title: 'Zemlja porijekla'
                },
                {
                    data: 'file_name',
                    title: 'Tip datoteke',
                    render: function(data) {
                        if (!data) return '<span class="badge bg-secondary">N/A</span>';
                        const ext = data.split('.').pop().toLowerCase();
                        const badgeMap = {
                            pdf: 'bg-danger',
                            xls: 'bg-success',
                            xlsx: 'bg-success',
                            jpg: 'bg-warning',
                            jpeg: 'bg-warning',
                            png: 'bg-warning',
                            txt: 'bg-dark'
                        };
                        const badge = badgeMap[ext] || 'bg-secondary';
                        return `<span class="badge ${badge} text-uppercase">${ext}</span>`;
                    }
                },
                {
                    data: 'total_price',
                    title: 'Cijena fakture',
                    render: function(data) {
                        return `${parseFloat(data).toFixed(2)} KM`;
                    }
                },
                {
                    data: 'date_of_issue',
                    title: 'Datum',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('hr');
                    }
                },
                {
                    data: 'scanned',
                    title: 'Skenirana',
                    render: function(data) {
                        return data === 1 ? 'Da' : 'Ne';
                    }
                },
                {
                    data: 'supplier.name',
                    title: 'Dobavljač',
                    defaultContent: '<span class="text-muted">N/A</span>'
                },
                {
                    data: 'supplier.owner',
                    title: 'Vlasnik',
                    defaultContent: '<span class="text-muted">N/A</span>'
                },
                {
                    data: null,
                    title: 'Akcija',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                             <button class="btn btn-sm btn-soft-info me-1 edit-invoice" data-id="${row.id}">
                                 <i class="ri-edit-line"></i>
                             </button>
                             <button class="btn btn-sm btn-soft-danger delete-invoice" data-id="${row.id}">
                                 <i class="ri-delete-bin-line"></i>
                             </button>
                         `;
                    }
                }

            ],
            dom: '<"datatable-topbar d-flex flex-wrap justify-content-between align-items-center ms-0 mb-3"Bf>rt<"row mt-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [

                {
                    text: '<i class="fas fa-wand-magic-sparkles fs-6 me-1" style="font-size:10px;"></i> Skeniraj fakturu s AI',
                    className: 'btn btn-info me-1 ms-1 rounded-1',
                    action: function() {
                        window.location.href = '/pages-scan';
                    }
                },
                {

                    extend: 'csv',
                    text: 'Export u CSV',
                    className: 'btn btn-info me-1 ms-1 rounded-1',
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export u Excel',
                    className: 'btn btn-info me-1 ms-1 rounded-1',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Export u PDF',
                    className: 'btn btn-info me-1 ms-1 rounded-1',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Štampa',
                    className: 'btn btn-info me-1 ms-1 rounded-1',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Kolone',
                    className: 'btn btn-info me-1 ms-1 rounded-1',
                },
                {
                    extend: 'pageLength',
                    text: 'Prikaži redova',
                    className: 'btn-info me-1 ms-1 rounded-1'
                }
            ],
            language: {
                paginate: {
                    first: "Prva",
                    last: "Posljednja",
                    next: "Sljedeća",
                    previous: "Prethodna"
                },
                info: "",
                infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
                infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
                search: '<i class="ri-search-eye-line fs-5 me-1"></i>',
                zeroRecords: "Nema pronađenih stavki"
            }
        });

        // Style the search input
        $('#invoiceTable_filter')
            .addClass('d-flex justify-content-end ms-1 me-3 mb-2') // match table container
            .find('input')
            .addClass('form-control bg-light border-1 rounded-0 w-100') // control input style
            .attr('placeholder', 'Pretraga...');
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).on('click', '.view-invoice', function(e) {
            e.preventDefault();
            const invoiceId = $(this).data('id');
            const token = localStorage.getItem("auth_token");

            fetch(`/api/invoices/${invoiceId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(invoice => {
                    // Fetch supplier details
                    fetch(`/api/suppliers/${invoice.supplier_id}`, {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        })
                        .then(res => res.json())
                        .then(supplier => {
                            // Fill invoice data
                            $('#invoice-no').text(invoice.id);
                            $('#invoice-date').text(new Date(invoice.date_of_issue).toLocaleDateString('hr'));
                            $('#total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)} KM`);
                            $('#card-total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)}`);
                            $('#address-details').text(invoice.country_of_origin || '-');
                            $('#payment-status').text(invoice.scanned === 1 ? 'Skenirano' : 'Nije skenirano');
                            $('#shipping-country').text(invoice.country_of_origin || '--');

                            // Supplier info
                            $('#billing-name').text(supplier.name || '--');
                            $('#billing-address-line-1').text(supplier.address || '--');
                            $('#billing-phone-no').text(supplier.contact_phone || '--');
                            $('#billing-tax-no').text(supplier.tax_id || '--');
                            $('#email').text(supplier.contact_email || '--');

                            // Fill invoice items and calculate total
                            let itemsHTML = '';
                            let totalSum = 0;

                            invoice.items.forEach((item, index) => {
                                const price = parseFloat(item.total_price || 0);
                                totalSum += price;

                                itemsHTML += `
                                        <tr>
                                            <th scope="row">${index + 1}</th>
                                            <td class="text-start fw-medium">${item.item_description_original}</td> <!-- Artikal -->
                                            <td class="text-muted text-wrap" style="white-space: normal; word-break: break-word; max-width: 500px;">${item.item_description}</td> <!-- Opis -->
                                            <td>${item.base_price} ${item.currency}</td> <!-- Cijena -->
                                            <td>${item.quantity}</td> <!-- Količina -->
                                            <td class="text-end">${item.total_price} ${item.currency}</td> <!-- Ukupno -->
                                        </tr>`;
                            });

                            $('#products-list').html(itemsHTML);
                            $('#modal-total-amount').text(totalSum.toFixed(2));

                            // Show modal
                            const modal = new bootstrap.Modal(document.getElementById('invoiceDetailsModal'));
                            modal.show();
                        });
                })
                .catch(err => {
                    console.error('Greška pri učitavanju fakture:', err);
                    alert('Greška pri učitavanju fakture.');
                });
        });
    });
</script>


@endsection