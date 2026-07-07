<div class="bg-white rounded-[28px] shadow-sm p-6">

    <div class="flex items-center justify-between mb-6">

        <h2 class="text-2xl font-bold text-[#343C6A]">
            Recent Transaction
        </h2>

        <button class="text-sm text-blue-600 hover:underline">
            View All
        </button>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="text-left text-gray-400 border-b">

                    <th class="pb-4 font-medium">Description</th>

                    <th class="pb-4 font-medium">Transaction ID</th>

                    <th class="pb-4 font-medium">Type</th>

                    <th class="pb-4 font-medium">Card</th>

                    <th class="pb-4 font-medium">Date</th>

                    <th class="pb-4 font-medium text-right">Amount</th>

                    <th class="pb-4 font-medium text-center">Receipt</th>

                </tr>

            </thead>

            <tbody>

                @foreach($transactions as $transaction)

                <tr class="border-b last:border-none hover:bg-gray-50 transition">

                    <td class="py-5 font-semibold text-[#343C6A]">
                        {{ $transaction->description }}
                    </td>

                    <td class="text-gray-500">
                        {{ $transaction->transaction_code }}
                    </td>

                    <td>
                        {{ $transaction->type }}
                    </td>

                    <td>
                        {{ $transaction->card->card_number }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M, h:i A') }}
                    </td>

                    <td class="text-right">

                        @if($transaction->category == 'Income')

                            <span class="font-semibold text-green-500">
                                + Rp {{ number_format($transaction->amount,0,',','.') }}
                            </span>

                        @else

                            <span class="font-semibold text-red-500">
                                - Rp {{ number_format($transaction->amount,0,',','.') }}
                            </span>

                        @endif

                    </td>

                    <td class="text-center">

                        <button class="px-4 py-2 rounded-full border hover:bg-gray-100 transition">

                            Download

                        </button>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>