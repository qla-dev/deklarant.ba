
    const isEditMode = false;


    if (isEditMode) {

        console.warn(" Edit mode detected – skipping scan/autofill script.");
        // Exit the script entirely
        // Note: Wrap the entire content below inside an IIFE or block
        // Or better – put all scan logic inside a condition
    } else {
        console.log(' Custom invoice JS loaded');

        function showRetryError(title, message, retryCallback) {
            Swal.fire({
                title: title,
                html: `<div class="text-danger mb-2">${message}</div>`,
                icon: "error",
                showCancelButton: true,
                confirmButtonText: "Pokušaj ponovo",
                cancelButtonText: "Odustani",
                customClass: {
                    confirmButton: "btn btn-soft-info me-2",
                    cancelButton: "btn btn-info"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) retryCallback();
            });
        }

        let _invoice_data = null;
        let processedTariffData = [];
        let globalAISuggestions = [];
        const remaining_scans = window.remainingScans;





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




        function updateTotalAmount() {
            let total = 0;

            // Loop through all invoice rows
            document.querySelectorAll("#newlink tr.product").forEach(function(row) {
                const price = parseFloat(row.querySelector('input[name="price[]"]')?.value || 0);
                const quantity = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);
                total += price * quantity;
            });

            // Format as currency
            const formatted = `${total.toFixed(2)} KM`;

            // Set values in both places
            document.getElementById("total-amount").value = formatted;
            document.getElementById("modal-total-amount").textContent = formatted;
            document.getElementById("total-edit").textContent = formatted;

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
            Swal.fire({
                title: 'Skeniranje',
                html: `
                    <div class="custom-swal-spinner mb-3"></div>
                    <div id="swal-status-message">Čeka na obradu</div>
                    <div class="mt-3 w-100">
                        <div class="progress" style="height: 8px;">
                            <div id="scan-progress-bar" class="progress-bar bg-info" role="progressbar" style="width: 0%; transition: width 0.6s;"></div>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.8rem;">
                            Preostalo vrijeme: <span id="scan-timer">30s</span>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false
            });

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



        async function waitForAIResult(showLoader = true) {
            const invoice_id = getInvoiceId();
            if (!invoice_id) return;

            let progress = 0;
            let countdown = 30;
            let progressBar = null;
            let timerText = null;
            let fakeInterval = null;
            let countdownInterval = null;

            if (showLoader) {
                Swal.fire({
                    title: 'Skeniranje',
                    html: `
                <div class="custom-swal-spinner mb-3"></div>
                <div id="swal-status-message">Čeka na obradu</div>
                <div class="mt-3 w-100">
                    <div class="progress" style="height: 8px;">
                        <div id="scan-progress-bar" class="progress-bar bg-info" role="progressbar" style="width: 0%; transition: width 0.6s;"></div>
                    </div>
                    <div class="text-muted mt-1" style="font-size: 0.8rem;">
                        Preostalo vrijeme: <span id="scan-timer">30s</span>
                    </div>
                </div>
            `,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                // Čekaj da se DOM renderuje u SweetAlert-u
                await new Promise(r => setTimeout(r, 50));

                progressBar = document.getElementById("scan-progress-bar");
                timerText = document.getElementById("scan-timer");

                // Fake progress do 35%
                fakeInterval = setInterval(() => {
                    if (progress < 35) {
                        progress += 0.5;
                        if (progressBar) progressBar.style.width = `${progress}%`;
                    }
                }, 200);

                // Odbrojavanje
                countdownInterval = setInterval(() => {
                    countdown--;
                    if (timerText) timerText.textContent = `${countdown}s`;
                    if (countdown <= 0) clearInterval(countdownInterval);
                }, 1000);
            }

            const stepTextMap = {
                null: "Čeka na početak obrade",
                conversion: "Konvertovanje dokumenta",
                extraction: "Ekstrakcija podataka",
                enrichment: "Obogaćivanje podataka"
            };

            while (true) {
                let json = null;
                let status = null;
                let step = null;
                let errorMsg = null;

                try {
                    const res = await fetch(`/api/invoices/${invoice_id}/scan`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        }
                    });

                    if (!res.ok) {
                        throw new Error(`Greška kod API poziva: ${res.status} ${res.statusText}`);
                    }

                    json = await res.json();
                    status = json?.status?.status;
                    step = json?.status?.processing_step;
                    errorMsg = json?.status?.error_message;

                    // Ažuriraj progress prema step-u
                    if (step === "conversion") progress = Math.max(progress, 40);
                    if (step === "extraction") progress = Math.max(progress, 70);
                    if (step === "enrichment") progress = Math.max(progress, 90);
                    if (progressBar) progressBar.style.width = `${progress}%`;

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

                console.log("Scan status:", status, "| Step:", step, "| Error:", errorMsg);

                const el = document.getElementById("swal-status-message");
                if (el) {
                    if (status === "failed" || status === "error") {
                        el.innerHTML = `<span class='text-danger'>Greška: ${errorMsg || 'Nepoznata greška'}</span><br><span class='text-muted'>Korak: ${stepTextMap[step] || step || ''}</span>`;
                    } else {
                        const message = stepTextMap[step] || "Obrađujemo podatke";
                        el.textContent = message;
                    }
                }

                if (status === "completed") {
                    clearInterval(fakeInterval);
                    clearInterval(countdownInterval);
                    if (progressBar) progressBar.style.width = "100%";
                    if (el) el.textContent = "Završeno";
                    await new Promise(r => setTimeout(r, 1000));
                    if (el) el.textContent = "Faktura spremljena u draft";
                    await new Promise(r => setTimeout(r, 1000));
                    Swal.close();
                    _invoice_data = null;
                    break;
                }

                if (status === "failed" || status === "error") {
                    clearInterval(fakeInterval);
                    clearInterval(countdownInterval);
                    await new Promise(r => setTimeout(r, 1000));
                    Swal.close();
                    console.error("Scan error:", errorMsg);

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


        //await updateRemainingScans();



        function initializeTariffSelects() {
            $('.select2-tariff').each(function() {
                const select = $(this);
                const prefillValue = select.data("prefill");

                select.select2({
                    placeholder: "Pretraži tarifne stavke...",
                    width: '100%',
                    minimumInputLength: 1,
                    ajax: {
                        transport: function(params, success, failure) {
                            const term = params.data.q?.toLowerCase() || "";
                            const filtered = processedTariffData.filter(item => item.search.includes(term));
                            success({
                                results: filtered
                            });
                        },
                        delay: 200
                    },
                    templateResult: function(item) {
                        if (!item.id && !item.text) return null;
                        const icon = item.isLeaf ? "•" : "▶";
                        return $(`<div style="padding-left:${item.depth * 20}px;" title="${item.display}">${icon} ${item.display}</div>`);
                    },
                    templateSelection: function(item) {
                        return item.id || "";
                    }
                });

                // Programmatically set prefill value, only with Tarifna oznaka
                if (prefillValue) {
                    const matched = processedTariffData.find(item => item.id === prefillValue);
                    if (matched) {
                        const option = new Option(matched.id, matched.id, true, true);
                        select.append(option).trigger('change');
                    }
                }
            });
        }



        function addRowToInvoice(item = {}, suggestions = []) {
            const tbody = document.getElementById("newlink");
            const index = tbody.children.length;

            globalAISuggestions.push(suggestions);

            const name = item.name || item.item_description_original || "";
            const tariff = item.tariff_code || "";
            const price = item.base_price || 0;
            const quantity = item.quantity || 0;
            const origin = item.country_of_origin || "DE";
            const total = (price * quantity).toFixed(2);
            const desc = item.item_description;
            const package_num = item.num_packages || 0;
            const qtype = item.quantity_type || "";

            console.log(` Adding row ${index + 1}:`, item, suggestions);

            const row = document.createElement("tr");
            row.classList.add("product");




            function generateCountryOptions(selectedCode = "") {
                // ISO country codes + names (lowercase for flag URL)
                const countries = [{
                        code: "af",
                        name: "Afghanistan"
                    }, {
                        code: "al",
                        name: "Albania"
                    }, {
                        code: "dz",
                        name: "Algeria"
                    },
                    {
                        code: "ad",
                        name: "Andorra"
                    }, {
                        code: "ao",
                        name: "Angola"
                    }, {
                        code: "ag",
                        name: "Antigua and Barbuda"
                    },
                    {
                        code: "ar",
                        name: "Argentina"
                    }, {
                        code: "am",
                        name: "Armenia"
                    }, {
                        code: "au",
                        name: "Australia"
                    },
                    {
                        code: "at",
                        name: "Austria"
                    }, {
                        code: "az",
                        name: "Azerbaijan"
                    }, {
                        code: "bs",
                        name: "Bahamas"
                    },
                    {
                        code: "bh",
                        name: "Bahrain"
                    }, {
                        code: "bd",
                        name: "Bangladesh"
                    }, {
                        code: "bb",
                        name: "Barbados"
                    },
                    {
                        code: "by",
                        name: "Belarus"
                    }, {
                        code: "be",
                        name: "Belgium"
                    }, {
                        code: "bz",
                        name: "Belize"
                    },
                    {
                        code: "ba",
                        name: "Bosnia and Herzegovina"
                    }, {
                        code: "hr",
                        name: "Croatia"
                    }, {
                        code: "rs",
                        name: "Serbia"
                    },
                    {
                        code: "me",
                        name: "Montenegro"
                    }, {
                        code: "si",
                        name: "Slovenia"
                    }, {
                        code: "mk",
                        name: "North Macedonia"
                    },
                    {
                        code: "de",
                        name: "Germany"
                    }, {
                        code: "fr",
                        name: "France"
                    }, {
                        code: "us",
                        name: "United States"
                    },
                    {
                        code: "gb",
                        name: "United Kingdom"
                    }, {
                        code: "it",
                        name: "Italy"
                    }, {
                        code: "es",
                        name: "Spain"
                    },
                    {
                        code: "cn",
                        name: "China"
                    }, {
                        code: "jp",
                        name: "Japan"
                    }, {
                        code: "in",
                        name: "India"
                    }
                    // Add more if needed (or I can give you all 195 full set)
                ];
                return countries.map(({
                    code,
                    name
                }) => {
                    const flagUrl = `https://flagcdn.com/w40/${code}.png`;
                    const isSelected = selectedCode?.toLowerCase() === code ? "selected" : "";
                    return `<option value="${code.toUpperCase()}" ${isSelected} data-flag="${flagUrl}">${code.toUpperCase()}</option>`;
                }).join("");

            }



            row.innerHTML = `
          <td style="width: 50px;">${index + 1}</td>

          <td colspan="2" style="width: 340px;">
            <div class="input-group" style="display: flex; gap: 0.25rem;">
              <input type="text" class="form-control item-name" name="item_name[]" placeholder="Naziv proizvoda" value="${name}" style="flex:1;">
              <button class="btn btn-outline-info rounded" onmouseover="updateTooltip(this)" type="button" onclick="searchFromInputs(this)" data-bs-toggle="tooltip" data-bs-placement="top"   title="">
                 <i class="fa-brands fa-google"></i>
            </button>
              <input type="text" class="form-control item-desc" name="item_desc[]" placeholder="Opis proizvoda" value="${desc}" style="flex:1;">
            </div>
            <input 
              type="text" 
              class="form-control form-control-sm mt-1" 
              style="font-size: 0.85rem; padding-left:14.4px; height:37.1px;" 
              name="item_prev[]" 
              placeholder="Prevod"
            >
          </td>

          <td class="text-start" style="width: 150px;">
            <div style="position: relative; width: 100%;">
              <select
                class="form-control select2-tariff "
                style="width: 100%; padding-right: 45px;"
                name="item_code[]"
                data-prefill="${tariff}"
                data-suggestions='${JSON.stringify(suggestions)
                .replace(/&/g, '&amp;')
                .replace(/'/g, '&#39;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')}'>
              </select>

              <button
                type="button"
                class="btn btn-outline-info btn-sm show-ai-btn"

                style="
                  position: absolute;
                  top: 50%;
                  right: 5px;
                  transform: translateY(-50%);
                  height: 30px;
                  width: 30px;
                  padding: 0;
                  border-radius: 3px;
                "
                
                title="Prikaži AI prijedloge"
              >
                <i class="fas fa-wand-magic-sparkles" style="font-size: 16px;"></i>
              </button>
            </div>
          </td>

          <td style="width: 60px;">
            <input 
              type="text" 
              class="form-control" 
              name="quantity_type[]" 
              placeholder="AD, AE.." 
              value="${qtype}"
              
            >
          </td>

          <td style="width: 70px;">
            <select class="form-select" name="origin[]" style="width: 100%;">
              ${generateCountryOptions(origin)}
            </select>
          </td>

          <td style="width: 60px;">
            <input 
              type="number" 
              class="form-control text-start-truncate" 
              name="price[]" 
              value="${price}" 
              style="width: 100%;"
              
            >
          </td>

          <td style="width: 80px;">
            <div style="display: flex; flex-direction: column; gap: 2px; width: 100%;">
              <div class="input-group input-group-sm" style="width: 100%;">
                <button 
                  class="btn btn-outline-info btn-sm decrement-qty" 
                  style="background: #f4f4fc !important; width: 20px; padding: 0;" 
                  type="button"
                >−</button>
                <input 
                  type="number" 
                  class="form-control text-center" 
                  name="quantity[]" 
                  value="${quantity}" 
                  step="1" 
                  min="0"
                  style="padding: 0 5px; height: 30px;"
                >
                <button 
                  class="btn btn-outline-info btn-sm increment-qty" 
                  style="background: #f4f4fc !important; width: 20px; padding: 0;" 
                  type="button"
                >+</button>
              </div>
              
             <div class="input-group input-group-sm" style="height: 30px;">
                <button 
                class="btn btn-outline-info btn-sm decrement-kolata"
                  style="padding: 0; width: 20px;"
                >−</button>

                <input
                  type="number"
                  class="form-control text-center"
                  name="kolata[]"
                  placeholder="Broj paketa"
                  min="0"
                  step="1"
                  style="height: 30px; padding: 0 5px; font-size: 10px;"
                  value="${package_num}"
                >

                <button 
                  class="btn btn-outline-info btn-sm increment-kolata" 
                  type="button" 
                  style="padding: 0; width: 20px;"
                >+</button>
                </div>
            </div>
          </td>

          <td style="width: 70px;">
            <input 
              type="text" 
              class="form-control text-start" 
              name="total[]" 
              value="${total}"
              style="width: 100%;"
            >
          </td>

          <td style="width: 20px; text-align: center;">
              <div style="display: flex; flex-direction: column; align-items: end; gap: 2px;">
                <button type="button" class="btn btn-danger btn-sm remove-row text-center "   style="width: 30px;" title="Ukloni red"  >
                  <i class="fas fa-times"></i>
                </button>
                
                <input type="checkbox" class="form-check-input " data-bs-toggle="tooltip"  style="width: 30px; height: 26.66px; cursor: pointer;" title="Povlastica DA/NE" />
              </div>
            </td>

        `;


            $(row).find('select[name="origin[]"]').select2({
                templateResult: formatFlag,
                templateSelection: formatFlag,
                placeholder: "Select a country",
                width: 'style',
                language: {
                    noResults: function() {
                        return "Nisu pronađeni rezultati";
                    },
                    searching: function() {
                        return "Pretraga…";
                    },
                    inputTooShort: function() {
                        return "Unesite još znakova…";
                    }
                }
            });

            function formatFlag(state) {
                if (!state.id) return state.text;
                const flagUrl = $(state.element).data('flag');
                return $(`<span class="flag-option"><img src="${flagUrl}" width="20" style="margin-right: 10px;" /> ${state.text}</span>`);
            }

            tbody.appendChild(row);
            initializeTariffSelects();

            updateTotalAmount();
        }
        $(document).on('click', '.increment-qty', function() {
            const input = $(this).siblings('input[name="quantity[]"]');
            input.val(parseInt(input.val() || 0) + 1).trigger('input');
            updateTotalAmount();
        });

        $(document).on('click', '.decrement-qty', function() {
            const input = $(this).siblings('input[name="quantity[]"]');
            const current = parseInt(input.val() || 0);
            if (current > 0) {
                input.val(current - 1).trigger('input');
                updateTotalAmount();
            }
        });
        $(document).on('input', 'input[name="price[]"], input[name="quantity[]"]', function() {
            const row = $(this).closest('tr');
            const price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            const quantity = parseInt(row.find('input[name="quantity[]"]').val()) || 0;
            const total = (price * quantity).toFixed(2);
            row.find('input[name="total[]"]').val(total);

            // Optional: update global total as well
            updateTotalAmount();
        });



        document.addEventListener('click', (event) => {
            // Handle decrement button click
            if (event.target.closest('.decrement-kolata')) {
                const container = event.target.closest('div'); // or closest input group wrapper
                const input = container.querySelector('input[name="kolata[]"]');
                if (input) {
                    let currentValue = parseInt(input.value) || 0;
                    if (currentValue > 0) {
                        input.value = currentValue - 1;
                        input.dispatchEvent(new Event('change')); // if you listen for changes
                    }
                }
            }

            // Handle increment button click
            if (event.target.closest('.increment-kolata')) {
                const container = event.target.closest('div'); // or closest input group wrapper
                const input = container.querySelector('input[name="kolata[]"]');
                if (input) {
                    let currentValue = parseInt(input.value) || 0;
                    input.value = currentValue + 1;
                    input.dispatchEvent(new Event('change'));
                }
            }

            // Initialize all tooltips once
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) { // avoid re-init
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover',
                        delay: {
                            show: 100,
                            hide: 100
                        }
                    });
                }
            });

            // Add a single click listener outside to hide tooltips on outside click
            document.addEventListener('click', function(e) {
                tooltipTriggerList.forEach(function(el) {
                    var tooltip = bootstrap.Tooltip.getInstance(el);
                    if (tooltip && e.target !== el && !el.contains(e.target)) {
                        tooltip.hide();
                    }
                });
            });



        });


        async function fillInvoiceData() {
            const invoice = await getInvoice();
            waitForEl("#invoice-id1", el => el.textContent = invoice.id || "—");
            waitForEl("#invoice-date-text", el => el.textContent = invoice.date_of_issue || "—");
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

            await updateRemainingScans();




        }


        async function fetchAndPrefillParties() {
            const taskId = window.global_invoice_id;
            if (!taskId || !token) return;

            try {
                const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });

                const data = await res.json();
                if (!res.ok) throw new Error("Greška u AI response");

                const {
                    supplier,
                    importer,
                    supplier_id,
                    importer_id
                } = data;
                // Get invoice data for IDs
                const invoice = await getInvoice();

                // --- SUPPLIER LOGIC ---
                let supplierId = invoice.supplier_id || supplier_id;
                if (window.forceNewSupplier) {
                    // Always remove any previous 'Novi klijent' option
                    $("#supplier-select2 option[value='new']").remove();
                    // Add and select 'Novi klijent'
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    if (supplier) {
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    } else {
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false);
                    }
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                    window.forceNewSupplier = false;
                } else if (supplierId) {
                    // Ensure the option exists before setting value
                    if ($(`#supplier-select2 option[value='${supplierId}']`).length === 0) {
                        const text = supplier?.name ? `${supplier.name} – ${supplier.address || ''}` : supplierId;
                        const newOption = new Option(text, supplierId, true, true);
                        $("#supplier-select2").append(newOption);
                    }
                    console.log("[SUPPLIER] Prefilling from ID:", supplierId, invoice.supplier_id ? 'invoice.supplier_id' : 'parties.supplier_id');
                    $("#supplier-select2").val(supplierId).trigger("change");
                    $.ajax({
                        url: `/api/suppliers/${supplierId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        },
                        success: function(dbSupplier) {
                            console.log("[SUPPLIER] Prefilling textboxes from DB for ID:", supplierId, dbSupplier);
                            $("#billing-name").val(dbSupplier.name || "").prop('readonly', true);
                            $("#billing-address-line-1").val(dbSupplier.address || "").prop('readonly', true);
                            $("#billing-phone-no").val(dbSupplier.contact_phone || "").prop('readonly', true);
                            $("#billing-tax-no").val(dbSupplier.tax_id || "").prop('readonly', true);
                            $("#email").val(dbSupplier.contact_email || "").prop('readonly', true);
                            $("#supplier-owner").val(dbSupplier.owner || "").prop('readonly', true);
                            const label = document.getElementById("billing-name-ai-label");
                            if (label) label.classList.add("d-none");
                        },
                        error: function() {
                            if (supplier) {
                                // Not found in DB, add 'Novi klijent' to select2
                                const newOption = new Option('Novi klijent', 'new', true, true);
                                $("#supplier-select2").append(newOption).trigger('change');
                                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                                $("#email").val(supplier.email || "").prop('readonly', false);
                                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                                const label = document.getElementById("billing-name-ai-label");
                                if (label) label.classList.remove("d-none");
                            }
                        }
                    });
                } else if (supplier) {
                    // Not found in DB, add 'Novi klijent' to select2
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    $("#billing-name").val(supplier.name || "").prop('readonly', false);
                    $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                    $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                    $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                    $("#email").val(supplier.email || "").prop('readonly', false);
                    $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                }

                // --- IMPORTER LOGIC ---
                let importerId = invoice.importer_id || importer_id;
                if (window.forceNewImporter) {
                    $("#importer-select2 option[value='new']").remove();
                    const newOption = new Option('Novi dobavljač', 'new', true, true);
                    $("#importer-select2").append(newOption).trigger('change');
                    if (importer) {
                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                    } else {
                        $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false);
                    }
                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.remove("d-none");
                    window.forceNewImporter = false;
                } else if (importerId) {
                    // Ensure the option exists before setting value
                    if ($(`#importer-select2 option[value='${importerId}']`).length === 0) {
                        const text = importer?.name ? `${importer.name} – ${importer.address || ''}` : importerId;
                        const newOption = new Option(text, importerId, true, true);
                        $("#importer-select2").append(newOption);
                    }
                    console.log("[IMPORTER] Prefilling from ID:", importerId, invoice.importer_id ? 'invoice.importer_id' : 'parties.importer_id');
                    $("#importer-select2").val(importerId).trigger("change");
                    $.ajax({
                        url: `/api/importers/${importerId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        },
                        success: function(dbImporter) {
                            console.log("[IMPORTER] Prefilling textboxes from DB for ID:", importerId, dbImporter);
                            $("#carrier-name").val(dbImporter.name || "").prop('readonly', true);
                            $("#carrier-address").val(dbImporter.address || "").prop('readonly', true);
                            $("#carrier-tel").val(dbImporter.contact_phone || "").prop('readonly', true);
                            $("#carrier-tax").val(dbImporter.tax_id || "").prop('readonly', true);
                            $("#carrier-email").val(dbImporter.contact_email || "").prop('readonly', true);
                            $("#carrier-owner").val(dbImporter.owner || "").prop('readonly', true);
                            const label = document.getElementById("carrier-name-ai-label");
                            if (label) label.classList.add("d-none");
                        },
                        error: function() {
                            if (importer) {
                                // Not found in DB, add 'Novi dobavljač' to select2
                                const newOption = new Option('Novi dobavljač', 'new', true, true);
                                $("#importer-select2").append(newOption).trigger('change');
                                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                                const label = document.getElementById("carrier-name-ai-label");
                                if (label) label.classList.remove("d-none");
                            }
                        }
                    });
                } else if (importer) {
                    // Always remove any previous 'Novi dobavljač' option
                    $("#importer-select2 option[value='new']").remove();
                    // Add and select 'Novi dobavljač'
                    const newOption = new Option('Novi dobavljač', 'new', true, true);
                    $("#importer-select2").append(newOption).trigger('change');
                    $("#carrier-name").val(importer.name || "").prop('readonly', false);
                    $("#carrier-address").val(importer.address || "").prop('readonly', false);
                    $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                    $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                    $("#carrier-email").val(importer.email || "").prop('readonly', false);
                    $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.remove("d-none");
                }

            } catch (err) {
                console.error("Greška u fetchAndPrefillParties:", err);
                showRetryError(
                    "Greška pri dohvaćanju podataka",
                    err.message || "Neuspješno dohvaćanje podataka",
                    () => fetchAndPrefillParties()
                );


            }


        }

        async function fetchAndPrefillSupplierOnly() {
            const taskId = window.global_invoice_id;
            if (!taskId || !token) return;

            try {
                const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    }
                });

                const data = await res.json();
                if (!res.ok) throw new Error("Greška u AI response");

                const {
                    supplier,
                    supplier_id
                } = data;
                const invoice = await getInvoice();

                let supplierId = invoice.supplier_id || supplier_id;
                if (window.forceNewSupplier) {
                    $("#supplier-select2 option[value='new']").remove();
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    if (supplier) {
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    } else {
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false);
                    }
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                    window.forceNewSupplier = false;

                } else if (supplierId) {
                    if ($(`#supplier-select2 option[value='${supplierId}']`).length === 0) {
                        const text = supplier?.name ? `${supplier.name} – ${supplier.address || ''}` : supplierId;
                        const newOption = new Option(text, supplierId, true, true);
                        $("#supplier-select2").append(newOption);
                    }
                    console.log("[SUPPLIER] Prefilling from ID:", supplierId);
                    $("#supplier-select2").val(supplierId).trigger("change");

                    $.ajax({
                        url: `/api/suppliers/${supplierId}`,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        },
                        success: function(dbSupplier) {
                            console.log("[SUPPLIER] Prefilling from DB:", dbSupplier);
                            $("#billing-name").val(dbSupplier.name || "").prop('readonly', true);
                            $("#billing-address-line-1").val(dbSupplier.address || "").prop('readonly', true);
                            $("#billing-phone-no").val(dbSupplier.contact_phone || "").prop('readonly', true);
                            $("#billing-tax-no").val(dbSupplier.tax_id || "").prop('readonly', true);
                            $("#email").val(dbSupplier.contact_email || "").prop('readonly', true);
                            $("#supplier-owner").val(dbSupplier.owner || "").prop('readonly', true);
                            const label = document.getElementById("billing-name-ai-label");
                            if (label) label.classList.add("d-none");
                        },
                        error: function(xhr) {
                            console.warn("Supplier not found in DB. Status:", xhr.status);

                            Swal.fire({
                                title: "Klijent nije pronađen",
                                text: "Podaci za klijenta nisu pronađeni u bazi. Unos će biti omogućen ručno.",
                                icon: "info",
                                confirmButtonText: "U redu",
                                confirmButtonColor: "#299dcb"
                            });

                            if (supplier) {
                                const newOption = new Option('Novi klijent', 'new', true, true);
                                $("#supplier-select2").append(newOption).trigger('change');
                                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                                $("#email").val(supplier.email || "").prop('readonly', false);
                                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                                const label = document.getElementById("billing-name-ai-label");
                                if (label) label.classList.remove("d-none");
                            }
                        }

                    });

                } else if (supplier) {
                    const newOption = new Option('Novi klijent', 'new', true, true);
                    $("#supplier-select2").append(newOption).trigger('change');
                    $("#billing-name").val(supplier.name || "").prop('readonly', false);
                    $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                    $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                    $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                    $("#email").val(supplier.email || "").prop('readonly', false);
                    $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.remove("d-none");
                }

            } catch (err) {
                console.error("Greška u fetchAndPrefillSupplierOnly:", err);
                showRetryError(
                    "Greška pri dohvaćanju dobavljača",
                    err.message || "Neuspješno dohvaćanje",
                    () => fetchAndPrefillSupplierOnly()
                );
            }
        }

        async function fetchAndPrefillImporterOnly() {
    const taskId = window.global_invoice_id;
    if (!taskId || !token) return;

    try {
        console.log("[IMPORTER] Starting fetchAndPrefillImporterOnly... Task ID:", taskId);

        const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });

        const data = await res.json();
        console.log("[IMPORTER] API response:", data);

        if (!res.ok) throw new Error("Greška u AI response");

        const { importer, importer_id } = data;
        const invoice = await getInvoice();
        console.log("[IMPORTER] Existing invoice data:", invoice);

        let importerId = invoice.importer_id || importer_id;
        console.log("[IMPORTER] Final importerId to use:", importerId);

        if (window.forceNewImporter) {
            console.log("[IMPORTER] Forcing new importer...");
            $("#importerr-select2 option[value='new']").remove();

            const labelText = importer?.name ? `${importer.name} – ${importer.address || ''}` : 'Novi dobavljač';
            const newOption = new Option(labelText, String(importerId), true, true);
            $("#importer-select2").append(newOption);
            $("#importer-select2").val(String(importerId)).trigger("change");

            console.log("[IMPORTER] Added 'new' option and triggered change");

            if (importer) {
                console.log("[IMPORTER] Prefilling fields with AI importer data");
                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
            } else {
                console.warn("[IMPORTER] No importer data provided, clearing fields");
                $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false);
            }

            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");

            window.forceNewImporter = false;

        } else if (importerId) {
            const stringId = String(importerId);
            console.log("[IMPORTER] importerId exists:", stringId);

            if ($(`#importer-select2 option[value='${stringId}']`).length === 0) {
                const text = importer?.name ? `${importer.name} – ${importer.address || ''}` : stringId;
                console.log("[IMPORTER] Option not found. Adding manually:", text);
                const newOption = new Option(text, stringId, true, true);
                $("#importer-select2").append(newOption);
            } else {
                console.log("[IMPORTER] Option already exists in select2:", stringId);
            }

            console.log("[IMPORTER] Setting carrier-select2 to:", stringId);
            $("#importer-select2").val(stringId).trigger("change");

            $.ajax({
                url: `/api/importers/${stringId}`,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                },
                success: function (dbImporter) {
                    console.log("[IMPORTER] DB importer found:", dbImporter);

                    $("#carrier-name").val(dbImporter.name || "").prop('readonly', true);
                    $("#carrier-address").val(dbImporter.address || "").prop('readonly', true);
                    $("#carrier-tel").val(dbImporter.contact_phone || "").prop('readonly', true);
                    $("#carrier-tax").val(dbImporter.tax_id || "").prop('readonly', true);
                    $("#carrier-email").val(dbImporter.contact_email || "").prop('readonly', true);
                    $("#carrier-owner").val(dbImporter.owner || "").prop('readonly', true);

                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.add("d-none");
                },
                error: function (xhr) {
                    console.warn("Importer not found in DB. Status:", xhr.status);

                    Swal.fire({
                        title: "Uvoznik nije pronađen",
                        text: "Podaci za uvoznika nisu pronađeni u bazi. Unos će biti omogućen ručno.",
                        icon: "info",
                        confirmButtonText: "U redu",
                        confirmButtonColor: "#299dcb"
                    });

                    if (importer) {
                        console.log("[IMPORTER] Falling back to AI importer data");
                        const newOption = new Option('Novi dobavljač', 'new', true, true);
                        $("#importer-select2").append(newOption).trigger('change');

                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);

                        const label = document.getElementById("carrier-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    }
                }
            });

        } else if (importer) {
            console.log("[IMPORTER] No ID but importer data available. Treating as new.");
            const newOption = new Option('Novi dobavljač', 'new', true, true);
            $("#importer-select2").append(newOption).trigger('change');

            $("#carrier-name").val(importer.name || "").prop('readonly', false);
            $("#carrier-address").val(importer.address || "").prop('readonly', false);
            $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
            $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
            $("#carrier-email").val(importer.email || "").prop('readonly', false);
            $("#carrier-owner").val(importer.owner || "").prop('readonly', false);

            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");
        }

    } catch (err) {
        console.error("Greška u fetchAndPrefillImporterOnly:", err);
        showRetryError(
            "Greška pri dohvaćanju uvoznika",
            err.message || "Neuspješno dohvaćanje",
            () => fetchAndPrefillImporterOnly()
        );
    }
}




        document.addEventListener("DOMContentLoaded", async () => {

            window.skipPrefillParties = false; // Always allow prefill on page load/scan
            console.log(" Page loaded. Starting init process...");

            // Parallelize tariff and invoice fetch for faster load
            const [tariffRes, invoice] = await Promise.all([
                fetch("{{ URL::asset('build/json/tariff.json') }}"),
                getInvoice()
            ]);
            const tariffData = await tariffRes.json();
            console.log(" Tariff data loaded:", tariffData);

            processedTariffData = tariffData
                .filter(item => item["Tarifna oznaka"] && item["Naziv"] && item["Puni Naziv"])
                .map(item => ({
                    id: item["Tarifna oznaka"],
                    text: item["Puni Naziv"].split(">>>").pop().trim(),
                    display: `${item["Tarifna oznaka"]} – ${item["Naziv"]}`,
                    depth: item["Puni Naziv"].split(">>>").length - 1,
                    isLeaf: item["Tarifna oznaka"].replace(/\s/g, '').length === 10,
                    search: [item["Naziv"], item["Puni Naziv"], item["Tarifna oznaka"]].join(" ").toLowerCase()
                }));
            console.log(" Processed tariff data:", processedTariffData);

            // Only show loader and run scan if needed
            let scanNeeded = false;
            if (invoice.task_id == null) scanNeeded = true;
            else if (!invoice.items?.length) scanNeeded = true;

            if (scanNeeded) {
                Swal.fire({
                    title: 'Skeniranje',
                    html: `<div class="custom-swal-spinner mb-3"></div><div id="swal-status-message">Čeka na obradu</div>`,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

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
                }


                Swal.close();
            }


            _invoice_data = null;

            await fillInvoiceData();
            if (!window.skipPrefillParties) {
                await fetchAndPrefillParties();
            }
            window.skipPrefillParties = false; // reset after use
            $("#supplier-select2").select2({
                placeholder: "Pretraži klijenta",
                allowClear: true,
                ajax: {
                    url: "/api/suppliers",
                    dataType: "json",
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    },
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data.data.map(s => ({
                            id: s.id,
                            text: `${s.name} – ${s.address}`,
                            full: s
                        }))
                    }),
                    cache: true
                },
                tags: true,
                allowClear: false
            });

            $('#supplier-select2').on('select2:select', function(e) {
                const data = e.params.data.full;
                if (data) {
                    $('#billing-name').val(data.name || "");
                    $('#billing-address-line-1').val(data.address || "");
                    $('#billing-phone-no').val(data.contact_phone || "");
                    $('#billing-tax-no').val(data.tax_id || "");
                    $('#email').val(data.contact_email || "");
                    $('#supplier-owner').val(data.owner || ""); // Fill owner field
                }
            });

            $("#importer-select2").select2({
                placeholder: "Pretraži dobavljača",
                allowClear: true,
                ajax: {
                    url: "/api/importers",
                    dataType: "json",
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        Authorization: `Bearer ${token}`
                    },
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data.data.map(s => ({
                            id: s.id,
                            text: `${s.name} – ${s.address}`,
                            full: s
                        }))
                    }),
                    cache: true
                },
                tags: true,
                allowClear: false
            });

            $('#importer-select2').on('select2:select', function(e) {
                const data = e.params.data.full;
                if (data) {

                    $('#carrier-address').val(data.address || "");
                    $('#carrier-name').val(data.name || "");
                    $('#carrier-phone').val(data.contact_phone || "");
                    $('#carrier-tax').val(data.tax_id || "");
                    $('#carrier-email').val(data.contact_email || "");
                    $('#carrier-owner').val(data.owner || ""); // Fill owner field
                }
            });


            // await promptForSupplierAfterScan();
            $(document).on('click', '.show-ai-btn', function() {
                console.log(" AI button clicked");

                const select = $(this).closest('td').find('select.select2-tariff');
                console.log(" Found select element:", select);

                let rawSuggestions = select.data("suggestions");
                try {
                    if (typeof rawSuggestions === "string") {
                        rawSuggestions = JSON.parse(rawSuggestions);
                    }
                } catch (err) {
                    console.error(" Failed to parse suggestions JSON:", err);
                    return;
                }

                console.log(" Raw suggestions:", rawSuggestions);

                if (!rawSuggestions) {
                    console.warn(" No suggestions found on data attribute.");
                    return;
                }

                if (!Array.isArray(rawSuggestions)) {
                    console.warn(" Suggestions are not an array:", typeof rawSuggestions);
                    return;
                }

                const sorted = [...rawSuggestions].sort((a, b) => a.closeness - b.closeness).slice(0, 10);
                console.log(" Sorted suggestions:", sorted);

                const container = document.getElementById("aiSuggestionsBody");
                if (!container) {
                    console.error(" aiSuggestionsBody not found in DOM");
                    return;
                }

                if (!sorted.length) {
                    container.innerHTML = `<div class="text-muted">Nema prijedloga.</div>`;
                    console.log("ℹ No sorted suggestions to show.");
                } else {
                    container.innerHTML = sorted.map((s, i) => `
            <div class="mb-2">
                <div><strong>${i + 1}. Tarifna oznaka:</strong> ${s.entry["Tarifna oznaka"]}</div>
                <div><strong>Naziv:</strong> ${s.entry["Naziv"]}</div>
                <button class="btn btn-sm btn-info mt-1 use-tariff" data-value="${s.entry["Tarifna oznaka"]}">
                    Koristi ovu oznaku
                </button>
                <hr>
            </div>
        `).join("");
                    console.log(" Inserted suggestions into modal body.");
                }

                $('#aiSuggestionModal').data('target-select', select);
                console.log(" Set data-target-select on modal.");

                const modalEl = document.getElementById("aiSuggestionModal");
                if (!modalEl) {
                    console.error(" Modal element not found with ID aiSuggestionModal");
                    return;
                }

                let modal = bootstrap.Modal.getInstance(modalEl);
                console.log(" Existing modal instance:", modal);

                if (!modal) {
                    modal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static',
                        keyboard: true
                    });
                    console.log(" Modal instance created.");
                }

                modal.show();
                console.log(" Bootstrap modal should be showing now.");
            });

            $(document).on('click', '.use-tariff', function() {
                const code = $(this).data('value');
                console.log(" User selected code:", code);

                const select = $('#aiSuggestionModal').data('target-select');
                console.log(" Target select:", select);

                if (select && code) {
                    const matched = processedTariffData.find(item => item.id === code);
                    console.log(" Matched tariff code:", matched);

                    if (matched) {
                        const option = new Option(matched.id, matched.id, true, true);
                        select.find('option').remove();
                        select.append(option).trigger('change');
                        console.log(" Code applied to select2 field.");
                    } else {
                        console.warn(" No match found in processedTariffData.");
                    }
                } else {
                    console.warn(" Select or code not defined properly.");
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById("aiSuggestionModal"));
                if (modal) {
                    modal.hide();
                    console.log(" Modal closed.");
                } else {
                    console.warn(" No modal instance to close.");
                }
            });

            document.addEventListener("click", function(e) {
                if (e.target.closest(".remove-row")) {
                    const button = e.target.closest(".remove-row");
                    const row = button.closest("tr");

                    Swal.fire({
                        title: "Oprez!",
                        text: "Odabrani proizvod će biti trajno uklonjen sa popisa trenutne deklaracije. Ova radnja nije ireverzibilna!",
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
                            updateTotalAmount();
                        }
                    });
                }
            });


            function formatDateToDDMMYYYY(dateString) {
                if (!dateString) return '';
                if (typeof dateString === 'string') {
                    const [year, month, day] = dateString.split('-');
                    return `${day}.${month}.${year}`;
                } else if (dateString instanceof Date) {
                    const d = dateString;
                    const day = String(d.getDate()).padStart(2, '0');
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const year = d.getFullYear();
                    return `${day}.${month}.${year}`;
                }
                return '';
            }

            flatpickr("#invoice-date", {
                locale: "bs",
                dateFormat: "d.m.Y"
            });





            const invNo = getInvoiceId();
            if (invNo) document.getElementById("invoice-no1").value = invNo;

            document.getElementById("invoice-date").value = formatDateToDDMMYYYY(new Date());

            console.log(" Invoice date and number set.");

            //document.getElementById("company-address").value = "Vilsonovo, 9, Sarajevo ";
            document.getElementById("company-zip").value = "71000";
            document.getElementById("company-email").value = "business@deklarant.ai";


            document.getElementById("billing-name")?.addEventListener("input", () => {
                const label = document.getElementById("billing-name-ai-label");
                if (label) label.classList.add("d-none");
            });

            // Hide AI label when user types in importer name
            document.getElementById("carrier-name")?.addEventListener("input", () => {
                const label = document.getElementById("carrier-name-ai-label");
                if (label) label.classList.add("d-none");
            });


        });


        // Add buttons above supplier and importer fields
        $(document).ready(function() {

            // Handler for new supplier
            $(document).on('click', '#add-new-supplier', function() {
                if (window.isResetConfirmed) return;

                Swal.fire({
                    title: 'Oprez!',
                    text: 'Ova radnja će izbrisati sve podatke za klijenta i omogućiti ponovno automatsko popunjavanje.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: "btn btn-soft-info me-2",
                        cancelButton: "btn btn-info"
                    },

                    focusCancel: true,

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set flag to trigger refetch
                        window.forceNewSupplier = false;
                        window.skipPrefillParties = false;

                        // Očistimo polja i učitamo ponovo iz baze
                        $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner")
                            .val("")
                            .prop('readonly', true)
                            .removeClass("is-invalid");

                        $("#supplier-select2").empty();

                        // Pokrećemo ponovno popunjavanje samo za supplier
                        fetchAndPrefillSupplierOnly();
                    }
                });
            });





            // Handler for new importer
            $(document).on('click', '#add-new-importer', function() {
                if (window.isResetConfirmed) return;

                Swal.fire({
                    title: 'Oprez!',
                    text: 'Ova radnja će izbrisati sve podatke za dobavljača i omogućiti ponovno automatsko popunjavanje.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: "btn btn-soft-info me-2",
                        cancelButton: "btn btn-info"
                    },

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.forceNewImporter = false;
                        window.skipPrefillParties = false;

                        // Očisti inpute i pripremi Select2
                        $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner")
                            .val("")
                            .prop('readonly', true)
                            .removeClass("is-invalid");

                        $("#importer-select2").empty();

                        // Ponovno preuzimanje samo importer podataka
                        fetchAndPrefillImporterOnly();
                    }
                });
            });
        });




        // Add SweetAlert confirmation for importer manual entry

        // 1. Add buttons in the DOM (jQuery, after DOMContentLoaded)
        $(document).ready(function() {
            // Add 'Popuni ponovo s AI' button next to 'Obriši' for supplier


            // Handler for supplier AI refill
            $(document).on('click', '#refill-supplier-ai', async function() {
                const taskId = window.global_invoice_id;
                if (!taskId || !window.token) return;
                try {
                    const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        }
                    });

                    const data = await res.json();
                    if (!res.ok) throw new Error("Greška u AI response");
                    const supplier = data.supplier;
                    if (supplier) {
                        // Set select2 to 'Novi klijent'
                        $("#supplier-select2 option[value='new']").remove();
                        var newOption = new Option('Novi klijent', 'new', true, true);
                        $("#supplier-select2").append(newOption).val('new').trigger('change');
                        // Fill fields
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                        const label = document.getElementById("billing-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    } else {
                        Swal.fire("Greška", "Nema AI podataka za klijenta", "error");
                    }
                } catch (err) {
                    Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
                }
            });

            // Handler for importer AI refill
            $(document).on('click', '#refill-importer-ai', async function() {
                const taskId = window.global_invoice_id;
                if (!taskId || !window.token) return;
                try {
                    const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Authorization: `Bearer ${token}`
                        }
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error("Greška u AI response");
                    const importer = data.importer;
                    if (importer) {
                        // Set select2 to 'Novi dobavljač'
                        $("#importer-select2 option[value='new']").remove();
                        var newOption = new Option('Novi dobavljač', 'new', true, true);
                        $("#importer-select2").append(newOption).val('new').trigger('change');
                        // Fill fields
                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                        const label = document.getElementById("carrier-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    } else {
                        Swal.fire("Greška", "Nema AI podataka za dobavljača", "error");
                    }
                } catch (err) {
                    Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
                }
            });
        });




    }
