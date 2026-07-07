<div class="grid grid-cols-2 gap-5">

    @foreach($cards as $card)

    <div
        class="h-[225px] rounded-[24px] overflow-hidden shadow-sm

        {{ $card->theme == 'gradient'
            ? 'bg-gradient-to-br from-[#2B164B] via-[#6C315C] to-[#F56594] text-white'
            : 'bg-white border border-gray-200 text-[#343C6A]'
        }}">

        <div class="px-6 pt-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-[11px] opacity-70">

                        Balance

                    </p>

                    <h3 class="text-[18px] font-bold mt-1">

                        Rp {{ number_format($card->balance,0,',','.') }}

                    </h3>

                </div>

                <div class="w-10 h-8 rounded-md bg-white/80 relative">

                    <div class="absolute left-3 top-0 bottom-0 w-px bg-gray-300"></div>

                    <div class="absolute left-6 top-0 bottom-0 w-px bg-gray-300"></div>

                </div>

            </div>

            <div class="grid grid-cols-2 mt-8">

                <div>

                    <p class="text-[10px] uppercase opacity-70">

                        CARD HOLDER

                    </p>

                    <p class="font-medium mt-1">

                        {{ $card->card_holder }}

                    </p>

                </div>

                <div>

                    <p class="text-[10px] uppercase opacity-70">

                        VALID THRU

                    </p>

                    <p class="font-medium mt-1">

                        {{ $card->expired_date }}

                    </p>

                </div>

            </div>

        </div>

        <div
            class="mt-7 px-6 py-5 flex items-center justify-between

            {{ $card->theme == 'gradient'
                ? 'bg-white/10'
                : 'border-t border-gray-200'
            }}">

            <h3 class="text-xl tracking-[3px]">

                {{ $card->card_number }}

            </h3>

            <div class="flex">

                <div class="w-8 h-8 rounded-full bg-white/60"></div>

                <div class="w-8 h-8 rounded-full bg-white/35 -ml-3"></div>

            </div>

        </div>

    </div>

    @endforeach

</div>