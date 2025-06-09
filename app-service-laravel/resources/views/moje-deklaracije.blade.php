@extends('layouts.master')
@section('title')
@lang('translation.invoices')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ai
@endslot
@slot('title')
Moje spašene deklaracije <span class="counter-value-invoice">0</span><span
    class="counter-value">/{{ Auth::user()->getActivePackageStats()->document_history ?? '0' }}</span>
@endslot
@endcomponent





<div class="row">
    <div class="col-lg-12">
        <div class=" card ribbon-box border mb-lg-4 position-relative rounded-0" id="invoiceList">
            <!-- Ribbon -->


            <div class="card-header border-0">
                <div class="d-flex align-items-center">

                </div>
            </div>

            <div class="card-body">
                <div id="invoice-loading" class="text-center my-4 mb-5">
                    <i class="mdi mdi-loading mdi-spin fs-24 text-info"></i>
                    <p class="text-muted">Učitavanje rezultata</p>
                </div>
                <div class="table-responsive table-card ms-1 me-1 mb-2" style="display:none" id="invoice-table-container">
                    <table id="invoiceTable" class="table  w-100">
                        <thead class="custom-table has-action">
                            <tr>
                                <th>ID</th>
                                <th>Ime dokumenta</th>
                               
                               
                                <th>Datum</th>
                                 <th>Zemlja porijekla</th>
                                <th>Dobavljač</th>
                                                                <th>Cijena</th>
                                 <th>Originalni dokument</th>

                                <th>Vlasnik</th>
                                <th style="padding-right: 20px!important;">Akcija</th>
                            </tr>
                        </thead>
                        <tbody class="table-light mb-1">
                            <!-- Loaded via DataTables -->
                        </tbody>
                    </table>
                </div>

                <div class="noresult " style="display: none">
                    <div class="text-center py-4">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#299cdb,secondary:#121331" style="width:75px;height:75px" delay="2000"></lord-icon>
                        <h5 class="mt-3">Nažalost! Nema rezultata</h5>
                        <p class="text-muted mb-0">Nismo pronašli nijednu fakturu prema vašem upitu.</p>
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
document.addEventListener("DOMContentLoaded", function () {
    if (!token || !user) {
        alert("Niste prijavljeni.");
        return;
    }

    console.log("Initializing DataTable for user:", user);

    const tableContainer = document.getElementById('invoice-table-container');
    const loader = document.getElementById('invoice-loading');

    const table = $('#invoiceTable').DataTable({
        ajax: {
            url: `/api/invoices/users/${user.id}`,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${token}`);
            },
            dataSrc: function (json) {
                if (loader) loader.remove();
                if (tableContainer) tableContainer.style.display = 'block';
                console.log("Fetched invoice data:", json);
                return json;
            }
        },
        scrollX: true,
        autoWidth: true,
        lengthChange: false,
        order: [[0, 'desc']],
        fixedColumns: { leftColumns: 1 },
        dom: '<"datatable-topbar d-flex flex-column flex-lg-row justify-content-between align-items-center mb-0 mb-md-4"fB>rt<"d-flex justify-content-between align-items-center mt-4 px-0"ip>',
        buttons: [
            {
                extend: 'csv',
                text: '<i class="ri-file-code-line align-bottom me-1"></i> Export u CSV',
                className: 'btn btn-soft-info me-1 rounded-1'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="ri-file-excel-line align-bottom me-1"></i> Export u Excel',
                className: 'btn btn-soft-info me-1 ms-1 rounded-1'
            },
            {
                extend: 'pdf',
                text: '<i class="ri-file-pdf-2-line align-bottom me-1"></i> Export u PDF',
                className: 'btn btn-soft-info me-1 ms-1 rounded-1'
            },
            {
                extend: 'print',
                text: '<i class="ri-printer-line align-bottom me-1"></i> Print',
                className: 'btn btn-soft-info me-1 ms-1 rounded-1'
            },
            {
                extend: 'colvis',
                text: 'Kolone',
                className: 'btn btn-soft-info me-1 ms-1 rounded-1'
            },
            {
                extend: 'pageLength',
                text: 'Prikaži redova',
                className: 'btn-soft-info me-1 ms-1 rounded-1'
            }
        ],
        columns: [
            { data: 'id', title: 'ID' },
            {
                data: 'file_name',
                title: 'Ime dokumenta',
                render: (data, type, row) => {
                    const name = data && data.length > 30 ? data.substring(0, 30) + '..' : data;
                    return `<a href="/deklaracija/${row.id}" target="_blank" rel="noopener noreferrer" class="text-info">${name}</a>`;
                }
            },
            {
                data: 'date_of_issue',
                title: 'Datum',
                render: data => new Date(data).toLocaleDateString('hr')
            },
            {
                data: 'country_of_origin',
                title: 'Zemlja porijekla',
                render: data => (!data || data === 'default')
                    ? '<span class="text-muted">Nepoznato</span>'
                    : data,
                defaultContent: '<span class="text-muted">Nepoznato</span>'
            },
            {
                data: 'supplier.name',
                title: 'Dobavljač',
                defaultContent: '<span class="text-muted">Nepoznato</span>'
            },
            {
                data: 'supplier.owner',
                title: 'Vlasnik',
                defaultContent: '<span class="text-muted">Nepoznato</span>'
            },
            {
                data: 'total_price',
                title: 'Cijena',
                render: data => `${parseFloat(data).toFixed(2)} KM`
            },
            {
                data: 'file_name',
                orderable: false,
                title: 'Originalni dokument',
                className: 'text-center',
                render: data => {
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
                    return `<span class="badge ${badgeMap[ext] || 'bg-secondary'} text-uppercase">${ext}</span>`;
                }
            },
            {
                data: null,
                title: 'Akcija',
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: row => `
                    <a class="btn btn-sm btn-soft-info me-1 view-invoice" href="/detalji-deklaracije/${row.id}" title="Pregledaj deklaraciju">
                        <i class="ri-eye-line"></i>
                    </a>
                    <a class="btn btn-sm btn-soft-info me-1 edit-invoice" href="/deklaracija/${row.id}" title="Uredi deklaraciju">
                        <i class="ri-edit-line"></i>
                    </a>
                    <button class="btn btn-sm btn-soft-danger delete-invoice" data-id="${row.id}" title="Obriši deklaraciju">
                        <i class="ri-delete-bin-line"></i>
                    </button>`
            }
        ],
        language: {
            paginate: {
                first: "←",
                last: "→",
                next: "→",
                previous: "←"
            },
            info: "Prikazivanje _START_ do _END_ od _TOTAL_ stavki",
            infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
            infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
            search: "",
            zeroRecords: "Nema pronađenih stavki"
        },
        initComplete: function () {
            const api = this.api();

            // Replace default filter with custom search UI
            $('#invoiceTable_filter')
                .addClass('flex-grow-1 me-0 order-0 order-lg-1')
                .css('max-width', '400px')
                .html(`
                    <div class="position-relative w-100">
                        <input type="text" class="form-control" placeholder="Pretraga..." autocomplete="off"
                            id="invoice-search-input" style="width: 100%; padding-left: 2rem;">
                        <span class="mdi mdi-magnify text-info fs-5 ps-2 position-absolute top-50 start-0 translate-middle-y"></span>
                        <span class="mdi mdi-close-circle position-absolute top-50 end-0 translate-middle-y me-2 d-none"
                            id="invoice-search-clear" style="cursor:pointer;"></span>
                    </div>
                `);

            // Apply order class to button group
            $('.dt-buttons').addClass('order-1 order-lg-0');

            const searchInput = $('#invoice-search-input');
            const searchClear = $('#invoice-search-clear');

            searchInput.on('input', function () {
                const val = $(this).val();
                console.log("🔍 Searching for:", val);
                api.search(val).draw();
                searchClear.toggleClass('d-none', val.length === 0);
            });

            searchClear.on('click', function () {
                searchInput.val('');
                api.search('').draw();
                $(this).addClass('d-none');
            });

            console.log("✅ Search input initialized.");
        }
    });

    // Update all invoice count placeholders
    table.on('xhr', function () {
        const data = table.ajax.json();
        const count = Array.isArray(data) ? data.length : 0;
        document.querySelectorAll('.counter-value-invoice').forEach(function (el) {
            el.textContent = count;
        });
    });
});
</script>







@endsection