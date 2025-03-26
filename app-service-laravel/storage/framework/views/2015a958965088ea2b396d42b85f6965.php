

<?php $__env->startSection('title', 'Skeniranje Dokumenata'); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/jsvectormap.min.css')); ?>" rel="stylesheet">
    <style>
        .dropzone {
            width: 450px;
            height: 450px;
            border: 10px dashedrgb(35, 37, 37); /* Thicker dashed border */
            border-radius: 12px;
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

        /* Custom corners */
        .corner {
            position: absolute;
            width: 50px;
            height: 50px;
            border: 7px solid #299cdb; /* Thicker corner borders */
        }

        .corner-top-left { top: -4px; left: -4px; border-right: none; border-bottom: none; }
        .corner-top-right { top: -4px; right: -4px; border-left: none; border-bottom: none; }
        .corner-bottom-left { bottom: -4px; left: -4px; border-right: none; border-top: none; }
        .corner-bottom-right { bottom: -4px; right: -4px; border-left: none; border-top: none; }

        /* File preview */
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

        /* Bigger icon for document scan */
        .scan-icon {
            font-size: 100px; /* Bigger icon */
            color: #299cdb;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            
            <!-- Drag & Drop File Upload Section -->
            <div class="dropzone" id="dropzone">
                <input type="file" id="fileInput" multiple>
                
                <!-- Custom Corner Borders -->
                <div class="corner corner-top-left"></div>
                <div class="corner corner-top-right"></div>
                <div class="corner corner-bottom-left"></div>
                <div class="corner corner-bottom-right"></div>
                
                <!-- Dropzone Content -->
                <div class="text-center" id="dropzone-content">
                <i class="ri-file-2-line text-info fs-1"></i> <!-- Larger Scan Icon -->
                    <p class="mt-3">Povucite i ispustite dokumente ovdje ili kliknite za odabir</p>
                </div>

                <!-- File List (Hidden initially) -->
                <div class="file-list" id="fileList" style="display: none;"></div>

            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/pages/dashboard-nft.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropzone = document.getElementById("dropzone");
            const fileInput = document.getElementById("fileInput");
            const fileList = document.getElementById("fileList");
            const dropzoneContent = document.getElementById("dropzone-content");

            // Function to update file list
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

            // Handle drag-and-drop
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
                Array.from(fileInput.files).forEach(f => dt.items.add(f)); // Keep existing files
                Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f)); // Add new files
                fileInput.files = dt.files;
                updateFileList(fileInput.files);
            });

            // Handle file input click
            dropzone.addEventListener("click", () => fileInput.click());

            fileInput.addEventListener("change", () => updateFileList(fileInput.files));
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layouts-horizontal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/pages-scan.blade.php ENDPATH**/ ?>