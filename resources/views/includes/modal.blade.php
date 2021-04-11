<div class="modal fade" id="modal-responsive{{$stock->id}}" tabindex="-1" aria-labelledby="companyName"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header border-0">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="card mr-3" style="width: 3rem;">
                            <img class="card-img" src="{{ $stock->firstProfile()->image }}">
                        </div>

                        <h4 id="companyName" class="modal-title mr-2">{{ $stock->firstProfile()->companyName }}
                            <small class="text-muted"> [{{ $stock->firstProfile()->symbol }}]</small>
                        </h4>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <ul class="nav nav-tabs pl-3 mt-3" role="tablist">
                <li role="presentation" class="nav-item">
                    <a class="nav-link active" href="#stockTab{{$stock->id}}" aria-controls="stockTab{{$stock->id}}"
                       role="tab" data-toggle="tab">Aandeel</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a class="nav-link" href="#transactionsTab{{$stock->id}}"
                       aria-controls="transactionsTab{{$stock->id}}" role="tab" data-toggle="tab">Transacties</a>
                </li>
            </ul>

            <div class="modal-body">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="stockTab{{$stock->id}}">
                        <div class="row mx-auto mt-1">
                            <h5><span
                                    class="badge badge-info text-white mr-1">{{ $stock->firstProfile()->sector }}</span>
                            </h5>
                            <h5><span
                                    class="badge badge-info text-white mr-1">{{ $stock->firstProfile()->currency }}</span>
                            </h5>
                            <h5><span
                                    class="badge badge-info text-white mr-1">{{ $stock->firstProfile()->country }}</span>
                            </h5>
                            <h5><span
                                    class="badge badge-info text-white">{{ $stock->firstProfile()->exchangeShortName }}</span>
                            </h5>
                        </div>

                        <h5 class="mt-3">Investering</h5>
                        <table class="table table-hover responsive nowrap w-100">
                            <tr>
                                <td>Aantal aandelen</td>
                                <td class="text-right">{{ number_format($stock->volume_of_shares, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Gemiddelde aankooppijs (GAK)</td>
                                <td class="text-right">€{{ number_format($stock->ps_avg_price_purchased, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Huidig aandeelprijs<br>
                                    <small class="text-muted">Laatst
                                        geüpdatet: {{ \Carbon\Carbon::parse($stock->firstProfile()->updated_at)->diffForhumans() }}</small>
                                </td>
                                <td class="text-right">
                                    €{{ number_format($stock->firstProfile()->price, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Totaal geïnvesteerd</td>
                                <td class="text-right">€{{ number_format($stock->stock_invested, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Gemaakte transactiekosten</td>
                                <td class="text-right">- €{{ number_format($stock->service_fees, 2, ',', '.') }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-5">Waardering</h5>
                        <table class="table table-hover responsive nowrap w-100">
                            <tr>
                                <td>Huidige waarde</td>
                                <td class="text-right">
                                    €{{ number_format($stock->stock_current_value, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Gewicht in portfolio</td>
                                <td class="text-right">
                                    <progress value="{{$stock->stock_weight}}" max="100"></progress>
                                    &nbsp;{{ number_format($stock->stock_weight, 2, ',', '.') }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Winst / verlies</td>
                                @if($stock->ps_profit_percentage > 0)
                                    <td class="text-right">€{{ $stock->ps_profit }} &nbsp;&nbsp;
                                        ↑ {{ number_format($stock->ps_profit_percentage, 2, ',', '.') }}%
                                    </td>
                                @else
                                    <td class="text-right">€{{  number_format($stock->ps_profit, 2, ',', '.') }} &nbsp;&nbsp;
                                        ↓ {{ number_format($stock->ps_profit_percentage, 2, ',', '.') }}%
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>Laagst / hoogst</td>
                                <td class="text-right">€{{ $stock->firstProfile()->range }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-5">Profiel</h5>
                        <table class="table table-hover responsive nowrap w-100">
                            <tr>
                                <td>Exchange</td>
                                <td class="text-right">{{ $stock->firstProfile()->exchange }}</td>
                            </tr>
                            <tr>
                                <td>Market cap</td>
                                <td class="text-right">
                                    €{{ number_format($stock->firstProfile()->mktCap, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Industrie</td>
                                <td class="text-right">{{ $stock->firstProfile()->industry }}</td>
                            </tr>
                            <tr>
                                <td width="50%">Aantal werknemers</td>
                                <td class="text-right">{{ number_format($stock->firstProfile()->fullTimeEmployees, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>CEO</td>
                                <td class="text-right">{{ $stock->firstProfile()->ceo }}</td>
                            </tr>
                            <tr>
                                <td>IPO datum</td>
                                <td class="text-right">{{ \Carbon\Carbon::parse($stock->firstProfile()->ipoDate)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Website</td>
                                <td class="text-right">
                                    <a target="_blank"
                                       href="{{ $stock->firstProfile()->website }}">{{ $stock->firstProfile()->website }}</a>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="transactionsTab{{$stock->id}}">

                        <table class="table table-hover responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>Aantal</th>
                                <th>Prijs</th>
                                <th>Servicekosten</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($stock->transactions() as $transaction)
                                <tr>
                                    <td>{{ $transaction->quantity }}</td>
                                    <td>
                                        €{{ number_format($transaction->closing_rate, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        €{{ number_format($transaction->service_fee, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>Geen data beschikbaar</tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button id="cancelBtnId" type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#modal-responsive{{$stock->id}}').on('hidden.bs.modal', function () {
            $(this).find('.nav-tabs a:first').tab('show');
            $('#cancelBtnId').click();
        });
    </script>
@endpush
