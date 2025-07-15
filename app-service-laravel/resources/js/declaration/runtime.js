
            $(document).on('click', '.increment-qty', function () {
                const input = $(this).siblings('input[name="quantity[]"]');
                input.val(parseInt(input.val() || 0) + 1).trigger('input');
                updateEstimates();
            });

            $(document).on('click', '.decrement-qty', function () {
                const input = $(this).siblings('input[name="quantity[]"]');
                const current = parseInt(input.val() || 0);
                if (current > 0) {
                    input.val(current - 1).trigger('input');
                    updateEstimates();
                }
            });

            $(document).on('input', 'input[name="price[]"], input[name="quantity[]"]', function () {
                const row = $(this).closest('tr');

                // Get raw input strings
                let priceRaw = row.find('input[name="price[]"]').val() || "";
                let quantityRaw = row.find('input[name="quantity[]"]').val() || "0";

                // Handle empty price field
                if (priceRaw.trim() === "") {
                    row.find('input[name="total[]"]').val("");
                    updateEstimates();
                    return;
                }

                // Normalize price by replacing comma with dot
                const price = parseFloat(priceRaw.replace(',', '.')) || 0;
                const quantity = parseInt(quantityRaw, 10) || 0;

                // Calculate and format total
                const total = formatDecimal(price * quantity, 2, '');

                // Set formatted total with comma
                row.find('input[name="total[]"]').val(total);

                // Update koleta and global total
                updateEstimates();
            });

            // Handle total field input for reverse calculation
            $(document).on('input', 'input[name="total[]"]', function () {
                const row = $(this).closest('tr');
                const $totalInput = $(this);
                const $priceInput = row.find('input[name="price[]"]');
                const $quantityInput = row.find('input[name="quantity[]"]');

                // Get raw total value
                let totalRaw = $totalInput.val() || "";

                // If field is empty, update calculations with 0
                if (totalRaw.trim() === "") {
                    const quantity = parseInt($quantityInput.val() || "0", 10) || 0;
                    if (quantity > 0) {
                        $priceInput.val("0,00");
                    }
                    updateEstimates();
                    return;
                }

                // Parse the total value (decimal-regex.js handles the cleaning)
                const total = parseFloat(totalRaw.replace(',', '.')) || 0;
                const quantity = parseInt($quantityInput.val() || "0", 10) || 0;

                // Calculate new price if quantity > 0
                if (quantity > 0) {
                    const newPrice = total / quantity;
                    const formattedPrice = formatDecimal(newPrice, 2);
                    $priceInput.val(formattedPrice);
                }

                // Update koleta and global total
                updateEstimates();
            });

            // Format total field when user leaves the field (blur event)
            $(document).on('blur', 'input[name="total[]"]', function () {
                const $totalInput = $(this);
                let totalRaw = $totalInput.val() || "";

                if (totalRaw.trim() === "") {
                    return; // Leave empty if user wants it empty
                }

                // If the value contains a comma but doesn't have 2 decimal places, add them
                if (totalRaw.includes(',')) {
                    const parts = totalRaw.split(',');
                    if (parts.length === 2 && parts[1].length === 1) {
                        // User typed something like "1,2" - add a zero to make it "1,20"
                        totalRaw = parts[0] + ',' + parts[1] + '0';
                    } else if (parts.length === 2 && parts[1].length === 0) {
                        // User typed something like "1," - add "00" to make it "1,00"
                        totalRaw = parts[0] + ',00';
                    }
                }

                $totalInput.val(totalRaw);
            });

            // Handle paste events for total field
            $(document).on('paste', 'input[name="total[]"]', function (e) {
                e.preventDefault();

                // Get pasted content
                const pastedText = (e.originalEvent || e).clipboardData.getData('text/plain');

                // Clean the pasted text to only allow numbers, comma, and dot
                const cleanedText = pastedText.replace(/[^\d.,]/g, '');

                // Insert the cleaned text
                const input = this;
                const start = input.selectionStart;
                const end = input.selectionEnd;
                const value = input.value;

                input.value = value.substring(0, start) + cleanedText + value.substring(end);
                input.setSelectionRange(start + cleanedText.length, start + cleanedText.length);

                // Trigger input event to format the value
                $(input).trigger('input');
            });