// ================ Google Search Functions ================

function getSearchQueryFromRow(row) {
    const nameInput = row.querySelector('.item-name');
    const descInput = row.querySelector('.item-desc');
    const name = nameInput?.value.trim() || '';
    const desc = descInput?.value.trim() || '';
    return encodeURIComponent(`${desc} hs code`);
}

function removeExistingTooltip(button) {
    const instance = bootstrap.Tooltip.getInstance(button);
    if (!instance) {
        console.warn("[Tooltip] No instance found on button");
        return;
    }

    console.log("[Tooltip] Instance found");
    instance.dispose();
    console.log("[Tooltip] instance.dispose() called");

    const tooltipId = button.getAttribute('aria-describedby');
    if (tooltipId) {
        const tooltipEl = document.getElementById(tooltipId);
        if (tooltipEl) {
            tooltipEl.remove();
            console.log(`[Tooltip] Removed lingering tooltip: ${tooltipId}`);
        }
        button.removeAttribute('aria-describedby');
    }
}

function reinitializeTooltip(button) {
    setTimeout(() => {
        new bootstrap.Tooltip(button, {
            trigger: 'hover',
            delay: { show: 100, hide: 100 }
        });
        console.log("[Tooltip] Reinitialized after click");
    }, 300);
}

function showMissingDescriptionAlert() {
    Swal.fire({
        icon: 'info',
        title: 'Nedostaje opis',
        text: 'Unesite naziv ili opis proizvoda za Google pretragu.',
        confirmButtonText: 'Uredu',
        confirmButtonColor: '#299dcb'
    });
}

function searchFromInputs(button) {
    console.log("[Tooltip] Google button clicked");
    const row = button.closest('tr');
    const query = getSearchQueryFromRow(row);

    removeExistingTooltip(button);

    if (query) {
        const url = `https://www.google.com/search?q=${query}`;
        console.log(`[Google] Opening search URL: ${url}`);
        window.open(url, '_blank');
    } else {
        console.log("[Google] Missing name or desc. Showing Swal.");
        showMissingDescriptionAlert();
    }

    reinitializeTooltip(button);
}

// ================ Invoice Saving Functions ================

function setupSaveButtonLoadingState(btn) {
    if (!btn) return;
    btn.setAttribute("data-disabled", "true");
    btn.classList.add("position-relative");
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="vertical-align: text-bottom;"></span>
        Spašavanje...
    `;
    btn.classList.remove("btn-secondary");
    btn.classList.add("btn-info");
}

function resetSaveButtonState(btn) {
    if (!btn) return;
    btn.disabled = false;
    btn.innerHTML = `<i class="ri-save-line align-bottom me-1"></i> Sačuvaj`;
}

function checkRequiredField(id, label, missingFields) {
    const input = document.getElementById(id);
    if (!input || !input.value.trim()) {
        input.classList.add("is-invalid");
        missingFields.push(label);
    } else {
        input.classList.remove("is-invalid");
    }
}

function validateRequiredFields() {
    let missingFields = [];

    checkRequiredField("billing-name", "Naziv klijenta", missingFields);
    checkRequiredField("billing-address-line-1", "Adresa klijenta", missingFields);
    checkRequiredField("carrier-name", "Naziv dobavljača", missingFields);
    checkRequiredField("carrier-address", "Adresa dobavljača", missingFields);

    return missingFields;
}

function showMissingFieldsAlert(missingFields) {
    Swal.fire("Greška", "Obavezna polja nisu popunjena:\n" + missingFields.join(", "), "error");
}

async function ensureEntity(endpoint, data, select2Id) {
    const selectedId = $(select2Id).val();
    if (selectedId === 'new') {
        const res = await fetch(`/api/${endpoint}`, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json?.error || `Greška kod spremanja ${endpoint}`);
        $(select2Id).append(new Option(companyNameForSelect2(json.data), json.data.id));
        $(select2Id).val(json.data.id).trigger("change");
        return json.data.id;
    }
    return selectedId;
}

async function handleSaveInvoice(btn) {
    setupSaveButtonLoadingState(btn);

    const missingFields = validateRequiredFields();
    if (missingFields.length > 0) {
        // showMissingFieldsAlert(missingFields);
        resetSaveButtonState(btn);
        return;
    }

    const userId = user?.id;
    if (!userId) {
        Swal.fire("Greška", "Korisnički ID nije pronađen", "error");
        return;
    }

    try {
        let supplierId = await getSupplierID();
        let importerId = await getImporterID();

        // if (!isValidId(supplierId)) {
        //     Swal.fire("Greška", "Molimo odaberite ili unesite validnog klijenta.", "error");
        //     resetSaveButtonState(btn);
        //     return;
        // }
        // if (!isValidId(importerId)) {
        //     Swal.fire("Greška", "Molimo odaberite ili unesite validnog dobavljača.", "error");
        //     resetSaveButtonState(btn);
        //     return;
        // }

        const invoiceId = getInvoiceId();
        console.log("💾 Saving to invoice ID:", invoiceId);

        const payload = buildInvoicePayload(supplierId, importerId);
        console.log(" Sending payload (update):", payload);

        const res = await fetch(`/api/invoices/${invoiceId}`, {
            method: "PUT",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            },
            body: JSON.stringify(payload)
        });

        const resJson = await res.json();

        if (!res.ok) {
            const backendMsg = resJson?.poruka || "Greška pri spašavanju deklaracije.";
            Swal.fire({
                icon: "error",
                title: "Neuspješno",
                html: `<div class="text-danger mb-2">${backendMsg}</div>`,
                confirmButtonText: "U redu",
                customClass: { confirmButton: "btn btn-info" },
                buttonsStyling: false
            });
            return;
        }

        // Swal.fire({
        //     icon: "success",
        //     title: "Uspješno",
        //     text: "Deklaracija je sačuvana",
        //     confirmButtonText: "U redu",
        //     customClass: { confirmButton: "btn btn-info" },
        //     buttonsStyling: false
        // });

    } catch (err) {
        console.error("Greška:", err);
        Swal.fire({
            icon: "error",
            title: "Greška",
            text: err.message || "Neočekivana greška pri komunikaciji sa serverom.",
            confirmButtonText: "U redu",
            customClass: { confirmButton: "btn btn-info" },
            buttonsStyling: false
        });
    } finally {
        resetSaveButtonState(btn);
    }
}

document.getElementById("save-invoice-btn")?.addEventListener("click", async function (e) {
    e.preventDefault();
    e.stopPropagation();
    await handleSaveInvoice(this);
});

// ================ XML Export Functions ================

function escapeXml(str) {
    const replacements = {
        "<": "&lt;",
        ">": "&gt;",
        "&": "&amp;",
        "'": "&apos;",
        '"': "&quot;",
    };
    return String(str).replace(/[<>&'"]/g, (c) => replacements[c]);
}

function buildXmlItem(item) {
    return `
  <item>
    <tarif>${escapeXml(item.tarif)}</tarif>
    <name>${escapeXml(item.name)}</name>
    <translation>${escapeXml(item.translation)}</translation>
    <origin>${escapeXml(item.origin)}</origin>
    <price>${escapeXml(item.price)}</price>
    <quantity>${escapeXml(item.quantity)}</quantity>
    <total>${escapeXml(item.total)}</total>
  </item>`;
}

function buildXmlInvoice(invoiceData) {
    let xml = `<invoice>\n`;
    invoiceData.items.forEach((item) => {
        xml += buildXmlItem(item);
    });
    xml += `  <subtotal>${escapeXml(invoiceData.subtotal)}</subtotal>\n`;
    xml += `  <tax>${escapeXml(invoiceData.tax)}</tax>\n`;
    xml += `  <discount>${escapeXml(invoiceData.discount)}</discount>\n`;
    xml += `  <shipping>${escapeXml(invoiceData.shipping)}</shipping>\n`;
    xml += `  <total>${escapeXml(invoiceData.total)}</total>\n`;
    xml += `</invoice>`;
    return xml;
}

function downloadXml(xml, filename) {
    const blob = new Blob([xml], { type: 'text/xml' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function handleExportXml() {
    const invoiceData = {
        items: [],
        subtotal: document.getElementById("cart-subtotal")?.value || "",
        tax: document.getElementById("cart-tax")?.value || "",
        discount: document.getElementById("cart-discount")?.value || "",
        shipping: document.getElementById("cart-shipping")?.value || "",
        total: document.getElementById("cart-total")?.value || "",
    };

    document.querySelectorAll("#newlink tr.product").forEach((row) => {
        invoiceData.items.push({
            tarif: row.querySelector(".select2-tariff")?.value || "",
            name: row.querySelector("input[name='item_name[]']")?.value || "",
            translation: row.querySelector("input[name='item_prev[]']")?.value || "",
            origin: row.querySelector("select[name='origin[]']")?.value || "",
            price: row.querySelector("input[name='price[]']")?.value || "",
            quantity: row.querySelector("input[name='quantity[]']")?.value || "",
            total: row.querySelector("input[name='total[]']")?.value || "",
        });
    });

    const xml = buildXmlInvoice(invoiceData);
    downloadXml(xml, "invoice.xml");
}

document.addEventListener("DOMContentLoaded", function () {
    const exportBtn = document.getElementById("export-xml");
    if (!exportBtn) return;
    exportBtn.addEventListener("click", handleExportXml);
});


document.getElementById("add-item")?.addEventListener("click", (e) => {
    e.preventDefault();
    console.log("Dodaj proizvod clicked");
    addRowToInvoice();
    initializeTariffSelects();
});

document.addEventListener("click", function (e) {
    const button = e.target.closest(".remove-row");
    if (!button) return;

    const row = button.closest("tr");

    // Short delay to let the browser fully handle prior UI rendering
    setTimeout(() => {
        Swal.fire({
            title: "Oprez!",
            text: "Odabrani proizvod će biti trajno uklonjen sa popisa trenutne deklaracije. Nakon akcije, deklaraciju morate spasiti!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Odustani",
            confirmButtonText: "Da, ukloni",
            customClass: {
                confirmButton: "btn btn-soft-info me-2",
                cancelButton: "btn btn-info"
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed && row) {
                row.remove();
                updateEstimates();
            }
        });
    }, 10);
});

document.addEventListener("click", function (e) {
    const btn = e.target.closest("button");
    if (!btn) return;

    const group = btn.closest(".input-group");
    const input = group?.querySelector("input");

    if (!input) return;

    const isMinus = btn.textContent.trim() === "−";
    const isPlus = btn.textContent.trim() === "+";

    if (isMinus || isPlus) {
        const val = parseInt(input.value) || 0;
        const min = parseInt(input.min) || 0;
        input.value = isMinus ? Math.max(min, val - 1) : val + 1;
        updateEstimates();
    }
});

document.addEventListener("click", function (e) {
    if (e.target.closest(".remove-row")) {
        const button = e.target.closest(".remove-row");
        const row = button.closest("tr");

        Swal.fire({
            title: "Oprez!",
            text: "Odabrani proizvod će biti trajno uklonjen sa popisa trenutne deklaracije. Nakon akcije, deklaraciju morate spasiti!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Odustani",
            confirmButtonText: "Da, ukloni",
            customClass: {
                confirmButton: "btn btn-soft-info me-2",
                cancelButton: "btn btn-info"
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed && row) {
                row.remove();
                updateEstimates();
            }
        });
    }
});