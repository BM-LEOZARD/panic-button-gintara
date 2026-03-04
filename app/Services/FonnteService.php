<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $apiUrl = 'https://api.fonnte.com/send';
    protected string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Kirim pesan WhatsApp melalui Fonnte
     *
     * @param  string
     * @param  string
     * @return array
     */
    public function send(string $target, string $message): array
    {
        if (empty($this->token)) {
            Log::error('[Fonnte] Token tidak dikonfigurasi.');
            return ['status' => false, 'message' => 'Token Fonnte belum dikonfigurasi.'];
        }

        $target = $this->normalizePhone($target);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->apiUrl, [
                'target' => $target,
                'message' => $message,
                'delay' => 2,
                'countryCode' => '62',
            ]);

            $body = $response->json();

            if ($response->ok() && isset($body['status']) && $body['status'] === true) {
                Log::info("[Fonnte] Pesan berhasil dikirim ke {$target}.");
                return ['status' => true, 'message' => 'Pesan berhasil dikirim.', 'data' => $body];
            }

            Log::warning("[Fonnte] Gagal kirim ke {$target}: " . json_encode($body));
            return ['status' => false, 'message' => $body['reason'] ?? 'Gagal mengirim pesan.', 'data' => $body];
        } catch (\Throwable $e) {
            Log::error('[Fonnte] Exception: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Normalisasi nomor HP ke format internasional 62xxx
     */
    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
