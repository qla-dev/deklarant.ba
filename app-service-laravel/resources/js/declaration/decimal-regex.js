const FORMAT_SELECTOR = '.price-input, .total-input, #total-weight-net, #total-weight-gross';

document.addEventListener('input', function (e) {
  if (!e.target.matches(FORMAT_SELECTOR)) return;
  let value = e.target.value;

  // allow only digits and comma
  value = value.replace(/[^0-9,]/g, '');

  // only one comma
  const parts = value.split(',');
  if (parts.length > 2) {
    value = parts[0] + ',' + parts[1];
  }

  // max 2 decimals
  if (parts.length === 2) {
    parts[1] = parts[1].slice(0, 2);
    value = parts[0] + ',' + parts[1];
  }

  // only clear-to-zero for weight fields (allow price and total fields to be empty)
  if (value.trim() === '' && e.target.matches('#total-weight-net, #total-weight-gross')) {
    value = '0';
  }

  e.target.value = value;
});
