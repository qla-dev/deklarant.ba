function addRowToInvoice(item = {}, suggestions = []) {
    const tbody = document.getElementById("newlink");

    const index = tbody.children.length;

    globalAISuggestions.push(suggestions);
    const itemId = item.id || "";
    const slotNumber = item.slot_number || "???";
    const name = item.name || item.item_description_original || "";
    const tariff = item.item_code || item.tariff_code || "";
    const price = item.base_price || 0;
    const quantity = item.quantity || 0;
    const origin = item.country_of_origin || "DE";
    const currency = item.currency || "EUR";
    const total = (typeof item.total_price !== 'undefined' && item.total_price !== null && item.total_price !== '')
        ? formatDecimal(parseFloat(item.total_price), 2, '')
        : formatDecimal(price * quantity, 2, '');
    const desc = (item.item_description ?? "") || "";
    const translate = item.translate || item.item_description_translated || "";
    const num_packages = item.num_packages || 0;
    const num_packages_locked = item.num_packages_locked || false;
    const tariff_privilege = item.tariff_privilege || 0;
    const qtype = item.quantity_type || "";
    const best_customs_code_matches = item.best_customs_code_matches || [];
    const weight_gross = item.weight_gross || 0;
    const weight_gross_locked = item.weight_gross_locked || false;
    const weight_net = item.weight_net || 0;
    const weight_net_locked = item.weight_net_locked || false;

    console.log(` Adding row ${index + 1}:`, item, suggestions);

    const row = document.createElement("tr");
    row.classList.add("product");



    row.innerHTML = `
              <td style="width: 0px!important;">

                <div class="th-counter" style="display: flex; flex-direction: column; gap: 2px; text-align: left;">
                    <div>${index + 1}</div>
                    <div style="margin-top: 1rem" id="slot-number-${index}" class="slot-number text-start">${slotNumber}</div>
                </div>
            </td>


              <td colspan="2" style="width: 340px;">
                <div class="input-group" style="display: flex; gap: 0.25rem;">
                  <input type="text" class="form-control item-name" name="item_name[]" placeholder="Naziv" value="${name}" style="flex:1; width:180px!important">
                  <input type="text" class="form-control item-desc" name="item_desc[]" placeholder="Opis" value="${desc}" style="flex:1;">
                </div>
              <input 
      type="text" 
      class="form-control form-control-sm mt-1 text-uppercase"
      style="font-size: 0.65rem; padding-left:14.4px; height:37.1px; text-transform: uppercase;"
      name="item_prev[]" 
      placeholder="Prevod"
      value="${translate}"
    >

              </td>
    <input type="hidden" name="item_id[]" value="${itemId || ''}">
    <input 
      type="hidden" 
      name="best_customs_code_matches[]" 
      value='${JSON.stringify(best_customs_code_matches || [])
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/&/g, '&amp;')
            .replace(/'/g, '&#39;')
        }'>

    <td class="text-start" style="width: 200px!important;">
      <div style="position: relative; width: 100%;" class="th-tarifa">
        <select
          class="form-control select2-tariff tariff-selection"
          style="width: 100%; padding-right: 75px;"
          name="item_code[]"
          data-prefill="${tariff || ''}"
          data-suggestions='${JSON.stringify(suggestions || [])
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/&/g, '&amp;')
            .replace(/'/g, '&#39;')
        }'>
        </select>

       <!-- 🔍 Google search tooltip button -->
    <button
      type="button"
      class="btn btn-outline-info btn-sm google-search-btn"
      style="
        position: absolute;
        top: 50%;
        right: 40px;
        transform: translateY(-50%);
        height: 30px;
        width: 30px;
        padding: 0;
        border-radius: 3px;
      "
      data-bs-toggle="tooltip"
      data-bs-placement="top"
      data-bs-original-title="Klikni za Google pretragu: ${name} ${desc}"
      onclick="searchFromInputs(this)"
    >
      <i class="fab fa-google" style="font-size: 15px;"></i>
    </button>



        <!-- ✨ AI suggestion button -->
        <button
          type="button"
          data-bs-toggle="tooltip"
          class="btn btn-outline-info btn-sm show-ai-btn"
          style="
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translateY(-50%);
            height: 30px;
            width: 30px;
            padding: 0;
            border-radius: 3px;
          "
          title="Prikaži AI prijedloge"
        >
          <i class="fas fa-wand-magic-sparkles" style="font-size: 16px;"></i>
        </button>
      </div>
    </td>

      </div>
    </td>


             <td style="width: 60px;">
      <input 
        type="text" 
        class="form-control" 
        name="quantity_type[]" 
        placeholder="AD, AE.." 
        value="${qtype || 'KOM'}"
      >
    </td>


          <td class="text-start" style="width: 130px;">
      <div style="position: relative; width: 100%;">
        <select class="form-select" name="origin[]" style="width: 100%;">
          ${generateCountryOptions(origin)}
        </select>

        <!-- ✅ Povlastica (checkbox) -->
        <input 
           type="checkbox" 
      class="form-check-input tariff-privilege-toggle"
      name="tariff_privilege_check[]"
      ${tariff_privilege !== 0 && tariff_privilege !== "0" ? 'checked' : ''}
      data-bs-toggle="tooltip"
      data-bs-original-title="${tariff_privilege !== 0 && tariff_privilege !== '0' ? tariff_privilege : 'Odaberi povlasticu'}"
      style="
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translateY(-50%);
            width: 26px;
            height: 26px;
            cursor: pointer;
            margin-top:0px!important;
            z-index:99!important;
            border: 1px solid #299cdb;
          "
        />
        <!-- Lock icon (hidden by default) -->
    <span
      style="
        position: absolute;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        width: 26px;
        height: 26px;
        border: 1px solid #ccc;
        border-radius: 3px;
        display: inline-block;
      "
      data-bs-toggle="tooltip"
      data-bs-placement="top"
      title="Odabrana država nema nijednu povlasticu"
      class="lock-disabled"
    >
      <i class="fa fa-lock" aria-hidden="true" style="
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 16px;
        color: #888;
      "></i>
    </span>




        <!-- hidden field -->
        <input 
          type="hidden" 
          name="tariff_privilege[]" 
          value="${tariff_privilege || 0}"
        >
      </div>
    </td>



     <td style="width: 70px;">
        <div style="position: relative; width: 100%;">
          <input 
            type="text" 
            class="form-control text-start procjena-field th-input decimal-input" 
            name="num_packages[]" 
            value="${formatDecimal(num_packages, 2, '')}" 
            style="width: 100%; background-color: #f9f9f9; padding-left: 6px; padding-right: 6px;"
            onblur="lockableInputBlurred()"
            ${num_packages_locked ? 'disabled' : ''}
          >
          <input
            type="checkbox"
            class="form-check-input lock-checkbox"
            name="num_packages_locked[]"
            ${num_packages_locked ? 'checked' : ''}
            onChange="updateEstimates()"
            style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); width: 26px; height: 26px; margin: 0; z-index: 2; opacity: 0; cursor: pointer;"
            data-bs-toggle="tooltip"
            title="${num_packages_locked ? 'Otključaj koletu' : 'Zaključaj koletu'}"
          >
          <span class="custom-lock-icon lock-disabled" style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); width: 26px; height: 26px; border-radius: 3px; display: inline-block; z-index: 1;" data-bs-toggle="tooltip" title="${num_packages_locked ? 'Otključaj koletu' : 'Zaključaj koletu'}"><i class="fa fa-lock"></i></span>
        </div>
    </td>

    <td style="width: 80px;">
      <div class="th-counter" style="display: flex; flex-direction: column; gap: 2px; width: 100%;">
        <div class="input-group input-group-sm" style="width: 100%;">
          <div style="position: relative; width: 100%;">
            <input 
              type="text" 
              class="form-control text-start rounded th-input decimal-input" 
              name="weight_gross[]" 
              value="${formatDecimal(weight_gross, 2, '')}" 
              step="1" 
              min="0"
              style="padding-left: 6px; padding-right: 6px; height: 30px; border-radius:6px;"
              ${weight_gross_locked ? 'disabled' : ''}
              onblur="lockableInputBlurred()"
            >
            <input
              type="checkbox"
              class="form-check-input lock-checkbox"
              name="weight_gross_locked[]"
              ${weight_gross_locked ? 'checked' : ''}
              onChange="updateEstimates()"
              style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); width: 22px; height: 22px; margin: 0; z-index: 2; opacity: 0; cursor: pointer;"
              data-bs-toggle="tooltip"
              title="${weight_gross_locked ? 'Otključaj bruto težinu' : 'Zaključaj bruto težinu'}"
            >
            <span class="custom-lock-icon lock-disabled" style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); width: 22px; height: 22px; border-radius: 3px; display: inline-block; z-index: 1;" data-bs-toggle="tooltip" title="${weight_gross_locked ? 'Otključaj bruto težinu' : 'Zaključaj bruto težinu'}"><i class="fa fa-lock"></i></span>
          </div>
        </div>
        <div class="input-group input-group-sm" style="height: 30px;">
          <div style="position: relative; width: 100%;">
            <input
              type="text"
              class="form-control text-start rounded th-input decimal-input"
              name="weight_net[]"
              min="0"
              step="1"
              style="height: 30px; padding-left: 6px; padding-right: 6px; font-size: 10px; border-radius:6px;"
              value="${formatDecimal(weight_net, 2, '')}"
              onblur="lockableInputBlurred()"
              ${weight_net_locked ? 'disabled' : ''}
            >
            <input
              type="checkbox"
              class="form-check-input lock-checkbox"
              name="weight_net_locked[]"
              ${weight_net_locked ? 'checked' : ''}
              onChange="updateEstimates()"
              style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); width: 22px; height: 22px; margin: 0; z-index: 2; opacity: 0; cursor: pointer;"
              data-bs-toggle="tooltip"
              title="${weight_net_locked ? 'Otključaj neto težinu' : 'Zaključaj neto težinu'}"
            >
            <span class="custom-lock-icon lock-disabled" style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); width: 22px; height: 22px; border-radius: 3px; display: inline-block; z-index: 1;" data-bs-toggle="tooltip" title="${weight_net_locked ? 'Otključaj neto težinu' : 'Zaključaj neto težinu'}"><i class="fa fa-lock"></i></span>
          </div>
        </div>
      </div>
    </td>


              <td style="width: 100px;">
                <div class="th-qty" style="display: flex; flex-direction: column; gap: 2px; width: 100%;">
                  <div class="input-group input-group-sm" style="width: 100%;">
                    <button 
                      class="btn btn-outline-info btn-sm decrement-qty" 
                      style="width: 20px; padding: 0;" 
                      type="button"
                    >−</button>
                    <input 
                      type="number" 
                      class="form-control text-center rounded-0" 
                      name="quantity[]" 
                      value="${quantity}" 
                      step="1" 
                      min="0"
                      style="padding: 0 5px; height: 37px;border-radius:0!important"
                    >
                    <button 
                      class="btn btn-outline-info btn-sm increment-qty" 
                      style=" width: 20px; padding: 0; " 
                      type="button"
                    >+</button>
                  </div>


                </div>
              </td>


      <td style="width: 60px;">
        <input 
            type="text" 
            class="form-control text-start-truncate price-input th-input" 
            name="price[]" 
            value="${formatDecimal(price, 2, '')}" 
            inputmode="decimal"
            style="width: 100%;" 
        />
        </td>


            <td style="width: 70px;">
                <input 
                    type="text" 
                    class="form-control text-start th-input total-input" 
                    name="total[]" 
                    value="${total}" 
                    inputmode="decimal"
                    style="width: 100%;"
                >
                <input 
                    type="hidden"
                    name="currency[]"
                    value="${currency}"
                >
            </td>

            <td style="width: 20px; text-align: center;">
                <div style="display: flex; flex-direction: column; align-items: end; gap: 2px;">
                <button type="button" class="btn btn-outline-danger btn-sm remove-row text-center" style="height: 37px; width: 37px; padding: 0; display: flex; align-items: center; justify-content: center;"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ukloni proizvod sa liste">
                    <i class="fa fa-trash" style="font-size: 0.9rem;"></i>
                </button>
                </div>
            </td>

            `;
    initCountrySelector(row);
    initTariffPrivilegeToggle(row);
    tbody.appendChild(row);
    initializeTariffSelects(row);
    updateEstimates();

    // Force uppercase for jedinica mjere (quantity_type) input
    const unitInput = row.querySelector("input[name='quantity_type[]']");
    if (unitInput) {
        unitInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Add tooltip initialization and autoclose for the trash button
    const trashBtn = row.querySelector('.remove-row');
    if (trashBtn && typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        new bootstrap.Tooltip(trashBtn);
        trashBtn.addEventListener('click', function() {
            const tip = bootstrap.Tooltip.getInstance(trashBtn);
            if (tip) tip.hide();
        });
    }

    // --- Custom lock icon styling for visually integrated checkboxes ---
    const style = document.createElement('style');
    style.innerHTML = `
      /* Unlocked: soft-info bg, no border */
      .lock-checkbox + .custom-lock-icon {
        background-color: #d1ecfa; /* soft-info for unlocked */
        border: none;
        display: inline-block;
        border-radius: 3px;
        text-align: center;
        line-height: 26px;
        transition: background 0.2s, border 0.2s;
        vertical-align: middle;
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-55%);
      }
      /* Locked: blue bg, no border */
      .lock-checkbox:checked + .custom-lock-icon {
        background-color: #299cdb; /* blue for locked */
        border: none;
      }
      .custom-lock-icon i {
        color: #299cdb; /* blue icon for unlocked */
        font-size: 16px;
        line-height: 26px;
        vertical-align: middle;
        position: relative;
        top: -1px;
        transition: color 0.2s;
      }
      .lock-checkbox:checked + .custom-lock-icon i {
        color: #fff; /* white icon for locked */
      }
      /* For smaller (22px) lock icons */
      .custom-lock-icon[style*='22px'] i {
        font-size: 14px;
        line-height: 22px;
        top: -3px;
      }
      /* Dark mode: handle in custom.scss */
    `;
    document.head.appendChild(style);

    // --- Tooltip update and autoclose for lock checkboxes ---
    document.addEventListener('change', function(e) {
      if (!e.target.classList.contains('lock-checkbox')) return;
      const checkbox = e.target;
      const lockIcon = checkbox.nextElementSibling;
      if (!lockIcon || !lockIcon.classList.contains('custom-lock-icon')) return;
      let field = checkbox.getAttribute('name');
      setTimeout(() => {
        let locked = checkbox.checked;
        let title = '';
        if (field === 'num_packages_locked[]') {
          title = locked ? 'Otključaj koletu' : 'Zaključaj koletu';
        } else if (field === 'weight_gross_locked[]') {
          title = locked ? 'Otključaj bruto težinu' : 'Zaključaj bruto težinu';
        } else if (field === 'weight_net_locked[]') {
          title = locked ? 'Otključaj neto težinu' : 'Zaključaj neto težinu';
        }
        checkbox.setAttribute('title', title);
        checkbox.setAttribute('data-bs-original-title', title);
        lockIcon.setAttribute('title', title);
        lockIcon.setAttribute('data-bs-original-title', title);
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
          bootstrap.Tooltip.getInstance(checkbox)?.dispose();
          bootstrap.Tooltip.getInstance(lockIcon)?.dispose();
          new bootstrap.Tooltip(checkbox);
          new bootstrap.Tooltip(lockIcon);
          setTimeout(() => {
            bootstrap.Tooltip.getInstance(checkbox)?.hide();
            bootstrap.Tooltip.getInstance(lockIcon)?.hide();
          }, 200);
        }
      }, 0);
    });
}
// Ensure tooltip is hidden when Swal closes (any method)
if (typeof Swal !== 'undefined' && !window._removeRowSwalTooltipHandler) {
    window._removeRowSwalTooltipHandler = true;
    const hideRemoveRowTooltips = function() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tip = bootstrap.Tooltip.getInstance(btn);
                if (tip) tip.hide();
            }
        });
    };
    // Patch Swal.fire to always add didClose and didDismiss
    if (Swal && Swal.fire) {
        const origSwalFire = Swal.fire;
        Swal.fire = function(...args) {
            let config = args[0] || {};
            // Wrap didClose
            const origDidClose = config.didClose;
            config.didClose = function(...a) {
                hideRemoveRowTooltips();
                if (typeof origDidClose === 'function') origDidClose.apply(this, a);
            };
            // Wrap didDismiss
            const origDidDismiss = config.didDismiss;
            config.didDismiss = function(...a) {
                hideRemoveRowTooltips();
                if (typeof origDidDismiss === 'function') origDidDismiss.apply(this, a);
            };
            args[0] = config;
            return origSwalFire.apply(this, args);
        };
    }
}