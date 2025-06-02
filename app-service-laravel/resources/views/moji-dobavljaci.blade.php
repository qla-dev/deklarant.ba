@extends('layouts.master')
@section('title')
@lang('translation.importers')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/css/table.min.css') }}"  rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ba
@endslot
@slot('title')
Lista dobavljača
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
                <div class="table-responsive table-card ms-1 me-1 mb-2" style="display:none">
                    <table id="suppliersTable" class="table w-100">
                        <thead class="custom-table">
                            <tr>
                                <th>ID</th>
                                <th>Naziv firme</th>
                                <th>Vlasnik</th>
                                <th>Adresa</th>
                                <th>Email</th>
                                <th>Telefon</th>
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
document.addEventListener("DOMContentLoaded", async function () {
    console.log("[INIT] Provjera lokalne pohrane...");
    console.log(" Korisnik:", user);
    console.log(" Token:", token?.substring(0, 25) + "...");

    if (!user || !token) {
        if (!user) console.warn("[TOPBAR] Backend user missing!");
        if (!token) console.warn("[TOPBAR] Auth token missing in localStorage.");
        return;
    }

    const API_URL = `/api/statistics/users/${user.id}`;
    const loader = document.getElementById('invoice-loading');
    const tableContainer = document.querySelector('.table-responsive');

    console.log(`📡 Pozivam API: ${API_URL}`);

    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const stats = response.data || {};
        const suppliers = stats.importer_stats?.importers ?? [];

        // Ažuriraj info brojeve (topbar itd.)
        const fields = {
            totalSuppliers: stats.supplier_stats?.total_suppliers ?? 0,
            totalInvoices: stats.total_invoices ?? 0,
            usedScans: stats.used_scans ?? 0,
            remainScansTopbar: stats.remaining_scans ?? 0
        };

        Object.entries(fields).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) el.innerText = value;
        });


        loader?.remove();
        tableContainer.style.display = 'block';


        const table = $('#suppliersTable').DataTable({
            data: suppliers,
            scrollX: true,
            autoWidth: true,
            lengthChange: false,
            fixedColumns: {
                leftColumns: 1
            },
            drawCallback: function () {
                $('.dataTables_paginate ul.pagination')
                    .addClass('pagination-separated pagination-sm justify-content-center mb-0');
                $('.dataTables_paginate ul.pagination li.page-item a.page-link')
                    .addClass('rounded');
            },
            columns: [
                {
                    data: null,
                    title: 'ID',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'name',
                    title: 'Naziv firme',
                    render: (data, type, row) => {
                        let avatarHTML;
                        if (row.avatar) {
                            const avatar = `/storage/uploads/suppliers/${row.avatar}`;
                            avatarHTML = `<img src="${avatar}" alt="avatar" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">`;
                        } else {
                            const firstLetter = row.name?.[0]?.toUpperCase() || "?";
                            avatarHTML = `
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm me-2"
                                     style="width: 30px; height: 30px; background-color: #299cdb; font-size: 14px;">
                                     ${firstLetter}
                                </div>`;
                        }
                        return `
                            <div class="d-flex align-items-center">
                                ${avatarHTML}
                                <span>${data}</span>
                            </div>`;
                    }
                },
                { data: 'owner', title: 'Vlasnik' },
                  {
                    data: null,
                    title: 'Adresa',
                    render: (data, type, row) => {
                        return row.contact_email ? row.address : 'Nepoznato';
                    }
                },
                {
                    data: null,
                    title: 'Email',
                    render: (data, type, row) => {
                        return row.contact_email ? row.contact_email : 'Nepoznato';
                    }
                },
                {
                    data: null,
                    title: 'Telefon',
                    render: (data, type, row) => {
                        return row.contact_phone ? row.contact_phone : 'Nepoznato';
                    }
                },
                {
                    data: null,
                    title: 'Akcija',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: row => {
                        let callBtn = '';
                        if (row.contact_phone) {
                            callBtn = `
                                <a href="tel:${row.contact_phone}" class="btn btn-sm btn-soft-success me-1" title="Pozovi dobavljača">
                                    <i class="ri-phone-line"></i>
                                </a>`;
                        }
                        return `
                          ${callBtn}
                            <button class="btn btn-sm btn-soft-danger delete-invoice" data-id="${row.id}" title="Obriši dobavljača">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                          
                        `;
                    }
                }
            ],
            dom: '<"datatable-topbar d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-4 px-0"i p>',
           buttons: [{
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
                paginate: { first: "←", last: "→", next: "→", previous: "←" },
                info: "Prikazivanje _START_ do _END_ od _TOTAL_ stavki",
                infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
                infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
                search: "",
                zeroRecords: "Nema pronađenih stavki"
            },
            initComplete: function () {
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
            }
        });

    } catch (error) {
        console.error("❌ Greška pri dohvaćanju statistike:", error);
    }
});
</script>







@endsection