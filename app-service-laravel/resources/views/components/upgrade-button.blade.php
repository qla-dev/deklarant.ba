<a href="{{ url('cijene-paketa') }}" class="btn btn-info w-100 text-white btn-sm fs-6" id="upgrade-btn">
    @if (
        Auth::user()->getOtherActivePackageStats() &&
        Auth::user()->getOtherActivePackageStats()->active == 1 &&
        \Carbon\Carbon::parse(Auth::user()->getOtherActivePackageStats()->expiration_date)->isFuture()
    )
        <i class="ri-arrow-up-circle-line"></i> Nadogradi paket
    @else
        <i class="fa-regular fa-tags"></i> Odaberi paket
    @endif
</a>
