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

function initDatePicker() {
    const invoiceDateInput = document.getElementById("invoice-date");
    if (invoiceDateInput) {
        invoiceDateInput.value = formatDateToDDMMYYYY(_invoice_data.date_of_issue || new Date());
    }
    console.log(" Invoice date and number set.");

    // Reformat existing value BEFORE flatpickr init
    const input1 = document.querySelector("#date");
    if (input1 && input1.value.includes("-") && input1.value.length === 10) {
        input1.value = formatToDMY(input1.value);
    }

    const input2 = document.querySelector("#invoice-date");
    if (input2 && input2.value.includes("-") && input2.value.length === 10) {
        input2.value = formatToDMY(input2.value);
    }

    // Now initialize Flatpickr
    flatpickr("#date", {
        locale: "bs",
        dateFormat: "d.m.Y"
    });

    flatpickr("#invoice-date", {
        locale: "bs",
        dateFormat: "d.m.Y"
    });
}