<?php

namespace App\Service\Exchange;

use App\Entity\Wallet;
use App\Repository\PriceRepository;
use App\Service\Utils;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinbaseOauth
{
    private string $encryptionKey;
    private string $initializationVector;
    private Wallet $wallet;
    private HttpClientInterface $httpClientInterface;
    private PriceRepository $priceRepository;

    public function __construct(string $encryptionKey, string $initializationVector, Wallet $wallet, HttpClientInterface $httpClientInterface, $priceRepository)
    {
        $this->encryptionKey = $encryptionKey;
        $this->initializationVector = $initializationVector;
        $this->wallet = $wallet;
        $this->httpClientInterface = $httpClientInterface;
        $this->priceRepository = $priceRepository;
    }

    public function getBalance($refresh = false, $code = '', string $oauthcoinbaseKey, string $oauthcoinbaseSecret)
    {
        // Refresh access token for update
        if ($refresh) {
            $refresh_token = Utils::decrypt($this->encryptionKey, $this->initializationVector, $this->wallet->getApiKey());
            $rep = $this->httpClientInterface->request(
                'POST',
                "https://api.coinbase.com/oauth/token?grant_type=refresh_token&client_id=$oauthcoinbaseKey&client_secret=$oauthcoinbaseSecret&refresh_token=$refresh_token&redirect_uri=https://127.0.0.1:8000/en/wallet"
            );
        } else {
            // Get first access token
            $rep = $this->httpClientInterface->request(
                'POST',
                "https://api.coinbase.com/oauth/token?grant_type=authorization_code&code=$code&client_id=$oauthcoinbaseKey&client_secret=$oauthcoinbaseSecret&redirect_uri=https://127.0.0.1:8000/en/wallet"
            );
            $cont = json_decode($rep->getContent(), true);
            $this->wallet->setApiKey(Utils::encrypt($this->encryptionKey, $this->initializationVector, $cont["refresh_token"]));
        }

        $cont = json_decode($rep->getContent(), true);
        $this->wallet->setApiKey(Utils::encrypt($this->encryptionKey, $this->initializationVector, $cont["refresh_token"]));
        $bearer = $cont["access_token"];
        $coinbase = [];
        $coins = [];
        $next = '';
        do {
            $coinbase = Utils::curlRequest("https://api.coinbase.com/v2/accounts?limit=100&order=asc&next_uri=$next", ["Authorization: Bearer $bearer", "CB-VERSION: 2015-04-08"]);
            $next = $coinbase['pagination']['next_uri'];
            foreach ($coinbase['data'] as $currency) {
                if ($currency['balance']['amount'] > 0.000) {
                    $price = $this->priceRepository->findBySymbol(strtolower($currency['balance']['currency']));
                    $price != null ? $value = number_format($price->getPrice() * $currency['balance']['amount'], 2, '.', ',') : $value = 0;
                    array_push($coins, array(
                        'symbol' => $currency['balance']['currency'],
                        'quantity' => $currency['balance']['amount'],
                        'value' => $value,
                    ));
                }
            }
        } while ($coinbase['pagination']['next_uri'] != null);
        return Utils::encrypt(
            $this->encryptionKey,
            $this->initializationVector,
            $coins
        );
    }
}
