@extends('layouts.master')
@section('title')
@lang('translation.calendar')
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ba
@endslot
@slot('title')
Kalendarni prikaz mojih faktura
@endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="row align-items-stretch">
            <div class="col-xl-3 d-flex flex-column ">
                <div class="card shadow-none mb-4">
                    <div class="card-body bg-info-subtle rounded">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i data-feather="calendar" class="text-info icon-dual-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="fs-15">Dobrodošli u interaktivni kalendar</h6>
                                <p class="text-muted mb-0">Ovdje imate pristup svim skeniranim fakturama i njihovim
                                    detaljima</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-h-100 mb-3">
                    <div class="card-body">
                        <button class="btn btn-info w-100" id="btn-new-event" data-bs-toggle="modal"
                            data-bs-target="#scanModal">
                            <i class="fas fa-wand-magic-sparkles fs-6 me-1"></i><span class="fs-6">Skeniraj deklaraciju s
                                AI</span>
                        </button>
                        <div id="external-events d-flex justify-content-center" class="mt-3">
                            <p class="text-muted w-100 text-center mb-0">Klikni za skeniranje nove deklaracije!</p>
                        </div>
                    </div>
                </div>
                <div class="card card-h-100">
                    <div class="card-body">
                        <a href="{{ url('moje-deklaracije') }}" class="btn btn-info w-100" id="btn-new-event">
                            <i class="fa fa-file fs-6"></i> <span class="fs-6">Sve deklaracije</span>
                        </a>
                        <div id="external-events" class="mt-3">
                            <p class="text-muted w-100 text-center mb-0">Klikni da pogledaš sve deklaracije!</p>
                        </div>
                    </div>
                </div>






                <div class="flex-grow-1 d-flex flex-column mb-2 overflow-hidden">
                    <h5 class="mb-3">Zadnje skenirane deklaracije</h5>
                    <div id="latest-invoices-list" class="pe-2 me-n1 flex-grow-1 overflow-auto">
                        <!-- Invoice cards will be inserted here -->
                    </div>
                </div>



            </div>

            <div class="col-xl-9 h-100">
                <div class="card h-100">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!--end row-->




    </div>
</div> <!-- end row-->

<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100"><i class="fas fa-file-alt"
                        style="font-size:14px;margin-top:-7px!important"></i> Pregled deklaracije</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row justify-content-center">
                    <div class="card" id="demo">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-header border-bottom-dashed p-4 d-flex justify-content-between">
                                    <div>
                                        <img src="{{ URL::asset('build/images/logo-dek.png') }}" class="card-logo"
                                            alt="logo" height="30">
                                        <div class="mt-4">
                                            <h6 class="text-muted text-uppercase fw-semibold">Adresa
                                            </h6>
                                            <p class="text-muted mb-1" id="address-details">--</p>
                                            <p class="text-muted mb-0" id="zip-code"><span>Poštanski
                                                    broj:</span> --</p>
                                        </div>
                                    </div>
                                    <div class="text-end">

                                        <h6><span class="text-muted fw-normal">Email:</span> <span id="email">--</span>
                                        </h6>
                                        <h6><span class="text-muted fw-normal">Web:</span> <a href="#"
                                                class="link-primary" target="_blank" id="website">--</a>
                                        </h6>
                                        <h6 class="mb-0"><span class="text-muted fw-normal">Telefon:</span> <span
                                                id="contact-no">--</span></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">
                                                Faktura #</p>
                                            <h5 class="fs-14 mb-0">#<span id="invoice-no">--</span></h5>
                                        </div>
                                        <div class="col-lg-3 col-6">
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Datum
                                            </p>
                                            <h5 class="fs-14 mb-0"><span id="invoice-date">--</span>
                                            </h5>
                                        </div>
                                        <div class="col-lg-3 col-6">
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">
                                                Skenirana</p>
                                            <span class="badge bg-light text-dark fs-11" id="payment-status">--</span>
                                        </div>
                                        <div class="col-lg-3 col-6">
                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan
                                                iznos</p>
                                            <h5 class="fs-14 mb-0"><span id="total-amount">--</span> KM
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="card-body p-4 border-top border-top-dashed">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">
                                                klijent</h6>
                                            <p class="fw-medium mb-2" id="billing-name">--</p>
                                            <p class="text-muted mb-1" id="billing-address-line-1">--
                                            </p>
                                            <p class="text-muted mb-1"><span>Telefon: </span><span
                                                    id="billing-phone-no">--</span></p>
                                            <p class="text-muted mb-0"><span>PIB: </span><span
                                                    id="billing-tax-no">--</span></p>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">
                                                Zemlja porijekla</h6>
                                            <p class="fw-medium mb-2" id="shipping-country">--</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Items -->
                            <div class="col-lg-12">
                                <div class="card-body p-4">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-borderless text-center table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr class="table-active">
                                                    <th>#</th>
                                                    <th>Artikal</th>
                                                    <th>Opis</th>
                                                    <th>Cijena</th>
                                                    <th>Količina</th>
                                                    <th>Ukupno</th>
                                                </tr>
                                            </thead>
                                            <tbody id="products-list">
                                                <!-- Dynamic rows will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="col-lg-12">
                                <div class="card-body pt-0">
                                    <div class="border-top border-top-dashed mt-2">
                                        <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                            style="width:250px">
                                            <tbody>
                                                <tr class="border-top border-top-dashed fs-15">
                                                    <th scope="row">Ukupno</th>
                                                    <th class="text-end"><span id="modal-total-amount">
                                                        </span> USD</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Detalji
                                            plaćanja:</h6>
                                        <p class="text-muted mb-1">Način plaćanja: <span
                                                class="fw-medium">Kartica</span></p>
                                        <p class="text-muted mb-1">Ime vlasnika kartice: <span class="fw-medium">Tin
                                                Tomić</span></p>
                                        <p class="text-muted mb-1">Broj kartice: <span class="fw-medium">xxxx xxxx xxxx
                                                1234</span></p>
                                        <p class="text-muted">Ukupno za platiti: <span class="fw-medium"><span
                                                    id="payment-method-amount">755.96</span> KM</span>
                                        </p>
                                    </div>

                                    <div class="mt-4">
                                        <div class="alert alert-info">
                                            <p class="mb-0"><span class="fw-semibold">Napomena:</span>
                                                <span id="note">Račun je informativnog karaktera.
                                                    Provjerite detalje prije plaćanja.</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                        <a href="javascript:window.print()" class="btn btn-success">
                                            <i class="ri-printer-line align-bottom me-1"></i> Print
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-primary">
                                            <i class="ri-download-2-line align-bottom me-1"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- row -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    /* Force .view-invoice links to always be white */
    .view-invoice,
    .view-invoice:visited {
        color: #fff !important;
        font-weight: bold;
        text-decoration: none;
    }

    .view-invoice:hover {
        text-decoration: underline;
        color: #cce5ff !important;
        /* optional lighter hover */
    }

    .fc .fc-day-today {
        background-color: #e6f7ff !important;
        /* Light blue */
        border-radius: 5px;
    }

    /* Optionally, also tweak number color inside today cell */
    .fc .fc-day-today .fc-daygrid-day-number {
        color: #299cdb;
        /* Bootstrap primary blue */
        font-weight: bold;
    }

    .modal-dialog.modal-xl {
        max-width: 75vw;
        /* or set fixed px: 1200px, 1400px */
    }

    /* Make calendar cells wider */
    #calendar .fc-daygrid-day-frame {
        min-height: 100px;
        /* Increase height of each cell */
        padding: 10px;
    }

    /* Optional: enlarge day number */
    #calendar .fc-daygrid-day-number {
        font-size: 1rem;
        font-weight: 600;
    }

    /* Optional: widen full calendar container */
    #calendar {
        font-size: 0.95rem;
        padding: 10px;
    }

    /* Optional: override FullCalendar’s grid spacing */
    .fc .fc-daygrid-body-natural .fc-daygrid-day {
        flex: 1 0 14.2857143%;
        /* forces equal width 7 days */
        max-width: none;
    }

    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background-color: #299cdb !important;
        /* color:#fff; */
    }

    .fc .fc-list-day-text {
        background-color: transparent !important;
        color: #212529 !important;
        /* Bootstrap's "text-dark" color */
        font-weight: 600;
    }

    .fc .fc-list-day-side-text {
        background-color: transparent !important;
        color: #212529 !important;
        /* Bootstrap's "text-dark" color */
        font-weight: 600;
    }

    /* Optional: Change the event title color */
    .fc .fc-list-event-title {
        color: #0d6efd !important;
        /* Bootstrap primary or any hex like #333 */
    }



    #latest-invoices-list {
        max-height: 550px;
        overflow-y: auto;
        padding-right: 5px;
    }
</style>

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="{{ URL::asset('build/libs/jquery/jquery.min.js') }}"></script> <!-- jQuery must come first -->
<script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/calendar.init.js') }}"></script>


<!-- Calendar dynamic logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        

        console.log('[Calendar] Loaded user:', user);
        console.log('[Calendar] Loaded token:', token);
    
        const userId = user?.id;
        console.log('[Calendar] Using userId:', userId);

        if (!userId || !token) {
            console.error('[Calendar] Missing user ID or auth token', { userId, token });
            return;
        }

        fetch(`/api/invoices/users/${userId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                console.log('[Calendar] Invoice fetch response status:', response.status);
                return response.json();
            })
            .then(invoices => {
                console.log('[Calendar] Raw invoices from API:', invoices);
                if (!Array.isArray(invoices)) {
                    invoices = [];
                }
                invoices = invoices.filter(i => i.date_of_issue);
                console.log('[Calendar] Filtered invoices with date_of_issue:', invoices);

                const events = invoices.map(invoice => ({
                    id: invoice.id,
                    title: invoice.file_name || `Faktura #${invoice.id}`,
                    start: invoice.date_of_issue,
                    allDay: true,
                    className: 'bg-info text-white',
                    extendedProps: {
                        invoiceData: invoice
                    }
                }));
                console.log('[Calendar] Events to render:', events);

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    themeSystem: 'bootstrap5',
                    firstDay: 1,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'multiMonthYear,dayGridMonth,listMonth'
                    },
                    buttonText: {
                        today: 'Danas',
                        month: 'Mjesec',
                        list: 'Lista',
                        week: 'Sedmica',
                        day: 'Dan',
                        year: 'Godina'
                    },
                    dayMaxEvents: 1,
                    moreLinkContent: function(args) {
                        return 'Prikaži još ' + args.num;
                    },
                    moreLinkDidMount: function(info) {
                        info.el.setAttribute('title', `Prikaži još ${info.num}`);
                        info.el.style.cursor = 'pointer';
                    },
                    events: events,
                    eventDidMount: function(info) {
                        const invoice = info.event.extendedProps.invoiceData;
                        const supplierName = invoice.supplier?.name || 'Nepoznat klijent';
                        const price = invoice.total_price || 'N/A';
                        info.el.setAttribute('title', `Fajl: ${invoice.file_name}\nklijent: ${supplierName}\nCijena: ${price} KM`);
                        info.el.style.cursor = 'pointer';
                    },
                    eventClick: function(info) {
                        // Redirect to details page instead of opening modal
                        const invoiceId = info.event.id;
                        window.open(`/detalji-deklaracije/${invoiceId}`, '_blank'); // open in new tab
                        // Or replace window.open with:
                        // window.location.href = `/detalji-fakture/${invoiceId}`; // open in same tab
                    },
                    dayHeaderContent: function(arg) {
                        const days = ['NED', 'PON', 'UTO', 'SRI', 'ČET', 'PET', 'SUB'];
                        return days[arg.date.getDay()];
                    },
                    titleFormat: function(date) {
                        const months = ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Juni', 'Juli', 'August', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'];
                        return `${months[date.date.month]} ${date.date.year}`;
                    },
                    datesSet: function() {
                        setTimeout(translateTooltips, 0);
                    }
                });

                calendar.render();
                console.log('[Calendar] Calendar rendered.');

                latestInvoicesList(invoices);
            })
            .catch(error => {
                console.error('[Calendar] Greška pri učitavanju faktura:', error);
            });

        function latestInvoicesList(invoices) {
            invoices = invoices.filter(i => i.created_at);
            invoices.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            const latestTen = invoices.slice(0, 10);
            const container = document.getElementById("latest-invoices-list");
            container.innerHTML = "";

            latestTen.forEach(invoice => {
                const supplierName = invoice.supplier?.name || "Nepoznat klijent";
                const fileName = invoice.file_name || "Nepoznat naziv";
                const totalPrice = parseFloat(invoice.total_price || 0).toFixed(2);
                const date = new Date(invoice.created_at).toLocaleDateString("hr", {
                    day: "numeric",
                    month: "long",
                    year: "numeric"
                });

                const cardHTML = `
                <div class='card mb-3 cursor-pointer view-invoice' data-id="${invoice.id}">
                    <div class='card-body'>
                    <div class='d-flex mb-4'>
                        <div class='flex-grow-1'>
                        <i class='mdi mdi-file-document-outline me-2 text-info'></i>
                        <span class='fw-medium text-info'>${date}</span>
                        </div>
                        <div class='flex-shrink-0'>
                        <small class='badge bg-info-subtle text-info ms-auto'>${totalPrice} KM</small>
                        </div>
                    </div>
                    <h6 class='card-title fs-16 text-truncate' title="${fileName}">${fileName}</h6>
                    <p class='text-muted text-truncate mb-0' title="${supplierName}">${supplierName}</p>
                    </div>
                </div>
                `;

                container.innerHTML += cardHTML;
            });
        }

        // Redirect on click instead of opening modal
        $(document).on('click', '.view-invoice', function() {
            const invoiceId = $(this).data('id');
             window.location.href = `/detalji-deklaracije/${invoiceId}`
            // Or:
            // window.location.href = `/detalji-fakture/${invoiceId}`;
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthMap = {
            "January": "Januar",
            "February": "Februar",
            "March": "Mart",
            "April": "April",
            "May": "Maj",
            "June": "Juni",
            "July": "Juli",
            "August": "August",
            "September": "Septembar",
            "October": "Oktobar",
            "November": "Novembar",
            "December": "Decembar"
        };

        function translateMonthTitles(selector) {
            document.querySelectorAll(selector).forEach(el => {
                const original = el.textContent.trim();
                const words = original.split(" ");
                const translatedWords = words.map(word => monthMap[word] || word);
                const translated = translatedWords.join(" ");
                if (translated !== original) {
                    el.textContent = translated;
                }
            });
        }

        function translateAll() {
            translateMonthTitles('.fc .fc-multimonth-title');
            translateMonthTitles('.fc .fc-popover-title');
        }

        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            const observer = new MutationObserver(() => {
                translateAll();
            });
            observer.observe(calendarEl, {
                childList: true,
                subtree: true
            });
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new MutationObserver(() => {
            const noEventsEl = document.querySelector(".fc-list-empty-cushion");
            if (noEventsEl && noEventsEl.textContent.includes("No events to display")) {
                noEventsEl.textContent = "Nema skeniranih faktura za ovaj dan";
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    });
</script>

<script>
    function translateTooltips() {
        const prevBtn = document.querySelector('.fc-prev-button');
        const nextBtn = document.querySelector('.fc-next-button');
        const todayBtn = document.querySelector('.fc-today-button');

        if (prevBtn?.title.includes("Godina")) {
            prevBtn.setAttribute('title', 'Prethodna godina');
        } else {
            prevBtn.setAttribute('title', 'Prethodni mjesec');
        }

        if (nextBtn?.title.includes("Godina")) {
            nextBtn.setAttribute('title', 'Sljedeća godina');
        } else {
            nextBtn.setAttribute('title', 'Sljedeći mjesec');
        }

        todayBtn?.setAttribute('title', 'Danas');

        document.querySelector('.fc-multiMonthYear-button')?.setAttribute('title', 'Godišnji pregled');
        document.querySelector('.fc-dayGridMonth-button')?.setAttribute('title', 'Mjesečni pregled');
        document.querySelector('.fc-listMonth-button')?.setAttribute('title', 'Pregled kroz listu');

        document.querySelectorAll('.fc-popover-close').forEach(el => {
            if (el.getAttribute('title') === 'Close') {
                el.setAttribute('title', 'Zatvori');
            }
        });

        document.querySelectorAll('.fc-list-empty-cushion').forEach(el => {
            if (el.textContent.includes('No events to display')) {
                el.textContent = 'Nema skeniranih faktura za ovaj dan';
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const observer = new MutationObserver(() => {
            translateTooltips();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    });
</script>





@endsection