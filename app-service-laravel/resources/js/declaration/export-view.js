 // ✅ Export to csv


function exportTableToCustomCSV() {
    const invoiceNo = document.getElementById("invoice-no")?.textContent?.trim() || "unknown";
    const filename = `deklaracija-ai_${invoiceNo}.csv`;

    // Helper to transliterate and uppercase diacritics
    function transliterate(str) {
        return str
            .toUpperCase()
            .replace(/Č/g, 'C')
            .replace(/Ć/g, 'C')
            .replace(/Š/g, 'S')
            .replace(/Đ/g, 'DJ')
            .replace(/Ž/g, 'Z');
    }

    // Define custom headers
    const headers = [
        "TPL1", "Zemlja porijekla", "Povlastica", "Naziv robe", "Broj komada",
        "Vrijednost", "Koleta", "Bruto kg", "Neto kg", "Required"
    ];

    // Start CSV with header row (no quotes)
    const csvLines = [ headers.join(';') ];

    // Process each product row
    document.querySelectorAll('#products-list tr').forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowData = [];

        // Extract bruto/neto
        let bruto = '';
        let neto = '';
        const kgSplit = cells[8]?.innerText.trim().split('/');
        if (kgSplit?.length === 2) {
            bruto = kgSplit[0].trim();
            neto = kgSplit[1].trim();
        }

        // Map fields, remove quotes, transliterate diacritics
        const tpl1     = transliterate((cells[3]?.innerText.trim() || '').replace(/\s+/g, '').slice(0, 8));
        const origin   = transliterate(cells[5]?.innerText.trim() || '');
        const povl     = transliterate(cells[6]?.innerText.trim() || '');
        const name     = transliterate(cells[2]?.textContent.trim() || '');
        const qty      = cells[7]?.innerText.trim() || '';
        const value    = cells[11]?.innerText.trim().split(' ')[0] || '';
        const koleta   = cells[9]?.innerText.trim() || '';

        rowData.push(
            tpl1,
            origin,
            povl,
            name,
            qty,
            value,
            koleta,
            bruto,
            neto,
            '' // Required is always empty
        );

        csvLines.push(rowData.join(';'));
    });

    // drop BOM + use CRLF
    const content = csvLines.join("\r\n");
    const blob = new Blob([ content ], { type: "text/csv" });

    // Trigger download
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
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
    const invoiceId = getVal("#invoice-id") || getVal("#invoice-id1");
    const date = getVal("#invoice-date");
    const incoterm = getVal("#incoterm");
    const incotermDestination = getVal("#incoterm-destination");
    const supplier = getVal("#supplier-name");
    const client = getVal("#carrier-name");
    const totalAmount = getVal("#total-1");
    const totalWeightGross = getVal("#total-weight-gross");
    const totalWeightNet = getVal("#total-weight-net");
    const totalPackages = getVal("#total-num-packages");

    const companyName = getVal("#shipping-email");
    const companyId = getVal("#shipping-vat");
    const companyAddress = getVal("#shipping-address");

    const rows = document.querySelectorAll("#products-list tr");
    if (!rows.length) {
        Swal.fire("Greška", "Nema stavki za ispis.", "error");
        return;
    }

    let sumBruto = 0, sumNeto = 0;

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
            <thead>
                <tr>
                    <th>#</th><th>Proizvod</th><th>Opis</th><th>Prevod</th>
                    <th>Tarifna oznaka</th><th>Tip kvantiteta</th><th>Zemlja porijekla</th>
                    <th>Povlastica</th><th>Količina</th><th>Bruto kg</th>
                    <th>Neto kg</th><th>Paketi</th><th>Vrijednost</th><th>Cijena</th><th>Ukupno</th>
                </tr>
            </thead>
            <tbody>
    `;

    rows.forEach((row, i) => {
        const cols = Array.from(row.children).map(td => td?.innerText?.trim() || "--");
        const weightSplit = cols[9]?.split("/") || ["0", "0"];
        const bruto = parseFloat(weightSplit[0]) || 0;
        const neto = parseFloat(weightSplit[1]) || 0;

        sumBruto += bruto;
        sumNeto += neto;

        html += `<tr><td>${i + 1}</td>${cols.slice(1).map(c => `<td>${c}</td>`).join("")}</tr>`;
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
            margin: 0.3,
            filename: `Deklaracija_${invoiceNo || invoiceId || 'export'}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
        }).from(container).save().then(() => container.remove());

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

