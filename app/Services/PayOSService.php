<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayOSService
{
    private $clientId;
    private $apiKey;
    private $checksumKey;
    private $baseUrl = 'https://api-merchant.payos.vn/v2/payment-requests';

    public function __construct()
    {
        $this->clientId = env('PAYOS_CLIENT_ID');
        $this->apiKey = env('PAYOS_API_KEY');
        $this->checksumKey = env('PAYOS_CHECKSUM_KEY');
    }

    /**
     * Tạo link thanh toán PayOS
     */
    public function createPaymentLink($orderCode, $amount, $description, $returnUrl, $cancelUrl)
    {
        $data = [
            'orderCode' => (int)$orderCode,
            'amount' => (int)$amount,
            'description' => $description,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
        ];

        // Tạo signature
        $signature = $this->createSignature($data);
        $data['signature'] = $signature;

        try {
            Log::info('PayOS API credentials', [
                'clientId' => $this->clientId,
                'apiKey' => substr($this->apiKey, 0, 10) . '...',
                'checksumKey' => substr($this->checksumKey, 0, 10) . '...'
            ]);

            $response = Http::withHeaders([
                'x-client-id' => $this->clientId,
                'x-api-key' => $this->apiKey,
            ])->asJson()->post($this->baseUrl, $data);

            $result = $response->json();

            Log::info('PayOS create payment link', [
                'request' => $data,
                'response' => $result
            ]);

            if ($response->successful() && isset($result['data']['checkoutUrl'])) {
                return [
                    'success' => true,
                    'checkoutUrl' => $result['data']['checkoutUrl'],
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Không thể tạo link thanh toán',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('PayOS create payment error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Lỗi kết nối PayOS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function getPaymentInfo($orderCode)
    {
        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->clientId,
                'x-api-key' => $this->apiKey,
            ])->get('https://api-merchant.payos.vn/v2/payment-requests/' . $orderCode);

            $result = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Không thể lấy thông tin thanh toán'
            ];
        } catch (\Exception $e) {
            Log::error('PayOS get payment info error', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Lỗi kết nối PayOS'
            ];
        }
    }

    /**
     * Tạo chữ ký cho request
     */
    private function createSignature($data)
    {
        $sortedData = [
            'amount' => $data['amount'],
            'cancelUrl' => $data['cancelUrl'],
            'description' => $data['description'],
            'orderCode' => $data['orderCode'],
            'returnUrl' => $data['returnUrl'],
        ];

        ksort($sortedData);
        
        $signatureString = implode('&', array_map(
            function ($key, $value) {
                return $key . '=' . $value;
            },
            array_keys($sortedData),
            $sortedData
        ));

        return hash_hmac('sha256', $signatureString, $this->checksumKey);
    }

    /**
     * Xác thực webhook từ PayOS
     */
    public function verifyWebhookSignature($webhookData, $signature)
    {
        $data = [
            'amount' => $webhookData['amount'],
            'code' => $webhookData['code'],
            'desc' => $webhookData['desc'],
            'orderCode' => $webhookData['orderCode'],
            'success' => $webhookData['success'],
        ];

        ksort($data);
        
        $signatureString = implode('&', array_map(
            function ($key, $value) {
                return $key . '=' . $value;
            },
            array_keys($data),
            $data
        ));

        $expectedSignature = hash_hmac('sha256', $signatureString, $this->checksumKey);

        return hash_equals($expectedSignature, $signature);
    }
}
