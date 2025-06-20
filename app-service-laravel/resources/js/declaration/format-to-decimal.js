    function formatDecimal(value, decimals = 2, currencySymbol = '') {
    if (isNaN(value)) return '';
    return `${parseFloat(value).toFixed(decimals).replace('.', ',')} ${currencySymbol}`.trim();
}


