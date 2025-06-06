<header id="page-topbar">
    <div class="layout-width ">
        <div class="navbar-header ">
            <div class="d-flex w-100">
                <!-- LOGO -->
                <div class="navbar-box horizontal-logo me-lg-5 d-flex align-items-center">
                    <a href="/" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-light-ai.png') }}" alt="" height="17">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-light-ai.png') }}" alt="" height="22">
                        </span>
                    </a>

                    <a href="/" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-dark-ai.png') }}" alt="" height="17">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dark-ai.png') }}" alt="" height="22">
                        </span>
                    </a>
                </div>




                <!-- App Search-->
                <form class="app-search d-none d-md-block me-5" style="width: 77%;" id="global-search-form">
                    <div class="position-relative">
                        <input type="text" class="form-control border"
                            placeholder="Pretraga po broju deklaracije, imenu dokumenta, dobavljaču, zemlji projekla..."
                            autocomplete="off" id="global-search" />
                        <span class="mdi mdi-magnify search-widget-icon text-info"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                            id="search-clear"></span>
                    </div>
                </form>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const input = document.getElementById("global-search");
                        const clear = document.getElementById("search-clear");

                        input.addEventListener("input", function () {
                            clear.classList.toggle("d-none", this.value.length === 0);
                        });

                        clear.addEventListener("click", function () {
                            input.value = "";
                            clear.classList.add("d-none");
                        });

                        document.getElementById("global-search-form").addEventListener("submit", function (e) {
                            e.preventDefault();
                            const keyword = input.value.trim();
                            if (keyword.length > 0) {
                                window.location.href = `/pretraga?keyword=${encodeURIComponent(keyword)}`;
                            }
                        });
                    });
                </script>


            </div>

            <div class="d-flex align-items-center">

                <div class="topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                    <a href="/kursna-lista"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none">
                        <i class="ri-exchange-dollar-line konverzija" style="font-size:27px"></i>
                    </a>
                </div>

                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                        id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
     aria-labelledby="page-header-search-dropdown">
    <form class="p-3" id="global-mobile-search-form">
        <div class="form-group m-0">
            <div class="input-group">
                <input id="global-mobile-search" type="text" class="form-control border border-2"
                       placeholder="Pretraga po broju deklaracije, imenu dokumenta, dobavljaču, zemlji porijekla..."
                       aria-label="Pretraga">
                <button type="button" class="btn btn-light d-none" id="search-mobile-clear" tabindex="-1">
                    <i class="mdi mdi-close"></i>
                </button>
                <button class="btn btn-info" type="submit">
                    <i class="mdi mdi-magnify"></i>
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const input = document.getElementById("global-mobile-search");
            const clear = document.getElementById("search-mobile-clear");
            const form = document.getElementById("global-mobile-search-form");

            input.addEventListener("input", function () {
                clear.classList.toggle("d-none", this.value.trim().length === 0);
            });

            clear.addEventListener("click", function () {
                input.value = "";
                clear.classList.add("d-none");
                input.focus();
            });

            form.addEventListener("submit", function (e) {
                e.preventDefault();
                const keyword = input.value.trim();
                if (keyword.length > 0) {
                    window.location.href = `/pretraga?keyword=${encodeURIComponent(keyword)}`;
                }
            });
        });
    </script>
</div>

                </div>



                <div class="dropdown topbar-head-dropdown header-item" id="menuDropdown">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-category-alt fs-2'></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"> Navigacija </h6>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col-6">
                                    <a class="dropdown-icon-item" href="kalendar">
                                        <i class="ri-calendar-line text-info fs-4 d-block mb-1"></i>
                                        <span>@lang('translation.statistic')</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="dropdown-icon-item" href="moje-deklaracije">
                                        <i class="ri-file-line text-info fs-4 d-block mb-1"></i>
                                        <span>@lang('translation.myorder')</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="dropdown-icon-item" href="moji-klijenti">
                                        <i class="ri-user-line text-info fs-4 d-block mb-1"></i>
                                        <span>@lang('translation.clients')</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="dropdown-icon-item" href="moji-dobavljaci">
                                        <i class="ri-truck-line text-info fs-4 d-block mb-1"></i>
                                        <span>@lang('translation.importers')</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="dropdown-icon-item" href="tarifne-oznake">
                                        <i class="mdi mdi-sticker-text-outline text-info fs-4 d-block mb-1"></i>
                                        <span>@lang('translation.declarant')</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="dropdown-icon-item" href="cijene-paketa">
                                        <i class="ri-price-tag-3-line text-info fs-4 d-block mb-1"></i>
                                        <span>@lang('translation.pricing')</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 text-center">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#scanModal"
                                        class="btn btn-info btn-sm text-white w-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-wand-magic-sparkles fs-6 me-1"></i>
                                        <span>Skeniraj deklaraciju sa AI</span>
                                    </a>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>




                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                        data-toggle-custom="fullscreen">
                        <i class='bx bx-fullscreen fs-2'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex  me-5">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
                        <i class='bx bx-moon fs-2'></i>
                    </button>
                </div>




                <div class="dropdown ms-sm header-item topbar-user">
                    <button type="button" class="btn shadow-none pe-0" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">

                            <span class="text-start me-xl-2">
                                <span class="d-none d-xl-inline-block fw-medium user-name-text text-end"
                                    id="topbar-username">
                                    {{ Auth::user()->username ?? 'Korisnik' }}
                                </span>
                               <span class="d-none d-xl-block fs-12 user-name-sub-text text-end">
    @if (Auth::user()->role === 'superadmin')
        Superadmin
    @elseif (Auth::user()->role === 'user')
        @if (
            !Auth::user()->getActivePackageName() ||
            !Auth::user()->getOtherActivePackageStats() ||
            Auth::user()->getOtherActivePackageStats()->active == 0 ||
            (Auth::user()->getRemainingScans() ?? 0) == 0 ||
            (Auth::user()->getOtherActivePackageStats()->expiration_date &&
             \Carbon\Carbon::parse(Auth::user()->getOtherActivePackageStats()->expiration_date)->isPast())
        )
            <a class="text-info" style="white-space:nowrap; cursor:pointer;" onclick="window.location.href='{{ url('cijene-paketa') }}'">Odaberi paket</a>
        @else
            {{ Auth::user()->getActivePackageName() }}
        @endif
    @else
        {{ ucfirst(Auth::user()->role) }}
    @endif
</span>


                            </span>

                            <img id="topbar-avatar" class="rounded-circle header-profile-user d-none" width="32"
                                height="32" />
                            <div id="topbar-avatar-fallback"
                                class="rounded-circle bg-info text-white d-flex justify-content-center align-items-center"
                                style="width: 32px; height: 32px; font-size: 14px; padding: 12px!important;">
                            </div>





                        </span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end border">
                        <!-- item-->
                        <h6 class="dropdown-header" id="dropdownWelcome">Dobrodošli
                            {{ Auth::user()->username ?? 'Korisnik' }}</h6>
                        <a class="dropdown-item" href="profil"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Moj nalog</span></a>
                        <a class="dropdown-item" href="faqs"><i
                                class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Pomoć</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="cijene-paketa"><i
                                class="fas fa-wand-magic-sparkles fs-12 text-muted me-1"></i> <span
                                class="align-middle">Dostupna skeniranja :
                                <b>{{ Auth::user()->getRemainingScans() ?? '0' }}</b></span></a>

                        <a class="dropdown-item" href="auth-lockscreen-basic"><i
                                class="mdi mdi-sleep text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Sleep</span></a>


                        <form method="POST" action="/custom-logout">
                            @csrf
                            <button class="dropdown-item" type="submit"><i
                                    class="bx bx-power-off text-muted fs-16 align-middle me-1"
                                    style="margin-top: -3px!important;"></i>
                                <span key="t-logout">@lang('translation.logout')</span></button>
                        </form>




                        <!-- Buttons Group -->




                    </div>
                </div>





            </div>

        </div>
    </div>
</header>
<div id="uploadLoader" style="display: none; text-align: center;">
    <div class="spinner-border text-info" role="status" style="width: 2rem; height: 2rem;">
        <span class="visually-hidden">Uploading...</span>
    </div>
</div>


<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
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
    user = @json(Auth::user());
    token = @json(session('auth_token'));
</script>


<script>
    document.addEventListener("DOMContentLoaded", async function () {


        const avatarBasePath = "/storage/uploads/avatars/";



        const avatarImg = document.getElementById("user-avatar");
        const avatarFallback = document.getElementById("avatar-fallback");

        const topbarImg = document.getElementById("topbar-avatar");
        const topbarFallback = document.getElementById("topbar-avatar-fallback");

        // Show initials immediately
        const firstLetter = (user.username || "U")[0].toUpperCase();
        if (avatarFallback) avatarFallback.textContent = firstLetter;
        if (topbarFallback) topbarFallback.textContent = firstLetter;

        try {
            const res = await fetch(`/api/users/${user.id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            const data = await res.json();
            const avatar = data.user?.avatar;

            if (avatar) {
                const imgPath = `${avatarBasePath}${avatar}`;

                const img = new Image();
                img.onload = () => {
                    // Profile image
                    if (avatarImg) {
                        avatarImg.src = imgPath;
                        avatarImg.classList.remove("d-none");
                        avatarFallback.classList.add("d-none");
                    }
                    // Topbar image
                    if (topbarImg) {
                        topbarImg.src = imgPath;
                        topbarImg.style.display = "block";
                        topbarFallback.style.display = "none";
                    }
                };
                img.onerror = () => {
                    console.warn("Avatar image failed to load.");
                };
                img.src = imgPath;
            }
        } catch (err) {
            console.error("Failed to fetch user or avatar:", err);
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const logoutLink = document.getElementById("logout-link");

        if (logoutLink) {
            logoutLink.addEventListener("click", async function () {


                if (!token) {
                    return window.location.href = "/login";
                }

                try {
                    await axios.post("/api/logoutUser", {}, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        },
                    });
                } catch (err) {
                    console.error("Logout failed:", err);
                }

            });
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const user = JSON.parse(localStorage.getItem("user"));

        const el = document.getElementById("dropdownUser");
        const el1 = document.getElementById("topbar-username");
        const el2 = document.getElementById("topbar-position");


        if (user && token && el) {
            el.textContent = user.username || "Korisnik";
            el1.textContent = user.username || "Korisnik";
            el2.textContent = user.designation || "Gost";

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


<!-- Upload files logic -->
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

        function closeScanModalIfOpen() {
            // Close scanModal if open
            const scanModalEl = document.getElementById('scanModal');
            if (scanModalEl) {
                let modalInstance = bootstrap.Modal.getInstance(scanModalEl);
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(scanModalEl);
                }
                modalInstance.hide();
            }
            // Remove/hide all modal backdrops
            document.querySelectorAll('.modal-backdrop.show, .modal-backdrop').forEach(el => {
                el.classList.remove('show');
                el.style.display = 'none';
                el.style.zIndex = '';
                el.remove();
            });
            document.body.classList.remove('modal-open');
        }

        function uploadFiles(files) {
            const formData = new FormData();
            Array.from(files).forEach(file => formData.append('file', file));

            document.getElementById("uploadLoader").style.display = "block";

            fetch('/api/storage/invoice-uploads', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(async response => {
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        data = {};
                    }
                    if (!response.ok) {
                        // Close modal before showing SweetAlert
                        closeScanModalIfOpen();
                        // Show backend error in SweetAlert
                        let message = data.message || 'Greška prilikom uploada.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload nije uspio',
                            text: message,
                            showCancelButton: true,
                            confirmButtonText: 'Zatvori',
                            cancelButtonText: 'Aktiviraj pretplatu',
                            customClass: {
                                confirmButton: 'btn btn-soft-info',
                                cancelButton: 'btn btn-info ms-2'
                            },
                            buttonsStyling: false,
                            reverseButtons: false
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href = '/cijene-paketa';
                            }
                        });
                        throw new Error(message);
                    }
                    return data;
                })
                .then(data => {
                    console.log('Upload successful:', data);
                    if (data.invoice_id) {
                        localStorage.setItem("scan_invoice_id", data.invoice_id);
                    }

                    closeScanModalIfOpen();

                    Swal.fire({
                        icon: 'success',
                        title: 'Uspješno uploadovan dokument',
                        confirmButtonText: 'Nastavi na obradu',
                        customClass: {
                            confirmButton: 'btn btn-info'
                        },
                        buttonsStyling: false
                    }).then(() => {
                        // Remove or hide .modal-backdrop.show overlays
                        document.querySelectorAll('.modal-backdrop.show').forEach(el => {
                            el.classList.remove('show');
                            el.style.display = 'none';
                            el.style.zIndex = '';
                            el.remove();
                        });
                        // Remove any remaining modal-backdrop overlays (even without .show)
                        document.querySelectorAll('.modal-backdrop').forEach(el => {
                            el.style.display = 'none';
                            el.style.zIndex = '';
                            el.remove();
                        });
                        document.body.classList.remove('modal-open');
                        if (window.location.pathname === "/deklaracija") {
                            setTimeout(() => location.reload(), 100);
                        } else {
                            window.location.href = "/deklaracija";
                        }
                    });
                })
                .catch(error => {
                    // Error already handled above, so just log here
                    console.error('Upload error:', error);
                })
                .finally(() => {
                    document.getElementById("uploadLoader").style.display = "none";
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


<!-- Statistics API -->
<script>
document.addEventListener("DOMContentLoaded", async function () {
    console.log("[INIT] Provjera lokalne pohrane...");
    console.log(" Korisnik:", user);
    console.log(" Token:", token?.substring(0, 25) + "...");

    if (!user || !token) {
        if (!user) console.warn("[TOPBAR] Backend user missing!");
        if (!token) console.warn("[TOPBAR] Auth token missing in localStorage.");
        return;
    }

    const API_URL = `/api/statistics/users/${user.id}`;
    console.log(`📡 Pozivam API: ${API_URL}`);

    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        console.log("✅ API response:", response);
        const stats = response.data || {};

        // Sigurno dohvaćanje vrijednosti
        const fields = {
            totalSuppliers: stats.supplier_stats?.total_suppliers ?? 0,
            totalImporters: stats.importer_stats?.total_importers ?? 0,
            suppliers: stats.supplier_stats?.suppliers ?? 0,
            totalInvoices: stats.total_invoices ?? 0,
            usedScans: stats.used_scans ?? 0,
            remainScansTopbar: stats.remaining_scans ?? 0
        };

        console.log("📌 Vrijednosti za prikaz u DOM-u:", fields);

        Object.entries(fields).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) {
                console.log(`➡️ Ažuriram #${id} na:`, value);
                el.innerText = value;
            } else {
                console.warn(`⚠️ Element s ID '${id}' nije pronađen u DOM-u.`);
            }
        });

        // Update all .counter-value-invoice elements to total invoice
        document.querySelectorAll('.counter-value-invoice').forEach(function(el) {
            el.textContent = fields.totalInvoices;
        });

    } catch (error) {
        console.error("❌ Greška pri dohvaćanju statistike:", error);
    }
});
</script>
