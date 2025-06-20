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
