<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tariff Matching</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.13.216/pdf.min.js"></script>
</head>

<body>
    <!-- Dropzone Form -->
    <form action="/upload" class="dropzone" id="invoice-dropzone">@csrf</form>

    <!-- Button for manually reading the PDF after successful upload -->
    <button id="manual-read-btn">Read PDF</button>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let uploadedFile = null; // Variable to store the uploaded file for later reading

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Check if Dropzone is already initialized
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(dz => dz.destroy()); // Destroy existing instances to avoid conflicts
        }

        // Initialize Dropzone
        Dropzone.autoDiscover = false;

        const myDropzone = new Dropzone("#invoice-dropzone", {
            url: "/upload",
            method: "post",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Adding CSRF token to Dropzone headers
            },
            success: function (file, response) {
                if (response.success) {
                    console.log('File uploaded successfully:', response.filename);

                    // Store the file for later use
                    uploadedFile = file;

                    // Show the manual read button after successful upload
                    document.getElementById('manual-read-btn').style.display = 'block';
                } else {
                    console.error('File upload failed:', response.message);
                }
            },
            error: function (file, errorMessage) {
                console.error('Upload error:', errorMessage);
            }
        });

        // Add an event listener to the manual read button
        document.getElementById('manual-read-btn').addEventListener('click', function () {
            if (uploadedFile) {
                console.log('Manually triggered read file process.');
                const blobFile = uploadedFile.dataURL ? dataURLtoBlob(uploadedFile.dataURL) : uploadedFile;
                readFile(blobFile);
            } else {
                console.error('No file available to read.');
            }
        });

        // Helper function to convert DataURL to Blob
        function dataURLtoBlob(dataURL) {
            const byteString = atob(dataURL.split(',')[1]);
            const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], { type: mimeString });
        }

        // Read PDF File
        // Read PDF File
function readFile(file) {
    console.log('Attempting to read PDF file...');
    const reader = new FileReader();

    reader.onload = function () {
        console.log('File read successful, extracting PDF content...');
        const typedArray = new Uint8Array(reader.result);
        pdfjsLib.getDocument(typedArray).promise
            .then(function (pdf) {
                return pdf.getPage(1);
            })
            .then(function (page) {
                return page.getTextContent();
            })
            .then(function (textContent) {
                // Reconstruct words from textContent
                let previousY = null;
                let line = '';
                let lines = [];

                textContent.items.forEach(function (item) {
                    // If the Y coordinate changes, assume a new line
                    if (previousY !== null && Math.abs(previousY - item.transform[5]) > 5) {
                        lines.push(line.trim());
                        line = '';
                    }

                    // Add item to the line and update the previous Y coordinate
                    line += item.str;
                    previousY = item.transform[5];
                });

                // Push the last line if not empty
                if (line.trim() !== '') {
                    lines.push(line.trim());
                }

                // Join lines to form the final text
                const text = lines.join('\n');
                console.log('Extracted text from PDF:', text);
                const wordsArray = text.split(/\s+/); // Split words by spaces
                detectLanguage(wordsArray);
            })
            .catch(function (error) {
                console.error('Error extracting PDF content:', error);
            });
    };

    reader.onerror = function (error) {
        console.error('Error reading file:', error);
    };

    reader.readAsArrayBuffer(file);
}

        // Detect Language
        function detectLanguage(wordsArray) {
            console.log('Detecting language...');
            const englishWords = ["the", "and", "of", "to", "in"];
            const turkishWords = ["ve", "bir", "bu", "için", "ile"];
            const bosnianWords = ["i", "da", "u", "na", "za"];

            let englishCount = 0, turkishCount = 0, bosnianCount = 0;

            wordsArray.forEach(word => {
                const lowerWord = word.toLowerCase();
                if (englishWords.includes(lowerWord)) englishCount++;
                if (turkishWords.includes(lowerWord)) turkishCount++;
                if (bosnianWords.includes(lowerWord)) bosnianCount++;
            });

            let language;
            if (englishCount >= turkishCount && englishCount >= bosnianCount) {
                language = "english";
            } else if (turkishCount >= englishCount && turkishCount >= bosnianCount) {
                language = "turkish";
            } else {
                language = "bosnian";
            }

            console.log(`Detected Language: ${language}`);
            searchTariffs(wordsArray, language);
        }

        // Search Tariffs
        function searchTariffs(wordsArray, language) {
            console.log('Initiating tariff search...');
            fetch('/search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Adding CSRF token to fetch request headers
                },
                body: JSON.stringify({
                    words: wordsArray,
                    language: language
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Top Matches:', data);
                    // You can now display the top matches in the UI
                })
                .catch(error => {
                    console.error('Error during tariff search:', error);
                });
        }
    });
</script>



</body>

</html>
