function companyNameForSelect2(company) {
    return company?.name ? `${company.name} – ${company.address || ''}` : company?.id
}

async function fetchAndPrefillParties() {
    const taskId = window.global_invoice_id;
    if (!taskId || !token) return;

    try {
        const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });

        const data = await res.json();
        if (!res.ok) throw new Error("Greška u AI response");

        const {
            supplier,
            importer,
            supplier_id,
            importer_id
        } = data;
        // Get invoice data for IDs
        const invoice = await getInvoice();

        // --- SUPPLIER LOGIC ---
        let supplierId = invoice.supplier_id || supplier_id;
        if (window.forceNewSupplier) {
            // Always remove any previous 'Novi klijent' option
            $("#supplier-select2 option[value='new']").remove();
            // Add and select 'Novi klijent'
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#supplier-select2").append(newOption).trigger('change');
            if (supplier) {
                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                $("#email").val(supplier.email || "").prop('readonly', false);
                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
            } else {
                $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false);
            }
            const label = document.getElementById("billing-name-ai-label");
            if (label) label.classList.remove("d-none");
            window.forceNewSupplier = false;
        } else if (supplierId) {
            // Ensure the option exists before setting value
            if ($(`#supplier-select2 option[value='${supplierId}']`).length === 0) {
                const text = companyNameForSelect2(supplier);
                const newOption = new Option(text, supplierId, true, true);
                $("#supplier-select2").append(newOption);
            }
            console.log("[SUPPLIER] Prefilling from ID:", supplierId, invoice.supplier_id ? 'invoice.supplier_id' : 'parties.supplier_id');
            $("#supplier-select2").val(supplierId).trigger("change");
            $.ajax({
                url: `/api/suppliers/${supplierId}`,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                },
                success: function (dbSupplier) {
                    console.log("[SUPPLIER] Prefilling textboxes from DB for ID:", supplierId, dbSupplier);
                    $("#billing-name").val(dbSupplier.name || "").prop('readonly', true);
                    $("#billing-address-line-1").val(dbSupplier.address || "").prop('readonly', true);
                    $("#billing-phone-no").val(dbSupplier.contact_phone || "").prop('readonly', true);
                    $("#billing-tax-no").val(dbSupplier.tax_id || "").prop('readonly', true);
                    $("#email").val(dbSupplier.contact_email || "").prop('readonly', true);
                    $("#supplier-owner").val(dbSupplier.owner || "").prop('readonly', true);
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.add("d-none");
                },
                error: function () {
                    if (supplier) {
                        // Not found in DB, add 'Novi klijent' to select2
                        const newOption = new Option('Novi klijent', 'new', true, true);
                        $("#supplier-select2").append(newOption).trigger('change');
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                        const label = document.getElementById("billing-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    }
                }
            });
        } else if (supplier) {
            // Not found in DB, add 'Novi klijent' to select2
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#supplier-select2").append(newOption).trigger('change');
            $("#billing-name").val(supplier.name || "").prop('readonly', false);
            $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
            $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
            $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
            $("#email").val(supplier.email || "").prop('readonly', false);
            $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
            const label = document.getElementById("billing-name-ai-label");
            if (label) label.classList.remove("d-none");
        }

        // --- IMPORTER LOGIC ---
        let importerId = invoice.importer_id || importer_id;
        if (window.forceNewImporter) {
            $("#importer-select2 option[value='new']").remove();
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#importer-select2").append(newOption).trigger('change');
            if (importer) {
                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
            } else {
                $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false);
            }
            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");
            window.forceNewImporter = false;
        } else if (importerId) {
            // Ensure the option exists before setting value
            if ($(`#importer-select2 option[value='${importerId}']`).length === 0) {
                const text = companyNameForSelect2(importer);
                const newOption = new Option(text, importerId, true, true);
                $("#importer-select2").append(newOption);
            }
            console.log("[IMPORTER] Prefilling from ID:", importerId, invoice.importer_id ? 'invoice.importer_id' : 'parties.importer_id');
            $("#importer-select2").val(importerId).trigger("change");
            $.ajax({
                url: `/api/importers/${importerId}`,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                },
                success: function (dbImporter) {
                    console.log("[IMPORTER] Prefilling textboxes from DB for ID:", importerId, dbImporter);
                    $("#carrier-name").val(dbImporter.name || "").prop('readonly', true);
                    $("#carrier-address").val(dbImporter.address || "").prop('readonly', true);
                    $("#carrier-tel").val(dbImporter.contact_phone || "").prop('readonly', true);
                    $("#carrier-tax").val(dbImporter.tax_id || "").prop('readonly', true);
                    $("#carrier-email").val(dbImporter.contact_email || "").prop('readonly', true);
                    $("#carrier-owner").val(dbImporter.owner || "").prop('readonly', true);
                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.add("d-none");
                },
                error: function () {
                    if (importer) {
                        // Not found in DB, add 'Novi klijent' to select2
                        const newOption = new Option('Novi klijent', 'new', true, true);
                        $("#importer-select2").append(newOption).trigger('change');
                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                        const label = document.getElementById("carrier-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    }
                }
            });
        } else if (importer) {
            // Always remove any previous 'Novi klijent' option
            $("#importer-select2 option[value='new']").remove();
            // Add and select 'Novi klijent'
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#importer-select2").append(newOption).trigger('change');
            $("#carrier-name").val(importer.name || "").prop('readonly', false);
            $("#carrier-address").val(importer.address || "").prop('readonly', false);
            $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
            $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
            $("#carrier-email").val(importer.email || "").prop('readonly', false);
            $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");
        }
    } catch (err) {
        console.error("Greška u fetchAndPrefillParties:", err);
        showRetryError(
            "Kredit za skeniranje nije iskorišten",
            err.message || "Neuspješno dohvaćanje podataka",
            () => fetchAndPrefillParties()
        );


    }

}

async function fetchAndPrefillSupplierOnly() {
    const taskId = window.global_invoice_id;
    if (!taskId || !token) return;

    try {
        const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });

        const data = await res.json();
        if (!res.ok) throw new Error("Greška u AI response");

        const {
            supplier,
            supplier_id
        } = data;
        const invoice = await getInvoice();

        let supplierId = invoice.supplier_id || supplier_id;
        if (window.forceNewSupplier) {
            $("#supplier-select2 option[value='new']").remove();
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#supplier-select2").append(newOption).trigger('change');
            if (supplier) {
                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                $("#email").val(supplier.email || "").prop('readonly', false);
                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
            } else {
                $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner").val("").prop('readonly', false);
            }
            const label = document.getElementById("billing-name-ai-label");
            if (label) label.classList.remove("d-none");
            window.forceNewSupplier = false;

        } else if (supplierId) {
            if ($(`#supplier-select2 option[value='${supplierId}']`).length === 0) {
                const text = companyNameForSelect2(supplier);
                const newOption = new Option(text, supplierId, true, true);
                $("#supplier-select2").append(newOption);
            }
            console.log("[SUPPLIER] Prefilling from ID:", supplierId);
            $("#supplier-select2").val(supplierId).trigger("change");

            $.ajax({
                url: `/api/suppliers/${supplierId}`,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                },
                success: function (dbSupplier) {
                    console.log("[SUPPLIER] Prefilling from DB:", dbSupplier);
                    $("#billing-name").val(dbSupplier.name || "").prop('readonly', true);
                    $("#billing-address-line-1").val(dbSupplier.address || "").prop('readonly', true);
                    $("#billing-phone-no").val(dbSupplier.contact_phone || "").prop('readonly', true);
                    $("#billing-tax-no").val(dbSupplier.tax_id || "").prop('readonly', true);
                    $("#email").val(dbSupplier.contact_email || "").prop('readonly', true);
                    $("#supplier-owner").val(dbSupplier.owner || "").prop('readonly', true);
                    const label = document.getElementById("billing-name-ai-label");
                    if (label) label.classList.add("d-none");
                },
                error: function (xhr) {
                    console.warn("Supplier not found in DB. Status:", xhr.status);

                    Swal.fire({
                        title: "Klijent nije pronađen",
                        text: "Podaci za klijenta nisu pronađeni u bazi. Unos će biti omogućen ručno.",
                        icon: "info",
                        confirmButtonText: "U redu",
                        confirmButtonColor: "#299dcb"
                    });

                    if (supplier) {
                        const newOption = new Option('Novi klijent', 'new', true, true);
                        $("#supplier-select2").append(newOption).trigger('change');
                        $("#billing-name").val(supplier.name || "").prop('readonly', false);
                        $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                        $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                        $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                        $("#email").val(supplier.email || "").prop('readonly', false);
                        $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                        const label = document.getElementById("billing-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    }
                }

            });

        } else if (supplier) {
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#supplier-select2").append(newOption).trigger('change');
            $("#billing-name").val(supplier.name || "").prop('readonly', false);
            $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
            $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
            $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
            $("#email").val(supplier.email || "").prop('readonly', false);
            $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
            const label = document.getElementById("billing-name-ai-label");
            if (label) label.classList.remove("d-none");
        }

    } catch (err) {
        console.error("Greška u fetchAndPrefillSupplierOnly:", err);
        showRetryError(
            "Greška pri dohvaćanju klijenta",
            err.message || "Neuspješno dohvaćanje",
            () => fetchAndPrefillSupplierOnly()
        );
    }
}

async function fetchAndPrefillImporterOnly() {
    const taskId = window.global_invoice_id;
    if (!taskId || !token) return;

    try {
        console.log("[IMPORTER] Starting fetchAndPrefillImporterOnly... Task ID:", taskId);

        const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            }
        });

        const data = await res.json();
        console.log("[IMPORTER] API response:", data);

        if (!res.ok) throw new Error("Greška u AI response");

        const { importer, importer_id } = data;
        const invoice = await getInvoice();
        console.log("[IMPORTER] Existing invoice data:", invoice);

        let importerId = invoice.importer_id || importer_id;
        console.log("[IMPORTER] Final importerId to use:", importerId);

        if (window.forceNewImporter) {
            console.log("[IMPORTER] Forcing new importer...");
            $("#importerr-select2 option[value='new']").remove();

            const labelText = importer?.name ? `${importer.name} – ${importer.address || ''}` : 'Novi klijent';
            const newOption = new Option(labelText, String(importerId), true, true);
            $("#importer-select2").append(newOption);
            $("#importer-select2").val(String(importerId)).trigger("change");

            console.log("[IMPORTER] Added 'new' option and triggered change");

            if (importer) {
                console.log("[IMPORTER] Prefilling fields with AI importer data");
                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
            } else {
                console.warn("[IMPORTER] No importer data provided, clearing fields");
                $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner").val("").prop('readonly', false);
            }

            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");

            window.forceNewImporter = false;

        } else if (importerId) {
            const stringId = String(importerId);
            console.log("[IMPORTER] importerId exists:", stringId);

            if ($(`#importer-select2 option[value='${stringId}']`).length === 0) {
                const text = companyNameForSelect2(imporeter);
                console.log("[IMPORTER] Option not found. Adding manually:", text);
                const newOption = new Option(text, stringId, true, true);
                $("#importer-select2").append(newOption);
            } else {
                console.log("[IMPORTER] Option already exists in select2:", stringId);
            }

            console.log("[IMPORTER] Setting carrier-select2 to:", stringId);
            $("#importer-select2").val(stringId).trigger("change");

            $.ajax({
                url: `/api/importers/${stringId}`,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                },
                success: function (dbImporter) {
                    console.log("[IMPORTER] DB importer found:", dbImporter);

                    $("#carrier-name").val(dbImporter.name || "").prop('readonly', true);
                    $("#carrier-address").val(dbImporter.address || "").prop('readonly', true);
                    $("#carrier-tel").val(dbImporter.contact_phone || "").prop('readonly', true);
                    $("#carrier-tax").val(dbImporter.tax_id || "").prop('readonly', true);
                    $("#carrier-email").val(dbImporter.contact_email || "").prop('readonly', true);
                    $("#carrier-owner").val(dbImporter.owner || "").prop('readonly', true);

                    const label = document.getElementById("carrier-name-ai-label");
                    if (label) label.classList.add("d-none");
                },
                error: function (xhr) {
                    console.warn("Importer not found in DB. Status:", xhr.status);

                    Swal.fire({
                        title: "Uvoznik nije pronađen",
                        text: "Podaci za uvoznika nisu pronađeni u bazi. Unos će biti omogućen ručno.",
                        icon: "info",
                        confirmButtonText: "U redu",
                        confirmButtonColor: "#299dcb"
                    });

                    if (importer) {
                        console.log("[IMPORTER] Falling back to AI importer data");
                        const newOption = new Option('Novi klijent', 'new', true, true);
                        $("#importer-select2").append(newOption).trigger('change');

                        $("#carrier-name").val(importer.name || "").prop('readonly', false);
                        $("#carrier-address").val(importer.address || "").prop('readonly', false);
                        $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                        $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                        $("#carrier-email").val(importer.email || "").prop('readonly', false);
                        $("#carrier-owner").val(importer.owner || "").prop('readonly', false);

                        const label = document.getElementById("carrier-name-ai-label");
                        if (label) label.classList.remove("d-none");
                    }
                }
            });

        } else if (importer) {
            console.log("[IMPORTER] No ID but importer data available. Treating as new.");
            const newOption = new Option('Novi klijent', 'new', true, true);
            $("#importer-select2").append(newOption).trigger('change');

            $("#carrier-name").val(importer.name || "").prop('readonly', false);
            $("#carrier-address").val(importer.address || "").prop('readonly', false);
            $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
            $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
            $("#carrier-email").val(importer.email || "").prop('readonly', false);
            $("#carrier-owner").val(importer.owner || "").prop('readonly', false);

            const label = document.getElementById("carrier-name-ai-label");
            if (label) label.classList.remove("d-none");
        }

    } catch (err) {
        console.error("Greška u fetchAndPrefillImporterOnly:", err);
        showRetryError(
            "Greška pri dohvaćanju uvoznika",
            err.message || "Neuspješno dohvaćanje",
            () => fetchAndPrefillImporterOnly()
        );
    }
}

function initImporterSupplierSelect2() {
    $("#supplier-select2").select2({
        placeholder: "Pretraži dobavljača",
        allowClear: true,
        ajax: {
            url: "/api/suppliers",
            dataType: "json",
            delay: 250,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            },
            data: params => ({
                search: params.term
            }),
            processResults: data => ({
                results: data.data.map(s => ({
                    id: s.id,
                    text: `${s.name} – ${s.address}`,
                    full: s
                }))
            }),
            minimumInputLength: 1, // ⬅️ ovo SPRJEČAVA da išta bude prikazano dok ne krene search
            tags: false,           // ⬅️ ovo SPRJEČAVA "ručni unos" koji ti ionako ne koristi
            allowClear: true,      // ⬅️ po želji – omogućava 'x' za brisanje izbora
            placeholder: "Pretraži...", // bolji UX
            cache: true
        },
        tags: true,
        allowClear: false
    });

    $('#supplier-select2').on('select2:select', function (e) {
        const data = e.params.data.full;
        if (data) {
            $('#billing-name').val(data.name || "");
            $('#billing-address-line-1').val(data.address || "");
            $('#billing-phone-no').val(data.contact_phone || "");
            $('#billing-tax-no').val(data.tax_id || "");
            $('#email').val(data.contact_email || "");
            $('#supplier-owner').val(data.owner || ""); // Fill owner field
        }
    });

    $("#importer-select2").select2({
        placeholder: "Pretraži klijenta",
        allowClear: true,
        ajax: {
            url: "/api/importers",
            dataType: "json",
            delay: 250,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Authorization: `Bearer ${token}`
            },
            data: params => ({
                search: params.term
            }),
            processResults: data => ({
                results: data.data.map(s => ({
                    id: s.id,
                    text: `${s.name} – ${s.address}`,
                    full: s
                }))
            }),
            cache: true
        },
        tags: true,
        allowClear: false
    });

    $('#importer-select2').on('select2:select', function (e) {
        const data = e.params.data.full;
        if (data) {

            $('#carrier-address').val(data.address || "");
            $('#carrier-name').val(data.name || "");
            $('#carrier-phone').val(data.contact_phone || "");
            $('#carrier-tax').val(data.tax_id || "");
            $('#carrier-email').val(data.contact_email || "");
            $('#carrier-owner').val(data.owner || ""); // Fill owner field
        }
    });
}


// Add buttons above supplier and importer fields
$(document).ready(function () {

    // Handler for new supplier
    $(document).on('click', '#add-new-supplier', function () {
        if (window.isResetConfirmed) return;

        Swal.fire({
            title: 'Oprez!',
            text: 'Ova radnja će izbrisati sve podatke za dobavljača i omogućiti ponovno automatsko popunjavanje.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Da',
            cancelButtonText: 'Ne',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                confirmButton: "btn btn-soft-info me-2",
                cancelButton: "btn btn-info"
            },

            focusCancel: true,

        }).then((result) => {
            if (result.isConfirmed) {
                // Set flag to trigger refetch
                window.forceNewSupplier = false;
                window.skipPrefillParties = false;

                // Očistimo polja i učitamo ponovo iz baze
                $("#billing-name, #billing-address-line-1, #billing-phone-no, #billing-tax-no, #email, #supplier-owner")
                    .val("")
                    .prop('readonly', true)
                    .removeClass("is-invalid");

                $("#supplier-select2").empty();

                // Pokrećemo ponovno popunjavanje samo za supplier
                fetchAndPrefillSupplierOnly();
            }
        });
    });

    // Handler for new importer
    $(document).on('click', '#add-new-importer', function () {
        if (window.isResetConfirmed) return;

        Swal.fire({
            title: 'Oprez!',
            text: 'Ova radnja će izbrisati sve podatke za klijenta i omogućiti ponovno automatsko popunjavanje.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Da',
            cancelButtonText: 'Ne',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                confirmButton: "btn btn-soft-info me-2",
                cancelButton: "btn btn-info"
            },

        }).then((result) => {
            if (result.isConfirmed) {
                window.forceNewImporter = false;
                window.skipPrefillParties = false;

                // Očisti inpute i pripremi Select2
                $("#carrier-name, #carrier-address, #carrier-tel, #carrier-tax, #carrier-email, #carrier-owner")
                    .val("")
                    .prop('readonly', true)
                    .removeClass("is-invalid");

                $("#importer-select2").empty();

                // Ponovno preuzimanje samo importer podataka
                fetchAndPrefillImporterOnly();
            }
        });
    });
});

// Add SweetAlert confirmation for importer manual entry

// 1. Add buttons in the DOM (jQuery, after DOMContentLoaded)
$(document).ready(function () {
    // Add 'Popuni ponovo s AI' button next to 'Obriši' for supplier


    // Handler for supplier AI refill
    $(document).on('click', '#refill-supplier-ai', async function () {
        const taskId = window.global_invoice_id;
        if (!taskId || !window.token) return;
        try {
            const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                }
            });

            const data = await res.json();
            if (!res.ok) throw new Error("Greška u AI response");
            const supplier = data.supplier;
            if (supplier) {
                // Set select2 to 'Novi klijent'
                $("#supplier-select2 option[value='new']").remove();
                var newOption = new Option('Novi klijent', 'new', true, true);
                $("#supplier-select2").append(newOption).val('new').trigger('change');
                // Fill fields
                $("#billing-name").val(supplier.name || "").prop('readonly', false);
                $("#billing-address-line-1").val(supplier.address || "").prop('readonly', false);
                $("#billing-phone-no").val(supplier.phone_number || "").prop('readonly', false);
                $("#billing-tax-no").val(supplier.vat_number || "").prop('readonly', false);
                $("#email").val(supplier.email || "").prop('readonly', false);
                $("#supplier-owner").val(supplier.owner || "").prop('readonly', false);
                const label = document.getElementById("billing-name-ai-label");
                if (label) label.classList.remove("d-none");
            } else {
                Swal.fire("Greška", "Nema AI podataka za dobavljača", "error");
            }
        } catch (err) {
            Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
        }
    });

    // Handler for importer AI refill
    $(document).on('click', '#refill-importer-ai', async function () {
        const taskId = window.global_invoice_id;
        if (!taskId || !window.token) return;
        try {
            const res = await fetch(`/api/invoices/${taskId}/scan/parties`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: `Bearer ${token}`
                }
            });
            const data = await res.json();
            if (!res.ok) throw new Error("Greška u AI response");
            const importer = data.importer;
            if (importer) {
                // Set select2 to 'Novi dobavljač'
                $("#importer-select2 option[value='new']").remove();
                var newOption = new Option('Novi klijent', 'new', true, true);
                $("#importer-select2").append(newOption).val('new').trigger('change');
                // Fill fields
                $("#carrier-name").val(importer.name || "").prop('readonly', false);
                $("#carrier-address").val(importer.address || "").prop('readonly', false);
                $("#carrier-tel").val(importer.phone_number || "").prop('readonly', false);
                $("#carrier-tax").val(importer.vat_number || "").prop('readonly', false);
                $("#carrier-email").val(importer.email || "").prop('readonly', false);
                $("#carrier-owner").val(importer.owner || "").prop('readonly', false);
                const label = document.getElementById("carrier-name-ai-label");
                if (label) label.classList.remove("d-none");
            } else {
                Swal.fire("Greška", "Nema AI podataka za klijenta", "error");
            }
        } catch (err) {
            Swal.fire("Greška", err.message || "Neuspješno dohvaćanje podataka", "error");
        }
    });
});

// Hide AI label when user types in importer name
document.getElementById("carrier-name")?.addEventListener("input", () => {
    const label = document.getElementById("carrier-name-ai-label");
    if (label) label.classList.add("d-none");
});