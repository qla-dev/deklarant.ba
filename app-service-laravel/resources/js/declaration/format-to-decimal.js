    function formatDecimal(value, decimals = 2, currencySymbol = '') {
    if (isNaN(value)) return '';
    return `${parseFloat(value).toFixed(decimals).replace('.', ',')} ${currencySymbol}`.trim();
}

function parseDecimalToDot(str) {
  return (str || "0").replace(",", ".");
}

