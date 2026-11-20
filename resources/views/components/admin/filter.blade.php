@props(['placeholder' => 'Cari...'])
<form method="GET" class="card card-body mb-3 p-2">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="{{ $placeholder }}" aria-label="Cari">
            </div>
        </div>
        {{ $slot }}
        <div class="col-auto">
            <button class="btn btn-primary">Terapkan</button>
            @if (collect(request()->except('page'))->filter()->isNotEmpty())
                <a href="{{ url()->current() }}" class="btn btn-light">Reset</a>
            @endif
        </div>
    </div>
</form>
