  @if (!Auth::user()->getActivePackageName() || !Auth::user()->getOtherActivePackageStats() || Auth::user()->getOtherActivePackageStats()->active == 0)
    <div class="text-danger status-text">
        <strong>Pretplata nije aktivna.</strong>
        <span class="text-decoration-underline" style="cursor:pointer;" onclick="window.location.href='{{ url('cijene-paketa') }}'">Odaberi paket po svojim potrebama!</span>
    </div>

@elseif ((Auth::user()->getRemainingScans() ?? 0) == 0)
    <div class="text-danger status-text">
        <strong>Pretplata {{ Auth::user()->getActivePackageName() }} je aktivna, ali su skeniranja potrošena.</strong>
        <span class="text-decoration-underline" style="cursor:pointer;" onclick="window.location.href='{{ url('cijene-paketa') }}'">Nadopuni paket!</span>
    </div>

@elseif (Auth::user()->getOtherActivePackageStats()->expiration_date && \Carbon\Carbon::parse(Auth::user()->getOtherActivePackageStats()->expiration_date)->isPast())
    <div class="text-danger status-text">
        <strong>Pretplata {{ Auth::user()->getActivePackageName() }} je istekla.</strong>
        <span class="text-decoration-underline" style="cursor:pointer;" onclick="window.location.href='{{ url('cijene-paketa') }}'">Reaktiviraj odmah!</span>
    </div>


@else
    <div class="text-info  status-text">Aktivna pretplata: <strong>{{ Auth::user()->getActivePackageName() }}</strong>. Preostalo skeniranja: <strong>{{ Auth::user()->getRemainingScans() }}</strong>.</div>
@endif


