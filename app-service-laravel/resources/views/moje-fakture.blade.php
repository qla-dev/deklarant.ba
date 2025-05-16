@extends('layouts.master')
@section('title')
@lang('translation.invoices')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    #invoiceTable_wrapper .dataTables_paginate {
        overflow-x: hidden;
        /* enable scrolling only if needed */
        flex-wrap: nowrap;
        /* prevent wrapping */
        white-space: nowrap;
        display: flex;
        /* enable flex layout */
        justify-content: end;
        align-items: center;
        margin-top: 4px !important;
        padding-top: 0 !important;
        padding-right: 0px;
        /* option
        
        /* Optional: space between buttons */



    }

    #invoiceTable_wrapper .dataTables_paginate .paginate_button {
        background-color: #fff;
        color: #299cdb !important;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        margin: 0 2px;
        font-size: 0.8125rem;
        width: 38px;
        height: 38px;

        display: inline-flex;
        /* ← fixes stacking + centers content */
        align-items: center;
        justify-content: center;

        padding: 0;
        transition: all 0.2s ease-in-out;
        margin-top: 4px !important;
        /* reduce top gap */
        padding-top: 0 !important;
        padding-bottom: 0 !important;

    }

    #invoiceTable_wrapper .table {
        margin-bottom: 0 !important;
    }


    #invoiceTable_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #eff2f7;

        color: #299cdb !important;
        cursor: pointer;
    }

    #invoiceTable_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #299cdb !important;
        color: #fff !important;
        border-color: #299cdb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);

    }

    #invoiceTable_wrapper .dataTables_paginate .paginate_button:focus {
        outline: none;
        box-shadow: none;
    }

    #invoiceTable_wrapper .dataTables_paginate .paginate_button.disabled {
        background-color: #eff2f7;
        color: #adb5bd !important;
        /* light gray text */
        border-color: #dee2e6;
        cursor: not-allowed;
        pointer-events: none;
        font-weight: bold;

        cursor: pointer;
    }

    #invoiceTable_wrapper .dataTables_paginate .paginate_button.previous,
    #invoiceTable_wrapper .dataTables_paginate .paginate_button.next,
    #invoiceTable_wrapper .dataTables_paginate .paginate_button.first,
    #invoiceTable_wrapper .dataTables_paginate .paginate_button.last {
        width: 50px;
        /* or whatever width you want */
    }

    #invoiceTable_wrapper .dataTables_info {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    #invoiceTable_wrapper .dataTables_paginate {
        margin-top: 4px !important;
        /* reduce from default ~16px */
        padding-top: 0 !important;
    }

    #invoiceTable_wrapper .dataTables_length,
    #invoiceTable_wrapper .dataTables_filter {
        margin-bottom: 4px !important;
        padding-bottom: 0 !important;

    }

    .table-card .dataTables_filter {
        padding: 0 !important;
        margin: 0 !important;
        /* optional for tighter alignment */
    }

    #invoiceTable_wrapper .dataTables_filter input {
        margin-left: 0 !important;
    }

    #invoiceList {
    --vz-card-spacer-y: 10px; /* or any smaller value you want */
    }
    .table-card .dataTables_info{
        padding-left: 0 !important;
    }






    .modal-dialog.modal-xl {
        max-width: 75vw;
        /* or set fixed px: 1200px, 1400px */
    }

   

</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ba
@endslot
@slot('title')
Moje spašene deklaracije
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
                <div class="table-responsive table-card ms-1 me-1 mb-0">
                    <table id="invoiceTable" class="table  w-100">
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
                        <tbody class="table-light mb-1">
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
        const token = localStorage.getItem("auth_token");
        const user = JSON.parse(localStorage.getItem("user"));

        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        console.log("Initializing DataTable for user:", user);

        const table = $('#invoiceTable').DataTable({
            ajax: {
                url: `/api/invoices/users/${user.id}`,
                dataSrc: function (json) {
                    console.log("Fetched invoice data:", json);
                    return json;
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Authorization", `Bearer ${token}`);
                }
            },
            scrollX: true,
            autoWidth: true,
            lengthChange: false,
            fixedColumns: {
                leftColumns: 1
            },
            columns: [
                { data: null, title: 'ID', render: (data, type, row, meta) => meta.row + 1 },
                {
                    data: 'file_name',
                    title: 'Moje fakture',
                    render: (data, type, row) => `<a href="/detalji-deklaracije/${row.id}" target="_blank" rel="noopener noreferrer" class="text-info">${data}</a>`

                },
                { data: 'country_of_origin', title: 'Zemlja porijekla' },
                {
                    data: 'file_name',
                    title: 'Tip datoteke',
                    render: function (data) {
                        if (!data) return '<span class="badge bg-secondary">N/A</span>';
                        const ext = data.split('.').pop().toLowerCase();
                        const badgeMap = {
                            pdf: 'bg-danger', xls: 'bg-success', xlsx: 'bg-success',
                            jpg: 'bg-warning', jpeg: 'bg-warning', png: 'bg-warning', txt: 'bg-dark'
                        };
                        return `<span class="badge ${badgeMap[ext] || 'bg-secondary'} text-uppercase">${ext}</span>`;
                    }
                },
                { data: 'total_price', title: 'Cijena fakture', render: data => `${parseFloat(data).toFixed(2)} KM` },
                { data: 'date_of_issue', title: 'Datum', render: data => new Date(data).toLocaleDateString('hr') },
                { data: 'scanned', title: 'Skenirana', render: data => data === 1 ? 'Da' : 'Ne' },
                { data: 'supplier.name', title: 'Dobavljač', defaultContent: '<span class="text-muted">N/A</span>' },
                { data: 'supplier.owner', title: 'Vlasnik', defaultContent: '<span class="text-muted">N/A</span>' },
                {
                    data: null, title: 'Akcija', orderable: false, searchable: false, className: 'text-center',
                    render: row => `
                        <button class="btn btn-sm btn-soft-info me-1 edit-invoice" data-id="${row.id}">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="btn btn-sm btn-soft-danger delete-invoice" data-id="${row.id}">
                            <i class="ri-delete-bin-line"></i>
                        </button>`
                }
            ],
            dom: '<"datatable-topbar d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-4 px-0"i p>',

                    buttons: [{
                            extend: 'csv',
                            text: 'Export u CSV',
                            className: 'btn btn-info me-1 ms-1 rounded-1'
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Export u Excel',
                            className: 'btn btn-info me-1 ms-1 rounded-1'
                        },
                        {
                            extend: 'pdf',
                            text: 'Export u PDF',
                            className: 'btn btn-info me-1 ms-1 rounded-1'
                        },
                        {
                            extend: 'print',
                            text: 'Štampa',
                            className: 'btn btn-info me-1 ms-1 rounded-1'
                        },
                        {
                            extend: 'colvis',
                            text: 'Kolone',
                            className: 'btn btn-info me-1 ms-1 rounded-1'
                        },
                        {
                            extend: 'pageLength',
                            text: 'Prikaži redova',
                            className: 'btn-info me-1 ms-1 rounded-1'
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

                $('#invoiceTable_filter')
                    .addClass('flex-grow-1 me-0')
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

                console.log(" Search input initialized.");
            }
        });
    });
</script>






@endsection