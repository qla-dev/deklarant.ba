document.addEventListener('input', function (e) {
  if (e.target.matches('.price-input')) {
    let value = e.target.value;

    // Remove invalid characters (allow only digits and comma)
    value = value.replace(/[^0-9,]/g, '');

    // Only one comma allowed
    const parts = value.split(',');
    if (parts.length > 2) {
      value = parts[0] + ',' + parts[1];
    }

    // Limit to 2 decimal places
    if (parts.length === 2) {
      parts[1] = parts[1].slice(0, 2);
      value = parts[0] + ',' + parts[1];
    }

    // If cleared completely, set to 0
    if (value.trim() === '') {
      value = '0';
    }

    e.target.value = value;
  }
});

document.addEventListener('blur', function (e) {
  if (e.target.matches('.price-input')) {
    let val = e.target.value;

    if (!val || val === '') {
      val = '0';
    }

    if (!val.includes(',')) {
      val += ',00';
    } else if (val.match(/^(\d+),(\d)$/)) {
      val += '0';
    }

    e.target.value = val;
  }
}, true); // useCapture=true to catch blur
