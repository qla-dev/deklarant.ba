function generateCountryOptions(selectedCode = "") {
    return window.countries.map(({ code, name }) => {
        const flagUrl = `https://flagcdn.com/w40/${code}.png`;
        const isSelected = selectedCode?.toLowerCase() === code ? "selected" : "";
        return `<option value="${code.toUpperCase()}" ${isSelected} data-flag="${flagUrl}">${code.toUpperCase()}</option>`;
    }).join("");
}

function initCountrySelector(row) {
    // 1) Initialize origin Select2 on the new row
    $(row).find('select[name="origin[]"]').select2({
        templateResult: formatFlag,
        templateSelection: formatFlag,
        placeholder: "Select a country",
        width: 'style',
        language: {
            noResults: () => "Nisu pronađeni rezultati",
            inputTooShort: () => "Unesite još znakova…"
        }
    }).on('select2:open', function () {
        const $search = $('.select2-container--open .select2-search__field');
        if ($search.length) setTimeout(() => $search[0].focus(), 0);
    });

    // 2) Global country-change listener
    $(document)
        .off('change', 'select[name="origin[]"]')
        .on('change', 'select[name="origin[]"]', function () {
            const code = $(this).val()?.toUpperCase();
            const allowedCode = allowedCountries[code];
            const $row = $(this).closest('tr');
            const $cb = $row.find('.tariff-privilege-toggle');
            const $lock = $row.find('.lock-icon');
            const $hidden = $row.find('input[name="tariff_privilege[]"]');

            if (!allowedCode) {
                // ← country *not* allowed
                $cb
                    .prop('checked', false)
                    .prop('disabled', true)
                    .hide();
                $lock.show();
                $hidden.val(0);
            } else {
                // ← country *allowed*
                $lock.hide();
                $cb
                    .prop('disabled', false)
                    .show();
                if ($cb.is(':checked')) {
                    $hidden.val(allowedCode);
                }
            }

            // update tooltip text based on checkbox state
            const tipText = $cb.is(':checked')
                ? $hidden.val()                  // show code when checked
                : 'Odaberi povlasticu';         // fallback

            const inst = bootstrap.Tooltip.getInstance($cb[0]);
            if (inst) {
                inst.setContent({ '.tooltip-inner': tipText });
            } else {
                $cb
                    .attr('data-bs-original-title', tipText)
                    .removeAttr('title');
                new bootstrap.Tooltip($cb[0]);
            }
        });

    // 3) Trigger once on row-creation to set the initial state & tooltip
    setTimeout(() => {
        $(row).find('select[name="origin[]"]').trigger('change');
    }, 0);

    function formatFlag(state) {
        if (!state.id) return state.text;
        const flagUrl = $(state.element).data('flag');
        return $(`<span class="flag-option"><img src="${flagUrl}" width="20"  /> ${state.text}</span>`);
    }
}