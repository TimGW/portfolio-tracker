<?php


namespace App\Remote;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
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
        $request = $this->buildRequestBody($this->allTransactions);

        $responseBody = Http::withOptions([
            'debug' => false
        ])->withHeaders([
            'Content-Type' => 'text/json',
            'X-OPENFIGI-APIKEY' => env('OPEN_FIGI_KEY'),
        ])->withBody(
            json_encode($request[1]),
            'text/json'
        )->post("https://api.openfigi.com/v1/mapping")->getBody();

        $stocks = $this->mapRemoteResponse($request[0], $responseBody);

        $this->saveTransactionData($stocks);

        return $stocks;
    }

    private function buildRequestBody($allTransactions): array
    {
        $requestBody = array();
        $stocks = array();

        foreach ($allTransactions as $transactionsForShare) {
            $volume_of_shares = array_sum(array_column($transactionsForShare, 'quantity'));
            if ($volume_of_shares == 0) continue;

            $isin = $transactionsForShare[0]['isin'];
            $exchCode = $this->getBBExchangeCode($transactionsForShare[0]['exchange']);
            $ps_avg_price_purchased = round(array_sum(array_column($transactionsForShare, 'closing_rate')) / count($transactionsForShare), 2);
            $total_service_fee = round(array_sum(array_column($transactionsForShare, 'service_fee')), 2);
            $currency = $transactionsForShare[0]['currency'];

            if (!empty($exchCode)) {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin", 'exchCode' => $exchCode);
            } else {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin");
            }

            $stock = new Stock;
            $stock->setIsin($isin);
            $stock->setExchange($exchCode);
            $stock->setVolumeOfShares($volume_of_shares);
            $stock->setPsAvgPricePurchased($ps_avg_price_purchased);
            $stock->setServiceFees($total_service_fee);
            $stock->setCurrency($currency);
            $stocks[] = $stock;
        }

        return array($stocks, $requestBody);
    }

    private function mapRemoteResponse($stocks, $responseBody): array
    {
        $responseDataSet = array_column(json_decode($responseBody, true), 'data');

        for ($i = 0; $i < count($responseDataSet); $i++) {
            $remoteTickerObjectData = $responseDataSet[$i][0];
            $appendix = $this->getAppendix($remoteTickerObjectData['exchCode']);

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
            $stocks[$i]->setStockTicker($tickerSymbol);
        }

        return (array_filter($stocks, function ($value) {
            return !empty($value->getStockTicker());
        }));
    }

    private function saveTransactionData($stocks)
    {
        foreach ($stocks as $stock) {
            Stock::updateOrCreate(
                [
                    'stock_ticker' => $stock->getStockTicker(),
                    'user_id' => Auth::id()
                ],
                [
                    'isin' => $stock->getIsin(),
                    'exchange' => $stock->getExchange(),
                    'volume_of_shares' => $stock->getVolumeOfShares(),
                    'ps_avg_price_purchased' => $stock->getPsAvgPricePurchased(),
                    'service_fees' => $stock->getServiceFees(),
                    'currency' => $stock->getCurrency()
                ]
            );
        }
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
            case "NA":
                return "AS";
            case "FP":
                return "PA";
            case "GY":
                return "DE";

            default:
                return "";
        }
    }
}
