const FORMAT_SELECTOR = '.price-input, #total-weight-net, #total-weight-gross';

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

  // empty → 0
  if (value.trim() === '') {
    value = '0';
  }

  e.target.value = value;
});

document.addEventListener('blur', function (e) {
  if (!e.target.matches(FORMAT_SELECTOR)) return;
  let val = e.target.value || '0';

  // ensure ",dd"
  if (!val.includes(',')) {
    val += ',00';
  } else if (/^(\d+),(\d)$/.test(val)) {
    val += '0';
  }

  e.target.value = val;
}, true);
