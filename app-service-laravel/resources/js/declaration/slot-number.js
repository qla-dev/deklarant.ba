function refreshSlotNumbers() {
    const items = buildInvoicePayload(undefined, undefined).items;
    const slotMap = new Map();
    let slotCounter = 1;
    items.forEach((item, index) => {
        const key = `${item.country_of_origin}|${item.item_code}|${item.tariff_privilege}`;
        if (!slotMap.has(key)) {
            slotMap.set(key, slotCounter++);
        }
        document.getElementById("slot-number-" + index).innerText = slotMap.get(key).toString().padStart(3, '0');
    })
    setTimeout(() => refreshSlotNumbers(), 1000);
}
setTimeout(() => refreshSlotNumbers(), 1000);