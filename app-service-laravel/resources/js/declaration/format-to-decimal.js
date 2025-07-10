    function formatDecimal(value, decimals = 2, currencySymbol = '') {
    if (isNaN(value)) return '';
    const formatted = parseFloat(value).toFixed(decimals).replace('.', ',');
    return currencySymbol ? `${formatted} ${currencySymbol}`.trim() : formatted;
}

function parseDecimalToDot(str) {
  return (str || "0").replace(",", ".");
}

