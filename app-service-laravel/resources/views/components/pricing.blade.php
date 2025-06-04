<div class="container">
    <div class="row justify-content-center">
@php
    $packages = [
        [
            'id' => 1,
            'name' => 'StartUp',
            'price' => 500,
            'description' => 'Za manja preduzeća',
            'features' => [
                '100 AI Skeniranih Deklaracija',
                '50 strana po Deklaraciji',
                '200 Deklaracija u historiji',
                'Prosječna brzina skeniranja: 20 s',
                '30 dana',
                '24/7 Support'
            ],
            'icon' => 'ri-star-s-fill'
        ],
        [
            'id' => 2,
            'name' => 'GoBig',
            'price' => 850,
            'description' => 'Idealno za biznise u razvoju',
            'features' => [
                '200 AI Skeniranih Deklaracija',
                '150 strana po Deklaraciji',
                '500 Deklaracija u historiji',
                'Prosječna brzina skeniranja: 10 s',
                '120 dana',
                '24/7 Support'
            ],
            'icon' => 'ri-medal-line'
        ],
        [
            'id' => 3,
            'name' => 'Business',
            'price' => 2000,
            'description' => 'Skrojeno za velike biznise',
            'features' => [
                '500 AI Skeniranih Deklaracija',
                'Neograničeno strana po Deklaraciji',
                'Neograničeno Deklaracija u historiji',
                'Prosječna brzina skeniranja: 4 s',
                '365 dana',
                '24/7 Support'
            ],
            'icon' => 'ri-shield-star-line'
        ],
    ];

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
            $stats->package_id === $package['id'] &&
            $stats->expiration_date &&
            !\Carbon\Carbon::parse($stats->expiration_date)->isPast() &&
            $stats->active == 1 &&
            Auth::user()->getRemainingScans() > 0;
    @endphp

    <div class="col-lg-4 mb-4">
        <div class="card pricing-box border-0 rounded-0 h-100 {{ $isActive || (!$anyActive && $package['id'] === 2) ? 'ribbon-box right' : '' }}">
            <div class="card-body p-4 m-2 d-flex flex-column">
                @if ($isActive)
                    <div class="ribbon-two ribbon-two-info"><span>Aktivan</span></div>
                @elseif (!$anyActive && $package['id'] === 2)
                    <div class="ribbon-two ribbon-two-info"><span>Popularno</span></div>
                @endif

                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <h5 class="fw-semibold mb-1">{{ $package['name'] }}</h5>
                        <p class="text-muted mb-0">{{ $package['description'] }}</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-light rounded-circle text-info">
                            <i class="{{ $package['icon'] }} text-info fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-2 pb-3">
                    <h2>{{ $package['price'] }} <small class="fs-5">KM</small></h2>
                </div>

                <hr class="my-3 text-muted">

                <ul class="list-unstyled text-muted vstack gap-3 mb-3">
                    @foreach ($package['features'] as $feature)
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>{{ $feature }}</li>
                    @endforeach
                </ul>

                <a id="btnAction-{{ $package['name'] }}" href="javascript:void(0);" class="btn btn-info w-100 mt-auto text-white" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal">
                    @if ($isActive)
                        Traje još: {{ ceil(\Carbon\Carbon::now()->floatDiffInDays(\Carbon\Carbon::parse($stats->expiration_date))) }} dana • Produži
                    @else
                        Započni
                    @endif
                </a>
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
            <div class=" d-flex align-items-center justify-content-between bg-info text-white py-1">
                <h5 class="modal-title d-flex align-items-center  ms-1 me-1 text-white"
                    id="paymentModalLabel">Unesite vaše podatke</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="cardName" class="form-label">Ime i prezime vlasnika kartice</label>
                        <input type="text" class="form-control border-info rounded-0" id="cardName"
                            placeholder="Ime i Prezime">
                    </div>
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Broj kartice</label>
                        <div class="input-group">
                            <input type="text" class="form-control border-info rounded-0" id="cardNumber"
                                placeholder="0000 0000 0000 0000">
                            <span class="input-group-text bg-white border-info rounded-0">
                                <img src="https://img.icons8.com/color/32/000000/mastercard-logo.png"
                                    id="cardLogo" alt="Mastercard" height="20">
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry" class="form-label">Datum isteka</label>
                            <input type="text" class="form-control border-info rounded-0" id="expiry"
                                placeholder="MM/GG">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvc" class="form-label">CVC kod</label>
                            <input type="text" class="form-control border-info rounded-0" id="cvc"
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
                            Prihvatam <a href="#">uslove korištenja</a> i <a href="#">politiku
                                privatnosti</a>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center align-items center ">
                <button type="button" class="btn btn-outline-info w-50"
                    data-bs-dismiss="modal">Otkaži</button>
                <button type="submit" class="btn btn-info text-white w-50">Plati</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal za izbor načina plaćanja -->
<div class="modal fade" id="paymentChoiceModal" tabindex="-1" aria-labelledby="paymentChoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info  shadow">
            <div class="d-flex justify-content-between bg-info py-1 px-1 text-white">

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-4">Molimo odaberite način na koji želite izvršiti uplatu:</p>
                <div class="d-grid gap-3">
                    <button class="btn btn-info text-white" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#paymentModal">
                        💳 Kartično plaćanje
                    </button>
                    <button class="btn btn-outline-info" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#virmanModal">
                        🧾 Plaćanje virmanom
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal za plaćanje virmanom -->
<div class="modal fade" id="virmanModal" tabindex="-1" aria-labelledby="virmanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info ">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="virmanModalLabel">Upute za plaćanje virmanom</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Naziv primaoca:</label>
                    <p class="mb-0">Qla Dev d.o.o. Sarajevo</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Broj računa (IBAN):</label>
                    <p class="mb-0">BA39 1542 0512 3456 7890</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Svrha uplate:</label>
                    <p class="mb-0">Uplata paketa usluga – <span class="text-info fw-semibold">[StartUp /
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
                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Zatvori</button>
                <button type="button" class="btn btn-info text-white">Uredu</button>
            </div>
        </div>
    </div>
</div>



