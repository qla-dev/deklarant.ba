@extends('layouts.master')
@section('title')
@lang('translation.clients')
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
deklarant.ai
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
                  <div id="invoice-loading" class="text-center my-4 mb-5">
                    <i class="mdi mdi-loading mdi-spin fs-24 text-info"></i>
                    <p class="text-muted">Učitavanje rezultata</p>
                </div>
                <div class="table-responsive table-card ms-1 me-1 mb-2" style="display:none">
                    <table id="suppliersTable" class="table w-100">
                        <thead class="custom-table  has-action">
                            <tr>
                                <th>ID</th>
                                <th>Naziv firme</th>
                                <th>ID broj</th>
                                <th>Vlasnik</th>
                                <th>Adresa</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th style="padding-right: 20px!important;">Akcija</th>

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
    if (!user || !token) {
        console.warn("[AUTH] Nedostaje user ili token.");
        return;
    }

    const loader = document.getElementById('invoice-loading');
    const tableContainer = document.querySelector('.table-responsive');
    const isSuperAdmin = user.role === 'superadmin';

    let suppliers = [];
    let fields = {};

    try {
        const response = isSuperAdmin
            ? await axios.get("/api/suppliers", { headers: { Authorization: `Bearer ${token}` } })
            : await axios.get(`/api/statistics/users/${user.id}`, { headers: { Authorization: `Bearer ${token}` } });

        if (isSuperAdmin) {
            suppliers = response.data?.data ?? [];
        } else {
            const stats = response.data || {};
            suppliers = stats.supplier_stats?.suppliers ?? [];
            fields = {
                totalSuppliers: stats.supplier_stats?.total_suppliers ?? 0,
                totalInvoices: stats.total_invoices ?? 0,
                usedScans: stats.used_scans ?? 0,
                remainScansTopbar: stats.remaining_scans ?? 0
            };

            Object.entries(fields).forEach(([id, value]) => {
                const el = document.getElementById(id);
                if (el) el.innerText = value;
            });
        }

        loader?.remove();
        tableContainer.style.display = 'block';

        $('#suppliersTable').DataTable({
            data: suppliers,
            scrollX: true,
            autoWidth: true,
            lengthChange: false,
            fixedColumns: { leftColumns: 1 },
            columns: [
                { data: null, title: 'ID', render: (data, type, row, meta) => meta.row + 1 },
                {
                    data: 'name', title: 'Naziv firme',
                    render: (data, type, row) => {
                        const avatar = row.avatar
                            ? `<img src="/storage/uploads/suppliers/${row.avatar}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">`
                            : `<div class="rounded-circle avatar-initials d-flex align-items-center justify-content-center text-white shadow-sm me-2" style="width: 30px; height: 30px; background-color: #299cdb; font-size: 14px;">${row.name?.[0]?.toUpperCase() || "?"}</div>`;
                        return `<div class="d-flex align-items-center">${avatar}<span>${data}</span></div>`;
                    }
                },
                { data: 'owner', title: 'Vlasnik' },
                {
                    data: 'tax_id', title: 'ID broj',
                    render: data => data?.trim() ? data : '<span class="text-muted">Nepoznato</span>'
                },
                {
                    data: null, title: 'Adresa',
                    render: row => row.contact_email ? row.address : '<span class="text-muted">Nepoznato</span>'
                },
                {
                    data: null, title: 'Email',
                    render: row => row.contact_email || '<span class="text-muted">Nepoznato</span>'
                },
                {
                    data: null, title: 'Telefon',
                    render: row => row.contact_phone || '<span class="text-muted">Nepoznato</span>'
                },
                {
                    data: null, title: 'Akcija', orderable: false, searchable: false, className: 'text-end',
                    render: row => {
                        const isUser = user.role === 'user';
                        const callBtn = row.contact_phone ? `<a href="tel:${row.contact_phone}" class="btn btn-sm btn-soft-success me-1"><i class="ri-phone-line"></i></a>` : '';
                        const emailBtn = row.contact_email ? `<a href="mailto:${row.contact_email}" class="btn btn-sm btn-soft-warning me-1"><i class="ri-mail-line"></i></a>` : '';
                        const deleteBtn = `<button class="btn btn-sm btn-soft-danger ${isUser ? 'd-none' : ''}" data-id="${row.id}"><i class="ri-delete-bin-line"></i></button>`;
                        const editBtn = `<button class="btn btn-sm btn-soft-info me-1 edit-synonyms ${isUser ? 'd-none' : ''}" data-id="${row.id}"><i class="ri-edit-line"></i></button>`;
                        return `${callBtn}${emailBtn}${editBtn}${deleteBtn}`;
                    }
                }
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
            drawCallback: function () {
                $('.dataTables_paginate ul.pagination').addClass('pagination-separated pagination-sm justify-content-center mb-0');
                $('.dataTables_paginate ul.pagination li.page-item a.page-link').addClass('rounded');
            },
            language: {
                paginate: { first: "←", last: "→", next: "→", previous: "←" },
                info: "Prikaz _START_ do _END_ od _TOTAL_ stavki",
                infoEmpty: "Nema rezultata",
                search: "",
                zeroRecords: "Nema podataka"
            },
            initComplete: function () {
                const api = this.api();
                $('#suppliersTable_filter').html(`
                    <div class="position-relative w-100">
                        <input type="text" class="form-control w-100" placeholder="Pretraga..." id="supplier-search-input" style="padding-left: 2rem;">
                        <span class="mdi mdi-magnify text-info fs-5 ps-2 position-absolute top-50 start-0 translate-middle-y"></span>
                        <span class="mdi mdi-close-circle position-absolute top-50 end-0 translate-middle-y me-2 d-none" id="supplier-search-clear" style="cursor:pointer;"></span>
                    </div>
                `).addClass('flex-grow-1 me-0 order-0 order-lg-1').css('max-width', '400px');

                $('.dt-buttons').addClass('order-1 order-lg-0');

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
        console.error("❌ Greška pri dohvaćanju:", error);
    }
});

// Uređivanje sinonima
document.addEventListener("click", async function (e) {
    const btn = e.target.closest(".edit-synonyms");
    if (!btn) return;

    const supplierId = btn.dataset.id;
    let synonyms = [];

    try {
        const res = await axios.get(`/api/suppliers/${supplierId}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        synonyms = res.data?.synonyms ?? [];
    } catch (err) {
        console.warn("❌ Greška pri dohvaćanju sinonima:", err);
        Swal.fire("Greška", "Ne mogu dohvatiti sinonime.", "error");
        return;
    }

    Swal.fire({
        title: 'Uredi sinonime',
        html: `<div class="d-flex flex-wrap gap-2 mb-3" id="synonyms-tags"></div>
               <input type="text" class="form-control" placeholder="Dodaj sinonim..." id="new-synonym">`,
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Zatvori',
        customClass: { cancelButton: 'btn btn-light' },
        didOpen: () => {
            const tagContainer = document.getElementById("synonyms-tags");
            const input = document.getElementById("new-synonym");

            const renderTags = () => {
                tagContainer.innerHTML = "";
                synonyms.forEach(syn => {
                    tagContainer.innerHTML += `
                        <span class="badge bg-info px-3 py-2 rounded-pill d-flex align-items-center">
                            <span>${syn}</span>
                            <button type="button" class="btn btn-sm btn-remove-synonym p-0 border-0 ms-2" data-value="${syn}">&times;</button>
                        </span>`;
                });
            };

            renderTags();

            input.addEventListener("keypress", async function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    const newVal = input.value.trim();
                    if (!newVal || synonyms.includes(newVal)) return;

                    try {
                        const updated = [...synonyms, newVal];
                        await axios.put(`/api/suppliers/${supplierId}`, { synonyms: updated }, {
                            headers: { Authorization: `Bearer ${token}` }
                        });
                        synonyms.push(newVal);
                        input.value = "";
                        renderTags();
                        showToast(`✔ Sinonim "${newVal}" dodat.`);
                    } catch (err) {
                        console.error("❌ Add error:", err);
                        Swal.showValidationMessage("Greška pri dodavanju.");
                    }
                }
            });

            tagContainer.addEventListener("click", async function (e) {
                const btn = e.target.closest(".btn-remove-synonym");
                if (!btn) return;
                const toRemove = btn.dataset.value;
                const updated = synonyms.filter(s => s !== toRemove);

                try {
                    await axios.put(`/api/suppliers/${supplierId}`, { synonyms: updated }, {
                        headers: { Authorization: `Bearer ${token}` }
                    });
                    synonyms = updated;
                    renderTags();
                    showToast(`✔ Sinonim "${toRemove}" uklonjen.`);
                } catch (err) {
                    console.error("❌ Delete error:", err);
                    Swal.showValidationMessage("Greška pri brisanju.");
                }
            });
        }
    });
});

function showToast(message = "") {
    const toast = document.createElement("div");
    toast.className = "position-fixed top-0 end-0 bg-dark text-white p-2 px-3 rounded shadow mt-3 me-3";
    toast.style.zIndex = "9999999";
    toast.style.transition = "opacity 0.3s ease";
    toast.innerText = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}
</script>










@endsection