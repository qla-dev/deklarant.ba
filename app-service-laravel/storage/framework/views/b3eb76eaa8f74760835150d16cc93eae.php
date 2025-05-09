
<?php $__env->startSection('title'); ?>
<?php echo app('translator')->get('translation.calendar'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?>
Apps
<?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?>
Kalendar faktura
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="row">
    <div class="col-12">
        <div class="row align-items-stretch">
            <div class="col-xl-3 d-flex flex-column ">
                <div class="card card-h-100 mb-3">
                    <div class="card-body">
                        <button class="btn btn-info w-100" id="btn-new-event">
                            <i class="fas fa-wand-magic-sparkles fs-6 me-1"></i><span class="fs-6">Skeniraj fakturu s AI</span>
                        </button>
                        <div id="external-events d-flex justify-content-center" class="mt-3">
                            <p class="text-muted">Klikni za skeniranje nove fakture!</p>
                        </div>
                    </div>
                </div>
                <div class="card card-h-100">
                    <div class="card-body">
                        <a href="<?php echo e(url('apps-invoices-list')); ?>" class="btn btn-info w-100" id="btn-new-event">
                            <i class="fa fa-file fs-6"></i> <span class="fs-6">Sve fakture</span>
                        </a>
                        <div id="external-events" class="mt-3">
                            <p class="text-muted">Klikni da pogledaš sve fakture!</p>
                        </div>
                    </div>
                </div>


                <div class="card shadow-none mb-4">
                    <div class="card-body bg-info-subtle rounded">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i data-feather="calendar" class="text-info icon-dual-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="fs-15">Dobrodošli u kalendar!</h6>
                                <p class="text-muted mb-0">Ovdje imate pristup svim skeniranim fakturama i njihovim detaljima</p>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="flex-grow-1 d-flex flex-column mb-2 overflow-hidden">
                    <h5 class="mb-3">Zadnje skenirane fakture</h5>
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
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pregled fakture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row justify-content-center">
                    <div class="col-xxl-9">
                        <div class="card" id="demo">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div
                                        class="card-header border-bottom-dashed p-4 d-flex justify-content-between">
                                        <div>
                                            <img src="<?php echo e(URL::asset('build/images/logo.svg')); ?>"
                                                class="card-logo" alt="logo" height="30">
                                            <div class="mt-4">
                                                <h6 class="text-muted text-uppercase fw-semibold">Adresa
                                                </h6>
                                                <p class="text-muted mb-1" id="address-details">--</p>
                                                <p class="text-muted mb-0" id="zip-code"><span>Poštanski
                                                        broj:</span> --</p>
                                            </div>
                                        </div>
                                        <div class="text-end">

                                            <h6><span class="text-muted fw-normal">Email:</span> <span
                                                    id="email">--</span></h6>
                                            <h6><span class="text-muted fw-normal">Web:</span> <a href="#"
                                                    class="link-primary" target="_blank" id="website">--</a>
                                            </h6>
                                            <h6 class="mb-0"><span
                                                    class="text-muted fw-normal">Telefon:</span> <span
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
                                                <span class="badge bg-light text-dark fs-11"
                                                    id="payment-status">--</span>
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
                                                    Dobavljač</h6>
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
                                            <table
                                                class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
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
                                            <p class="text-muted mb-1">Ime vlasnika kartice: <span
                                                    class="fw-medium">Tin Tomić</span></p>
                                            <p class="text-muted mb-1">Broj kartice: <span
                                                    class="fw-medium">xxxx xxxx xxxx 1234</span></p>
                                            <p class="text-muted">Ukupno za platiti: <span
                                                    class="fw-medium"><span
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
</div>
<?php $__env->stopSection(); ?>
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
    color: #212529 !important; /* Bootstrap's "text-dark" color */
    font-weight: 600;
    }
    .fc .fc-list-day-side-text {
    background-color: transparent !important;
    color: #212529 !important; /* Bootstrap's "text-dark" color */
    font-weight: 600;
    }

/* Optional: Change the event title color */
    .fc .fc-list-event-title {
        color: #0d6efd !important; /* Bootstrap primary or any hex like #333 */
    }

    

    #latest-invoices-list {
        max-height: 550px;
        overflow-y: auto;
        padding-right: 5px;
    }
    
</style>

<?php $__env->startSection('script'); ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="<?php echo e(URL::asset('build/libs/jquery/jquery.min.js')); ?>"></script> <!-- jQuery must come first -->
<script src="<?php echo e(URL::asset('build/libs/fullcalendar/index.global.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/pages/calendar.init.js')); ?>"></script>


<!-- Calendar dynamic logic -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const token = localStorage.getItem('auth_token');
        const user = JSON.parse(localStorage.getItem("user"));
        const userId = user?.id;

        if (!userId || !token) {
            console.error("Missing user ID or auth token");
            return;
        }

        fetch(`/api/invoices/users/${userId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(invoices => {
            invoices = invoices.filter(i => i.date_of_issue);

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

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'multiMonthYear,dayGridMonth,listMonth'
                },
                events: events,
                eventDidMount: function (info) {
                    const invoice = info.event.extendedProps.invoiceData;
                    const supplierName = invoice.supplier?.name || 'Nepoznat dobavljač';
                    const price = invoice.total_price || 'N/A';
                    info.el.setAttribute('title', `Fajl: ${invoice.file_name}\nDobavljač: ${supplierName}\nCijena: ${price} KM`);
                    info.el.style.cursor = 'pointer';
                },
                eventClick: function (info) {
                    openInvoiceModal(info.event.id);
                }
            });

            calendar.render();
            latestInvoicesList(invoices);
        })
        .catch(error => {
            console.error('Greška pri učitavanju faktura:', error);
        });

        function latestInvoicesList(invoices) {
            invoices = invoices.filter(i => i.created_at);
            invoices.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            const latestTen = invoices.slice(0, 10);
            const container = document.getElementById("latest-invoices-list");
            container.innerHTML = "";

            latestTen.forEach(invoice => {
                const supplierName = invoice.supplier?.name || "Nepoznat dobavljač";
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
                                    <span class='fw-medium'>${date}</span>
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

        // Delegated event for view-invoice cards (latest scanned invoices)
        $(document).on('click', '.view-invoice', function () {
            const invoiceId = $(this).data('id');
            openInvoiceModal(invoiceId);
        });

        function openInvoiceModal(invoiceId) {
            const token = localStorage.getItem("auth_token");

            fetch(`/api/invoices/${invoiceId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(invoice =>
                fetch(`/api/suppliers/${invoice.supplier_id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(res => res.json())
                .then(supplier => ({
                    invoice,
                    supplier
                }))
            )
            .then(({ invoice, supplier }) => {
                $('#invoice-no').text(invoice.id);
                $('#invoice-date').text(new Date(invoice.date_of_issue).toLocaleDateString('hr'));
                $('#total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)} KM`);
                $('#modal-total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)} KM`);
                $('#payment-status').text(invoice.scanned ? 'Skenirana' : 'Nije skenirana');
                $('#shipping-country').text(invoice.country_of_origin || '--');
                $('#billing-name').text(supplier.name || '--');
                $('#billing-address-line-1').text(supplier.address || '--');
                $('#billing-phone-no').text(supplier.contact_phone || '--');
                $('#billing-tax-no').text(supplier.tax_id || '--');
                $('#email').text(supplier.contact_email || '--');

                let itemsHTML = '';
                let totalSum = 0;

                invoice.items.forEach((item, index) => {
                    const price = parseFloat(item.total_price || 0);
                    totalSum += price;
                    itemsHTML += `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td class="text-start fw-medium">${item.item_description_original}</td>
                            <td class="text-muted text-wrap" style="white-space: normal; word-break: break-word; max-width: 500px;">${item.item_description}</td>
                            <td>${item.base_price} ${item.currency}</td>
                            <td>${item.quantity}</td>
                            <td class="text-end">${item.total_price} ${item.currency}</td>
                        </tr>`;
                });

                $('#products-list').html(itemsHTML);
                $('#modal-total-amount').text(totalSum.toFixed(2));

                // Fix backdrop issue if modal previously failed
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');

                // Show modal
                const existingModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('invoiceDetailsModal'));
                existingModal.show();

            })
            .catch(err => {
                console.error('Greška pri učitavanju fakture:', err);
                alert('Greška pri učitavanju fakture.');
            });
        }
    });
</script>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/apps-calendar.blade.php ENDPATH**/ ?>