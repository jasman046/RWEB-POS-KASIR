<div class="transaction-table-wrapper">

    {{-- TAMBAHKAN BLOK STYLE INI DI SINI --}}
    <style>
        /* Paksa rata kanan & tengah dari dalam file Blade (Bypass Cache/Vite) */
        table.transaction-table th.amount-column,
        table.transaction-table td.amount-column {
            text-align: right !important;
        }
        table.transaction-table th.receipt-column,
        table.transaction-table td.receipt-column {
            text-align: center !important;
        }
    </style>
    {{-- =================================== --}}

    <div class="transaction-table-header">
        <h2>Recent Transactions</h2>
        <div class="transaction-tabs">
            <div class="transaction-tabs">
                <a href="{{ route('transactions.index') }}" class="tab {{ empty($category) ? 'active' : '' }}">
                    All Transactions
                </a>
                <a href="{{ route('transactions.index', ['category' => 'Income']) }}" class="tab {{ $category == 'Income' ? 'active' : '' }}">
                    Income
                </a>
                <a href="{{ route('transactions.index', ['category' => 'Expense']) }}" class="tab {{ $category == 'Expense' ? 'active' : '' }}">
                    Expense
                </a>
            </div>
        </div>
    </div>

    <div class="transaction-table-card">
        <table class="transaction-table">
            <colgroup>
                <col style="width:25%">
                <col style="width:15%">
                <col style="width:13%">
                <col style="width:10%">
                <col style="width:14%">
                <col style="width:11%">
                <col style="width:12%">
            </colgroup>

            <!-- BAGIAN JUDUL TABEL (HEADER) -->
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Transaction ID</th>
                    <th>Type</th>
                    <th>Card</th>
                    <th>Date</th>
                    <!-- Class dipasang di th -->
                    <th class="amount-column">Amount</th>
                    <th class="receipt-column">Receipt</th>
                </tr>
            </thead>

            <!-- BAGIAN ISI DATA TABEL -->
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>
                        <div class="description-cell">
                            <div class="transaction-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <span>
                                {{ $transaction->description }}
                            </span>
                        </div>
                    </td>

                    <td>
                        {{ $transaction->transaction_code }}
                    </td>

                    <td>
                        <span class="badge-type">
                            {{ $transaction->type }}
                        </span>
                    </td>

                    <td>
                        **** {{ substr($transaction->card->card_number,-4) }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                    </td>

                    <!-- Class yang sama dipasang di td -->
                    <td class="amount-column">
                        <span class="{{ $transaction->category == 'Income' ? 'amount-income' : 'amount-expense' }}">
                            {{ $transaction->category == 'Income' ? '+' : '-' }}
                            Rp {{ number_format($transaction->amount,0,',','.') }}
                        </span>
                    </td>

                    <!-- Class yang sama dipasang di td -->
                    <td class="receipt-column">
                        <a href="{{ route('transactions.download',$transaction) }}" class="receipt-btn">
                            Download
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-table">
                        Belum ada transaksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>