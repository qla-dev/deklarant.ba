
//  Google search 


function searchFromInputs(button) {
  console.log("[Tooltip] Google button clicked");

  const row = button.closest('tr');
  const nameInput = row.querySelector('.item-name');
  const descInput = row.querySelector('.item-desc');
  const name = nameInput?.value.trim() || '';
  const desc = descInput?.value.trim() || '';
  const query = encodeURIComponent(`${desc} hs code`);

  // 🛑 Kill existing tooltip instance
  const instance = bootstrap.Tooltip.getInstance(button);
  if (instance) {
    console.log("[Tooltip] Instance found");
    instance.dispose(); // <- kills it completely
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
  } else {
    console.warn("[Tooltip] No instance found on button");
  }

  // 🔍 Open Google if query is valid
  if (name || desc) {
    const url = `https://www.google.com/search?q=${query}`;
    console.log(`[Google] Opening search URL: ${url}`);
    window.open(url, '_blank');
  } else {
    console.log("[Google] Missing name or desc. Showing Swal.");
    Swal.fire({
      icon: 'info',
      title: 'Nedostaje opis',
      text: 'Unesite naziv ili opis proizvoda za Google pretragu.',
      confirmButtonText: 'Uredu',
      confirmButtonColor: '#299dcb'
    });
  }

  // ✅ Reinit tooltip later if needed
  setTimeout(() => {
    new bootstrap.Tooltip(button, {
      trigger: 'hover',
      delay: { show: 100, hide: 100 }
    });
    console.log("[Tooltip] Reinitialized after click");
  }, 300); // give time for old DOM to go away
}










//  Save logic script final



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

    document.getElementById("save-invoice-btn").addEventListener("click", async function(e) {
        e.preventDefault();
        e.stopPropagation();
        const btn = this;
        btn.setAttribute("data-disabled", "true"); // custom attribute
        btn.classList.add("position-relative");
        btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="vertical-align: text-bottom;"></span>
        Spašavanje...
    `;
        btn.classList.remove("btn-secondary"); // In case it gets changed
        btn.classList.add("btn-info"); // Force blue color

        let missingFields = [];

        function checkRequired(id, label) {
            const input = document.getElementById(id);
            if (!input || !input.value.trim()) {
                input.classList.add("is-invalid");
                missingFields.push(label);
            } else {
                input.classList.remove("is-invalid");
            }
        }

        // Check all required fields
        checkRequired("billing-name", "Naziv klijenta");
        checkRequired("billing-address-line-1", "Adresa klijenta");
 

        checkRequired("carrier-name", "Naziv dobavljača");
        checkRequired("carrier-address", "Adresa dobavljača");


        if (missingFields.length > 0) {
            Swal.fire("Greška", "Obavezna polja nisu popunjena:\n" + missingFields.join(", "), "error");
            btn.disabled = false;
            btn.innerHTML = `<i class="ri-save-line align-bottom me-1"></i> Sačuvaj`;
            return;
        }


        const userId = user?.id;
        if (!userId) {
            Swal.fire("Greška", "Korisnički ID nije pronađen", "error");
            return;
        }

        async function ensureEntity(endpoint, data, select2Id) {
            const selectedId = $(select2Id).val();
            // If 'Novi klijent' or 'Novi dobavljač' is selected, create new
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
            } else {
                // Use existing ID
                return selectedId;
            }
        }

        try {
            // Build supplier and importer data
            const supplierData = {
                name: document.getElementById("billing-name").value.trim(),
                address: document.getElementById("billing-address-line-1").value.trim(),
                tax_id: document.getElementById("billing-tax-no").value.trim(),
                contact_phone: document.getElementById("billing-phone-no").value.trim(),
                contact_email: document.getElementById("email").value.trim(),
                owner: document.getElementById("supplier-owner").value.trim() || null,
                avatar: null,
                synonyms: []
            };

            const importerData = {
                name: document.getElementById("carrier-name").value.trim(),
                address: document.getElementById("carrier-address").value.trim(),
                tax_id: document.getElementById("carrier-tax").value.trim(),
                contact_phone: document.getElementById("carrier-tel").value.trim(),
                contact_email: document.getElementById("carrier-email").value.trim(),
                owner: document.getElementById("carrier-owner").value.trim() || null,
                avatar: null,
                synonyms: []
            };

            // --- Ensure valid integer IDs for supplier and importer ---
            function isValidId(id) {
                return id && !isNaN(id) && Number.isInteger(Number(id)) && Number(id) > 0;
            }

            // Only create if 'Novi ...' is selected, otherwise just use ID
            let supplierId = await ensureEntity("suppliers", supplierData, "#supplier-select2");
            let importerId = await ensureEntity("importers", importerData, "#importer-select2");

            // If still not valid, show error and abort
            if (!isValidId(supplierId)) {
                Swal.fire("Greška", "Molimo odaberite ili unesite validnog klijenta.", "error");
                btn.disabled = false;
                btn.innerHTML = `<i class=\"ri-save-line align-bottom me-1\"></i> Sačuvaj`;
                return;
            }
            if (!isValidId(importerId)) {
                Swal.fire("Greška", "Molimo odaberite ili unesite validnog dobavljača.", "error");
                btn.disabled = false;
                btn.innerHTML = `<i class=\"ri-save-line align-bottom me-1\"></i> Sačuvaj`;
                return;
            }

            supplierId = Number(supplierId);
            importerId = Number(importerId);

            // Build invoice items
            const items = [];

document.querySelectorAll("#newlink tr.product").forEach((row, index) => {
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
    const base_price = parsedPrice.toFixed(2).replace('.', ','); // this is a string
    const quantity = parseFloat(row.querySelector('[name="quantity[]"]')?.value || "0");
    const total_price = formatDecimal(base_price * quantity, 2);
    const quantity_type = row.querySelector('[name="quantity_type[]"]')?.value || "";
    const package_num = row.querySelector('[name="kolata[]"]')?.value || "";
    const weight_gross = row.querySelector('[name="weight_gross[]"]')?.value || "";
    const weight_net = row.querySelector('[name="weight_net[]"]')?.value || "";

    const povlastica = row.querySelector('input[type="checkbox"]')?.checked ? 1 : 0;
    const tariff_privilege = row.querySelector('input[name="tariff_privilege[]"]')?.value || "0";

    console.log(`[ROW ${index + 1}]`, {
        origin,
        base_price,
        quantity,
        quantity_type,
        package_num,
        tariff_privilege,
        povlastica,
        item_code,
        item_name,
        item_description,
        item_description_translated,
        total_price,
        weight_gross,
        weight_net
    });

    items.push({
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
        version: new Date().getFullYear()
    });
});



            function toISODate(dmy) {
                if (!dmy) return null;
                const [day, month, year] = dmy.split(".");
                if (!day || !month || !year) return null;
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            }

            // Use the existing invoice file name and ID from upload
            const invoiceId = getInvoiceId();
            console.log("💾 Saving to invoice ID:", invoiceId);
            const invoiceData = await getInvoice();
            const fileName = invoiceData.file_name || "invoice.pdf";

            const payload = {

                incoterm: document.getElementById("incoterm").value.trim(),
                incoterm_destination: document.getElementById("incoterm-destination").value.trim(),
                invoice_number: document.getElementById("invoice-no").value.trim(),
                file_name: fileName, // use the file name from the uploaded invoice
                total_price:        parseFloat((document.getElementById("total-amount")?.value         || "0").replace(',', '.')),
                total_weight_net:   parseFloat((document.getElementById("total-weight-net")?.value   || "0").replace(',', '.')),
                total_weight_gross: parseFloat((document.getElementById("total-weight-gross")?.value || "0").replace(',', '.')),
                total_num_packages: parseInt(document.getElementById("total-num-packages")?.value || "0", 10),

                date_of_issue: (() => {
                    const dateValue = document.getElementById("invoice-date")?.value;
                    console.log("Raw date value:", dateValue);
                    const isoDate = toISODate(dateValue);
                    console.log("Converted to ISO:", isoDate);
                    return isoDate;
                })(),
                items,
                supplier_id: supplierId,
                importer_id: importerId // always send both
            };

            console.log(" Sending payload (update):", payload);

            // Update the existing invoice instead of creating a new one
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
        // ⛔ Backend returned an error – handle both 'poruka' and 'backend_error'
        const backendMsg = resJson?.poruka || "Greška pri spašavanju deklaracije.";



        Swal.fire({
            icon: "error",
            title: "Neuspješno",
            html: `<div class="text-danger mb-2">${backendMsg}</div>`,
            confirmButtonText: "U redu",
            customClass: {
                confirmButton: "btn btn-info"
            },
            buttonsStyling: false
        });

        return; // ⚠️ Prevents running success block below
    }

    Swal.fire({
        icon: "success",
        title: "Uspješno",
        text: "Deklaracija je sačuvana",
        confirmButtonText: "U redu",
        customClass: {
            confirmButton: "btn btn-info"
        },
        buttonsStyling: false
    });

} catch (err) {
    console.error("Greška:", err);
    Swal.fire({
        icon: "error",
        title: "Greška",
        text: err.message || "Neočekivana greška pri komunikaciji sa serverom.",
        confirmButtonText: "U redu",
        customClass: {
            confirmButton: "btn btn-info"
        },
        buttonsStyling: false
    });
} finally {
    btn.disabled = false;
    btn.innerHTML = `<i class="ri-save-line align-bottom me-1"></i> Sačuvaj`;
}
    });

//  Export to XML -->

    document.addEventListener("DOMContentLoaded", function() {
        const exportBtn = document.getElementById("export-xml");
        if (!exportBtn) return;

        exportBtn.addEventListener("click", () => {
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

            // Escape function for XML safety
            const escapeXml = (str) =>
                String(str).replace(/[<>&'"]/g, (c) => ({
                    "<": "&lt;",
                    ">": "&gt;",
                    "&": "&amp;",
                    "'": "&apos;",
                    '"': "&quot;",
                }));

            // Build XML string
            let xml = `<invoice>\n`;
            invoiceData.items.forEach((item) => {

                xml += `  <item>\n`;
                xml += `    <tarif>${escapeXml(item.tarif)}</tarif>\n`;
                xml += `    <name>${escapeXml(item.name)}</name>\n`;
                xml += `    <translation>${escapeXml(item.translation)}</translation>\n`;
                xml += `    <origin>${escapeXml(item.origin)}</origin>\n`;
                xml += `    <price>${escapeXml(item.price)}</price>\n`;
                xml += `    <quantity>${escapeXml(item.quantity)}</quantity>\n`;
                xml += `    <total>${escapeXml(item.total)}</total>\n`;
                xml += `  </item>\n`;
            });
            xml += `  <subtotal>${escapeXml(invoiceData.subtotal)}</subtotal>\n`;
            xml += `  <tax>${escapeXml(invoiceData.tax)}</tax>\n`;
            xml += `  <discount>${escapeXml(invoiceData.discount)}</discount>\n`;
            xml += `  <shipping>${escapeXml(invoiceData.shipping)}</shipping>\n`;
            xml += `  <total>${escapeXml(invoiceData.total)}</total>\n`;
            xml += `</invoice>`;

            // Create blob and download
            const blob = new Blob([xml], {
                type: 'text/xml'
            });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "invoice.xml";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });




