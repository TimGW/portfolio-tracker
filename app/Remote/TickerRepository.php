<?php


namespace App\Remote;


use Illuminate\Support\Facades\Http;

class TickerRepository
{

    private $allTransactions;

    public function __construct($allTransactions)
    {
        $this->allTransactions = $allTransactions;
    }

    public function getTickerList(): string
    {
        $responseBody = $this->getRemoteTickers($this->allTransactions)->getBody();
        $tickers = $this->mapRemoteResponse($responseBody);
        return implode(',', array_filter($tickers));
    }

    private function buildRequestBody($allTransactions): array
    {
        $requestBody = array();
        foreach ($allTransactions as $data) {
            $exchCode = $this->getBBExchangeCode($data[0]['exchange']);
            $isin = $data[0]['isin'];

            if (!empty($exchCode)) {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin", 'exchCode' => $exchCode);
            } else {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin");
            }
        }
        return $requestBody;
    }

    private function getRemoteTickers($allTransactions): \Illuminate\Http\Client\Response
    {
        return Http::withOptions([
            'debug' => false
        ])->withHeaders([
            'Content-Type' => 'text/json',
            'X-OPENFIGI-APIKEY' => env('OPEN_FIGI_KEY'),
        ])->withBody(
            json_encode($this->buildRequestBody($allTransactions)),
            'text/json'
        )->post("https://api.openfigi.com/v1/mapping");
    }

    private function mapRemoteResponse($responseBody): array
    {
        $result = array();
        $responseDataSet = array_column(json_decode($responseBody, true), 'data');

        foreach ($responseDataSet as $data) {
            $remoteTickerObjectData = $data[0];

            if (!$this->isCommonStock($remoteTickerObjectData['securityType'])) {
                $appendix = $this->getAppendix($remoteTickerObjectData['exchCode']);

                if (!empty($appendix)) {
                    $result[] = $remoteTickerObjectData['ticker'] . "." . $appendix;
                } else {
                    $result[] = $remoteTickerObjectData['ticker'];
                }
            }
        }

        return $result;
    }

    private function getBBExchangeCode($giro_exchange): string
    {
        switch (strtoupper($giro_exchange)) {
            case "EAM":
                return "NA";
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
            case "US":
            default:
                return "";
        }
    }

    private function isCommonStock($value)
    {
        return strcasecmp($value, "Common Stock");
    }
}
