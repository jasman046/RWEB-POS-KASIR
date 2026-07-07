@php

$colors = [
    'blue',
    'yellow',
    'red',
    'blue',
    'yellow',
    'blue'
];

$maxExpense = collect($chartData)->max('total');

@endphp

<div class="expense-card">

    <div class="expense-chart">

        @foreach($chartData as $item)

            @php

                if($maxExpense > 0){

                    $height = max(
                        ($item['total'] / $maxExpense) * 130,
                        18
                    );

                }else{

                    $height = 12;

                }

            @endphp

            <div class="expense-bar-item">

                <div
                    class="expense-bar {{ $colors[$loop->index] }}"
                    style="height: {{ $height }}px;">
                </div>

                <span>{{ $item['label'] }}</span>

            </div>

        @endforeach

    </div>

</div>