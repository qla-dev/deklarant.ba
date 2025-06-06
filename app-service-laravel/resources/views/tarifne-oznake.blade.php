@extends('layouts.master')
@section('title')
@lang('translation.tarife')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    #tariffTable_wrapper .dataTables_paginate {
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

    #tariffTable_wrapper .dataTables_paginate .paginate_button {
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

    #tariffTable_wrapper .table {
        margin-bottom: 0 !important;
    }


    

    #tariffTable_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #299cdb !important;
        color: #fff !important;
        border-color: #299cdb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);

    }

    #tariffTable_wrapper .dataTables_paginate .paginate_button:focus {
        outline: none;
        box-shadow: none;
    }

    #tariffTable_wrapper .dataTables_paginate .paginate_button.disabled {
        background-color: #eff2f7;
        color: #adb5bd !important;
        /* light gray text */
        border-color: #dee2e6;
        cursor: not-allowed;
        pointer-events: none;
        font-weight: bold;

        cursor: pointer;
    }

    #tariffTable_wrapper .dataTables_paginate .paginate_button.previous,
    #tariffTable_wrapper .dataTables_paginate .paginate_button.next,
    #tariffTable_wrapper .dataTables_paginate .paginate_button.first,
    #tariffTable_wrapper .dataTables_paginate .paginate_button.last {
        width: 50px;
        /* or whatever width you want */
    }

    #tariffTable_wrapper .dataTables_info {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    #tariffTable_wrapper .dataTables_paginate {
        margin-top: 4px !important;
        /* reduce from default ~16px */
        padding-top: 0 !important;
    }

    #tariffTable_wrapper .dataTables_length,
    #tariffTable_wrapper .dataTables_filter {
        margin-bottom: 4px !important;
        padding-bottom: 0 !important;

    }

    .table-card .dataTables_filter {
        padding: 0 !important;
        margin: 0 !important;
        /* optional for tighter alignment */
    }

    #tariffTable_wrapper .dataTables_filter input {
        margin-left: 0 !important;
    }
    .table-card .dataTables_info{
        padding-left: 0 !important;
    }


    #invoiceList {
        --vz-card-spacer-y: 10px;
        /* or any smaller value you want */
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
deklarant.ai
@endslot
@slot('title')
Baza tarifnih oznaka
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
                 <div id="tariff-loading" class="text-center my-4 mb-5">
                    <i class="mdi mdi-loading mdi-spin fs-24 text-info"></i>
                    <p class="text-muted">Učitavanje rezultata</p>
                </div>
                <div class="table-responsive table-card ms-1 me-1 mb-2" style="display:none">
                    <table id="tariffTable" class="table mb-2 w-100">
                        <thead class="custom-table">
                            <tr>
                                <th>ID</th>
                                <th>Tarifna oznaka</th>
                                <th>Naziv</th>
                                <th>Jedinica</th>
                                <th>Stopa (%)</th>
                                <th>EU</th>
                                <th>CEFTA</th>
                                <th>IRN</th>
                                <th>TUR</th>
                                <th>CHE, LIE</th>
                                <th>ISL</th>
                                <th>NOR</th>
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
    document.addEventListener("DOMContentLoaded", function () {
        const loader = document.getElementById('tariff-loading');
        const tableContainer = document.querySelector('.table-responsive');

        fetch('/build/json/tariff.json')
            .then(response => response.json())
            .then(data => {
                loader?.remove();
                tableContainer && (tableContainer.style.display = 'block');

                const table = $('#tariffTable').DataTable({
                    data: data,
                    scrollX: true,
                    autoWidth: true,
                    lengthChange: false,
                    fixedColumns: {
                        leftColumns: 1
                    },
                    columns: [
                        { data: null, title: 'ID', render: (data, type, row, meta) => meta.row + 1 },
                        { data: 'Tarifna oznaka', title: 'Tarifna oznaka' },
                        { data: 'Naziv', title: 'Naziv' },
                        { data: 'Dopunska jedinica', title: 'Jedinica' },
                        { data: 'Carinska stopa (%)', title: 'Stopa (%)' },
                        { data: 'EU', title: 'EU' },
                        { data: 'CEFTA', title: 'CEFTA' },
                        { data: 'IRN', title: 'IRN' },
                        { data: 'TUR', title: 'TUR' },
                        { data: 'CHE, LIE', title: 'CHE, LIE' },
                        { data: 'ISL', title: 'ISL' },
                        { data: 'NOR', title: 'NOR' }
                    ],
                    dom: '<"datatable-topbar d-flex flex-column flex-lg-row justify-content-between align-items-center mb-3"fB>rt<"d-flex justify-content-between align-items-center mt-4 px-0"ip>',
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

                        $('#tariffTable_filter')
                            .addClass('flex-grow-1 me-0 order-0 order-lg-1')
                            .css('max-width', '400px')
                            .html(`
                                <div class="position-relative w-100">
                                    <input type="text" class="form-control" placeholder="Pretraga..." autocomplete="off"
                                        id="tariff-search-input" style="width: 100%; padding-left: 2rem;">
                                    <span class="mdi mdi-magnify text-info fs-5 ps-2 position-absolute top-50 start-0 translate-middle-y"></span>
                                    <span class="mdi mdi-close-circle position-absolute top-50 end-0 translate-middle-y me-2 d-none"
                                        id="tariff-search-clear" style="cursor:pointer;"></span>
                                </div>
                            `);

                        $('.dt-buttons').addClass('order-1 order-lg-0');

                        const input = $('#tariff-search-input');
                        const clear = $('#tariff-search-clear');

                        input.on('input', function () {
                            const val = $(this).val();
                            api.search(val).draw();
                            clear.toggleClass('d-none', val.length === 0);
                        });

                        clear.on('click', function () {
                            input.val('');
                            api.search('').draw();
                            $(this).addClass('d-none');
                        });

                        console.log("✅ Tariff search field initialized.");
                    }
                });
            })
            .catch(err => {
                loader?.remove();
                console.error("❌ Greška pri učitavanju podataka o tarifnim oznakama:", err);
            });
    });
</script>







@endsection