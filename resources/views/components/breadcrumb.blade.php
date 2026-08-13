<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">

        <li class="breadcrumb-item">
            <a
                href="{{ route('dashboard') }}"
                class="text-muted"
                style="text-decoration: none;"
            >
                Dashboard
            </a>
        </li>

        <li class="breadcrumb-item active" aria-current="page">
            @if(isset($icon))
                <i class="{{ $icon }}"></i>
            @endif

            {{ $title }}
        </li>

    </ol>
</nav>
