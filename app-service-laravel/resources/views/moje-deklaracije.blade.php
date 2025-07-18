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
                               
                               
                                <th style="width: 100px;">Datum</th>
                                <th style="width: 100px;"><i class="fa fa-balance-scale"></i> Bruto/Neto</th>
                                <th style="width: 100px;"><i class="fa fa-cubes"></i> Broj koleta</th>
                                
                                <th>Primatelj</th>
                                                            
                                 <th>Originalni dokument</th>

                                <th>Status</th>
                                <th>Cijena</th>
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
                data: 'importer.name',
                title: 'Primatelj',
                defaultContent: '<span class="text-muted">Nepoznato</span>'
            },
            
            
          {
    data: null,
    title: '<i class="fa-solid fa-scale-unbalanced"></i> Bruto/Neto',
    render: function(data, type, row) {
        const gross = row.total_weight_gross ?? '';
        const net = row.total_weight_net ?? '';
        function formatNum(val) {
            const num = parseFloat(val);
            if (isNaN(num)) return '';
            return num.toFixed(2).replace('.', ',');
        }
        if (!gross && !net) {
            return '<span class="text-muted">Nepoznato</span>';
        }
        return `${formatNum(gross)} / ${formatNum(net)}`;
    },
    defaultContent: '<span class="text-muted">Nepoznato</span>'
},{
    data: 'total_num_packages',
    title: '<i class="fa-regular fa-box-open"></i> Broj Koleta',
    render: function(data, type, row) {
        function formatNum(val) {
            const num = parseFloat(val);
            if (isNaN(num)) return '';
            return num.toFixed(2).replace('.', ',');
        }
        return data ? formatNum(data) : '<span class="text-muted">Nepoznato</span>';
    },
    defaultContent: '<span class="text-muted">Nepoznato</span>'
},
       


            {
                data: 'file_name',
                orderable: false,
                title: 'Vrsta',
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
    data: 'internal_status',
    title: 'Status',
    className: 'text-center',
    render: function(data, type, row) {
        if (type !== 'display') return data;

        const baseClass = 'badge d-inline-block w-100 py-1';

        // Provjera ako nema task_id
        if (!row.task_id) {
            return `<span class="${baseClass} bg-danger text-white">
                        <i class="fas fa-times-circle me-1"></i> Odbijena
                    </span>`;
        }

        switch (data) {
            case 0:
                return `<span class="${baseClass} bg-primary text-white">
                            <i class="fas fa-spinner fa-spin me-1"></i> U obradi
                        </span>`;
            case 1:
                return `<span class="${baseClass} bg-warning text-dark">
                            <i class="fas fa-pencil-alt me-1"></i> Draft
                        </span>`;
            case 2:
                return `<span class="${baseClass} bg-success text-white">
                            <i class="fas fa-check-circle me-1"></i> Spremljena
                        </span>`;
            case 3:
                return `<span class="${baseClass} bg-danger text-white">
                            <i class="fas fa-times-circle me-1"></i> Neuspješna
                        </span>`;
            default:
                return `<span class="${baseClass} bg-secondary">
                            <i class="fas fa-question-circle me-1"></i> Nepoznato
                        </span>`;
        }
    }
},{
    data: 'total_price',
    title: 'Cijena',
    render: function(data, type, row) {
        const numericValue = parseFloat(data?.toString().replace(/[^\d.,-]/g, "").replace(",", "."));
        if (isNaN(numericValue)) return type === 'sort' || type === 'type' ? 0 : '--';
        
        return type === 'sort' || type === 'type'
            ? numericValue
            : `${numericValue.toFixed(2).replace('.', ',')} KM`;
    }
}
,
            {
                data: null,
                title: 'Akcija',
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: row => {
    const id = row.id;
    const status = row.internal_status;
    const hasTask = !!row.task_id;

    const baseBtn = 'btn btn-sm me-1';
    const viewBtn = `<a class="${baseBtn} btn-soft-info view-invoice" href="/detalji-deklaracije/${id}" title="Pregledaj deklaraciju">
                        <i class="ri-eye-line"></i>
                     </a>`;
    const editBtn = `<a class="${baseBtn} btn-soft-info edit-invoice" href="/deklaracija/${id}" title="Uredi deklaraciju">
                        <i class="ri-edit-line"></i>
                     </a>`;
    const deleteBtn = `<button class="${baseBtn} btn-soft-danger delete-invoice" data-id="${id}" title="Obriši deklaraciju">
                        <i class="ri-delete-bin-line"></i>
                      </button>`;
    const trackBtn = ``;
    const aiBtn = `<button class="${baseBtn} btn-info ai-followup" onclick="handleMagic(${id})" title="AI nastavak">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </button>`;
                      if (!hasTask) {
       return aiBtn;
    }
                    

    switch (status) {
        case 0: // U obradi
            return trackBtn;

        case 3: // Neuspješna
            return aiBtn + deleteBtn;

        default: // Sve ostalo: draft, spremljena, odbijena
            return viewBtn + editBtn + deleteBtn;
    }
}

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



<script>
  // DELETE handler
  $('#invoiceTable').on('click', '.delete-invoice', function () {
    const invoiceId = $(this).data('id');
    Swal.fire({
      title: 'Upozorenje',
      text: 'Jeste li sigurni da želite obrisati ovu deklaraciju?',
      icon: 'warning',
      showCancelButton: true,
      reverseButtons: true,
      customClass: {
        confirmButton: "btn btn-info",
        cancelButton: "btn btn-soft-info me-2"
      },
      confirmButtonText: 'Da, obriši',
      cancelButtonText: 'Ne, odustani'
    }).then((result) => {
      if (!result.isConfirmed) return;

      $.ajax({
        url: `/api/invoices/${invoiceId}`,
        type: 'DELETE',
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        success(response) {
          Swal.fire({
            icon: 'success',
            title: 'Obrisano!',
            text: response.message,
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            // full page reload on success
            location.reload();
          });
        },
        error(xhr) {
          const err = xhr.responseJSON?.error || 'Došlo je do greške prilikom brisanja.';
          Swal.fire({
            icon: 'error',
            title: 'Greška',
            text: err
          });
        }
      });
    });
  });
</script>



@endsection