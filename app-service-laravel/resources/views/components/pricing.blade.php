<div class="container">
    <div class="row justify-content-center">
@php
    use App\Models\Package;
     $packages = Package::getAllPackages() ?? [];


    $stats = Auth::user()->getOtherActivePackageStats();
    $anyActive = $stats &&
        $stats->expiration_date &&
        !\Carbon\Carbon::parse($stats->expiration_date)->isPast() &&
        $stats->active == 1 &&
        Auth::user()->getRemainingScans() > 0;
@endphp

@foreach ($packages as $package)
    @php
        $isActive = $stats &&
            $stats->package_id === $package->id &&
            $stats->expiration_date &&
            !\Carbon\Carbon::parse($stats->expiration_date)->isPast() &&
            $stats->active == 1 &&
            Auth::user()->getRemainingScans() > 0;
    @endphp

    <div class="col-lg-4 mb-4">
        <div class="card pricing-box border-0 rounded-0 h-100 {{ $isActive || (!$anyActive && $package->id === 2) ? 'ribbon-box right' : '' }}">
            <div class="card-body p-4 m-2 d-flex flex-column">
                @if ($isActive)
                    <div class="ribbon-two ribbon-two-info"><span>Aktivan</span></div>
                @elseif (!$anyActive && $package->id === 2)
                    <div class="ribbon-two ribbon-two-info"><span>Popularno</span></div>
                @endif

                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <h5 class="fw-semibold mb-1">{{ $package->name }}</h5>
                        <p class="text-muted mb-0">{{ $package->description }}</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-light rounded-circle text-info">
                            <i class="{{ $package->icon }} text-info fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-2 pb-3">
                    <h2>{{ $package->price }} <small class="fs-5">KM</small></h2>
                </div>

                <hr class="my-3 text-muted">

                <ul class="list-unstyled text-muted vstack gap-3 mb-3">
                    <li><i class="ri-checkbox-circle-fill text-info me-2"></i>{{ $package->available_scans }} AI Skeniranih Deklaracija</li>
                    <li><i class="ri-checkbox-circle-fill text-info me-2"></i>{{ $package->page_limit }} strana po Deklaraciji</li>
                    <li><i class="ri-checkbox-circle-fill text-info me-2"></i>{{ $package->document_history }} Deklaracija u historiji</li>
                    <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: {{ $package->speed_limit }}</li>
                    <li><i class="ri-checkbox-circle-fill text-info me-2"></i>{{ $package->duration }} dana</li>
                    <li><i class="ri-checkbox-circle-fill text-info me-2"></i>24/7 Support</li>
                </ul>

               
                    @if ($isActive)
                    <a href="#" class="btn btn-info w-100 mt-auto text-white disabled" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal">
                        Traje još: {{ ceil(\Carbon\Carbon::now()->floatDiffInDays(\Carbon\Carbon::parse($stats->expiration_date))) }} dana • Produži
                        </a>
                    @else
                  <a id="btnAction-{{ $package->id }}"
   href="javascript:void(0);"
   class="btn btn-info w-100 mt-auto text-white {{ $loop->iteration > 1 ? 'disabled' : '' }}"
   data-package-id="{{ $package->id }}"
   data-duration="{{ $package->duration }}"
   data-available-scans="{{ $package->available_scans }}"
   data-bs-toggle="modal"
   data-bs-target="#paymentChoiceModal">
   Započni
</a>

                    @endif
               
            </div>
        </div>
    </div>
@endforeach




    </div>
</div>



<!-- Payment Method Modal -->
<!-- Modal za plaćanje karticom -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info  rounded-0 shadow">
            <div class=" d-flex align-items-center justify-content-between bg-info text-white p-3">
                <h5 class="modal-title d-flex align-items-center  ms-1 me-1 text-white "
                    id="paymentModalLabel">Unesite vaše podatke</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="cardName" class="form-label">Ime i prezime vlasnika kartice</label>
                        <input type="text" class="form-control rounded-0" id="cardName"
                            placeholder="Ime i Prezime">
                    </div>
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Broj kartice</label>
                        <div class="input-group">
                            <input type="text" class="form-control rounded-0" id="cardNumber"
                                placeholder="0000 0000 0000 0000" style="border-bottom-right-radius: 0!important; border-top-right-radius: 0!important;">
                            <span class="input-group-text bg-white rounded-0">
                                <img src="https://img.icons8.com/color/32/000000/mastercard-logo.png"
                                    id="cardLogo" alt="Mastercard" height="20">
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry" class="form-label">Datum isteka</label>
                            <input type="text" class="form-control rounded-0" id="expiry"
                                placeholder="MM/GG">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvc" class="form-label">CVC kod</label>
                            <input type="text" class="form-control rounded-0" id="cvc"
                                placeholder="123">
                        </div>
                    </div>


                    <div class="form-check mb-3">
                        <input class="form-check-input border-info rounded-0" type="checkbox" id="saveCard">
                        <label class="form-check-label" for="saveCard">
                            Sačuvaj podatke o kartici za naredna plaćanja
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input border-info rounded-0" type="checkbox"
                            id="termsCheck">
                        <label class="form-check-label" for="termsCheck">
                            Prihvatam <a href="#" class=" text-info" >uslove korištenja</a> i <a href="#" class=" text-info">politiku
                                privatnosti</a>
                        </label>
                    </div>
                </form>
            </div>
            <img src="https://avatars.githubusercontent.com/u/59373352?s=280&v=4"
                class="img-fluid mx-auto d-block mb-3" alt="Visa Card" style="max-width: 100px;">
            <div class="modal-footer d-flex justify-content-center align-items-center " style="flex-direction: row; flex-wrap: nowrap!important;">
                <button type="button" class="btn btn-outline-info w-50"
                    data-bs-dismiss="modal">Otkaži</button>
                <button type="submit" class="btn btn-info text-white w-50">Plati</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal za plaćanje virmanom -->
<div class="modal fade" id="virmanModal" tabindex="-1" aria-labelledby="virmanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info ">
            <div class="modal-header p-3 bg-info text-white">
                <h5 class="modal-title text-white" id="virmanModalLabel">Upute za plaćanje virmanom</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Naziv primaoca:</label>
                    <p class="mb-0">"qla.dev"</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Adresa primaoca:</label>
                    <p class="mb-0">Vilsonovo šetalište 9</p>
                </div>
                 <div class="mb-3">
                    <label class="form-label fw-bold">ID broj:</label>
                    <p class="mb-0">485004586212</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Broj računa (IBAN):</label>
                    <p class="mb-0">BA39 1542 0512 3456 7890</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Svrha uplate:</label>
                    <p class="mb-0">Uplata za deklarant.ai paket usluga – <span class="text-info fw-semibold">[StartUp /
                            GoBig / Business]</span></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Iznos:</label>
                    <p class="mb-0 text-info fs-5">[unesi iznos u KM]</p>
                </div>
                <hr>
                <p class="text-muted small">
                    Nakon izvršene uplate, molimo pošaljite dokaz o uplati na email:
                    <strong>uplate@qla.dev</strong> radi brže obrade.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Uredu</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal za izbor načina plaćanja -->
<div class="modal fade" id="paymentChoiceModal" tabindex="-1" aria-labelledby="paymentChoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="d-flex justify-content-between bg-info py-2 px-3 text-white align-items-center rounded-top">
                <h5 class="modal-title mb-0 text-white">Izaberi način plaćanja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-4">Odaberi način na koji želiš izvršiti uplatu</p>
                <div class="row g-3 justify-content-center">
                    <div class="col-6 col-md-6">
                        <button class="w-100 border border-2 rounded-3 py-4 d-flex flex-column align-items-center justify-content-center payment-option"
                                data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <div class="fs-1">💳</div>
                            <div class="fw-bold mt-2">Kartično plaćanje</div>
                        </button>
                    </div>
                    <div class="col-6 col-md-6">
                        <button class="w-100 border border-2 rounded-3 py-4 d-flex flex-column align-items-center justify-content-center payment-option"
                                data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#virmanModal">
                            <div class="fs-1">🧾</div>
                            <div class="fw-bold mt-2">Plaćanje virmanom</div>
                        </button>
                    </div>
                    <div class="col-12">
                       <button id="btnActivatePackage" class="w-100 btn btn-md btn-success fw-bold">
    <i class="fa fa-check-circle me-1"></i> Aktiviraj trial
</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const userId = {{ Auth::id() }};
        let selectedPackageId = null;

        const token = @json(session('auth_token')); 
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Track selected package from "Započni" buttons
        document.querySelectorAll('[id^="btnAction-"]').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedPackageId = btn.getAttribute("data-package-id");
            });
        });

        // Handle "Aktiviraj pretplatu" click
        document.getElementById("btnActivatePackage").addEventListener("click", async () => {
            if (!selectedPackageId || !userId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Greška',
                    text: 'Nije moguće aktivirati pretplatu.',
                });
                return;
            }

            try {
                const btnEl = document.querySelector(`#btnAction-${selectedPackageId}`);
                const duration = parseInt(btnEl.getAttribute("data-duration")) || 30;
                const availableScans = parseInt(btnEl.getAttribute("data-available-scans")) || 100;

                // Calculate expiration date
                const expirationDate = new Date();
                expirationDate.setDate(expirationDate.getDate() + duration);
                const expirationISO = expirationDate.toISOString().split('T')[0];

                const body = {
                    active: true,
                    expiration_date: expirationISO,
                    remaining_scans: availableScans
                };

                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const res = await fetch(`/api/user-packages/users/${userId}/packages/${selectedPackageId}`, {
                    method: "POST",
                    headers: headers,
                    body: JSON.stringify(body),
                    credentials: "include"
                });

                if (!res.ok) throw new Error("Neuspješna aktivacija");

                const modal = bootstrap.Modal.getInstance(document.getElementById("paymentChoiceModal"));
                modal.hide();

                setTimeout(() => {
    Swal.fire({
        icon: 'success',
        title: 'Uspješno ste aktivirali trial!',
        text: 'Ugodno korištenje želi tim deklarant.ai',
        timer: 2000,
        showConfirmButton: false
    });

    setTimeout(() => {
        window.location.href = '/';
    }, 2000);
}, 300);


            } catch (err) {
                console.error("Greška:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Greška!',
                    text: 'Došlo je do problema pri aktivaciji paketa. Obratite se administratoru.',
                    footer: '<a href="/contact">Kontaktirajte podršku</a>'
                });
            }
        });
    });
</script>







