@if ($errors->any())
    <div {{ $attributes }}>
        <div class="text-sm font-bold space-y-1">
            @foreach ($errors->all() as $error)
                <div>
                    @if ($error === 'These credentials do not match our records.')
                        Email Atau Password salah
                    @else
                        {{ $error }}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
