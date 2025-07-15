const FORMAT_SELECTOR = '.price-input, .total-input, #total-weight-net, #total-weight-gross, .decimal-input';

document.addEventListener('input', function (e) {
  if (!e.target.matches(FORMAT_SELECTOR)) return;
  let value = e.target.value;

  // allow only digits, comma, and dot
  value = value.replace(/[^0-9,.]/g, '');

  // convert all dots to commas
  value = value.replace(/\./g, ',');

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

  e.target.value = value;
});
