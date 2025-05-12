<header id="page-topbar">
    <div class="layout-width ">
        <div class="navbar-header ">
            <div class="d-flex w-100">
                <!-- LOGO -->
                <div class="navbar-box horizontal-logo me-5 d-flex align-items-center">
                    <a href="/" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-dek.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dek.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="/" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-dek-white.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dek-white.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                

                <!-- App Search-->
                <form class="app-search d-none d-md-block me-5" style="width: 77%;">
                    <div class="position-relative">
                        <input type="text" class="form-control border" placeholder="Pretraga..." autocomplete="off" id="search-options" value="">
                        <span class="mdi mdi-magnify search-widget-icon text-info"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg" style="width: 56%;" id="search-dropdown">
                        <div data-simplebar style="max-height: 320px;">
                            <!-- item-->
                            <div class="dropdown-header">
                                <h6 class="text-overflow text-muted mb-0 text-uppercase">Recent Searches</h6>
                            </div>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a href="index" class="btn btn-soft-secondary btn-sm rounded-pill">how to setup <i class="mdi mdi-magnify ms-1"></i></a>
                                <a href="index" class="btn btn-soft-secondary btn-sm rounded-pill">buttons <i class="mdi mdi-magnify ms-1"></i></a>
                            </div>
                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-1 text-uppercase">Pages</h6>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                                <span>Analytics Dashboard</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                                <span>Help Center</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                <span>My account settings</span>
                            </a>

                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-2 text-uppercase">Members</h6>
                            </div>

                            <div class="notification-list">
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="m-0">Angela Bernier</h6>
                                            <span class="fs-11 mb-0 text-muted">Manager</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="m-0">David Grasso</h6>
                                            <span class="fs-11 mb-0 text-muted">Web Designer</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="{{ URL::asset('build/images/users/avatar-5.jpg') }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="m-0">Mike Bunch</h6>
                                            <span class="fs-11 mb-0 text-muted">React Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="text-center pt-3 pb-1">
                            <a href="pages-search-results" class="btn btn-primary btn-sm">View All Results <i class="ri-arrow-right-line ms-1"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control border border-2" placeholder="Search ..." aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-category-alt fs-2'></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"> Web Apps </h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#!" class="btn btn-sm btn-soft-info shadow-none"> View All Apps
                                        <i class="ri-arrow-right-s-line align-middle"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/github.png') }}" alt="Github">
                                        <span>GitHub</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/bitbucket.png') }}" alt="bitbucket">
                                        <span>Bitbucket</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/dribbble.png') }}" alt="dribbble">
                                        <span>Dribbble</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/dropbox.png') }}" alt="dropbox">
                                        <span>Dropbox</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/mail_chimp.png') }}" alt="mail_chimp">
                                        <span>Mail Chimp</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/slack.png') }}" alt="slack">
                                        <span>Slack</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none" data-toggle-custom="fullscreen">
                        <i class='bx bx-fullscreen fs-2'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
                        <i class='bx bx-moon fs-2'></i>
                    </button>
                </div>

                <div class="topbar-head-dropdown ms-1 header-item me-5" id="notificationDropdown">
                    <a href="/cijene-paketa" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none">
                        <i class='bx bx-box fs-2'></i>
                    </a>
                </div>


                <div class="dropdown ms-sm header-item topbar-user">
                    <button type="button" class="btn shadow-none pe-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        <span class="d-flex align-items-center">

                         <span class="text-start me-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text" id="topbar-username"><script>document.write(JSON.parse(localStorage.getItem("user"))?.username || "Korisnik");</script>!</span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Founder</span>
                                
                                
                            </span>

                        <img id="topbar-avatar" class="rounded-circle header-profile-user" src="/build/images/users/avatar-1.jpg" alt="Header Avatar" width="32" height="32">

                            

                           
                            
                        </span>
                    </button>
                    
                    <div class="dropdown-menu dropdown-menu-end border">
                        <!-- item-->
                        <h6 class="dropdown-header">
  Dobrodošli <script>document.write(JSON.parse(localStorage.getItem("user"))?.email);</script></h6>
                        <a class="dropdown-item" href="pages-profile"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Moj nalog</span></a>   
                        <a class="dropdown-item" href="pages-faqs"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Pomoć</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="pages-profile"><i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Dostupna skeniranja : <b>123</b></span></a>
                       
                        <a class="dropdown-item" href="auth-lockscreen-basic"><i class="mdi mdi-sleep text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Sleep</span></a>

                        <a class="dropdown-item" href="javascript:void(0);" id="logout-link">
                            <i class="bx bx-power-off font-size-16 align-middle me-1"></i> 
                            <span key="t-logout">@lang('translation.logout')</span>
                        </a>


                        

                        <!-- Buttons Group -->
                        
                        

                    </div>
                </div>
                



                
            </div>
            
        </div>
    </div>
</header>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/chart.js/chart.umd.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/chartjs.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>


  

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Swiper CSS -->


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const logoutLink = document.getElementById("logout-link");

        if (logoutLink) {
            logoutLink.addEventListener("click", async function () {
                const token = localStorage.getItem("auth_token");

                if (!token) {
                    return window.location.href = "/login";
                }

                try {
                    await axios.post("/api/auth/logout", {}, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                } catch (err) {
                    console.error("Logout failed:", err);
                }

                localStorage.removeItem("auth_token");
                localStorage.removeItem("user");
                window.location.href = "/login";
            });
        }
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const user = JSON.parse(localStorage.getItem("user"));

    if (user) {
        const topbarAvatar = document.getElementById("topbar-avatar");

        if (topbarAvatar) {
            const avatarUrl = `/storage/uploads/avatars/${user.avatar}`;

            const testImg = new Image();
            testImg.onload = function () {
                topbarAvatar.src = avatarUrl;
            };
            testImg.onerror = function () {
                topbarAvatar.src = "/build/images/users/avatar-1.jpg";
            };
            testImg.src = avatarUrl;
        }
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const fullscreenBtn = document.querySelector('[data-toggle-custom="fullscreen"]');

    if (!fullscreenBtn) return;

    fullscreenBtn.addEventListener("click", function (e) {
        e.preventDefault();
        document.body.classList.toggle("fullscreen-enable");

        if (!document.fullscreenElement &&
            !document.mozFullScreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement) {

            const docEl = document.documentElement;

            if (docEl.requestFullscreen) {
                docEl.requestFullscreen();
            } else if (docEl.mozRequestFullScreen) {
                docEl.mozRequestFullScreen();
            } else if (docEl.webkitRequestFullscreen) {
                docEl.webkitRequestFullscreen();
            } else if (docEl.msRequestFullscreen) {
                docEl.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });

    // Exit handler
    function exitHandler() {
        if (!document.fullscreenElement &&
            !document.mozFullScreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement) {
            document.body.classList.remove("fullscreen-enable");
        }
    }

    document.addEventListener("fullscreenchange", exitHandler);
    document.addEventListener("webkitfullscreenchange", exitHandler);
    document.addEventListener("mozfullscreenchange", exitHandler);
    document.addEventListener("MSFullscreenChange", exitHandler);
});
</script>

<script>
    function setLayoutMode(attribute, mode, bodyClass, htmlEl) {
        htmlEl.setAttribute(attribute, mode);
        document.body.classList.remove("layout-mode-light", "layout-mode-dark");
        document.body.classList.add(bodyClass);

        // Save preference (optional)
        localStorage.setItem("theme", mode);

        // Optional: force window resize if needed
        window.dispatchEvent(new Event("resize"));
    }

    function initDarkModeToggle() {
        const html = document.documentElement;
        const toggleBtn = document.querySelector(".light-dark-mode");

        // Load saved preference
        const saved = localStorage.getItem("theme");
        if (saved) {
            setLayoutMode("data-bs-theme", saved, `layout-mode-${saved}`, html);
        }

        if (toggleBtn) {
            toggleBtn.addEventListener("click", function () {
                const currentMode = html.getAttribute("data-bs-theme");
                const newMode = currentMode === "dark" ? "light" : "dark";
                setLayoutMode("data-bs-theme", newMode, `layout-mode-${newMode}`, html);
            });
        }
    }

    // Initialize on page load
    document.addEventListener("DOMContentLoaded", initDarkModeToggle);
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("auth_token");
    if (!token) {
        alert("Niste prijavljeni. Molimo ulogujte se.");
        window.location.href = "/auth-login-basic";
        return;
    }

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
        Array.from(files).forEach(file => formData.append('file', file));

        progressContainer.style.display = "block";
        progressBar.style.width = "0%";
        progressBar.innerText = "0%";

        let fakeProgress = 0;
        const fakeInterval = setInterval(() => {
            fakeProgress += 5;
            if (fakeProgress > 100) fakeProgress = 100;

            progressBar.style.width = fakeProgress + "%";
            progressBar.innerText = fakeProgress + "%";

            if (fakeProgress === 100) {
                clearInterval(fakeInterval);
            }
        }, 150);

        fetch('http://localhost:8080/api/upload', {
            method: 'POST',
            body: formData 
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Upload failed");
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload successful:', data);

            Swal.fire({
                icon: "success",
                title: "Dokument uspješno uploadan!",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Save returned task_id to localStorage (important for next steps!)
                if (data.task_id) {
                    localStorage.setItem("scan_task_id", data.task_id);
                }
                window.location.href = "/apps-invoices-create"; 
            });
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Greška prilikom uploada.');
            progressContainer.style.display = "none";
        });
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






