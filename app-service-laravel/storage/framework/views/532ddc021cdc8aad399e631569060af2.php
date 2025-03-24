
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="<?php echo e(URL::asset('build/images/profile-bg.jpg')); ?>" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="<?php if(Auth::user()->avatar != ''): ?> <?php echo e(URL::asset('images/' . Auth::user()->avatar)); ?><?php else: ?><?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?> <?php endif; ?>" alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">Anna Adame</h3>
                    <p class="text-white text-opacity-75">Owner & Founder</p>
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2"><i
                                class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>California,
                            United States</div>
                        <div><i class="ri-building-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>Themesbrand
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">24.3K</h4>
                            <p class="fs-14 mb-0">Followers</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">1.3K</h4>
                            <p class="fs-14 mb-0">Following</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex profile-wrapper">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Activities</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Projects</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Documents</span>
                            </a>
                        </li>
                    </ul>
                    <div class="flex-shrink-0">
                        <a href="pages-profile-settings" class="btn btn-success"><i
                                class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-5">Complete Your Profile</h5>
                                        <div class="progress animated-progress custom-progress progress-label">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 30%"
                                                aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                                <div class="label">30%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Full Name :</th>
                                                        <td class="text-muted">Anna Adame</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Mobile :</th>
                                                        <td class="text-muted">+(1) 987 6543</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">E-mail :</th>
                                                        <td class="text-muted">daveadame@velzon.com</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Location :</th>
                                                        <td class="text-muted">California, United States
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Joining Date</th>
                                                        <td class="text-muted">24 Nov 2021</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Portfolio</h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span
                                                        class="avatar-title rounded-circle fs-16 bg-body text-light shadow">
                                                        <i class="ri-github-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-primary shadow">
                                                        <i class="ri-global-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-success shadow">
                                                        <i class="ri-dribbble-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-danger shadow">
                                                        <i class="ri-pinterest-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Skills</h5>
                                        <div class="d-flex flex-wrap gap-2 fs-15">
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">Photoshop</a>
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">illustrator</a>
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">HTML</a>
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">CSS</a>
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">Javascript</a>
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">Php</a>
                                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">Python</a>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Suggestions</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a href="#" role="button" id="dropdownMenuLink2"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-2-fill fs-14"></i>
                                                    </a>

                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuLink2">
                                                        <li><a class="dropdown-item" href="#">View</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#">Edit</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center py-3">
                                                <div class="avatar-xs flex-shrink-0 me-3">
                                                    <img src="<?php echo e(URL::asset('build/images/users/avatar-3.jpg')); ?>" alt=""
                                                        class="img-fluid rounded-circle shadow" />
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="fs-14 mb-1">Esther James</h5>
                                                        <p class="fs-13 text-muted mb-0">Frontend
                                                            Developer</p>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-success shadow-none"><i
                                                            class="ri-user-add-line align-middle"></i></button>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center py-3">
                                                <div class="avatar-xs flex-shrink-0 me-3">
                                                    <img src="<?php echo e(URL::asset('build/images/users/avatar-4.jpg')); ?>" alt=""
                                                        class="img-fluid rounded-circle shadow" />
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="fs-14 mb-1">Jacqueline Steve</h5>
                                                        <p class="fs-13 text-muted mb-0">UI/UX Designer
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-success shadow-none"><i
                                                            class="ri-user-add-line align-middle"></i></button>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center py-3">
                                                <div class="avatar-xs flex-shrink-0 me-3">
                                                    <img src="<?php echo e(URL::asset('build/images/users/avatar-5.jpg')); ?>" alt=""
                                                        class="img-fluid rounded-circle shadow" />
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="fs-14 mb-1">George Whalen</h5>
                                                        <p class="fs-13 text-muted mb-0">Backend
                                                            Developer</p>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-success shadow-none"><i
                                                            class="ri-user-add-line align-middle"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div>
                                <!--end card-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('build/js/pages/profile.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/pages-profile.blade.php ENDPATH**/ ?>