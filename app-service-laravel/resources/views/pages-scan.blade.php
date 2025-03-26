@extends('layouts.layouts-horizontal')

@section('title', 'Skeniranje Dokumenata')

@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet">
    <style>
        .dropzone {
            width:450px;
            height: 450px;
            border: dashed rgb(59, 171, 171); /* Fixed typo */
           
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

        .corner-top-left { top: -4px; left: -4px; border-right: none; border-bottom: none; }
        .corner-top-right { top: -4px; right: -4px; border-left: none; border-bottom: none; }
        .corner-bottom-left { bottom: -4px; left: -4px; border-right: none; border-top: none; }
        .corner-bottom-right { bottom: -4px; right: -4px; border-left: none; border-top: none; }

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
       

    </style>
@endsection

@section('content')


    <!-- your dropzone -->

<div class=" d-flex justify-content-center align-items-center" >
    <div class="dropzone" id="dropzone">
        <input type="file" id="fileInput" multiple>
        <div class="corner corner-top-left"></div>
        <div class="corner corner-top-right"></div>
        <div class="corner corner-bottom-left"></div>
        <div class="corner corner-bottom-right"></div>
        <div class="text-center" id="dropzone-content">
            <i class="ri-file-2-line text-info fs-1"></i>
            <p class="mt-3">Povucite i ispustite dokumente ovdje ili kliknite za odabir</p>
        </div>
        <div class="file-list" id="fileList" style="display: none;"></div>
        <div class="progress mt-3" style="width: 100%; display: none;" id="uploadProgressContainer">
            <div id="uploadProgressBar" class="progress-bar bg-info" role="progressbar" style="width: 0%">0%</div>
        </div>
    </div>
</div>

   


@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ URL::asset('build/js/pages/dashboard-nft.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropzone = document.getElementById("dropzone");
        const fileInput = document.getElementById("fileInput");
        const fileList = document.getElementById("fileList");
        const dropzoneContent = document.getElementById("dropzone-content");
        const progressContainer = document.getElementById("uploadProgressContainer");
        const progressBar = document.getElementById("uploadProgressBar");

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

        function uploadFiles(files) {
            const formData = new FormData();
            Array.from(files).forEach(file => {
                formData.append('file', file);
            });

            progressContainer.style.display = "block";
            progressBar.style.width = "0%";
            progressBar.innerText = "0%";

            axios.post('/api/storage/uploads', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function (progressEvent) {
                    const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressBar.style.width = percent + "%";
                    progressBar.innerText = percent + "%";
                }
            })
            .then(response => {
                progressBar.style.width = "100%";
                progressBar.innerText = "100%";
                console.log('Upload successful:', response.data);
                alert(response.data.message);

                setTimeout(() => {
                    if (confirm("Želite li uploadati još jedan dokument?")) {
                        fileInput.value = "";
                        updateFileList([]);
                        progressBar.style.width = "0%";
                        progressBar.innerText = "0%";
                        progressContainer.style.display = "none";
                    }
                }, 300);
            })
            .catch(error => {
                console.error('Upload failed:', error);
                alert('Upload failed. Please try again.');
                progressContainer.style.display = "none";
            });
        }

        dropzone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropzone.classList.add("bg-light");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("bg-light");
        });

        dropzone.addEventListener("drop", (e) => {
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
