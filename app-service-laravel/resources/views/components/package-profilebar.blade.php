@php
    $user = Auth::user();
    $otherPackage = $user->getOtherActivePackageStats();
    $packageName = $user->getActivePackageName();
    $remainingScans = $user->getRemainingScans() ?? 0;

    $expirationDate = $otherPackage->expiration_date ?? null;
    $formattedDate = $expirationDate ? \Carbon\Carbon::parse($expirationDate)->format('d.m.Y') : null;
    $now = \Carbon\Carbon::now();
    $expirationCarbon = $expirationDate ? \Carbon\Carbon::parse($expirationDate)->startOfDay() : null;
    $daysLeft = $expirationCarbon ? $now->startOfDay()->diffInDays($expirationCarbon, false) : null;

    $iconHTML = '';
    switch (strtolower($packageName)) {
        case 'gobig':
            $iconHTML = '<i class="ri-medal-line text-info fs-4 me-2 position-absolute" style="margin-top:-3px!important;margin-left: 45px;"></i>';
            break;
        case 'startup':
            $iconHTML = '<i class="ri-star-s-fill text-info fs-4 me-2 position-absolute" style="margin-top:-4px!important;margin-left: 58px;"></i>';
            break;
        case 'business':
            $iconHTML = '<i class="ri-shield-star-line text-info fs-4 me-2 position-absolute" style="margin-top:-4px!important;margin-left: 68px;"></i>';
            break;
    }
@endphp

@if ($otherPackage && $otherPackage->active == 1)
    <div class="d-flex" style="flex-direction: column;">
        <span class="d-inline-flex align-items-center gap-1 fs-15">
            <h5 class="card-title mb-0 mobile-hide"><span >Tvoj aktivni paket je</span></h5>
            <b class="d-inline-flex gap-1 text-info mb-0 me-4" style="margin-bottom:-1px!important">
                {{ $packageName }}
                {!! $iconHTML !!}
            </b>
        </span>

        {{-- Custom expiration and usage notices --}}
        @if ($expirationCarbon && $expirationCarbon->lt($now))
            <span class="text-danger">Pretplata je istekla</span>

        @elseif ($remainingScans == 0)
            <span class="text-danger">Nema dostupnih skeniranja</span>

        @elseif ($remainingScans < 10)
            <span class="text-warning">Imate još {{ $remainingScans }} skeniranja</span>

        @elseif ($daysLeft !== null && $daysLeft <= 10 && $daysLeft > 0)
            <span class="text-warning">Ističe za {{ $daysLeft }} dana</span>

        @elseif ($daysLeft !== null && $daysLeft > 10)
            <span class="text-muted">Ističe za {{ $daysLeft }} dana</span>

        @elseif ($formattedDate)
            <span class="text-muted">Aktivan do: {{ $formattedDate }}</span>
        @endif
    </div>
@else
    {{-- Fallback if no active package or package is not active --}}
    <a class="text-danger" style="white-space:nowrap; cursor:pointer;"
        onclick="window.location.href='{{ url('cijene-paketa') }}'">
        Pretplata nije aktivna. <br><strong class="text-decoration-underline mobile-hide">Odaberi paket po svojim potrebama!</strong>
    </a>
@endif