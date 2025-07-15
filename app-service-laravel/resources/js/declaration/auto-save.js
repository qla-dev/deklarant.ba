function isEqualIgnoringPaths(obj1, obj2, pathsToIgnore = []) {
  const cleanObj1 = _.cloneDeep(obj1);
  const cleanObj2 = _.cloneDeep(obj2);

  pathsToIgnore.forEach(path => {
    if (path.includes('[]')) {
      const [arrayPath, ...restPathParts] = path.split('[]');
      const subPath = restPathParts.join('').replace(/^\./, '');

      const arr1 = _.get(cleanObj1, arrayPath);
      const arr2 = _.get(cleanObj2, arrayPath);

      if (Array.isArray(arr1) && Array.isArray(arr2)) {
        arr1.forEach(item => _.unset(item, subPath));
        arr2.forEach(item => _.unset(item, subPath));
      }
    } else {
      _.unset(cleanObj1, path);
      _.unset(cleanObj2, path);
    }
  });

  // Custom comparator to treat 0 and null as equal
  const customizer = (val1, val2) => {
    if ((val1 === 0 && val2 == null) || (val2 === 0 && val1 == null)) {
      return true;
    }
    // Return undefined to let lodash handle all other comparisons
    return undefined;
  };

  return _.isEqualWith(cleanObj1, cleanObj2, customizer);
}

function deepApply(target, source) {
  return _.mergeWith(target, source, (objValue, srcValue) => {
    // If both are arrays, merge them by index
    if (Array.isArray(objValue) && Array.isArray(srcValue)) {
      return objValue.map((item, index) => {
        // Apply deepApply recursively to each pair of items
        if (_.isObject(item) && _.isObject(srcValue[index])) {
          return deepApply(item, srcValue[index]);
        }
        // If no corresponding index, keep original or take new
        return srcValue[index] !== undefined ? srcValue[index] : item;
      });
    }
  });
}

async function checkAutoSave() {
    if (thingsToInitialize <= 0 && _invoice_data) {
        const invoicePayload = buildInvoicePayload(await getSupplierID(), await getImporterID());
        const same = isEqualIgnoringPaths(_invoice_data, invoicePayload, [
            "id", "country_of_origin", "created_at", "file_name",
            "internal_status", "scan_time", "task_id", "updated_at", "user_id",
            "items[].best_customs_code_matches", "items[].id", "items[].invoice_id",
            "items[].created_at", "items[].item_id", "items[].item_name",
            "items[].updated_at"
        ]);
        if (!same) {
            await handleSaveInvoice(document.getElementById("save-invoice-btn"));
            deepApply(_invoice_data, invoicePayload)
        }
    }
    setTimeout(checkAutoSave, 2000);
}

setTimeout(checkAutoSave, 2000);