function showRetryError(title, message) {
    Swal.fire({
        title: title,
        html: `<div class="text-danger">${message}</div>`,
        icon: "error",
        showCancelButton: true,
        confirmButtonText: "Pokušaj ponovo",
        cancelButtonText: "Odustani",
        reverseButtons: true, // ⬅️ Flip button positions
        customClass: {
            confirmButton: "btn btn-info",
            cancelButton: "btn btn-soft-info me-2"
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.close();
            startAiScan().then(() => waitForAiResult(true));
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            location.href = "/"; // Redirect ONLY if user clicked "Odustani"
        }
    });
}

var _invoice_data = null;
let globalAISuggestions = [];

// Add global flags
window.forceNewSupplier = false;
window.forceNewImporter = false;
window.skipPrefillParties = false; // NEW: skip prefill after manual clear

function getInvoiceId() {
    const id = window.global_invoice_id;
    console.log(" Invoice ID:", id);
    return id;
}

async function updateRemainingScans() {
    console.log(" updateRemainingScans() called ");

    if (!user?.id || !token) {
        console.warn("Missing user or token in updateRemainingScans");
        return;
    }

    // Use global value and decrease it
    const newRemaining = Math.max(0, remaining_scans - 1); // safe fallback to 0

    try {
        const response = await fetch(`/api/user-packages/users/${user.id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                remaining_scans: newRemaining
            })
        });

        if (!response.ok) {
            throw new Error(`PUT failed with status ${response.status}`);
        }

        const data = await response.json();
        console.log(" Scan count updated in backend:", data);

    } catch (err) {
        console.error(" Failed to update scan count:", err);
    }
}

async function getInvoice() {
    if (!_invoice_data) {
        console.log(" Fetching invoice...");
        const res = await fetch(`/api/invoices/${getInvoiceId()}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });
        _invoice_data = await res.json();
        console.log(" Invoice data fetched:", _invoice_data);
    } else {
        console.log(" Using cached invoice data:", _invoice_data);
    }
    return _invoice_data;
}

async function startAiScan() {
    const taskId = getInvoiceId();

    if (!taskId) {
        console.warn("No task ID found in localStorage.");
        return false;
    }

    console.log("Starting AI scan for task ID:", taskId);

    //  Show loader inside scan function


    try {
        const response = await fetch(`/api/invoices/${taskId}/scan`, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });

        if (!response.ok) {
            let errorText = "Nepoznata greška";
            try {
                const err = await response.json();
                errorText = err?.error || errorText;
            } catch (jsonErr) {
                console.warn("Response nije u JSON formatu", jsonErr);
            }

            console.error("AI scan response greška:", errorText);
            Swal.close();

            showRetryError(
                "Greška pri pokretanju skeniranja",
                errorText,
                () => startAiScan()
            );

            return false;
        }

        console.log("AI scan started successfully");
        return true;

    } catch (error) {
        console.error("AI scan fetch failed:", error);
        Swal.close();

        showRetryError(
            "Greška pri komunikaciji",
            error.message || "Nepoznata greška",
            () => startAiScan()
        );

        return false;
    }
}

async function fillInvoiceData() {
    const invoice = await getInvoice();
    waitForEl("#invoice-id1", el => el.textContent = invoice.id || "—");
    waitForEl("#invoice-date-text", el => {
        const rawDate = invoice.date_of_issue ? new Date(invoice.date_of_issue) : new Date();
        el.textContent = rawDate.toLocaleDateString('hr'); // e.g. "17. 6. 2025."
        if (invoice.incoterm_destination) {
            const destinationInput = document.getElementById("incoterm-destination");
            if (destinationInput) {
                destinationInput.value = invoice.incoterm_destination;
            }
        }

    });

    waitForEl("#pregled", el => {
        el.addEventListener("click", () => {
            window.location.href = `/detalji-deklaracije/${invoice.id}`;
        });
    });

    const items = invoice.items || [];
    console.log(" Invoice items:", items);

    items.forEach((item, index) => {
        const matches = item.best_customs_code_matches || [];

        const bestMatch = matches.reduce((best, current) => {
            return !best || current.closeness < best.closeness ? current : best;
        }, null);

        const bestTariffCode = bestMatch?.entry?.["Tarifna oznaka"] || "";

        const suggestions = matches.map(code => ({
            entry: {
                "Tarifna oznaka": code.entry?.["Tarifna oznaka"],
                "Naziv": code.entry?.["Naziv"]
            },
            closeness: code.closeness
        }));

        addRowToInvoice({
            ...item,
            tariff_code: bestTariffCode
        }, suggestions);
    });
    if (invoice.incoterm) {
        // Extract only the code (first word) for the select
        const incotermCode = invoice.incoterm.split(' ')[0];
        document.getElementById("incoterm").value = incotermCode;
    }
    if (invoice.invoice_number) {
        const cleaned = invoice.invoice_number.replaceAll("/", "");
        document.getElementById("invoice-no").value = cleaned;
    }
    thingInitialized();
    await updateRemainingScans();
}

document.addEventListener("DOMContentLoaded", async () => {
    window.skipPrefillParties = false; // Always allow prefill on page load/scan
    console.log(" Page loaded. Starting init process...");

    const invoice = await getInvoice();

    // Only show loader and run scan if needed
    let scanNeeded = false;
    if (invoice.task_id == null) scanNeeded = true;
    else if (!invoice.items?.length) scanNeeded = true;

    if (scanNeeded) {
        let scanStarted = true;

        // Start scan only if task_id is null
        if (invoice.task_id == null) {
            scanStarted = await startAiScan();
        }

        // Only continue if scan actually started or items are already being processed
        if (!scanStarted) {
            // Stop further steps like waitForAIResult/fetchParties
            return;
        }

        if (!invoice.items?.length) {
            await waitForAIResult();
            if (window.location.href.endsWith('/deklaracija')) {
                window.location.href = '/deklaracija/' + getInvoiceId();
                return;
            }
        }

        Swal.close();
    }

    _invoice_data = null;

    await fillInvoiceData();
    if (!window.skipPrefillParties) {
        await fetchAndPrefillParties();
    }
    window.skipPrefillParties = false; // reset after use
    initImporterSupplierSelect2();
    thingInitialized();

    const invNo = getInvoiceId();
    if (invNo) document.getElementById("invoice-no1").value = invNo;

    // Prefill total weights and package count
    const rawNet = invoice.total_weight_net;
    const rawGross = invoice.total_weight_gross;
    const rawPackages = invoice.total_num_packages;

    // if parseFloat(...) is NaN, use 0
    const netVal = isNaN(parseFloat(rawNet)) ? 0 : rawNet;
    const grossVal = isNaN(parseFloat(rawGross)) ? 0 : rawGross;
    const packagesVal = isNaN(parseFloat(rawPackages)) ? 0 : rawPackages;

    // Prefill total weights and package count
    setField("#total-weight-net", formatDecimal(netVal, 2, ''));
    setField("#total-weight-gross", formatDecimal(grossVal, 2, ''));
    setField("#total-num-packages", formatDecimal(packagesVal, 2, ''));

    console.log("Weights and package count set:",
        invoice.total_weight_net,
        invoice.total_weight_gross,
        invoice.total_weight_gross,
        invoice.total_num_packages
    );

    // ─── now recalc per-row estimates ───
    updateEstimates();
    initDatePicker();
});


async function waitForAIResult(showLoader = true) {
    window.AI_SCAN_STARTED = true;
    const invoice_id = getInvoiceId();
    if (!invoice_id) return;

    let progress = 0; // Start at 0%
    let countdown = 50;
    let progressBar = null;
    let timerText = null;
    let fakeInterval = null;
    let countdownInterval = null;
    let lastStep = null;
    let stuckTimer = 0;

    const stepTextMap = {
        null: "Pokretanje AI tehnologije u pozadini",
        conversion: "Konvertovanje dokumenta u potreban format",
        extraction: "Obrada deklaracije pomoću AI tehnologije",
        enrichment: "Obogaćivanje podataka i generisanje deklaracije"
    };

    // Show loader Swal immediately
    if (showLoader) {
        document.getElementById('pre-ai-overlay')?.classList.add('d-none');

        Swal.fire({
            title: "Skeniranje",
            html: `
                    <div class="custom-swal-spinner mb-3"></div>
                    <div id="swal-status-message">Čeka na obradu</div>
                    <div class="mt-3 w-100">
                        <div class="progress" style="height: 16px;">
                            <div id="scan-progress-bar"
                                 class="progress-bar progress-bar-striped progress-bar-animated bg-info fw-bold text-white"
                                 role="progressbar"
                                 style="width: 0%; font-size: 0.85rem; line-height: 16px; transition: width 0.6s ease;"
                                 aria-valuemin="0" aria-valuemax="100">0%
                            </div>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.85rem;">
                            Preostalo vrijeme: <span id="scan-timer">50s</span>
                        </div>
                    </div>
                `,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        await new Promise(r => setTimeout(r, 0));
        progressBar = document.getElementById("scan-progress-bar");
        timerText = document.getElementById("scan-timer");

        // Start continuous progress movement
        fakeInterval = setInterval(() => {
            if (progress < 95) { // Don't go above 95% until complete
                progress = Math.min(95, progress + 0.5);
                updateProgressBar(progress);
            }
        }, 1000);

        // Start countdown timer
        countdownInterval = setInterval(() => {
            countdown--;
            if (timerText) {
                timerText.textContent = `${countdown}s`;
            }
            // Reset countdown when it hits 5 seconds
            if (countdown <= 5) {
                countdown = 15;
                timerText.textContent = `${countdown}s`;
            }
        }, 1000);
        setTimeout(() => {
            const container = timerText?.parentElement;
            if (container) {
                const notice = document.createElement("div");
                notice.className = "text-muted mt-2";
                notice.style.fontSize = "0.82rem";
                notice.innerHTML = `Ovaj proces može završiti u pozadini. Prati progres kroz pregled <a href="/moje-deklaracije" class="text-info">mojih deklaracija.</a> Kada se završi skeniranje, status će preći u draft, te ćeš moći revizirati podatke i dalje manipulisati sa istim.`;
                container.appendChild(notice);
            }
        }, 7000);
    }

    function updateProgressBar(value) {
        if (!progressBar) return;
        const clamped = Math.min(95, Math.max(0, value)); // Allow starting from 0%
        progressBar.style.width = `${clamped}%`;
        progressBar.innerHTML = `${Math.floor(clamped)}%`;
    }

    while (true) {
        let status, step, errorMsg;

        try {
            const res = await fetch(`/api/invoices/${invoice_id}/scan`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                }
            });

            if (!res.ok) throw new Error(`Greška kod API poziva: ${res.status} ${res.statusText}`);
            const json = await res.json();

            status = json?.status?.status;
            step = json?.status?.processing_step;
            errorMsg = json?.status?.error_message;

            // Step progress logic
            const stepProgress = {
                conversion: 30,
                extraction: 50,
                enrichment: 80
            };
            const targetProgress = stepProgress[step] || 10;

            if (step !== lastStep) {
                stuckTimer = 0;
                progress = Math.max(progress, targetProgress);
                updateProgressBar(progress);
                lastStep = step;
            } else {
                stuckTimer++;
                if (stuckTimer >= 3) {
                    progress = Math.max(0, progress - 3); // bounce back but never below 0%
                    updateProgressBar(progress);
                    await new Promise(r => setTimeout(r, 500));
                    progress = Math.max(progress, targetProgress);
                    updateProgressBar(progress);
                    stuckTimer = 0;
                }
            }

        } catch (err) {
            console.error("Greška u waitForAIResult:", err);
            clearInterval(fakeInterval);
            clearInterval(countdownInterval);
            Swal.close();
            showRetryError(
                "Greška pri skeniranju",
                err.message || "Nepoznata greška",
                () => waitForAIResult()
            );
            break;
        }

        // Update status text
        const el = document.getElementById("swal-status-message");
        if (el) {
            if (status === "failed" || status === "error") {
                el.innerHTML = `<span class='text-danger'>Greška: ${errorMsg || 'Nepoznata greška'}</span><br><span class='text-muted'>Korak: ${stepTextMap[step] || step || ''}</span>`;
            } else {
                el.textContent = stepTextMap[step] || "Obrađujemo podatke...";
            }
        }

        // SUCCESS
        if (status === "completed") {
            clearInterval(fakeInterval);
            clearInterval(countdownInterval);
            updateProgressBar(100); // Show 100% on completion

            Swal.close();

            setTimeout(() => {
                Swal.fire({
                    icon: "success",
                    title: "Završeno",
                    text: "Deklaracija je uspješno spremljena u draft",
                    showConfirmButton: false,
                    timer: 3000,
                    allowOutsideClick: false,
                    position: "center"
                });
            }, 300);

            _invoice_data = null;
            break;
        }

        // FAILURE
        if (status === "failed" || status === "error") {
            clearInterval(fakeInterval);
            clearInterval(countdownInterval);
            Swal.close();
            showRetryError(
                "Greška pri skeniranju",
                `${errorMsg || "Nepoznata greška"}<br><span class="text-muted">${stepTextMap[step] || step || ""}</span>`,
                () => waitForAIResult()
            );
            break;
        }

        await new Promise(r => setTimeout(r, 2000));
    }
}
$.fn.select2.defaults.set("language", {
    searching: function () {
        return "Pretraga...";
    },
    noResults: function () {
        return "Nema rezultata";
    },
    inputTooShort: function (args) {
        return `Unesite još ${args.minimum - args.input.length} znakova`;
    },
    loadingMore: function () {
        return "Učitavanje još rezultata...";
    },
});