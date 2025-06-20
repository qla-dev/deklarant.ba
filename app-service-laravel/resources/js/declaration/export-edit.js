  // ✅ Export to CSV
function exportTableToCustomCSV() {
    const invoiceNo = document.getElementById("invoice-no")?.value?.trim() || "unknown";
    const filename = `deklaracija-ai_${invoiceNo}.csv`;

    const headers = [
        "TPL1", "Zemlja porijekla", "Povlastica", "Naziv robe", "Broj komada",
        "Vrijednost", "Koleta", "Bruto kg", "Neto kg", "Required"
    ];
    let csv = [headers.join(";")];

    // ✅ Remove last row forcibly
    const rows = Array.from(document.querySelectorAll("#products-table tbody tr")).slice(0, -1);

    rows.forEach((row) => {
        const rowData = [];

        let tplName = row.querySelector('select[name="item_code[]"]')?.value || "";
        tplName = tplName.replace(/\s+/g, '').slice(0, 8);  // Remove spaces and limit to 8 digits
        rowData.push(`"${tplName}"`);


        const origin = row.querySelector('select[name="origin[]"]')?.value || "";
        rowData.push(`"${origin}"`);

        const tariffVal = row.querySelector('input[name="tariff_privilege[]"]')?.value || "0";
        const povlastica = tariffVal !== "0" ? tariffVal : "";
        rowData.push(`"${povlastica}"`);



       const translatedName = row.querySelector('input[name="item_prev[]"]')?.value.trim().toUpperCase() || "";
       rowData.push(`"${translatedName}"`);

        const qty = row.querySelector('input[name="quantity[]"]')?.value || "";
        rowData.push(`"${qty}"`);

        let rawPrice = row.querySelector('input[name="total[]"]')?.value || "";
        let numericOnly = rawPrice.replace(/[^\d.,]/g, "").replace(",", ".");
        let formattedValue = numericOnly ? parseFloat(numericOnly).toFixed(2).replace(".", ",") : "";
        rowData.push(`"${formattedValue}"`);

        const koleta = row.querySelector('input[name="procjena[]"]')?.value || "";
        rowData.push(`"${koleta}"`);

        const bruto = row.querySelector('input[name="weight_gross[]"]')?.value || "";
        rowData.push(`"${bruto}"`);

        const neto = row.querySelector('input[name="weight_net[]"]')?.value || "";
        rowData.push(`"${neto}"`);

        rowData.push(`""`);

        csv.push(rowData.join(";"));
    });

    const csvFile = new Blob(["\uFEFF" + csv.join("\n")], {
        type: "text/csv;charset=utf-8;"
    });

    const link = document.createElement("a");
    link.href = URL.createObjectURL(csvFile);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

  // ✅ Print preview
function getVal(sel) {
    const el = document.querySelector(sel);
    return el?.value?.trim() || el?.textContent?.trim() || "—";
}

function renderPrintTableAndPrint(isDownloadOnly = false, container = null) {
    const invoiceNo = getVal("#invoice-no");
    const invoiceId = getVal("#invoice-id1");
    const date = getVal("#invoice-date");
    const incoterm = getVal("#incoterm");
    const incotermDestination = getVal("#incoterm-destination");
    const supplier = $("#supplier-select2").select2("data")[0]?.text || "—";
    const client = $("#importer-select2").select2("data")[0]?.text || "—";
    const totalAmount = getVal("#total-amount");
    const totalWeightGross = getVal("#total-weight-gross");
    const totalWeightNet = getVal("#total-weight-net");
    const totalPackages = getVal("#total-num-packages");

    const companyName = getVal("#company-name");
    const companyId = getVal("#company-id");
    const companyAddress = getVal("#company-address");

    const rows = document.querySelectorAll("#newlink tr.product");
    if (rows.length === 0) {
        Swal.fire("Greška", "Nema stavki za ispis.", "error");
        return;
    }

    let sumBruto = 0;
    let sumNeto = 0;

    const headers = [
        "Redni broj", "Opis (Original)", "Opis", "Opis (Preveden)",
        "Tarifna oznaka", "Jedinica mjere", "Zemlja porijekla", "Povlastica",
        "Količina", "Bruto kg", "Neto kg", "Paketi", "Procijenjeno", "Cijena/jedinica", "Ukupna cijena"
    ];

    let html = `
    <div style="font-family: Arial, sans-serif;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <img src="/build/images/logo-light-ai.png" alt="Logo" height="40" style="max-height: 40px;">
            <div style="text-align: right; font-size: 12px;">
                <strong>Moji podaci</strong><br>
                ${companyName}<br>
                ${companyId}<br>
                ${companyAddress}
            </div>
        </div>

        <h2 style="text-align: center;">Deklaracija ${invoiceId ? `#${invoiceId}` : ''}</h2>

        <table style="width: 100%; font-size: 13px; margin-bottom: 20px; border: none;">
            <tr><td><strong>Broj fakture:</strong></td><td>${invoiceNo}</td></tr>
            <tr><td><strong>Datum:</strong></td><td>${date}</td></tr>
            <tr><td><strong>Incoterm:</strong></td><td>${incoterm} – ${incotermDestination}</td></tr>
            <tr><td><strong>Dobavljač:</strong></td><td>${supplier}</td></tr>
            <tr><td><strong>Klijent:</strong></td><td>${client}</td></tr>
            <tr><td><strong>Ukupan iznos:</strong></td><td>${totalAmount}</td></tr>
            <tr><td><strong>Ukupna bruto masa:</strong></td><td>${totalWeightGross}</td></tr>
            <tr><td><strong>Ukupna neto masa:</strong></td><td>${totalWeightNet}</td></tr>
            <tr><td><strong>Ukupan broj paketa:</strong></td><td>${totalPackages}</td></tr>
        </table>

        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size: 11px; border-collapse: collapse;">
            <thead><tr>${headers.map(h => `<th>${h}</th>`).join("")}</tr></thead>
            <tbody>
    `;

    rows.forEach((row, i) => {
        const get = s => row.querySelector(s)?.value?.trim() || row.querySelector(s)?.textContent?.trim() || "";
        const getCheck = s => row.querySelector(s)?.checked ? "DA" : "NE";

        const bruto = parseFloat(get('input[name="weight_gross[]"]')) || 0;
        const neto = parseFloat(get('input[name="weight_net[]"]')) || 0;
        sumBruto += bruto;
        sumNeto += neto;

        const rowData = [
            i + 1,
            get('input[name="item_name[]"]'),
            get('input[name="item_desc[]"]'),
            get('input[name="item_prev[]"]'),
            get('select[name="item_code[]"]') || get('input[name="item_code[]"]'),
            get('input[name="quantity_type[]"]'),
            get('select[name="origin[]"]'),
            getCheck('input[name="tariff_privilege[]"]'),
            get('input[name="quantity[]"]'),
            bruto.toFixed(2),
            neto.toFixed(2),
            get('input[name="kolata[]"]'),
            get('input[name="procjena[]"]'),
            get('input[name="price[]"]'),
            get('input[name="total[]"]'),
        ];

        html += `<tr>${rowData.map(d => `<td>${d}</td>`).join("")}</tr>`;
    });

    html += `
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background: #f9f9f9;">
                    <td colspan="9">Ukupno</td>
                    <td>${sumBruto.toFixed(2)}</td>
                    <td>${sumNeto.toFixed(2)}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>`;

    if (isDownloadOnly && container) {
        container.innerHTML = html;
        document.body.appendChild(container);

        html2pdf().set({
            margin:       0.3,
            filename:     `Deklaracija_${invoiceNo || invoiceId || 'export'}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        })
        .from(container)
        .save()
        .then(() => container.remove());

        return;
    }

    const win = window.open("", "", "width=1000,height=800");
    win.document.write(`
        <html><head><title>Deklaracija ${invoiceNo}</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #000; padding: 6px; font-size: 11px; }
            th { background: #f2f2f2; }
            h2 { text-align: center; }
        </style>
        </head><body>${html}</body></html>
    `);
    win.document.close();
    win.focus();
    win.print();
    win.onafterprint = () => win.close();
}



function autoDownloadPDF() {
    const printContent = document.createElement('div');
    renderPrintTableAndPrint(true, printContent); // use modified function below
}


