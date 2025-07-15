function getCurrency() {
    let lockedCurrency = document.getElementById("currency-lock").value;
    if (!lockedCurrency) {
        for (const row of document.querySelectorAll("#newlink tr.product")) {
            const currencyInput = row.querySelector('input[name="currency[]"]');
            if (currencyInput && currencyInput.value.trim()) {
                lockedCurrency = currencyInput.value.trim();
                document.getElementById("currency-lock").value = lockedCurrency;
                console.log("✅ Locked currency:", lockedCurrency);
                break;
            }
        }
    }
    return lockedCurrency;
}

function setTotalPrice(total) {
    const currencySymbols = {
        "EUR": "€",
        "USD": "$",
        "KM": "KM",
        "BAM": "KM"
    };
    const currency = getCurrency() || "EUR";
    const currencySymbol = currencySymbols[currency] || currency;
    const formatted = `${formatDecimal(total, 2)} ${currencySymbol}`;
    document.getElementById("total-amount").value = formatted;
    document.getElementById("modal-total-amount").textContent = formatted;
    document.getElementById("total-edit").textContent = formatted;
}

function getCoefficients() {
    const payload = buildInvoicePayload(undefined, undefined);

    let totalPriceNumPackages = 0;
    let totalPriceGrossWeight = 0;
    let totalPriceNetWeight = 0;
    let totalPrice = 0;
    let numPackages = payload.total_num_packages;
    let grossWeight = payload.total_weight_gross;
    let netWeight = payload.total_weight_net;

    payload.items.forEach(item => {
        if (!item.weight_gross_locked) {
            totalPriceGrossWeight += item.total_price || 0;
        } else {
            grossWeight -= item.weight_gross || 0;
        }
        if (!item.weight_net_locked) {
            totalPriceNetWeight += item.total_price || 0;
        } else {
            netWeight -= item.weight_net || 0;
        }
        if (!item.num_packages_locked) {
            totalPriceNumPackages  += item.total_price || 0;
        } else {
            numPackages -= item.num_packages || 0;
        }
        totalPrice += item.total_price || 0;
    });

    // round totalValues to 2 decimal places
    totalPriceGrossWeight = Math.round(totalPriceGrossWeight * 100) / 100;
    totalPriceNetWeight = Math.round(totalPriceNetWeight * 100) / 100;
    totalPriceNumPackages = Math.round(totalPriceNumPackages * 100) / 100;

    totalPrice = Math.round(totalPrice * 100) / 100;
    setTotalPrice(totalPrice);

    const q1 = (numPackages > 0 && totalPriceNumPackages > 0) ? numPackages / totalPriceNumPackages : 0;
    const q2 = (grossWeight > 0 && totalPriceGrossWeight > 0) ? grossWeight / totalPriceGrossWeight : 0;
    const q3 = (netWeight > 0 && totalPriceNetWeight > 0) ? netWeight / totalPriceNetWeight : 0;

    return { q1, q2, q3, q1Total: numPackages, q2Total: grossWeight, q3Total: netWeight };
}

function updateSpecificEstimates(q, totalValue, rows, items, selector, is_locked_key) {
    let totalAdded = 0;
    const lastUnlockedItem = items.slice().reverse().find(item => !item[is_locked_key]);
    items.forEach((item, index) => {
        if (!item[is_locked_key]) {
            let result = q * item.total_price;
            // round result to 2 decimals
            result = Math.round(result * 100) / 100;
            totalAdded += result;
            // if we are on last item, don't add it normally ...
            // subtract from total in order to get the exact value
            if (item == lastUnlockedItem) {
                totalAdded -= result;
                result = totalValue - totalAdded;
            }
            result = Math.max(result, 0);
            rows[index].querySelector(selector).value = result.toFixed(2).replace(".", ",");
        }
    });
}

function updateLockableInputStatuses(rows, items) {
    items.forEach((item, index) => {
        rows[index].querySelector("input[name='num_packages[]']").disabled = item.num_packages_locked;
        rows[index].querySelector("input[name='weight_gross[]']").disabled = item.weight_gross_locked;
        rows[index].querySelector("input[name='weight_net[]']").disabled = item.weight_net_locked;
    })
}

function updateNumPackagesEstimates(q1, totalValue, rows, items) {
    updateSpecificEstimates(q1, totalValue, rows, items, "input[name='num_packages[]']", "num_packages_locked");
}
function updateBrutoEstimates(q2, totalValue, rows, items) {
    updateSpecificEstimates(q2, totalValue, rows, items, "input[name='weight_gross[]']", "weight_gross_locked");
}
function updateNetoEstimates(q3, totalValue, rows, items) {
    updateSpecificEstimates(q3, totalValue, rows, items, "input[name='weight_net[]']", "weight_net_locked");
}

function updateEstimates() {
    const { q1, q2, q3, q1Total, q2Total, q3Total } = getCoefficients();
    console.log(getCoefficients());
    const payload = buildInvoicePayload(undefined, undefined);
    const rows = Array.from(document.querySelectorAll("#newlink tr.product").values());
    updateNumPackagesEstimates(q1, q1Total, rows, payload.items);
    updateBrutoEstimates(q2, q2Total, rows, payload.items);
    updateNetoEstimates(q3, q3Total, rows, payload.items);
    updateLockableInputStatuses(rows, payload.items);
}

// This is set to be called onblur on input. It assumes the next element is a
// checkbox input. It checks it and then calls updateEstimates().
function lockableInputBlurred() {
    const checkbox = event.target.nextElementSibling;
    checkbox.checked = true;
    updateEstimates();
}