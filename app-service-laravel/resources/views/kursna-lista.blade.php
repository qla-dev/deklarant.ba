@extends('layouts.master')
@section('title')
@lang('translation.kurs')

@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>

    #exchangeTable_wrapper .px-0 {
        display: none!important;
    }

    .dataTable tbody tr:last-child td {
    border-bottom: unset !important;
}

</style>
@endsection
@section('content')
    @component('components.breadcrumb')
    @slot('li_1')
        deklarant.ai
    @endslot
    @slot('title')
        Kursna lista za dan {{ date('d.m.Y') }}
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

            <div class="card-body pb-0">
                 <div id="invoice-loading" class="text-center my-4 mb-5">
                    <i class="mdi mdi-loading mdi-spin fs-24 text-info"></i>
                    <p class="text-muted">Učitavanje rezultata</p>
                </div>
                 <div class="table-responsive table-card ms-1 me-1 mb-2" style="display:none" id="invoice-table-container">
                    <table id="exchangeTable" class="table mb-2 w-100" >
                        <thead class="custom-table">
                            <tr>
                                <th>#</th>
                                <th>Šifra</th>
                                <th>Valuta</th>
                                <th>Država</th>
                                <th>Jedinica</th>
                                <th>Kupovni kurs</th>
                                <th>Srednji kurs</th>
                                <th>Prodajni kurs</th>
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
        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        const currencyCodebook = {
            280: 'DEM', 724: 'ESP', 40: 'ATS', 56: 'BEF', 246: 'FIM', 250: 'FRF',
            300: 'GRD', 372: 'IEP', 380: 'ITL', 620: 'PTE', 442: 'LUF', 978: 'EUR',
            36: 'AUD', 124: 'CAD', 203: 'CZK', 208: 'DKK', 348: 'HUF', 392: 'JPY',
            578: 'NOR', 752: 'SEK', 756: 'CHF', 949: 'TRY', 826: 'GBP', 840: 'USD',
            643: 'RUB', 156: 'CNY', 941: 'RSD'
        };

        const countryFlagMap = {
            'emu': 'eu',
            'australia': 'au',
            'canada': 'ca',
            'czech r': 'cz',
            'denmark': 'dk',
            'hungary': 'hu',
            'japan': 'jp',
            'norway': 'no',
            'sweden': 'se',
            'switzerland': 'ch',
            'turkey': 'tr',
            'g.britain': 'gb',
            'usa': 'us',
            'russia': 'ru',
            'china': 'cn',
            'serbia': 'rs'
        };

        const countryNameBS = {
            'emu': 'EMU',
            'australia': 'Australija',
            'canada': 'Kanada',
            'czech r': 'Češka',
            'denmark': 'Danska',
            'hungary': 'Mađarska',
            'japan': 'Japan',
            'norway': 'Norveška',
            'sweden': 'Švedska',
            'switzerland': 'Švicarska',
            'turkey': 'Turska',
            'g.britain': 'Velika Britanija',
            'usa': 'SAD',
            'russia': 'Rusija',
            'china': 'Kina',
            'serbia': 'Srbija'
        };

        const countryCorrections = {
            'dennmark': 'denmark'
        };

        fetch("/api/exchange-rates", {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
            .then(res => res.json())
            .then(data => {
                const items = (data.CurrencyExchangeItems || [])
                    .filter(item => item.AlphaCode !== 'XDR')
                    .map(item => {
                        const original = item.Country;
                        const normalized = original.trim().toLowerCase().replace(/\s+/g, ' ').replace(/\.$/, '');
                        const corrected = countryCorrections[normalized] || normalized;
                        const flagCode = countryFlagMap[corrected] || 'un';
                        const displayName = countryNameBS[corrected] || original;

                        return {
                            ...item,
                            Code: item.NumCode,
                            FlagCode: flagCode,
                            CountryBS: displayName
                        };
                    });

                const tableContainer = document.getElementById('invoice-table-container');
                const loader = document.getElementById('invoice-loading');
                loader && loader.remove();
                tableContainer && (tableContainer.style.display = 'block');

                $('#exchangeTable').DataTable({
                    data: items,
                    scrollX: true,
                    autoWidth: true,
                    lengthChange: false,
                    pageLength: 16,
                    paging: false,
                    columns: [
                        {
                            data: null,
                            title: '#',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        { data: 'Code', title: 'Šifra' },
                        { data: 'AlphaCode', title: 'Valuta' },
                        {
                            data: 'CountryBS',
                            title: 'Zemlja',
                            render: function (countryBS, type, row) {
                                return `
                                    <img src="https://flagcdn.com/${row.FlagCode}.svg"
                                         class="rounded-circle me-2"
                                         style="height: 24px; width: 24px; object-fit: cover;"
                                         alt="${countryBS}" />
                                    <span>${countryBS}</span>
                                `;
                            }
                        },
                        { data: 'Units', title: 'Jedinica' },
                        { data: 'Buy', title: 'Kupovni kurs' },
                        { data: 'Middle', title: 'Srednji kurs' },
                        { data: 'Sell', title: 'Prodajni kurs' }
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
                        info: "",
                        infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
                        infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
                        search: "",
                        zeroRecords: "Nema pronađenih stavki"
                    },
                    initComplete: function () {
                        const api = this.api();

                        $('#exchangeTable_filter')
                            .addClass('flex-grow-1 me-0 order-0 order-lg-1')
                            .css('max-width', '400px')
                            .html(`
                                <div class="position-relative w-100">
                                    <input type="text" class="form-control" placeholder="Pretraga..." autocomplete="off"
                                           id="exchange-search-input" style="width: 100%; padding-left: 2rem;">
                                    <span class="mdi mdi-magnify text-info fs-5 ps-2 position-absolute top-50 start-0 translate-middle-y"></span>
                                    <span class="mdi mdi-close-circle position-absolute top-50 end-0 translate-middle-y me-2 d-none"
                                          id="exchange-search-clear" style="cursor:pointer;"></span>
                                </div>
                            `);

                        $('.dt-buttons').addClass('order-1 order-lg-0');

                        const input = $('#exchange-search-input');
                        const clear = $('#exchange-search-clear');

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
            })
            .catch(err => {
                console.error("Greška pri učitavanju kursne liste:", err);
            });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const infoElement = document.querySelector(".dataTables_info");
    if (infoElement && infoElement.parentElement) {
        const parent = infoElement.parentElement;
        parent.replaceWith(...parent.childNodes); // unwrap the parent
    }
});

</script>





@endsection