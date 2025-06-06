@php
    $package = Auth::user()->getActivePackageStats();
    $otherPackage = Auth::user()->getOtherActivePackageStats();
    $remainingScans = Auth::user()->getRemainingScans() ?? 0;
    $now = \Carbon\Carbon::now();
@endphp

@if (!$package || $otherPackage->active == 0)
    <div class="bg-danger text-white text-center py-1 rounded-0">
        <i class="ri-alert-line me-1 d-none d-md-inline"></i> 
        <span>Pretplata neaktivna. <b class="d-none d-md-inline">Aktiviraj!</b></span>
    </div>

@elseif ($remainingScans == 0)
    <div class="bg-danger text-white text-center py-1 rounded-0">
           <i class="ri-alert-line me-1 d-none d-md-inline"></i> 
        <span>Nema dostupnih.<b class="d-none d-md-inline" >Nadopuni!</b></span>
    </div>

@elseif ($otherPackage->expiration_date && \Carbon\Carbon::parse($otherPackage->expiration_date)->lt($now))
    <div class="bg-danger text-white text-center py-1 rounded-0">
           <i class="ri-alert-line me-1 d-none d-md-inline"></i> 
        <span>Pretplata je istekla. <b class="d-none d-md-inline">Aktiviraj!</b></span>
    </div>

@elseif (
    $otherPackage->expiration_date &&
    abs(\Carbon\Carbon::parse($otherPackage->expiration_date)->startOfDay()->diffInDays($now->startOfDay(), false)) <= 10 &&
    \Carbon\Carbon::parse($otherPackage->expiration_date)->gt($now)
)
    @php
        $daysLeft = abs(\Carbon\Carbon::parse($otherPackage->expiration_date)->startOfDay()->diffInDays($now->startOfDay(), false));
    @endphp
    <div class="bg-danger text-white text-center py-1 rounded-0">
           <i class="ri-alert-line me-1 d-none d-md-inline"></i> 
        <span>Traje {{ $daysLeft }} dana. <b class="d-none d-md-inline">Produži!</b></span>
    </div>




@elseif ($remainingScans < 10)
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
        <span>Traje {{ $daysLeft }} dana</span>
    </div>
@endif
