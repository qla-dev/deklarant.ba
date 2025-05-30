@extends('layouts.master')
@section('title')
@lang('translation.dobavljaci')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    #suppliersTable_wrapper .dataTables_paginate {
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

    #suppliersTable_wrapper .dataTables_paginate .paginate_button {
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

    #suppliersTable_wrapper .table {
        margin-bottom: 0 !important;
    }


    #suppliersTable_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #eff2f7;

        color: #299cdb !important;
        cursor: pointer;
    }

    #suppliersTable_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #299cdb !important;
        color: #fff !important;
        border-color: #299cdb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);

    }

    #suppliersTable_wrapper .dataTables_paginate .paginate_button:focus {
        outline: none;
        box-shadow: none;
    }

    #suppliersTable_wrapper .dataTables_paginate .paginate_button.disabled {
        background-color: #eff2f7;
        color: #adb5bd !important;
        /* light gray text */
        border-color: #dee2e6;
        cursor: not-allowed;
        pointer-events: none;
        font-weight: bold;

        cursor: pointer;
    }

    #suppliersTable_wrapper .dataTables_paginate .paginate_button.previous,
    #suppliersTable_wrapper .dataTables_paginate .paginate_button.next,
    #suppliersTable_wrapper .dataTables_paginate .paginate_button.first,
    #suppliersTable_wrapper .dataTables_paginate .paginate_button.last {
        width: 50px;
        /* or whatever width you want */
    }

    #suppliersTable_wrapper .dataTables_info {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    #suppliersTable_wrapper .dataTables_paginate {
        margin-top: 4px !important;
        /* reduce from default ~16px */
        padding-top: 0 !important;
    }

    #suppliersTable_wrapper .dataTables_length,
    #suppliersTable_wrapper .dataTables_filter {
        margin-bottom: 4px !important;
        padding-bottom: 0 !important;

    }

    .table-card .dataTables_filter {
        padding: 0 !important;
        margin: 0 !important;
        /* optional for tighter alignment */
    }

    #suppliersTable_wrapper .dataTables_filter input {
        margin-left: 0 !important;
    }

    #invoiceList {
        --vz-card-spacer-y: 10px;
        /* or any smaller value you want */
    }
    .table-card .dataTables_info{
        padding-left: 0 !important;
    }






    
</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ba
@endslot
@slot('title')
Lista klijenata
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
                <div class="table-responsive table-card ms-1 me-1 mb-2">
                    <table id="suppliersTable" class="table mb-2 w-100">
                        <thead class="table-info">
                            <tr>
                                <th>ID</th>
                                <th>Naziv firme</th>
                                <th>Vlasnik</th>
                                <th>Profit prošle godine</th>
                                <th>Profit ove godine</th>
                                <th>Promjena(%)</th>
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
        

        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        fetch(`/api/statistics/users/${user.id}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(res => res.json())
            .then(data => {
                const profitMap = {};
                data.supplier_profit_changes.forEach(p => {
                    profitMap[p.supplier_id] = p;
                });

                const enrichedSuppliers = data.suppliers.map(supplier => ({
                    ...supplier,
                    ...profitMap[supplier.id]
                }));

                const table = $('#suppliersTable').DataTable({
                    data: enrichedSuppliers,
                    scrollX: true,
                    autoWidth: true,
                    lengthChange: false,
                    fixedColumns: {
                        leftColumns: 1
                    },
                    drawCallback: function() {
                        $('.dataTables_paginate ul.pagination')
                            .addClass('pagination-separated pagination-sm justify-content-center mb-0');
                        $('.dataTables_paginate ul.pagination li.page-item a.page-link')
                            .addClass('rounded');
                    },
                    columns: [{
                            data: null,
                            title: 'ID',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: 'name',
                            title: 'Naziv firme',
                            render: (data, type, row) => {
                                const avatar = row.avatar ? `/storage/uploads/suppliers/${row.avatar}` : '/build/images/users/avatar-1.jpg';
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${avatar}" alt="avatar" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                        <span>${data}</span>
                                    </div>`;
                            }
                        },
                        {
                            data: 'owner',
                            title: 'Vlasnik'
                        },
                        {
                            data: 'last_year_profit',
                            title: 'Profit prošle godine',
                            render: data => data ? `${parseFloat(data).toFixed(2)} KM` : '-'
                        },
                        {
                            data: 'current_year_profit',
                            title: 'Profit ove godine',
                            render: data => data ? `${parseFloat(data).toFixed(2)} KM` : '-'
                        },
                        {
                            data: 'percentage_change',
                            title: 'Promjena (%)',
                            render: data => {
                                if (!data) return '-';
                                const num = parseFloat(data);
                                const badge = num > 0 ? 'success' : num < 0 ? 'danger' : 'secondary';
                                return `<span class="badge bg-${badge}">${data}</span>`;
                            }
                        },
                        {
                            data: null,
                            title: 'Akcija',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: row => `
                                <button class="btn btn-sm btn-soft-info me-1 edit-supplier" data-id="${row.id}">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-soft-danger delete-supplier" data-id="${row.id}">
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
                    initComplete: function() {
                        const api = this.api();

                        $('#suppliersTable_filter')
                            .addClass('flex-grow-1 me-0')
                            .css('max-width', '400px')
                            .html(`
                                <div class="position-relative w-100">
                                    <input type="text" class="form-control" placeholder="Pretraga..." autocomplete="off"
                                        id="supplier-search-input" style="width: 100%; padding-left: 2rem;">
                                    <span class="mdi mdi-magnify text-info fs-5 ps-2 position-absolute top-50 start-0 translate-middle-y"></span>
                                    <span class="mdi mdi-close-circle position-absolute top-50 end-0 translate-middle-y me-2 d-none"
                                        id="supplier-search-clear" style="cursor:pointer;"></span>
                                </div>
                            `);

                        const input = $('#supplier-search-input');
                        const clear = $('#supplier-search-clear');

                        input.on('input', function() {
                            const val = $(this).val();
                            api.search(val).draw();
                            clear.toggleClass('d-none', val.length === 0);
                        });

                        clear.on('click', function() {
                            input.val('');
                            api.search('').draw();
                            $(this).addClass('d-none');
                        });

                        console.log(" Supplier search input initialized.");
                    }
                });
            })
            .catch(err => {
                console.error(" Greška pri učitavanju podataka:", err);
            });
    });
</script>






@endsection