var processedTariffData = [];
fetch(window.global_tariff_path)
    .then(res => res.json())
    .then((tariffData) => {
        console.log(" Tariff data loaded:", tariffData);
        processedTariffData = tariffData
            .filter(item => item["Tarifna oznaka"] && item["Naziv"] && item["Puni Naziv"])
            .map(item => ({
                id: item["Tarifna oznaka"],
                text: item["Puni Naziv"].split(">>>").pop().trim(),
                display: `${item["Tarifna oznaka"]} – ${item["Naziv"]}`,
                depth: item["Puni Naziv"].split(">>>").length - 1,
                isLeaf: item["Tarifna oznaka"].replace(/\s/g, '').length >= 10,
                search: [item["Naziv"], item["Puni Naziv"], item["Tarifna oznaka"]].join(" ").toLowerCase()
            }));
        console.log(" Processed tariff data:", processedTariffData);
    })

function smartMaskHandler(e) {
    const rawInput = e.target.value;
    const digitsOnly = rawInput.replace(/\D+/g, "");

    if (digitsOnly.length >= 4) {
        const formatted = digitsOnly.replace(
            /^(\d{4})(\d{0,2})(\d{0,2})(\d{0,2})/,
            (_, a, b, c, d) => [a, b, c, d].filter(Boolean).join(' ')
        );

        if (/^\d[\d\s]*$/.test(rawInput)) {
            e.target.value = formatted;
            setTimeout(() => {
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }, 0);
        }
    }
}

$(document).on('click', '.use-tariff', function () {
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

// Handle AI suggestions button click
$(document).on('click', '.show-ai-btn', function () {
    console.log("AI button clicked");

    // 1) find the select2 and parse its suggestions
    const $select = $(this).closest('td').find('select.select2-tariff');
    let raw = $select.data('suggestions');
    if (!raw) {
        console.warn("No suggestions data on select");
        return;
    }
    try {
        if (typeof raw === 'string') raw = JSON.parse(raw);
    } catch (e) {
        console.error("Bad JSON in suggestions:", e);
        return;
    }
    if (!Array.isArray(raw)) {
        console.warn("Suggestions not an array:", raw);
        return;
    }

    // 2) sort by closeness, take top 10
    const sorted = raw
        .slice()
        .sort((a, b) => a.closeness - b.closeness)
        .slice(0, 10);

    // 3) render into the modal body
    const $body = $('#aiSuggestionsBody');
    if (sorted.length === 0) {
        $body.html('<div class="text-muted">Nema prijedloga.</div>');
    } else {
        const html = sorted.map((s, i) => {
            const isLast = i === sorted.length - 1;
            const code = s.entry["Tarifna oznaka"];
            const childName = s.entry["Naziv"];

            // split out the parent code (first segment)
            const parts = code.split(' ');
            let nameLabel;

            if (parts.length > 1) {
                const parentCode = parts[0];
                // look up the parent in your master list
                const parentItem = processedTariffData.find(item => item.id === parentCode);
                const parentName = parentItem
                    ? (parentItem.Naziv || parentItem.text || parentItem.label)
                    : parentCode;

                // format: Parent – Child (with child in bold)
                nameLabel = `${parentName} – <strong>${childName}</strong>`;
            } else {
                // no parent segment: just bold the whole name
                nameLabel = `<strong>${childName}</strong>`;
            }

            return `
                <div${!isLast ? ' class="mb-3"' : ''}>
                <div>
                    <strong>${i + 1}. Tarifna oznaka:</strong> ${code}
                </div>
                <div>
                    <strong>Naziv:</strong> ${nameLabel}
                </div>
                <button type="button"
                        class="btn btn-sm btn-info mt-1 use-tariff"
                        data-value="${code}">
                    Koristi ovu oznaku
                </button>
                ${!isLast ? '<hr>' : ''}
                </div>
            `;
        }).join('');

        $body.html(html);
    }

    // 4) stash the select2 field for the "use-tariff" handler
    $('#aiSuggestionModal').data('target-select', $select);

    // 5) show the Bootstrap modal
    const modalEl = document.getElementById('aiSuggestionModal');
    let modal = bootstrap.Modal.getInstance(modalEl)
        || new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: true });
    modal.show();
});

async function initializeTariffSelects(row) {
    await waitForFunction(() => processedTariffData.length);
    $select = $(row).find('.select2-tariff').first();
    const prefillValue = $select.data("prefill");

    // Reset Select2 if already initialized
    if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
    }

    $select.select2({
        placeholder: "Izaberi oznaku",
        allowClear: false,
        width: '100%',
        dropdownCssClass: 'tariff-selection',
        minimumInputLength: 1,
        language: {
            inputTooShort: () => "Pretraži oznake...",
            searching: () => "Pretraga...",
            noResults: () => "Nema rezultata..",
            loadingMore: () => "Učitavanje još rezultata..."
        },
        ajax: {
            transport: function (params, success, failure) {
                const term = (params.data.q || "").toLowerCase();

                // show spinner…
                const container = document.querySelector('.select2-results__options');
                if (container) {
                    container.innerHTML = `
                                <li class="select2-results__option" role="alert" aria-live="assertive">
                                    <i class="fa fa-spinner fa-spin" style="margin-right:6px;"></i>
                                    Pretraga...
                                </li>`;
                }

                // Find selected value and its parent
                let selectedItem = null;
                let parentItem = null;
                if ($select.val()) {
                    selectedItem = processedTariffData.find(item => item.id === $select.val());
                    if (selectedItem && selectedItem.id && selectedItem.id.length > 4) {
                        const parentId = selectedItem.id.slice(0, 4);
                        parentItem = processedTariffData.find(item => item.id === parentId);
                    }
                }

                // Filter matches
                const matches = processedTariffData.filter(item =>
                    item.id.toLowerCase().includes(term) ||
                    item.display.toLowerCase().includes(term)
                );
                const rest = processedTariffData.filter(item =>
                    !matches.some(m => m.id === item.id)
                );

                // Compose result: parent, selected, then matches+rest
                let results = [];
                if (parentItem) results.push(parentItem);
                if (selectedItem && (!parentItem || selectedItem.id !== parentItem.id)) results.push(selectedItem);
                // Remove duplicates
                const ids = new Set(results.map(i => i.id));
                const restList = [...matches, ...rest].filter(i => !ids.has(i.id));
                results = [...results, ...restList];

                setTimeout(() => {
                    success({ results });
                    // reset scroll
                    const dropdown = document.querySelector('.select2-results__options');
                    if (dropdown) dropdown.scrollTop = 0;
                }, 200);
            },
            delay: 200
        },

        templateResult: function (item) {
            if (!item || !item.id || !item.display) return null;

            const digits = item.id.replace(/\D+/g, '');
            const is4Digit = /^\d{4}$/.test(digits);
            const is6Digit = /^\d{6}$/.test(digits);

            let isParent = false;
            if (is4Digit) {
                isParent = true;
            } else if (is6Digit) {
                const parentId = item.id.slice(0, 4);
                isParent = !processedTariffData.some(other =>
                    other.id.startsWith(parentId) && /^\d{4}$/.test(other.id.replace(/\D+/g, ''))
                );
            }

            const icon = isParent ? "›" : "•";
            const padding = isParent ? 0 : item.depth * 5;
            const fontWeight = isParent ? "bold" : "normal";

            // Only items with 10 or more digits are enabled/clickable
            const isClickable = digits.length >= 10;
            const style = isClickable
                ? `padding-left:${padding}px; font-weight:${fontWeight}; cursor:pointer;`
                : `padding-left:${padding}px; font-weight:${fontWeight}; color:#aaa; cursor:not-allowed;`;

            // Tooltip: just description (without tariff number)
            let parentDesc = "";
            if (item.id && item.id.length > 4) {
                const parentId = item.id.slice(0, 4);
                const parent = processedTariffData.find(i => i.id === parentId);
                parentDesc = parent ? parent.display : "";
            }

            // --- CHILD DESC CLEANUP ---
            // Remove all digits and spaces before first letter in child desc
            function trimToFirstLetter(str) {
                if (!str) return "";
                // Find first letter (unicode safe)
                const match = str.match(/[A-Za-zŠĐČĆŽšđčćž]/);
                if (!match) return str;
                const idx = str.indexOf(match[0]);
                return str.slice(idx);
            }

            // Remove tariff numbers from tooltip for child
            let onlyDesc = item.display?.replace(/^\s*\d+\s*[-–]?\s*/, '') || "";
            onlyDesc = trimToFirstLetter(onlyDesc);

            // Parent desc is untouched (as requested)
            let onlyParentDesc = parentDesc.replace(/^\s*\d+\s*[-–]?\s*/, '');

            const tooltip = onlyParentDesc && onlyParentDesc !== onlyDesc
                ? `${onlyParentDesc} — ${onlyDesc}`
                : onlyDesc;

            // Remove native title, add data-bs-toggle for Bootstrap tooltip
            return $(`<div style="${style}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="${tooltip}">
                        ${icon} ${item.display}
                    </div>`);
        },
        templateSelection: function (item) {
            // Only allow selection if 10 or more digits
            const digits = (item?.id || '').replace(/\D+/g, '');
            if (digits.length >= 10) {
                // Set tooltip on the rendered selection
                setTimeout(() => {
                    const $container = $select.next('.select2-container').find('.select2-selection__rendered');
                    let parentDesc = "";
                    if (item.id && item.id.length > 4) {
                        const parentId = item.id.slice(0, 4);
                        const parent = processedTariffData.find(i => i.id === parentId);
                        parentDesc = parent ? parent.display : "";
                    }
                    // Remove tariff numbers from tooltip for child
                    function trimToFirstLetter(str) {
                        if (!str) return "";
                        const match = str.match(/[A-Za-zŠĐČĆŽšđčćž]/);
                        if (!match) return str;
                        const idx = str.indexOf(match[0]);
                        return str.slice(idx);
                    }
                    let onlyDesc = item.display?.replace(/^\s*\d+\s*[-–]?\s*/, '') || "";
                    onlyDesc = trimToFirstLetter(onlyDesc);
                    let onlyParentDesc = parentDesc.replace(/^\s*\d+\s*[-–]?\s*/, '');
                    const tooltip = onlyParentDesc && onlyParentDesc !== onlyDesc
                        ? `${onlyParentDesc} — ${onlyDesc}`
                        : onlyDesc;
                    $container.removeAttr('title'); // Remove native
                    $container.attr('data-bs-toggle', 'tooltip');
                    $container.attr('data-bs-placement', 'top');
                    $container.attr('data-bs-title', tooltip);
                    // Init or update Bootstrap tooltip
                    if (typeof bootstrap !== "undefined") {
                        bootstrap.Tooltip.getInstance($container[0])?.dispose();
                        new bootstrap.Tooltip($container[0]);
                    }
                }, 0);
                return item?.id || "";
            }
            return "";
        },
        // Prevent selection of items with less than 10 digits
        matcher: function (params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }
            if (typeof data.text === 'undefined') {
                return null;
            }
            const term = params.term.toLowerCase();
            if (data.text.toLowerCase().indexOf(term) > -1) {
                const digits = (data.id || '').replace(/\D+/g, '');
                if (digits.length >= 10) {
                    return data;
                }
            }
            return null;
        }
    });

    // Prevent selection of items with less than 10 digits
    $select.on('select2:selecting', function (e) {
        const digits = (e.params.args.data.id || '').replace(/\D+/g, '');
        if (digits.length < 10) {
            e.preventDefault();
        }
    });

    $select.on('select2:open', function () {
        setTimeout(() => {
            const input = document.querySelector('.select2-container--open .select2-search__field');
            if (input) {
                input.style.minWidth = '200px';
                input.focus();
                input.removeEventListener('input', smartMaskHandler);
                input.addEventListener('input', smartMaskHandler, { passive: true });

                // Prefill search field with selected value
                const selected = $select.val();
                if (selected) {
                    input.value = selected;
                    const evt = new Event('input', { bubbles: true });
                    input.dispatchEvent(evt);
                }
            }
            // Enable Bootstrap tooltips for dropdown items
            setTimeout(() => {
                if (window.tooltipManager) {
                    window.tooltipManager.reinitializeTooltips();
                } else {
                    $('.select2-results__option [data-bs-toggle="tooltip"]').each(function () {
                        if (!$(this).data('bs.tooltip')) {
                            new bootstrap.Tooltip(this);
                        }
                    });
                }
            }, 0);
        }, 0);
    });

    // Set tooltip for selection on initial load and after selection
    function setTooltipForSelection() {
        setTimeout(() => {
            const $container = $select.next('.select2-container').find('.select2-selection__rendered');
            const selectedId = $select.val();
            if (!selectedId) return;
            const selectedItem = processedTariffData.find(i => i.id === selectedId);
            if (!selectedItem) return;
            let parentDesc = "";
            if (selectedItem.id && selectedItem.id.length > 4) {
                const parentId = selectedItem.id.slice(0, 4);
                const parent = processedTariffData.find(i => i.id === parentId);
                parentDesc = parent ? parent.display : "";
            }
            // Remove tariff numbers from tooltip for child
            function trimToFirstLetter(str) {
                if (!str) return "";
                const match = str.match(/[A-Za-zŠĐČĆŽšđčćž]/);
                if (!match) return str;
                const idx = str.indexOf(match[0]);
                return str.slice(idx);
            }
            let onlyDesc = selectedItem.display?.replace(/^\s*\d+\s*[-–]?\s*/, '') || "";
            onlyDesc = trimToFirstLetter(onlyDesc);
            let onlyParentDesc = parentDesc.replace(/^\s*\d+\s*[-–]?\s*/, '');
            const tooltip = onlyParentDesc && onlyParentDesc !== onlyDesc
                ? `${onlyParentDesc} — ${onlyDesc}`
                : onlyDesc;
            $container.removeAttr('title');
            $container.attr('data-bs-toggle', 'tooltip');
            $container.attr('data-bs-placement', 'top');
            $container.attr('data-bs-title', tooltip);
            if (typeof bootstrap !== "undefined") {
                bootstrap.Tooltip.getInstance($container[0])?.dispose();
                new bootstrap.Tooltip($container[0]);
            }
        }, 0);
    }

    $select.on('select2:select', setTooltipForSelection);

    // Also set tooltip on initial load if value is prefilled
    setTooltipForSelection();

    if (prefillValue) {
        const match = processedTariffData.find(item => item.id === prefillValue);
        if (match) {
            const digits = (match.id || '').replace(/\D+/g, '');
            if (digits.length >= 10) {
                const option = new Option(match.id, match.id, true, true);
                $select.append(option).trigger('change');
            }
        }
    }
}