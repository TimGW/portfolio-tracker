<?php


namespace App\Remote;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class SymbolRepository
{
    private $allTransactions;

    public function __construct($allTransactions)
    {
        $this->allTransactions = $allTransactions;
    }

    public function getStocksWithSymbols(): array
    {
        $request = $this->buildRequest();

        $responseBody = Http::withOptions([
            'debug' => false
        ])->withHeaders([
            'Content-Type' => 'text/json',
            'X-OPENFIGI-APIKEY' => env('OPEN_FIGI_KEY'),
        ])->withBody(
            json_encode($request[1]),
            'text/json'
        )->post("https://api.openfigi.com/v1/mapping")->getBody();

        return $this->mapRemoteResponse($request[0], $responseBody);
    }

    private function buildRequest(): array
    {
        $requestBody = array();
        $stocks = array();

        foreach ($this->allTransactions as $isinTransactions) {
            // $isinTransactions are grouped transactions by isin and exchange

            foreach ($isinTransactions as $exchangeTransactions) {
                // $exchangeTransactions are all transactions for an exchange

                $first = $exchangeTransactions[0];
                $volume_of_shares = $exchangeTransactions->sum('quantity');
                if ($volume_of_shares === 0) continue;

                $exchCode = $this->getBBExchangeCode($first->exchange);
                $ps_avg_price_purchased = round($exchangeTransactions->sum('closing_rate') / count($exchangeTransactions), 2);
                $total_service_fee = round($exchangeTransactions->sum('service_fee'), 2);

                if (!empty($exchCode)) {
                    $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$first->isin", 'exchCode' => $exchCode);
                } else {
                    $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$first->isin");
                }

                $stock = new Stock;
                $stock->isin = $first->isin;
                $stock->exchange = $first->exchange;
                $stock->volume_of_shares = $volume_of_shares;
                $stock->ps_avg_price_purchased = $ps_avg_price_purchased;
                $stock->service_fees = $total_service_fee;
                $stock->currency = $first->currency;
                $stocks[] = $stock;
            }
        }

        return array($stocks, $requestBody);
    }

    private function mapRemoteResponse($stocks, $responseBody): array
    {
        $responseDataSet = array_column(json_decode($responseBody, true), 'data');

        for ($i = 0; $i < count($responseDataSet); $i++) {
            $remoteTickerObjectData = $responseDataSet[$i][0];
            $appendix = $this->getAppendix($stocks[$i]->exchange);
            $tickerSymbol = "";

            // skip adding ETF's to the ticker list
            if (!strcasecmp($remoteTickerObjectData['securityType'], "Common Stock")) {
                if (!empty($appendix)) {
                    $tickerSymbol = $remoteTickerObjectData['ticker'] . "." . $appendix;
                } else {
                    $tickerSymbol = $remoteTickerObjectData['ticker'];
                }
            }

            // map the imported transactions to a symbol
            $stocks[$i]->symbol = $tickerSymbol;
        }

        return (array_filter($stocks, function ($value) {
            return !empty($value->symbol);
        }));
    }

    private function getBBExchangeCode($giro_exchange): string
    {
        switch (strtoupper($giro_exchange)) {
            case "EAM":
                return "NA";
            case "EPA":
                return "FP";
            case "XET":
                return "GY";
            case "NSY":
            case "NDQ":
                return "US";
            default:
                return "";
        }
    }

    private function getAppendix($exchange): string
    {
        switch (strtoupper($exchange)) {
            case "EAM":
                return "AS";
            case "EPA":
                return "PA";
            case "XET":
                return "DE";
            default:
                return "";
        }
    }
}
