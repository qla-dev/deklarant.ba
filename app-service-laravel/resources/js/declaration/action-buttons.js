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

function getInvoiceId() {
    const scanId = window.global_invoice_id;
    if (scanId) {
        console.log("Using scanned invoice ID:", scanId);
        return scanId;
    }
    const match = window.location.pathname.match(/\/deklaracija\/(\d+)/);
    const urlId = match ? match[1] : null;
    console.log("Using URL invoice ID:", urlId);
    return urlId;
}

async function getInvoice() {
    const id = getInvoiceId();
    if (!id) return {};
    const res = await fetch(`/api/invoices/${id}`, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    });
    return await res.json();
}

function setupSaveButtonLoadingState(btn) {
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
        return json.data.id;
    }
    return selectedId;
}

function buildSupplierData() {
    return {
        name: document.getElementById("billing-name").value.trim(),
        address: document.getElementById("billing-address-line-1").value.trim(),
        tax_id: document.getElementById("billing-tax-no").value.trim(),
        contact_phone: document.getElementById("billing-phone-no").value.trim(),
        contact_email: document.getElementById("email").value.trim(),
        owner: document.getElementById("supplier-owner").value.trim() || null,
        avatar: null,
        synonyms: []
    };
}

function buildImporterData() {
    return {
        name: document.getElementById("carrier-name").value.trim(),
        address: document.getElementById("carrier-address").value.trim(),
        tax_id: document.getElementById("carrier-tax").value.trim(),
        contact_phone: document.getElementById("carrier-tel").value.trim(),
        contact_email: document.getElementById("carrier-email").value.trim(),
        owner: document.getElementById("carrier-owner").value.trim() || null,
        avatar: null,
        synonyms: []
    };
}

function isValidId(id) {
    return id && !isNaN(id) && Number.isInteger(Number(id)) && Number(id) > 0;
}

function toISODate(dmy) {
    if (!dmy) return null;
    const [day, month, year] = dmy.split(".");
    if (!day || !month || !year) return null;
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
}

function buildInvoiceItem(row) {
    const item_id_raw = row.querySelector('[name="item_id[]"]')?.value || null;
    const item_id = item_id_raw ? parseInt(item_id_raw) : null;

    const item_name = row.querySelector('[name="item_name[]"]')?.value?.trim() || "";
    const item_description_original = item_name;
    const item_description = row.querySelector('[name="item_desc[]"]')?.value?.trim() || "";
    const item_description_translated = row.querySelector('[name="item_prev[]"]')?.value.trim() || "";

    const item_code = $(row).find('[name="item_code[]"]').val() || "";

    const bestMatchesRaw = row.querySelector('[name="best_customs_code_matches[]"]')?.value || "[]";
    let best_customs_code_matches = [];
    try {
        best_customs_code_matches = JSON.parse(bestMatchesRaw);
    } catch (e) {
        console.warn("Invalid JSON in best_customs_code_matches[]:", bestMatchesRaw);
    }

    const origin = $(row).find('[name="origin[]"]').val() || "";
    const rawPrice = row.querySelector('[name="price[]"]')?.value || "0";
    const parsedPrice = parseFloat(rawPrice.replace(',', '.')) || 0;
    const base_price = parsedPrice.toFixed(2).replace('.', ',');
    const quantity = parseFloat(row.querySelector('[name="quantity[]"]')?.value || "0");
    const totalInputRaw = row.querySelector('input[name="total[]"]')?.value || "";
    let total_price = null;
    if (totalInputRaw.trim() !== "") {
        total_price = parseFloat(totalInputRaw.replace(',', '.')) || 0;
    } else {
        total_price = parseFloat(base_price.replace(',', '.')) * quantity;
    }
    const quantity_type = row.querySelector('[name="quantity_type[]"]')?.value || "";
    const package_num = row.querySelector('[name="kolata[]"]')?.value || "";
    const weight_gross = row.querySelector('[name="weight_gross[]"]')?.value || "";
    const weight_net = row.querySelector('[name="weight_net[]"]')?.value || "";
    const tariff_privilege = row.querySelector('input[name="tariff_privilege[]"]')?.value || "0";
    const slot_number = parseInt(row.querySelector('.slot-number')?.innerText || "-1", 10);

    return {
        item_id,
        item_name,
        item_code,
        best_customs_code_matches,
        item_description,
        item_description_original,
        item_description_translated,
        origin,
        base_price,
        quantity,
        quantity_type,
        package_num,
        tariff_privilege,
        weight_gross,
        weight_net,
        total_price,
        currency: "EUR",
        slot_number,
        version: new Date().getFullYear()
    };
}

function buildInvoicePayload(supplierId, importerId) {
    const items = [];
    document.querySelectorAll("#newlink tr.product").forEach((row) => {
        items.push(buildInvoiceItem(row));
    });
    return {
        incoterm: document.getElementById("incoterm").value.trim(),
        incoterm_destination: document.getElementById("incoterm-destination").value.trim(),
        invoice_number: document.getElementById("invoice-no").value.trim(),
        total_price: parseFloat((document.getElementById("total-amount")?.value || "0").replace(',', '.')),
        total_weight_net: parseFloat((document.getElementById("total-weight-net")?.value || "0").replace(',', '.')),
        total_weight_gross: parseFloat((document.getElementById("total-weight-gross")?.value || "0").replace(',', '.')),
        total_num_packages: parseInt(document.getElementById("total-num-packages")?.value || "0", 10),
        date_of_issue: toISODate(document.getElementById("invoice-date")?.value),
        items,
        supplier_id: supplierId,
        importer_id: importerId
    };
}

async function handleSaveInvoice(btn) {
    setupSaveButtonLoadingState(btn);

    const missingFields = validateRequiredFields();
    if (missingFields.length > 0) {
        showMissingFieldsAlert(missingFields);
        resetSaveButtonState(btn);
        return;
    }

    const userId = user?.id;
    if (!userId) {
        Swal.fire("Greška", "Korisnički ID nije pronađen", "error");
        return;
    }

    try {
        const supplierData = buildSupplierData();
        const importerData = buildImporterData();

        let supplierId = await ensureEntity("suppliers", supplierData, "#supplier-select2");
        let importerId = await ensureEntity("importers", importerData, "#importer-select2");

        if (!isValidId(supplierId)) {
            Swal.fire("Greška", "Molimo odaberite ili unesite validnog klijenta.", "error");
            resetSaveButtonState(btn);
            return;
        }
        if (!isValidId(importerId)) {
            Swal.fire("Greška", "Molimo odaberite ili unesite validnog dobavljača.", "error");
            resetSaveButtonState(btn);
            return;
        }

        supplierId = Number(supplierId);
        importerId = Number(importerId);

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

        Swal.fire({
            icon: "success",
            title: "Uspješno",
            text: "Deklaracija je sačuvana",
            confirmButtonText: "U redu",
            customClass: { confirmButton: "btn btn-info" },
            buttonsStyling: false
        });

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

document.getElementById("save-invoice-btn")?.addEventListener("click", async function(e) {
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

document.addEventListener("DOMContentLoaded", function() {
    const exportBtn = document.getElementById("export-xml");
    if (!exportBtn) return;
    exportBtn.addEventListener("click", handleExportXml);
});
