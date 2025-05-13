@extends('layouts.layouts-horizontal')

@section('title', 'Skeniranje Dokumenata')

@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .dropzone {
        width: 450px;
        height: 450px;
        border: dashed rgb(59, 171, 171);
        /* Fixed typo */

        background-color: #f8f9fa;
        text-align: center;
        padding: 50px;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease-in-out;
    }
    
    @keyframes bounce-in {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.2); opacity: 1; }
    80% { transform: scale(0.95); }
    100% { transform: scale(1); }
    }


    .dropzone:hover {
        background-color: #e3f2fd;
    }

    .dropzone input {
        display: none;
    }

    .corner {
        position: absolute;
        width: 50px;
        height: 50px;
        border: 7px solid #299cdb;
    }

    .corner-top-left {
        top: -4px;
        left: -4px;
        border-right: none;
        border-bottom: none;
    }

    .corner-top-right {
        top: -4px;
        right: -4px;
        border-left: none;
        border-bottom: none;
    }

    .corner-bottom-left {
        bottom: -4px;
        left: -4px;
        border-right: none;
        border-top: none;
    }

    .corner-bottom-right {
        bottom: -4px;
        right: -4px;
        border-left: none;
        border-top: none;
    }

    .file-list {
        margin-top: 15px;
        width: 100%;
        max-height: 150px;
        overflow-y: auto;
        text-align: left;
        padding: 10px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
    }

    .file-item {
        font-size: 14px;
        padding: 5px;
        border-bottom: 1px solid #e3e3e3;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-item:last-child {
        border-bottom: none;
    }

    .remove-file {
        cursor: pointer;
        color: red;
        font-size: 16px;
        font-weight: bold;
    }

    .scan-icon {
        font-size: 150px;
        color: #299cdb;
    }

    .checkmark-animation {
        font-size: 3rem;
        color: #28a745;
        animation: pop-in 0.6s ease-out forwards;
        opacity: 0;
    }

    @keyframes pop-in {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        80% {
            transform: scale(1.2);
            opacity: 1;
        }
        100% {
            transform: scale(1);
        }
    }
</style>
@endsection

@section('content')


<!-- your dropzone -->

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <div class="dropzone" id="dropzone">
        <input type="file" id="fileInput" multiple>
        <div class="corner corner-top-left"></div>
        <div class="corner corner-top-right"></div>
        <div class="corner corner-bottom-left"></div>
        <div class="corner corner-bottom-right"></div>
        <div class="text-center" id="dropzone-content">
            <i class="ri-file-2-line text-info fs-1"></i>
            <p class="mt-3">Prevucite dokument ovdje ili kliknite kako bi uploadali i skenirali vašu fakturu</p>
        </div>
        <div class="file-list" id="fileList" style="display: none;"></div>
        <div class="progress mt-3 w-100" id="uploadProgressContainer" style="display: none;">
            <div id="uploadProgressBar" class="progress-bar bg-info" role="progressbar" style="width: 0%">0%</div>
        </div>
        <div id="scanningLoader" class="mt-4 text-center d-none">
            <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-semibold" id="scanningText">Skeniranje fakture...</p>

            <div id="successCheck" class="d-none mt-3">
                <i class="ri-checkbox-circle-fill text-success fs-1 animate__animated animate__zoomIn"></i>
                <p class="text-success fw-semibold mt-2">Uspješno skenirano!</p>
            </div>

            

        </div>
    </div>
</div>



@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ URL::asset('build/js/pages/dashboard-nft.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const token = localStorage.getItem("auth_token");
        if (!token) {
            alert("Niste prijavljeni. Molimo ulogujte se.");
            window.location.href = "/login";
            return;
        }

        const dropzone = document.getElementById("dropzone");
        const fileInput = document.getElementById("fileInput");
        const fileList = document.getElementById("fileList");
        const dropzoneContent = document.getElementById("dropzone-content");
        const progressContainer = document.getElementById("uploadProgressContainer");
        const progressBar = document.getElementById("uploadProgressBar");
        const scanningLoader = document.getElementById("scanningLoader");
        const scanningText = document.getElementById("scanningText");
        const successCheck = document.getElementById("successCheck");

        function updateFileList(files) {
            fileList.innerHTML = "";
            if (files.length > 0) {
                fileList.style.display = "block";
                dropzoneContent.style.display = "none";
            } else {
                fileList.style.display = "none";
                dropzoneContent.style.display = "block";
            }

            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement("div");
                fileItem.classList.add("file-item");

                const fileName = document.createElement("span");
                fileName.textContent = file.name;

                const removeBtn = document.createElement("span");
                removeBtn.textContent = "×";
                removeBtn.classList.add("remove-file");
                removeBtn.dataset.index = index;

                removeBtn.addEventListener("click", function () {
                    let dt = new DataTransfer();
                    let fileArray = Array.from(fileInput.files);
                    fileArray.splice(index, 1);
                    fileArray.forEach(f => dt.items.add(f));
                    fileInput.files = dt.files;
                    updateFileList(fileInput.files);
                });

                fileItem.appendChild(fileName);
                fileItem.appendChild(removeBtn);
                fileList.appendChild(fileItem);
            });
        }

        function generateFakeScanData() {
            return [
                { "Tarifna oznaka": "0101 21 00 00", "Naziv": "čistokrvne priplodne životinje", "Quantity": 2, "Unit Price": 540.00 },
                { "Tarifna oznaka": "2710 12 41 00", "Naziv": "kerozin", "Quantity": 120, "Unit Price": 1.25 },
                { "Tarifna oznaka": "0201 30 00 00", "Naziv": "goveđe meso", "Quantity": 46, "Unit Price": 1.14 }
            ];
        }

        function simulateScan() {
            scanningLoader.classList.remove("d-none");
            dropzoneContent.style.display = "none";
            fileList.style.display = "none";

            const spinner = scanningLoader.querySelector(".spinner-border");
            const stages = [
                { text: "Skeniranje fakture...", until: 25 },
                { text: "Prepoznavanje podataka...", until: 60 },
                { text: "Generisanje fakture...", until: 90 },
                { text: "Završeno skeniranje...", until: 100 }
            ];

            let progress = 0;
            const interval = setInterval(() => {
                progress++;

                for (const stage of stages) {
                    if (progress <= stage.until) {
                        scanningText.innerText = stage.text;
                        break;
                    }
                }

                if (progress >= 100) {
                    clearInterval(interval);

                    if (spinner) {
                        spinner.classList.add("fade-out");
                        setTimeout(() => {
                            spinner.remove();
                            scanningText.classList.add("d-none");

                            successCheck.classList.remove("d-none");
                            successCheck.classList.add("animate__animated", "animate__fadeIn");

                            // Save fake scan results to localStorage and wait before redirect
                            const fakeScanResults = generateFakeScanData();
                            console.log("Saving to localStorage:", fakeScanResults);
                            localStorage.setItem("ai_scan_result", JSON.stringify(fakeScanResults));

                            setTimeout(() => {
                                window.location.href = "/apps-invoices-create";
                            }, 500);
                        }, 400);
                    }
                }
            }, 50);
        }

        function uploadFiles(files) {
            const formData = new FormData();
            Array.from(files).forEach(file => formData.append('file', file));

            progressContainer.style.display = "block";
            progressBar.style.width = "0%";
            progressBar.innerText = "0%";

            let fakeProgress = 0;
            const fakeInterval = setInterval(() => {
                fakeProgress += 3;
                if (fakeProgress > 100) fakeProgress = 100;

                progressBar.style.width = fakeProgress + "%";
                progressBar.innerText = fakeProgress + "%";

                if (fakeProgress === 100) {
                    clearInterval(fakeInterval);
                    Swal.fire({
                        icon: "success",
                        title: "Uspješno uploadan dokument",
                        showConfirmButton: false,
                        timer: 1600
                    }).then(() => {
                        progressContainer.style.display = "none";
                        simulateScan();
                    });
                }
            }, 200);
        }

        dropzone.addEventListener("dragover", e => {
            e.preventDefault();
            dropzone.classList.add("bg-light");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("bg-light");
        });

        dropzone.addEventListener("drop", e => {
            e.preventDefault();
            dropzone.classList.remove("bg-light");
            let dt = new DataTransfer();
            Array.from(fileInput.files).forEach(f => dt.items.add(f));
            Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
            fileInput.files = dt.files;
            updateFileList(fileInput.files);
            uploadFiles(fileInput.files);
        });

        dropzone.addEventListener("click", () => fileInput.click());

        fileInput.addEventListener("change", () => {
            updateFileList(fileInput.files);
            uploadFiles(fileInput.files);
        });
    });
</script>












@endsection