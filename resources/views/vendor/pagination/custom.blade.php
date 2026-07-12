@if ($paginator->hasPages())
    <style>
        /* CSS Custom untuk Pagination ASgor */
        .asgor-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            font-family: inherit;
        }
        .asgor-pagination a, .asgor-pagination span.nav-text {
            text-decoration: none;
            color: #1814F3; /* Warna biru sesuai tema ASgor */
            font-size: 16px;
            font-weight: 500;
        }
        .asgor-pagination span.nav-text.disabled {
            color: #B1B1B1; /* Warna abu-abu saat disabled */
            cursor: not-allowed;
        }
        .asgor-pagination .page-num {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px; /* Bikin kotak melengkung */
            text-decoration: none;
            color: #1814F3;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }
        .asgor-pagination .page-num.active {
            background-color: #1814F3;
            color: white; /* Angka warna putih, background biru untuk yang aktif */
        }
        .asgor-pagination a.page-num:hover {
            background-color: rgba(24, 20, 243, 0.1); /* Efek hover tipis-tipis */
        }
    </style>

    <nav role="navigation" aria-label="Pagination Navigation" class="asgor-pagination">
        
        {{-- Tombol Previous --}}
        @if ($paginator->onFirstPage())
            <span class="nav-text disabled">
                &lt; Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="nav-text">
                &lt; Previous
            </a>
        @endif

        {{-- Deretan Angka (1, 2, 3...) --}}
        @foreach ($elements as $element)
            {{-- Separator Tiga Titik --}}
            @if (is_string($element))
                <span class="page-num">{{ $element }}</span>
            @endif

            {{-- Link Angka --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-num active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-num">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="nav-text">
                Next &gt;
            </a>
        @else
            <span class="nav-text disabled">
                Next &gt;
            </span>
        @endif

    </nav>
@endif