@php
    $package = Auth::user()->getActivePackageStats();
    $otherPackage = Auth::user()->getOtherActivePackageStats();
    $remainingScans = Auth::user()->getRemainingScans() ?? 0;
    $now = \Carbon\Carbon::now();
@endphp

@if (!$package || $otherPackage->active == 0)
    <div class="bg-danger text-white text-center py-1 rounded-0">
        <i class="ri-alert-line me-1"></i> 
        <span>Pretplata neaktivna. <b>Aktiviraj!</b></span>
    </div>

@elseif ($remainingScans == 0)
    <div class="bg-danger text-white text-center py-1 rounded-0">
        <i class="ri-alert-line me-1"></i>
        <span><b>Nema dostupnih.</b> Nadopuni!</span>
    </div>

@elseif ($otherPackage->expiration_date && \Carbon\Carbon::parse($otherPackage->expiration_date)->lt($now))
    <div class="bg-danger text-white text-center py-1 rounded-0">
        <i class="ri-alert-line me-1"></i>
        <span>Pretplata je istekla. <b>Aktiviraj!</b></span>
    </div>

@elseif (
    $otherPackage->expiration_date &&
    abs(\Carbon\Carbon::parse($otherPackage->expiration_date)->startOfDay()->diffInDays($now->startOfDay(), false)) <= 5 &&
    \Carbon\Carbon::parse($otherPackage->expiration_date)->gt($now)
)
    @php
        $daysLeft = abs(\Carbon\Carbon::parse($otherPackage->expiration_date)->startOfDay()->diffInDays($now->startOfDay(), false));
    @endphp
    <div class="bg-danger text-white text-center py-1 rounded-0">
        <i class="ri-alert-line me-1"></i>
        <span>Ističe za {{ $daysLeft }} dana. <b>Produži!</b></span>
    </div>




@elseif ($remainingScans < 5)
    <div class="bg-danger text-white text-center py-1 rounded-0">
        <i class="ri-alert-line me-1"></i>
        <span><b>{{ $remainingScans }}</b> dostupno. <b>Nadopuni!</b></span>
    </div>

@else
    @php
         $daysLeft = abs(\Carbon\Carbon::parse($otherPackage->expiration_date)->startOfDay()->diffInDays($now->startOfDay(), false));
    @endphp
    <div class="bg-info text-white text-center py-1 rounded-0">
        <i class="ri-pulse-ai-line me-1"></i>
        <span>Pretplata traje još {{ $daysLeft }} dana</span>
    </div>
@endif
