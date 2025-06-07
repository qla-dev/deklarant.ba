@extends('layouts.master')
@section('title')
@lang('translation.users')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/css/table.min.css') }}"  rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
<style>.flatpickr-calendar {
  z-index: 10000 !important; /* above SweetAlert2 */
  visibility: visible !important;
  opacity: 1 !important;
}</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ai
@endslot
@slot('title')
Lista korisnika
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
              <!-- Loader -->
<div id="supplier-loader" class="text-center my-4 mb-5">
    <i class="mdi mdi-loading mdi-spin fs-24 text-info"></i>
    <p class="text-muted">Učitavanje rezultata...</p>
</div>

<!-- DataTable Container -->
<div id="userStatsContainer" class="table-responsive table-card ms-1 me-1 mb-2" style="display: none;">
    <table id="userStatsTable" class="table w-100 align-middle">
        <thead class="custom-table has-action">
            <tr>
                <th>#</th>
                <th>Korisničko ime</th>
                <th>Ime i prezime</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Grad</th>
                <th>Država</th>
                <th>Datum pridruživanja</th>
                <th>Naziv firme</th>
                <th>Adresa firme</th>
                <th>Ukupno faktura</th>
                <th>Preostali skenovi</th>
                <th>Aktivna pretplata</th>
                <th>Naziv paketa</th>
                <th>Datum isteka</th>
                <th class="text-center">Akcija</th>

            </tr>
        </thead>
        <tbody class="table-light">
            <!-- Popunjava se putem DataTables -->
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/bs.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
<script>
    dayjs.extend(dayjs_plugin_customParseFormat);
</script>


<script>
let availablePackages = [];

document.addEventListener("DOMContentLoaded", async function () {
    const token = localStorage.getItem("auth_token");
    const API_URL = "/api/statistics/users";

    if (!token) return console.warn("Token nije pronađen.");

    try {
        const packageRes = await axios.get("/api/packages", {
            headers: { Authorization: `Bearer ${token}` }
        });
        availablePackages = packageRes.data.data || [];

        const response = await axios.get(API_URL, {
            headers: { Authorization: `Bearer ${token}` }
        });
        const users = response.data || [];

        document.getElementById('supplier-loader')?.remove();
        document.getElementById('userStatsContainer').style.display = 'block';

        $('#userStatsTable').DataTable({
            data: users,
            scrollX: true,
            autoWidth: false,
            lengthChange: false,
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'username' },
                { data: 'name' },
                { data: 'email' },
                { data: 'phone_number', render: d => d || '<span class="text-muted">Nepoznato</span>' },
                { data: 'location.city', render: d => d || '<span class="text-muted">Nepoznato</span>' },
                { data: 'location.country', render: d => d || '<span class="text-muted">Nepoznato</span>' },
                { data: 'joining_date', render: d => d ? dayjs(d).format('DD.MM.YYYY') : '<span class="text-muted">Nepoznato</span>' },
                { data: 'company.name', render: d => d || '<span class="text-muted">Nepoznato</span>' },
                { data: 'company.address', render: d => d || '<span class="text-muted">Nepoznato</span>' },
                { data: 'statistics.total_invoices' },
                { data: 'statistics.remaining_scans' },
                { data: 'statistics.active', render: d => d ? 'Da' : 'Ne' },
                { data: 'package.name', render: d => d || '<span class="text-muted">Neaktivno</span>' },
                { data: 'statistics.expiration_date', render: d => d ? dayjs(d).format('DD.MM.YYYY') : '<span class="text-muted">Neaktivno</span>' },
                {
                    data: null,
                    title: 'Akcija',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: row => `
                        <button class="btn btn-sm btn-soft-info edit-user-package" 
                            data-id="${row.id}" 
                            data-package-id="${row.package?.id ?? ''}" 
                            data-active="${row.statistics.active ? 1 : 0}"
                            data-expiration="${row.statistics.expiration_date ?? ''}"
                            data-remaining="${row.statistics.remaining_scans ?? ''}">
                            <i class="ri-edit-line"></i> Uredi
                        </button>`
                }
            ],
            dom: '<"datatable-topbar d-flex flex-column flex-lg-row justify-content-between align-items-center mb-0 mb-md-4"fB>rt<"d-flex justify-content-between align-items-center mt-4 px-0"ip>',
            buttons: [
                { extend: 'csv', text: '<i class="ri-file-code-line me-1"></i> CSV', className: 'btn btn-soft-info me-1 rounded-1' },
                { extend: 'excelHtml5', text: '<i class="ri-file-excel-line me-1"></i> Excel', className: 'btn btn-soft-info me-1 rounded-1' },
                { extend: 'pdf', text: '<i class="ri-file-pdf-2-line me-1"></i> PDF', className: 'btn btn-soft-info me-1 rounded-1' },
                { extend: 'print', text: '<i class="ri-printer-line me-1"></i> Print', className: 'btn btn-soft-info me-1 rounded-1' },
                { extend: 'colvis', text: 'Kolone', className: 'btn btn-soft-info me-1 rounded-1' },
                { extend: 'pageLength', text: 'Prikaži redova', className: 'btn-soft-info me-1 rounded-1' }
            ],
            language: {
                paginate: { first: "←", last: "→", next: "→", previous: "←" },
                info: "Prikaz _START_ do _END_ od _TOTAL_ korisnika",
                infoEmpty: "Prikaz 0 do 0 od 0 korisnika",
                infoFiltered: "(filtrirano iz _MAX_ ukupno)",
                search: "",
                zeroRecords: "Nema pronađenih korisnika"
            },
            initComplete: function () {
                const api = this.api();
                $('#userStatsTable_filter')
                    .addClass('flex-grow-1 me-0 order-0 order-lg-1')
                    .css('max-width', '400px')
                    .html(`
                        <div class="position-relative w-100">
                            <input type="text" class="form-control" placeholder="Pretraga..." autocomplete="off"
                                id="user-search-input" style="width: 100%; padding-left: 2rem;">
                            <span class="mdi mdi-magnify text-info fs-5 ps-2 position-absolute top-50 start-0 translate-middle-y"></span>
                            <span class="mdi mdi-close-circle position-absolute top-50 end-0 translate-middle-y me-2 d-none"
                                id="user-search-clear" style="cursor:pointer;"></span>
                        </div>
                    `);

                $('.dt-buttons').addClass('order-1 order-lg-0');

                const input = $('#user-search-input');
                const clear = $('#user-search-clear');

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

    } catch (err) {
        console.error("❌ Greška pri učitavanju:", err);
    }
});

document.addEventListener("click", async function (e) {
    if (e.target.closest(".edit-user-package")) {
        const btn = e.target.closest(".edit-user-package");
        const userId = btn.dataset.id;
        const currentPackageId = btn.dataset.packageId;
        const active = btn.dataset.active;
        const expiration = btn.dataset.expiration;
        const remaining = btn.dataset.remaining;

        const packageOptions = availablePackages.map(pkg => `
            <option value="${pkg.id}" ${pkg.id == currentPackageId ? 'selected' : ''}>${pkg.name}</option>
        `).join("");

        Swal.fire({
            title: 'Uredi korisnički paket',
            html: `
                <div class="mb-3 text-start">
                    <label class="form-label">Paket:</label>
                    <select id="swal-package" class="form-select">${packageOptions}</select>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Aktivan:</label><br/>
                    <label class="form-check form-switch">
                        <input type="checkbox" id="swal-active" class="form-check-input" ${active == 1 ? 'checked' : ''}>
                    </label>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Preostali skenovi:</label>
                    <input type="number" id="swal-remaining" class="form-control" value="${remaining}">
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Datum isteka:</label>
                    <input type="text" id="swal-expiration" class="form-control" value="${expiration ? dayjs(expiration).format('DD.MM.YYYY') : ''}">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Spasi',
            cancelButtonText: 'Otkaži',
            customClass: {
                confirmButton: 'btn btn-info me-2',
                cancelButton: 'btn btn-light'
            },
            buttonsStyling: false,
            didOpen: () => {
                const toggle = document.getElementById('swal-active');
                const expirationInput = document.getElementById('swal-expiration');
                const select = document.getElementById('swal-package');

                select.focus();
                select.classList.add('border-info');
                select.style.boxShadow = '0 0 0 0.2rem rgba(61,213,243,0.25)';

                const parsedExpiration = expirationInput.value
                    ? dayjs(expirationInput.value, "DD.MM.YYYY").format("YYYY-MM-DD")
                    : "";

                try {
                    flatpickr(expirationInput, {
                        dateFormat: "d.m.Y",
                        defaultDate: parsedExpiration || null,
                        locale: "bs",
                        allowInput: true,
                        appendTo: Swal.getPopup(),
                        onReady: function () {
                            expirationInput.setAttribute("maxlength", "10");
                            expirationInput.setAttribute("placeholder", "DD.MM.GGGG");
                            expirationInput.style.cursor = "pointer";

                            expirationInput.addEventListener("keypress", function (e) {
                                if (!/[\d.]/.test(e.key)) {
                                    e.preventDefault();
                                }
                            });
                        }
                    });
                } catch (e) {
                    console.error("❌ flatpickr initialization failed:", e);
                }

                toggle.addEventListener('change', function () {
                    toggle.classList.toggle('bg-info', this.checked);
                    toggle.classList.toggle('border-info', this.checked);
                });

                if (toggle.checked) {
                    toggle.classList.add('bg-info', 'border-info');
                }
            },
            preConfirm: async () => {
                const packageId = document.getElementById('swal-package').value;
                const newActive = document.getElementById('swal-active').checked ? 1 : 0;
                const rawExpiration = document.getElementById('swal-expiration').value;
                const newRemaining = document.getElementById('swal-remaining').value;

                if (!rawExpiration) {
                    Swal.showValidationMessage("Datum isteka je obavezan.");
                    return false;
                }

                const parsed = dayjs(rawExpiration, "DD.MM.YYYY", true);
                if (!parsed.isValid()) {
                    Swal.showValidationMessage("Neispravan format datuma.");
                    return false;
                }

                const newExpiration = parsed.format("YYYY-MM-DD");

                try {
                    const token = localStorage.getItem("auth_token");
                    const response = await axios.post(
                        `/api/user-packages/users/${userId}/packages/${packageId}`,
                        {
                            active: newActive,
                            expiration_date: newExpiration,
                            remaining_scans: newRemaining
                        },
                        {
                            headers: { Authorization: `Bearer ${token}` }
                        }
                    );
                    return response.data;
                } catch (error) {
                    console.error("Greška:", error);
                    Swal.showValidationMessage("Greška pri spremanju podataka.");
                    return false;
                }
            }
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Uspješno ažurirano!',
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#userStatsTable').DataTable().clear().destroy();
                document.dispatchEvent(new Event("DOMContentLoaded"));
            }
        });
    }
});
</script>




@endsection
