@php
    use App\Models\Package;
    $packages = Package::getAllPackages() ?? [];
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 mb-4 d-flex align-items-center justify-content-center position-relative" style="min-height: 48px;">
            <span class="me-3 fw-semibold h4" id="labelMonthly">Mjesečno</span>
            <div class="form-check form-switch d-flex align-items-center" style="height: 100%; margin-top: -7px; padding:0!important">
                <input class="form-check-input" type="checkbox" id="toggleYearly" style="width:5em;height:2em; margin:0!important; padding:0!important">
            </div>
            <span class="ms-3 fw-semibold h4 d-flex align-items-center position-relative g-label-badge-flex" id="labelYearly" style="z-index:1;">
                Godišnje
                <span class="badge bg-info ms-2 position-absolute badge-below-label-sm" style="left: 100%; margin-left: 8px;">15% popusta</span>
            </span>
        </div>

@foreach ($packages as $package)
    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-duration="{{ 800 + ($loop->index * 200) }}">
        <div class="card pricing-box border-0 rounded-3 h-100 position-relative shadow-sm {{ $package->id === 2 ? 'border-info border-2' : '' }}">
            @if($package->id === 2)
                <div class="ribbon-two ribbon-two-info" style="z-index: 10;"><span>Popularno</span></div>
            @endif
            
            <!-- Icon positioned at top-right corner -->
            <div class="avatar-sm" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                <div class="avatar-title bg-light text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%;">
                    <i class="{{ $package->icon }} text-info fs-4"></i>
                </div>
            </div>
            
            <div class="card-body p-4 d-flex flex-column">
                <div class="text-start mb-4">
                    <h5 class="fw-bold mb-2 h2">{{ $package->name }}</h5>
                    <p class="text-muted small mb-0">{{ $package->description }}</p>
                </div>

                <!-- Price -->
                <div class="text-center mb-4">
                    <h2 class="mb-0">
                        <span class="package-price" data-monthly="{{ $package->price_monthly ?? $package->price }}" data-yearly="{{ $package->price_yearly ?? ($package->price * 12 * 0.85) }}" data-monthly12="{{ ($package->price_monthly ?? $package->price) * 12 }}">
                            {{ number_format($package->price_monthly ?? $package->price, 2, ',', '.') }}
                        </span>
                        <span class="price-currency"> KM</span>
                        <span class="yearly-original-price text-danger small d-none" style="text-decoration: line-through; font-size: 0.5em;">{{ number_format(($package->price_monthly ?? $package->price) * 12, 2, ',', '.') }} KM</span>
                    </h2>
                    <p class="text-muted small mb-0 billing-period">mjesečno</p>
                </div>

                <!-- Features -->
                <ul class="list-unstyled text-muted vstack gap-3 mb-4">
                    <li><i class="fas fa-wand-magic-sparkles text-info me-2"></i>{{ $package->available_scans }} AI tokena</li>
                    <li><i class="ri-money-dollar-circle-line text-info me-2"></i>Cijena tokena: {{ number_format($package->token_price, 2, ',', '.') }} KM</li>
                    <li><i class="ri-file-edit-line text-info me-2"></i>Obrada deklaracije: {{ (int) $package->declaration_token_cost }} AI tokena</li>
                    <li><i class="ri-check-double-line text-info me-2"></i>Mogućnost kreiranja do {{ floor($package->available_scans / 10) }} deklaracija</li>
                    <li><i class="ri-pages-line text-info me-2"></i>{{ $package->page_limit }} stranica po deklaraciji</li>
                    <li><i class="ri-add-circle-line text-info me-2"></i>Dodatna stranica: 1 AI token</li>
                    <li><i class="ri-archive-line text-info me-2"></i>{{ $package->document_history }} deklaracija u arhivi</li>
                    <li><i class="ri-timer-flash-line text-info me-2"></i>Prosječna brzina skeniranja: {{ $package->speed_limit }}</li>
                    <li><i class="ri-calendar-check-line text-info me-2"></i>Trajanje paketa: {{ $package->duration }} dana</li>
                    <li><i class="ri-customer-service-2-line text-info me-2"></i>24/7 Support</li>
                    <li><i class="ri-device-line text-info me-2"></i>Istovremena prijava sa {{ $package->simultaneous_logins }} MAC adresa</li>
                </ul>

                <a href="#" class="btn btn-info w-100 mt-auto text-white openRegister">Započni</a>
            </div>
        </div>
    </div>
@endforeach

    </div>
</div> 