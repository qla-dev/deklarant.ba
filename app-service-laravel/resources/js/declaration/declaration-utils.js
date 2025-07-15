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

function buildSupplierData() {
    return {
        name: document.getElementById("billing-name").value.trim(),
        address: document.getElementById("billing-address-line-1").value.trim(),
        tax_id: document.getElementById("billing-tax-no").value.trim(),
        contact_phone: document.getElementById("billing-phone-no").value.trim(),
        contact_email: document.getElementById("email").value.trim(),
        owner: document.getElementById("supplier-owner").value.trim() || null,
        avatar: null
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
        avatar: null
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
    function str(selector, defaultValue = "") {
        return row.querySelector(selector)?.value?.trim() || defaultValue
    }
    function bool(selector, defaultValue = false) {
        return row.querySelector(selector)?.checked || defaultValue
    }
    function num(selector, decimalPlaces = undefined) {
        const ret = parseFloat(str(selector, "0").replace(",", "."))
        if (decimalPlaces != null) {
            return parseFloat(ret.toFixed(decimalPlaces))
        } else {
            return ret
        }
    }
    const item_id_raw = str('[name="item_id[]"]', null);
    const item_id = item_id_raw ? parseInt(item_id_raw) : null;

    const item_name = str('[name="item_name[]"]');
    const item_description_original = item_name;
    const item_description = str('[name="item_desc[]"]');
    const item_description_translated = str('[name="item_prev[]"]');

    const item_code = $(row).find('[name="item_code[]"]').val() || "";

    const bestMatchesRaw = str('[name="best_customs_code_matches[]"]', "[]");
    let best_customs_code_matches = [];
    try {
        best_customs_code_matches = JSON.parse(bestMatchesRaw);
    } catch (e) {
        console.warn("Invalid JSON in best_customs_code_matches[]:", bestMatchesRaw);
    }

    const country_of_origin = $(row).find('[name="origin[]"]').val() || "";
    const base_price = num('[name="price[]"]', 2);
    const quantity = num('[name="quantity[]"]');
    const totalInputRaw = num('input[name="total[]"]');
    let total_price = null;
    if (totalInputRaw != 0) {
        total_price = totalInputRaw;
    } else {
        total_price = base_price * quantity;
    }
    const quantity_type = str('[name="quantity_type[]"]');
    const num_packages = num('[name="num_packages[]"]');
    const num_packages_locked = bool('[name="num_packages_locked[]"]');
    const weight_gross = num(('[name="weight_gross[]"]'));
    const weight_gross_locked = bool('[name="weight_gross_locked[]"]');
    const weight_net = num(('[name="weight_net[]"]'));
    const weight_net_locked = bool('[name="weight_net_locked[]"]');
    const tariff_privilege = str('input[name="tariff_privilege[]"]');
    const currency = str('input[name="currency[]"]');
    const slot_number = parseInt(row.querySelector('.slot-number')?.innerText || "-1", 10);

    return {
        item_id,
        item_name,
        item_code,
        best_customs_code_matches,
        item_description,
        item_description_original,
        item_description_translated,
        country_of_origin,
        base_price,
        quantity,
        quantity_type,
        num_packages,
        tariff_privilege,
        weight_gross,
        weight_net,
        total_price,
        currency,
        slot_number,
        weight_gross_locked,
        weight_net_locked,
        num_packages_locked,
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

async function getSupplierID() {
    const supplierData = buildSupplierData();
    const id = await ensureEntity("suppliers", supplierData, "#supplier-select2");
    return isValidId(id) ? Number(id) : undefined
}

async function getImporterID() {
    const importerData = buildImporterData();
    const id = await ensureEntity("importers", importerData, "#importer-select2");
    return isValidId(id) ? Number(id) : undefined
}

function formatToDMY(isoDate) {
    if (!isoDate) return "";
    const [year, month, day] = isoDate.split("-");
    return `${day}-${month}-${year}`;
}

function setField(selector, value) {
    const el = document.querySelector(selector);
    if (el) el.value = value || "";
}

function setText(selector, value) {
    const el = document.querySelector(selector);
    if (el) el.textContent = value || "";
}

function waitForEl(selector, callback) {
    const el = document.querySelector(selector);
    if (el) return callback(el);

    const observer = new MutationObserver(() => {
        const el = document.querySelector(selector);
        if (el) {
            observer.disconnect();
            callback(el);
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

async function waitForFunction(fun) {
    return new Promise((resolve, reject) => {
        if (fun()) resolve();
        const interval = setInterval(() => {
            if (fun()) {
                clearInterval(interval);
                resolve();
            }
        }, 100);
    });
}