<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-[#F5F7FB]">

<div class="max-w-7xl mx-auto py-8 px-6">

    {{-- Header Section --}}
    <div class="grid grid-cols-3 gap-6 mb-4">

        <div class="col-span-2 flex items-center justify-between">

            <h2 class="text-[24px] font-semibold text-[#343C6A]">
                Kartu Debit
            </h2>

            <button
                class="text-[17px] font-semibold text-[#343C6A] hover:text-blue-600 transition">

                + Tambah Kartu

            </button>

        </div>

        <div>

            <h2 class="text-[24px] font-semibold text-[#343C6A]">

                My Expense

            </h2>

        </div>

    </div>

    {{-- Card & Chart --}}
    <div class="grid grid-cols-3 gap-6">

        <div class="col-span-2">

            @include('transaction.components.cards')

        </div>

        <div>

            @include('transaction.components.chart')

        </div>

    </div>

    <div class="mt-8">

        @include('transaction.components.table')

    </div>

</div>

</body>

</html>