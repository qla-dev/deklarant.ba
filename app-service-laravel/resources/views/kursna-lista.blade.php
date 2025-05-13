@extends('layouts.master')
@section('title')

@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    #exchangeTable_wrapper .dataTables_paginate {
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

    #exchangeTable_wrapper .dataTables_paginate .paginate_button {
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

    #exchangeTable_wrapper .table {
        margin-bottom: 0 !important;
    }


    #exchangeTable_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #eff2f7;

        color: #299cdb !important;
        cursor: pointer;
    }

    #exchangeTable_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #299cdb !important;
        color: #fff !important;
        border-color: #299cdb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);

    }

    #exchangeTable_wrapper .dataTables_paginate .paginate_button:focus {
        outline: none;
        box-shadow: none;
    }

    #exchangeTable_wrapper .dataTables_paginate .paginate_button.disabled {
        background-color: #eff2f7;
        color: #adb5bd !important;
        /* light gray text */
        border-color: #dee2e6;
        cursor: not-allowed;
        pointer-events: none;
        font-weight: bold;

        cursor: pointer;
    }

    #exchangeTable_wrapper .dataTables_paginate .paginate_button.previous,
    #exchangeTable_wrapper .dataTables_paginate .paginate_button.next,
    #exchangeTable_wrapper .dataTables_paginate .paginate_button.first,
    #exchangeTable_wrapper .dataTables_paginate .paginate_button.last {
        width: 50px;
        /* or whatever width you want */
    }

    #exchangeTable_wrapper .dataTables_info {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    #exchangeTable_wrapper .dataTables_paginate {
        margin-top: 4px !important;
        /* reduce from default ~16px */
        padding-top: 0 !important;
    }

    #exchangeTable_wrapper .dataTables_length,
    #exchangeTable_wrapper .dataTables_filter {
        margin-bottom: 4px !important;
        padding-bottom: 0 !important;

    }

    .table-card .dataTables_filter {
        padding: 0 !important;
        margin: 0 !important;
        /* optional for tighter alignment */
    }

    #exchangeTable_wrapper .dataTables_filter input {
        margin-left: 0 !important;
    }
    .table-card .dataTables_info{
        padding-left: 0 !important;
    }
    #invoiceList {
    --vz-card-spacer-y: 10px; /* or any smaller value you want */
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
Kursna lista
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
                    <table id="exchangeTable" class="table mb-2 w-100">
                        <thead class="table-info">
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
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem("auth_token");
        const user = JSON.parse(localStorage.getItem("user"));

        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        const currencyCodebook = {
            280: 'DEM',
            724: 'ESP',
            40: 'ATS',
            56: 'BEF',
            246: 'FIM',
            250: 'FRF',
            300: 'GRD',
            372: 'IEP',
            380: 'ITL',
            620: 'PTE',
            442: 'LUF',
            978: 'EUR',
            36: 'AUD',
            124: 'CAD',
            203: 'CZK',
            208: 'DKK',
            348: 'HUF',
            392: 'JPY',
            578: 'NOK',
            752: 'SEK',
            756: 'CHF',
            949: 'TRY',
            826: 'GBP',
            840: 'USD',
            643: 'RUB',
            156: 'CNY',
            941: 'RSD'
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

        fetch("/api/exchange-rates", {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
            .then(res => res.json())
            .then(data => {
                const items = (data.CurrencyExchangeItems || [])
                    .filter(item => item.AlphaCode !== 'XDR') //  filter out IMF
                    .map(item => {
                        const original = item.Country;
                        const normalized = original.trim().toLowerCase().replace(/\s+/g, ' ').replace(/\.$/, '');
                        const flagCode = countryFlagMap[normalized] || 'un';
                        const displayName = countryNameBS[normalized] || original;

                       

                        return {
                            ...item,
                            Code: item.NumCode,
                            FlagCode: flagCode,
                            CountryBS: displayName
                        };
                    });

                $('#exchangeTable').DataTable({
                    data: items,
                    scrollX: true,
                    autoWidth: true,
                    lengthChange: false,
                    pageLength: 16,
                    paging: false,
                    columns: [{
                            data: null,
                            title: '#',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: 'Code',
                            title: 'Šifra'
                        },
                        {
                            data: 'AlphaCode',
                            title: 'Valuta'
                        },
                        {
                            data: 'CountryBS',
                            title: 'Zemlja',
                            render: function(countryBS, type, row) {
                                return `
                            <img src="https://flagcdn.com/${row.FlagCode}.svg"
                                 class="rounded-circle me-2"
                                 style="height: 24px; width: 24px; object-fit: cover;"
                                 alt="${countryBS}" />
                            <span>${countryBS}</span>
                        `;
                            }
                        },
                        {
                            data: 'Units',
                            title: 'Jedinica'
                        },
                        {
                            data: 'Buy',
                            title: 'Kupovni kurs'
                        },
                        {
                            data: 'Middle',
                            title: 'Srednji kurs'
                        },
                        {
                            data: 'Sell',
                            title: 'Prodajni kurs'
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
                        info: "",
                        infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
                        infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
                        search: "",
                        zeroRecords: "Nema pronađenih stavki"
                    },
                    initComplete: function() {
                        const api = this.api();

                        $('#exchangeTable_filter')
                            .addClass('flex-grow-1 me-0')
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

                        const input = $('#exchange-search-input');
                        const clear = $('#exchange-search-clear');

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
                    }
                });
            })
            .catch(err => {
                console.error("Greška pri učitavanju kursne liste:", err);
            });
    });
</script>







@endsection