
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- Profile Foreground -->
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="<?php echo e(URL::asset('build/images/profile-bg.jpg')); ?>" class="profile-wid-img" alt="Profile Background">
        </div>
    </div>

    <!-- Profile Header -->
    <div class="pt-4 mb-4 profile-wrapper pb-lg-4">
        <div class="row g-4 align-items-center">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img id="user-avatar" src="/build/images/users/avatar-1.jpg" class="img-thumbnail rounded-circle" alt="User Avatar">
                </div>
            </div>
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">Anna Adame</h3>
                    <p class="text-white-50">Owner & Founder</p>
                    <div class="hstack text-white-50 gap-2">
                        <span><i class="ri-map-pin-user-line align-middle"></i> California, United States</span>
                        <span><i class="ri-building-line align-middle"></i> Themesbrand</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text-center text-white-50">
                    <div class="col-6">
                        <h4 class="text-white mb-1">24.3K</h4>
                        <p class="fs-14 mb-0">Followers</p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-white mb-1">1.3K</h4>
                        <p class="fs-14 mb-0">Following</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-12">

            <!-- Navigation Tabs -->
            <ul class="nav nav-pills animation-nav profile-nav py-3 flex-grow-1 gap-2 " role="tablist">

                <li class="nav-item">
                    <a class="nav-link  active" data-bs-toggle="tab" href="#overview-tab">Overview</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#activities">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#projects">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">

                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview-tab">
                    <div class="row">

                        <!-- Left Side Cards -->
                        <div class="col-xxl-3"> 
                            <!-- Current Plan Card -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <p class="fw-semibold">Vaš trenutni paket je <b>Starter</b></p>
                                    <a href="pages-pricing" class="btn btn-info text-white btn-sm">
                                        <i class="ri-arrow-up-circle-line"></i> Nadogradite paket
                                    </a>
                                </div>
                            </div>

                            <!-- Info Card -->
                            
                            <!-- Suppliers Card -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Moji dobavljači</h5>
                                    <div class="d-flex align-items-center py-2">
                                        <img src="<?php echo e(URL::asset('build/images/users/orbico.png')); ?>" class="avatar-xs rounded-circle me-3">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">ORBICO</h6>
                                            <small class="text-muted">Bosna i Hercegovina</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center py-2">
                                        <img src="<?php echo e(URL::asset('build/images/users/hifa.png')); ?>" class="avatar-xs rounded-circle me-3">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">HIFA</h6>
                                            <small class="text-muted">Bosna i Hercegovina</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center py-2">
                                        <img src="<?php echo e(URL::asset('build/images/users/samsung.png')); ?>" class="avatar-xs rounded-circle me-3">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">Samsung</h6>
                                            <small class="text-muted">Južna Koreja</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Moji dokumenti</h5>
                                        <a data-bs-toggle="tab"
                                        href="#activities" id="viewAllDocuments" class="text-info fs-13">View all</a>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-6 col-sm-3">
                                            <div class="text-center">
                                                <i class="ri-file-pdf-2-line fs-24 text-info"></i>
                                                <p class="fs-13 text-muted mt-1 mb-0">Dokument.pdf</p>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <div class="text-center">
                                                <i class="ri-file-image-line fs-24 text-info"></i>
                                                <p class="fs-13 text-muted mt-1 mb-0">Slika.jpg</p>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <div class="text-center">
                                                <i class="ri-file-excel-2-line fs-24 text-info"></i>
                                                <p class="fs-13 text-muted mt-1 mb-0">Tabela.xlsx</p>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <div class="text-center">
                                                <i class="ri-file-image-line fs-24 text-info"></i>
                                                <p class="fs-13 text-muted mt-1 mb-0">Grafika.png</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Personal Details Form -->
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-pane"  role="tabpanel">
                                        <form action="javascript:void(0);">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="firstnameInput" class="form-label text-info">
                                                           Ime</label>
                                                        <input type="text" class="form-control" id="firstnameInput"
                                                            placeholder="Unesite vaše ime">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="lastnameInput" class="form-label text-info">Prezime</label>
                                                        <input type="text" class="form-control" id="lastnameInput"
                                                            placeholder="Enter your lastname" value="Surname" >
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="phonenumberInput" class="form-label text-info">Broj mobitela</label>
                                                        <input type="text" class="form-control" id="phonenumberInput"
                                                            placeholder="Unesite Vaš broj mobitela">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="emailInput" class="form-label text-info">Email
                                                            adresa</label>
                                                        <input type="email" class="form-control" id="emailInput"
                                                            placeholder="Enter your email" >
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="JoiningdatInput" class="form-label text-info">Joining
                                                            Date</label>
                                                        <input type="text" class="form-control" data-provider="flatpickr"
                                                            id="JoiningdatInput" data-date-format="d M, Y"
                                                            data-deafult-date="24 Nov, 2021" placeholder="Select date" />
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="designationInput" class="form-label text-info">Designation</label>
                                                        <input type="text" class="form-control" id="designationInput"
                                                            placeholder="Designation" value="Lead Designer / Developer">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="websiteInput1" class="form-label text-info">Website</label>
                                                        <input type="text" class="form-control" id="websiteInput1"
                                                            placeholder="www.example.com" value="www.velzon.com" />
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="cityInput" class="form-label text-info">City</label>
                                                        <input type="text" class="form-control" id="cityInput" placeholder="City"
                                                            value="California" />
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="countryInput" class="form-label text-info">Country</label>
                                                        <input type="text" class="form-control" id="countryInput"
                                                            placeholder="Country" value="United States" />
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="zipcodeInput" class="form-label text-info">Zip
                                                            Code</label>
                                                        <input type="text" class="form-control" minlength="5" maxlength="6"
                                                            id="zipcodeInput" placeholder="Enter zipcode" value="90011">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-12">
                                                    <div class="mb-3 pb-2">
                                                        <label for="exampleFormControlTextarea"
                                                            class="form-label text-info">Description</label>
                                                        <textarea class="form-control" id="exampleFormControlTextarea" placeholder="Enter your description"
                                                            rows="3">Hi I'm Anna Adame,It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is European languages are members of the same family.</textarea>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-12">
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <button type="submit" class="btn btn-info">Ažuriraj podatke</button>
                                                        
                                                    </div>
                                                </div>
                                                <!--end col-->
                                            </div>
                                        <!--end row-->
                                        </form>
                                   
                                    </div>

                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="tab-pane fade " id="activities">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Document</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-info">
                                <tr>
                                    <td>1</td>
                                    <td class="fw-semibold">Ugovor_o_saradnji.pdf</td>
                                    <td>
                                        <img src="<?php echo e(URL::asset('build/images/users/orbico.png')); ?>" class="avatar-xs rounded-circle me-2">
                                        ORBICO
                                    </td>
                                    <td><span class="badge bg bg-info">PDF</span></td>
                                    <td><a href="#" class="link-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="fw-semibold">Cjenovnik.xlsx</td>
                                    <td>
                                        <img src="<?php echo e(URL::asset('build/images/users/hifa.png')); ?>" class="avatar-xs rounded-circle me-2">
                                        HIFA
                                    </td>
                                    <td><span class="badge bg-success">Excel</span></td>
                                    <td><a href="#" class="link-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="fw-semibold">Ponuda_Samsung.jpg</td>
                                    <td>
                                        <img src="<?php echo e(URL::asset('build/images/users/samsung.png')); ?>" class="avatar-xs rounded-circle me-2">
                                        Samsung
                                    </td>
                                    <td><span class="badge bg-warning">Image</span></td>
                                    <td><a href="#" class="link-primary">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/profile.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const avatarImg = document.getElementById('user-avatar');
            const token = localStorage.getItem('token');

            if (token) {
                axios.get('/api/auth/me', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    const user = response.data;
                    if (user.avatar && user.avatar !== '') {
                        const avatarUrl = `/storage/uploads/avatars/${user.avatar}`;

                        const testImg = new Image();
                        testImg.onload = function () {
                            avatarImg.src = avatarUrl;
                        };
                        testImg.onerror = function () {
                            avatarImg.src = "/build/images/users/avatar-1.jpg";
                        };
                        testImg.src = avatarUrl;
                    }
                })
                .catch(error => {
                    console.error("Greška prilikom dohvata korisnika:", error);
                });
            }
        });
    </script>
    <script>
        document.getElementById('viewAllDocuments').addEventListener('click', function(event) {
        event.preventDefault();

    // Manually trigger the Bootstrap tab
        const triggerTab = new bootstrap.Tab(document.querySelector('a[href="#activities"]'));
        triggerTab.show();
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/pages-profile.blade.php ENDPATH**/ ?>