$("#newlink").on("change", ".tariff-privilege-toggle", function () {
    const checkbox = $(this);
    const hiddenInput = checkbox.closest("td").find("input[name='tariff_privilege[]']");
    const row = checkbox.closest("tr");

    // Grab the item name or fallback to translation
    const itemName = row.find("input[name='item_name[]']").val()?.trim();
    const itemPrevod = row.find("input[name='item_prev[]']").val()?.trim();
    const titleText = itemPrevod || itemName || "Odaberi povlasticu";

    if (checkbox.is(":checked")) {
        Swal.fire({
            title: titleText,
            html: `
                <div class="select-wrapper position-relative mt-2">
                    <select id="povlastica-select" class="form-select swal2-select">
                        <option value="">Odaberi povlasticu</option>
                        <option value="CEFTA/AP">Povlastica po sporazumu CEFTA/AP</option>
                        <option value="EFTA1">Povlastica izloženih cijena</option>
                        <option value="EFTA2">Švicarska, Lihtenštajn</option>
                        <option value="EFTA2T">Švicarska, Lihtenštajn – Tranziciona pravila</option>
                        <option value="EFTA3">Island</option>
                        <option value="EFTA3T">Island – Tranziciona pravila</option>
                        <option value="EFTA4">Norveška</option>
                        <option value="EFTA4T">Norveška – Tranziciona pravila</option>
                    </select>
                    <i class="fa fa-chevron-down dropdown-arrow-icon"></i>
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
            } else {
                checkbox.prop('checked', false);
                hiddenInput.val("0");
            }
        });
    } else {
        hiddenInput.val("0");
    }
});
