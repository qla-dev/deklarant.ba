// Validate that neto (net) weight cannot be greater than bruto (gross) weight
// Applies to both total and per-item fields
// Shows SweetAlert2 and resets the problematic field to 0,00 if violated

document.addEventListener('DOMContentLoaded', function () {
    // Helper to parse value ("0,00" to float)
    function parseDecimal(val) {
        if (!val) return 0;
        return parseFloat(val.replace(/\./g, '').replace(',', '.')) || 0;
    }
    // Helper to format value (float to "0,00")
    function formatDecimal(val) {
        return val.toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    // Show SweetAlert2 error
    function showWeightError(type) {
        let msg = type === 'total'
            ? 'Neto težina ne može biti veća od bruto težine'
            : 'Neto težina ne može biti veća od bruto težine za stavku';
        Swal.fire({
            icon: 'error',
            title: 'Neispravan unos',
            text: msg,
            confirmButtonText: 'OK',
            customClass: { popup: 'custom-swal-popup', confirmButton: 'bg-info' }
        });
    }

    // Validate total fields (on change/blur)
    function validateTotalWeights(e) {
        const netInput = document.getElementById('total-weight-net');
        const grossInput = document.getElementById('total-weight-gross');
        if (!netInput || !grossInput) return;
        const net = parseDecimal(netInput.value);
        const gross = parseDecimal(grossInput.value);
        // Determine which field is being edited
        const isEditingNet = e && e.target === netInput;
        const isEditingGross = e && e.target === grossInput;
        if (net > gross) {
            showWeightError('total');
            if (isEditingNet) {
                netInput.value = grossInput.value; // set neto to bruto's value
                netInput.dispatchEvent(new Event('input', { bubbles: true }));
            } else if (isEditingGross) {
                grossInput.value = netInput.value; // set bruto to neto's value
                grossInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }
    // Validate per-item fields (on change/blur)
    function validateItemWeights(e) {
        const row = e.target.closest('tr');
        if (!row) return;
        const netInput = row.querySelector('input[name="weight_net[]"]');
        const grossInput = row.querySelector('input[name="weight_gross[]"]');
        if (!netInput || !grossInput) return;
        const net = parseDecimal(netInput.value);
        const gross = parseDecimal(grossInput.value);
        const isEditingNet = e && e.target === netInput;
        const isEditingGross = e && e.target === grossInput;
        if (net > gross) {
            showWeightError('item');
            if (isEditingNet) {
                netInput.value = grossInput.value; // set neto to bruto's value
                netInput.dispatchEvent(new Event('input', { bubbles: true }));
            } else if (isEditingGross) {
                grossInput.value = netInput.value; // set bruto to neto's value
                grossInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }

    // Attach listeners for total fields (on change/blur)
    const totalNet = document.getElementById('total-weight-net');
    const totalGross = document.getElementById('total-weight-gross');
    if (totalNet && totalGross) {
        totalNet.addEventListener('change', validateTotalWeights);
        totalGross.addEventListener('change', validateTotalWeights);
    }
    // Attach listeners for per-item fields (delegated, on change/blur)
    const productsTable = document.getElementById('products-table');
    if (productsTable) {
        productsTable.addEventListener('change', function (e) {
            if (e.target && (e.target.name === 'weight_net[]' || e.target.name === 'weight_gross[]')) {
                validateItemWeights(e);
            }
        });
    }
}); 