                <h1>ČESTITAMO! Uspešno ste izvršili poručivanje.</h1>

                <p>Obaveštavamo Vas da ste kreirali porudžbenicu (Broj online porudžbenice: <strong>{!! $order->number !!}</strong>), dana {!! $order->date!!}</p>

                <p>Da bi ste uspešno završili kupovinu, potrebno je da uplatite novac u najbližoj banci, pošti ili putem Vašeg e-banking naloga.
                    Požurite, ovim ste samo rezervisali kupovinu, tek nakon uplate ostvarujete pravo na ovu robu.
                    Rok za uplatu je {{(int)config('gumamax.bank_transfer_waiting_period') * 24}}h od trenutka kreiranja porudžbenice.</p>

                <h2>Koje informacije su Vam potrebne da biste izvršili uplatu?</h2>

                <ol>
                    <li>Primalac: <strong>{!! $order->merchant->name !!}</strong></li>
                    <li>Tekući račun: <strong> {!! config('gumamax.default_bank_account_number') !!}</strong></li>
                    <li>Ukupno za uplatu: <strong>{!! $order->amount_with_tax + $order->shipping_amount_with_tax !!} RSD</strong></li>
                    <li>Poziv na broj: <strong>{!! config('gumamax.partner_id_internet_prodaja', '')!!}-{!! $order->number!!}</strong></li>
                </ol>


                Instrukcije za uplatu smo Vam poslali putem email-a.
                Takođe, instrukcije možete u svakom trenutku da nađete u okviru <a href="{{URL::to('/profile')}}">Vašeg naloga</a>, na sledeći način:
                <ol>
                    <li>Ulogujte se u svoj nalog na Gumamax sajtu</li>
                    <li>Kliknite na svoje ime u gornjem desnom uglu</li>
                    <li>Kliknite na “Porudžbine”</li>
                    <li>Kliknite na “Detalji” pored ove porudžbine i naći ćete precizne instrukcije kako da popunite uplatnicu</li>
                </ol>

                <h2>Izgled popunjene uplatnice</h2>
                <img src="{!! url('img/pay-order/'.$order->id.'.png') !!}" alt="Uplatnica">

