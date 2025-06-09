@extends('layouts.master')
@section('title')
@lang('translation.tarife')
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