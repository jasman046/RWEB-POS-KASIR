<div class="debit-cards">

    @foreach($cards as $card)

    <div class="debit-card {{ $card->theme == 'gradient' ? 'card-gradient' : 'card-light' }}">

        <div class="card-top">

            <div>

                <p class="card-label">Balance</p>

                <h3 class="card-balance">
                    Rp {{ number_format($card->balance,0,',','.') }}
                </h3>

            </div>

            <div class="card-chip"></div>

        </div>

        <div class="card-middle">

            <div>

                <span class="card-small-title">
                    CARD HOLDER
                </span>

                <p class="card-value">
                    {{ $card->card_holder }}
                </p>

            </div>

            <div>

                <span class="card-small-title">
                    VALID THRU
                </span>

                <p class="card-value">
                    {{ $card->expired_date }}
                </p>

            </div>

        </div>

        <div class="card-footer">

            <span class="card-number">

                {{ $card->card_number }}

            </span>

            <div class="master-card">

                <div class="circle-one"></div>

                <div class="circle-two"></div>

            </div>

        </div>

    </div>

    @endforeach

</div>