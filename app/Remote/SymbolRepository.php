<?php


namespace App\Remote;

use Illuminate\Support\Facades\Http;

class SymbolRepository
{
    private $transactionRepo;
    private $allTransactions;

    public function __construct($transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
        $this->allTransactions = $transactionRepo->getTransactionByIsinAndExch();
    }

    public function buildSymbols(): array
    {
        $request = $this->buildRequest();

        $responseBody = Http::withOptions([
            'debug' => false
        ])->withHeaders([
            'Content-Type' => 'text/json',
            'X-OPENFIGI-APIKEY' => env('OPEN_FIGI_KEY'),
        ])->withBody(
            json_encode(array_column($request, 'requestBody')),
            'text/json'
        )->post("https://api.openfigi.com/v1/mapping")->getBody();

        return $this->mapRemoteResponse($request, $responseBody);
    }

    private function buildRequest(): array
    {
        $symbols = array();
        foreach ($this->allTransactions as $isinTransactions) {
            // $isinTransactions are grouped transactions by isin and exchange

            foreach ($isinTransactions as $key => $exchTransactions) {
                // $exchangeTransactions are all transactions for an exchange

                if ($exchTransactions->sum('quantity') === 0) continue; // skip fully sold stocks

                $first = $exchTransactions[0];
                if (!empty($first->exchange)) {
                    $requestBody = array('idType' => "ID_ISIN", 'idValue' => "$first->isin", 'exchCode' => $first->exchange);
                } else {
                    $requestBody = array('idType' => "ID_ISIN", 'idValue' => "$first->isin");
                }

                $symbol = array();
                $symbol['isin'] = $first->isin;
                $symbol['exchange'] = $first->exchange;
                $symbol['requestBody'] = $requestBody;
                $symbols[] = $symbol;
            }
        }

        return $symbols;
    }

    private function mapRemoteResponse($symbols, $responseBody): array
    {
        $responseDataSet = array_column(json_decode($responseBody, true), 'data');

        for ($i = 0; $i < count($responseDataSet); $i++) {
            $remoteTickerObjectData = $responseDataSet[$i][0]; // assuming first one is correct

            $appendix = $this->getSymbolAppendix($remoteTickerObjectData['exchCode']);
            $symbol = "";

            // skip adding ETF's to the ticker list
            if (!strcasecmp($remoteTickerObjectData['securityType'], "Common Stock")) {
                if (!empty($appendix)) {
                    $symbol = $remoteTickerObjectData['ticker'] . "." . $appendix;
                } else {
                    $symbol = $remoteTickerObjectData['ticker'];
                }
            }

            $symbols[$i]['symbol'] = $symbol;
        }

        $result = (array_filter($symbols, function ($symbol) {
            return !empty($symbol['symbol']);
        }));

        $this->transactionRepo->saveTransactions($result);

        return array_column($result, 'symbol');
    }

    private function getSymbolAppendix($exchCode): string
    {
        switch (strtoupper($exchCode)) {
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
