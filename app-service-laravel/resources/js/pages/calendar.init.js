/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Updated and Debugged by: ChatGPT
*/

console.log('calendar.init.js script loaded');

var calendar = null;
var start_date = document.getElementById("event-start-date");
var timepicker1 = document.getElementById("timepicker1");
var timepicker2 = document.getElementById("timepicker2");

document.addEventListener('DOMContentLoaded', function () {
    console.log('[INIT] DOM loaded.');

    const calendarEl = document.getElementById('calendar');
    const token = localStorage.getItem("auth_token");
    const user = JSON.parse(localStorage.getItem("user"));
    const userId = user?.id;

    console.log('[INIT] Token:', token);
    console.log('[INIT] User:', user);
    console.log('[INIT] UserID:', userId);

    if (!calendarEl || !token || !userId) {
        console.error('[INIT] Missing calendar element, token, or user ID.');
        return;
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        editable: false,
        droppable: false,
        selectable: false,
        navLinks: true,
        initialView: getInitialView(),
        themeSystem: 'bootstrap',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        windowResize: function () {
            var newView = getInitialView();
            calendar.changeView(newView);
        },
        eventContent: function (arg) {
            return { html: arg.event.title };
        }
    });

    console.log('[CALENDAR] Initialized.');

    calendar.render();
    console.log('[CALENDAR] Rendered.');

    fetchInvoicesAndPopulateCalendar(token, userId);
    setupInvoiceModal(token);
});

function getInitialView() {
    if (window.innerWidth >= 768 && window.innerWidth < 1200) {
        return 'timeGridWeek';
    } else if (window.innerWidth <= 768) {
        return 'listMonth';
    } else {
        return 'dayGridMonth';
    }
}

async function fetchInvoicesAndPopulateCalendar(token, userId) {
    console.log('[FETCH] Fetching invoices for user ID:', userId);

    try {
        const response = await fetch(`/api/invoices/users/${userId}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        console.log('[FETCH] Response status:', response.status);

        if (!response.ok) {
            throw new Error('Failed to fetch invoices');
        }

        const invoices = await response.json();
        console.log('[FETCH] Invoices received:', invoices);

        invoices.forEach(invoice => {
            console.log('[FETCH] Adding invoice to calendar:', invoice.file_name, invoice.date_of_issue);

            calendar.addEvent({
                title: `<div class="alert alert-info py-1 px-2 mb-0 d-inline-block" role="alert" style="border-radius: 10px;">
                            <a href="#" class="view-invoice text-info fw-bold text-decoration-none" data-id="${invoice.id}">
                                ${invoice.file_name}
                            </a>
                        </div>`,

                start: invoice.date_of_issue,
                backgroundColor: 'transparent',
                borderColor: 'transparent',
                textColor: '#inherit',
                allDay: true
            });
        });

        console.log('[FETCH] All invoices added to calendar.');

    } catch (error) {
        console.error('[FETCH] Error fetching invoices:', error);
    }
}

function setupInvoiceModal(token) {
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('view-invoice')) {
            e.preventDefault();
            const invoiceId = e.target.getAttribute('data-id');
            console.log('[MODAL] Clicked invoice ID:', invoiceId);

            fetch(`/api/invoices/${invoiceId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(invoice => {
                console.log('[MODAL] Invoice fetched:', invoice);
                return fetch(`/api/suppliers/${invoice.supplier_id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(res => res.json())
                .then(supplier => ({ invoice, supplier }));
            })
            .then(({ invoice, supplier }) => {
                console.log('[MODAL] Supplier fetched:', supplier);
                fillInvoiceModal(invoice, supplier);
            })
            .catch(err => {
                console.error('[MODAL] Error loading invoice details:', err);
                alert('Greška pri učitavanju fakture.');
            });
        }
    });
}

function fillInvoiceModal(invoice, supplier) {
    console.log('[MODAL] Filling modal with invoice and supplier data.');

    const setTextContent = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    };

    setTextContent('invoice-no', invoice.id);
    setTextContent('invoice-date', new Date(invoice.date_of_issue).toLocaleDateString('hr'));
    setTextContent('total-amount', `${parseFloat(invoice.total_price).toFixed(2)} KM`);
    setTextContent('card-total-amount', `${parseFloat(invoice.total_price).toFixed(2)}`);
    setTextContent('address-details', invoice.country_of_origin || '-');
    setTextContent('payment-status', invoice.scanned === 1 ? 'Skenirano' : 'Nije skenirano');
    setTextContent('shipping-country', invoice.country_of_origin || '--');

    setTextContent('billing-name', supplier.name || '--');
    setTextContent('billing-address-line-1', supplier.address || '--');
    setTextContent('billing-phone-no', supplier.contact_phone || '--');
    setTextContent('billing-tax-no', supplier.tax_id || '--');
    setTextContent('email', supplier.contact_email || '--');

    const productsList = document.getElementById('products-list');
    if (productsList) {
        productsList.innerHTML = '';
        let totalSum = 0;

        invoice.items.forEach((item, index) => {
            const price = parseFloat(item.total_price || 0);
            totalSum += price;

            const row = document.createElement('tr');
            row.innerHTML = `
                <th scope="row">${index + 1}</th>
                <td class="text-start fw-medium">${item.item_description}</td>
                <td class="text-muted">${item.item_description_original}</td>
                <td>${item.base_price} ${item.currency}</td>
                <td>${item.quantity}</td>
                <td class="text-end">${item.total_price} ${item.currency}</td>
            `;
            productsList.appendChild(row);
        });

        setTextContent('modal-total-amount', totalSum.toFixed(2));
    }

    const modal = new bootstrap.Modal(document.getElementById('invoiceDetailsModal'));
    modal.show();

    console.log('[MODAL] Modal shown.');
}
