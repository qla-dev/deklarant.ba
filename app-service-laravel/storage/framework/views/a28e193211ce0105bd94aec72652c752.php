

<?php $__env->startSection('title'); ?>
<?php echo app('translator')->get('translation.signin'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg"  id="auth-particles">
        <div class="bg-overlay"></div>


<?php $__env->startSection('title'); ?>
    Prijava
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>




    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="index" class="d-inline-block auth-logo">
                                <img src="<?php echo e(URL::asset('build/images/logo-light.png')); ?>" alt="" height="20">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome Back !</h5>
                                <p class="text-muted">Sign in to continue to Velzon.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form action="<?php echo e(route('login')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['email'];

    <!-- Page Content -->

    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mt-sm-5 mb-4">
                    <a href="index" class="d-inline-block auth-logo">
                        <img src="<?php echo e(URL::asset('build/images/logo-dark.svg')); ?>" alt="Logo" height="35">
                    </a>
                    <p class="mt-3 fs-15 fw-medium text-dark">Pristupite svom računu</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4 border-0 shadow-lg rounded-2">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="text-info fw-bold">Dobrodošli natrag!</h5>
                                <p class="text-muted">Prijavite se kako biste nastavili.</p>
                            </div>

                            <div class="p-2 mt-4">
                                <form id="login-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="username" class="form-label text-info">Korisničko ime <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control border-info" id="username" placeholder="Unesite korisničko ime">
                                    </div>

                                        <input type="text" class="form-control border-info <?php $__errorArgs = ['email'];

$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;

unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email', 'admin@themesbrand.com')); ?>" id="username" name="email" placeholder="Enter username">

unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email', 'admin@themesbrand.com')); ?>" id="username" name="email" placeholder="Unesite korisničko ime">

                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>

                                            <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>

                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="<?php echo e(route('password.update')); ?>" class="text-muted">Forgot password?</a>
                                        </div>
                                        <label class="form-label" for="password-input">Password <span class="text-danger">*</span></label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input <?php $__errorArgs = ['password'];

                                    <!-- Password -->

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label text-info" for="password-input">Lozinka <span class="text-danger">*</span></label>
                                            <a href="<?php echo e(route('password.update')); ?>" class="text-muted">Zaboravili ste lozinku?</a>
                                        </div>
                                        <div class="position-relative">

                                            <input type="password" class="form-control border-info pe-5" placeholder="Unesite lozinku" id="password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-muted password-addon" type="button"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>


                                            <input type="password" class="form-control border-info pe-5 password-input <?php $__errorArgs = ['password'];

$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;

unset($__errorArgs, $__bag); ?>" name="password" placeholder="Enter password" id="password-input" value="12345678">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>

unset($__errorArgs, $__bag); ?>" name="password" placeholder="Unesite lozinku" id="password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-muted password-addon" type="button"><i class="ri-eye-fill align-middle"></i></button>

                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                                <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo e($message); ?></strong>
                                                </span>

                                                <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>

                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>


                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Sign In</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>

                                    <!-- Remember Me -->

                                    <div class="form-check">
                                        <input class="form-check-input border-info" type="checkbox" id="auth-remember-check">
                                        <label class="form-check-label text-muted" for="auth-remember-check">Zapamti me</label>
                                    </div>

                                    <div class="mt-4">

                                        <button class="btn btn-info w-100 fw-bold" type="button" id="login-btn">Prijava</button>

                                        <button class="btn btn-info w-100 fw-bold" type="submit">Prijava</button>
                                    </div>

                                    <!-- Social Login -->
                                    <div class="mt-4 text-center">
                                        <h5 class="fs-13 mb-4 text-muted">Ili se prijavite putem</h5>
                                        <div>
                                            <button type="button" class="btn btn-primary btn-icon"><i class="ri-facebook-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-danger btn-icon"><i class="ri-google-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-dark btn-icon"><i class="ri-github-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-info btn-icon"><i class="ri-twitter-fill fs-16"></i></button>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Don't have an account ? <a href="/register" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>

                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0 text-muted">Nemate račun? <a href="/register" class="fw-semibold text-info">Registrujte se</a></p>

                    </div>
                </div>
            </div>

            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/libs/particles.js/particles.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/pages/particles.app.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/pages/password-addon.init.js')); ?>"></script>


        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <script>document.write(new Date().getFullYear())</script> eDeklarant <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="<?php echo e(URL::asset('build/libs/particles.js/particles.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/pages/particles.app.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Dummy MAC generator (for local dev)
    function getMACAddress() {
        return '00:11:22:33:44:55';
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("login-btn").addEventListener("click", async function (event) {
            event.preventDefault();
            console.log("Login button clicked");

            const username = document.getElementById("username").value;
            const password = document.getElementById("password-input").value;

            if (!username || !password) {
                alert("Molimo unesite korisničko ime i lozinku.");
                return;
            }

            const mac = getMACAddress(); // No more error here ✅

            try {
                const response = await axios.post("/api/auth/login", {
                    username,
                    password,
                }, {
                    headers: {
                        'MAC-Address': mac
                    }
                });

                const { token, user } = response.data;

                localStorage.setItem("auth_token", token);
                localStorage.setItem("user", JSON.stringify(user));

                console.log("Login successful, redirecting...");
                window.location.href = "/";
            } catch (error) {
                console.error("Login error:", error);
                alert(error.response?.data?.message || "Greška prilikom prijave.");
            }
        });
    });
</script>


    <script src="<?php echo e(URL::asset('build/libs/particles.js/particles.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/particles.app.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/form-validation.init.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>