@extends('layouts.master-without-nav')

@section('title')
    Prijava
@endsection

@section('content')
<div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay" style="opacity:.3!important"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mt-sm-5 mb-4">
                    <a href="/" class="d-inline-block auth-logo">
                        <img src="{{ URL::asset('build/images/logo-dek-white.png')}}" alt="Logo" height="35">
                    </a>
                
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4 border-0 shadow-lg rounded-2">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="text-info fw-bold">Dobrodošli natrag!</h5>
                                <p class="text-muted">Prijavite se kako biste nastavili</p>
                            </div>

                            <div class="p-2 mt-4">
                                <form id="login-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label text-info">Korisničko ime <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="username" placeholder="Unesite korisničko ime">
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label text-info" for="password-input">Lozinka <span class="text-danger">*</span></label>
                                            <a href="{{ route('password.update') }}" class="text-muted">Zaboravili ste lozinku?</a>
                                        </div>
                                        <div class="position-relative">
                                            <input type="password" class="form-control pe-5" placeholder="Unesite lozinku" id="password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-muted password-addon" type="button"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="auth-remember-check">
                                        <label class="form-check-label text-muted" for="auth-remember-check">Zapamti me</label>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-info w-100 fw-bold" type="button" id="login-btn">Prijava</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0 text-muted">Nemate račun? <a href="/register" class="fw-semibold text-info">Registrujte se</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <script>document.write(new Date().getFullYear())</script> deklarant.ba <i class="mdi mdi-heart text-info"></i> Razvijeno od strane <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-qla.png') }}" alt="" height="17" style="margin-top:-3px">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
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

            const mac = getMACAddress(); // No more error here 

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


@endsection
