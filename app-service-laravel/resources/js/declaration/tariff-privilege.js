$("#newlink").on("change", ".tariff-privilege-toggle", function () {
    const checkbox = $(this);
    const hiddenInput = checkbox.closest("td").find("input[name='tariff_privilege[]']");
    const row = checkbox.closest("tr");

    const itemName = row.find("input[name='item_name[]']").val()?.trim();
    const itemPrevod = row.find("input[name='item_prev[]']").val()?.trim();
const titleText = (itemPrevod || itemName || "Odaberi povlasticu").toUpperCase();


    if (checkbox.is(":checked")) {
        Swal.fire({
            title: titleText,
            html: `
                <div class="select-wrapper position-relative mt-2">
                <select id="povlastica-select" class="form-select swal2-select">
                    <option value="">Odaberi povlasticu</option>
                    <option value="TRP">Turska</option>
                    <option value="EUP">Evropska unija</option>
                    <option value="IRP">Iran</option>
                    <option value="CEFTA/AP">Povlastica po sporazumu CEFTA/AP</option>
                    <option value="EFTA1">Povlastica izloženih cijena</option>
                    <option value="EFTA2">Švicarska, Lihtenštajn</option>
                    <option value="EFTA2T">Švicarska, Lihtenštajn – Tranziciona pravila</option>
                    <option value="EFTA3">Island</option>
                    <option value="EFTA3T">Island – Tranziciona pravila</option>
                    <option value="EFTA4">Norveška</option>
                    <option value="EFTA4T">Norveška – Tranziciona pravila</option>
                </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Spasi',
            cancelButtonText: 'Otkaži',
            customClass: {
                confirmButton: "btn btn-info",
                cancelButton: "btn btn-soft-info me-2"
            },
            reverseButtons: true,
            preConfirm: () => {
                const value = document.getElementById('povlastica-select')?.value;
                if (!value) {
                    Swal.showValidationMessage('Odaberi jednu od ponuđenih povlastica');
                }
                return value;
            }
        }).then(result => {
            if (result.isConfirmed) {
                hiddenInput.val(result.value);
                checkbox.attr('data-bs-original-title', result.value) // for Bootstrap tooltip internals
        .attr('title', result.value); // optional, for DOM consistency

// Refresh tooltip instance
bootstrap.Tooltip.getInstance(checkbox[0])?.dispose();
new bootstrap.Tooltip(checkbox[0]);
            } else {
                checkbox.prop('checked', false);
                hiddenInput.val("0");
                checkbox.attr('title', 'Odaberi povlasticu').tooltip('dispose').tooltip();
            }
        });
    } else {
        hiddenInput.val("0");
        checkbox.attr('title', 'Odaberi povlasticu').tooltip('dispose').tooltip();
    }
});
const allowedCountries = {
  // Turkey
  "TR": "TRP",

  // EU countries (27 members, post-Brexit)
  "AT": "EUP", // Austria
  "BE": "EUP", // Belgium
  "BG": "EUP", // Bulgaria
  "HR": "EUP", // Croatia
  "CY": "EUP", // Cyprus
  "CZ": "EUP", // Czechia
  "DK": "EUP", // Denmark
  "EE": "EUP", // Estonia
  "FI": "EUP", // Finland
  "FR": "EUP", // France
  "DE": "EUP", // Germany
  "GR": "EUP", // Greece
  "HU": "EUP", // Hungary
  "IE": "EUP", // Ireland
  "IT": "EUP", // Italy
  "LV": "EUP", // Latvia
  "LT": "EUP", // Lithuania
  "LU": "EUP", // Luxembourg
  "MT": "EUP", // Malta
  "NL": "EUP", // Netherlands
  "PL": "EUP", // Poland
  "PT": "EUP", // Portugal
  "RO": "EUP", // Romania
  "SK": "EUP", // Slovakia
  "SI": "EUP", // Slovenia
  "ES": "EUP", // Spain
  "SE": "EUP", // Sweden

  // Iran
  "IR": "IRP",

  // CEFTA – Central European Free Trade Agreement members
  "AL": "CEFTA/AP", // Albania
  "BA": "CEFTA/AP", // Bosnia and Herzegovina
  "MK": "CEFTA/AP", // North Macedonia
  "ME": "CEFTA/AP", // Montenegro
  "RS": "CEFTA/AP", // Serbia

  // EFTA countries
  "CH": "EFTA2",   // Switzerland
  "LI": "EFTA2",   // Liechtenstein
  "IS": "EFTA3",   // Iceland
  "NO": "EFTA4",   // Norway

  // EFTA transitional rules (added as special pseudo-codes)
  "CHT": "EFTA2T", // Switzerland – Transitional
  "LIT": "EFTA2T", // Liechtenstein – Transitional
  "IST": "EFTA3T", // Iceland – Transitional
  "NOT": "EFTA4T", // Norway – Transitional
};

