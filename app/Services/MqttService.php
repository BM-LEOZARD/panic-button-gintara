<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MqttService
{
  protected string $host;
  protected int $port;
  protected string $clientIdPrefix;
  protected string $topic;

  public function __construct()
  {
    $this->host = config('mqtt.host', 'broker.emqx.io');
    $this->port = config('mqtt.port', 1883);
    $this->clientIdPrefix = config('mqtt.client_id_prefix', 'panicbutton_laravel_');
    $this->topic = config('mqtt.topic', 'panic/button');
  }

  public function publish(array $payload): bool
  {
    $json = json_encode($payload);
    $clientId = $this->clientIdPrefix . uniqid();

    try {
      $socket = fsockopen($this->host, $this->port, $errno, $errstr, 5);

      if (!$socket) {
        Log::error("[MQTT] Gagal connect ke {$this->host}:{$this->port} — {$errstr} ({$errno})");
        return false;
      }

      stream_set_timeout($socket, 5);

      $connectPacket = $this->buildConnect($clientId);
      fwrite($socket, $connectPacket);

      $connack = fread($socket, 4);
      if (!$connack || ord($connack[0]) !== 0x20 || ord($connack[3]) !== 0x00) {
        Log::error('[MQTT] CONNACK gagal atau return code bukan 0');
        fclose($socket);
        return false;
      }

      $publishPacket = $this->buildPublish($this->topic, $json);
      fwrite($socket, $publishPacket);

      fwrite($socket, chr(0xE0) . chr(0x00));
      fclose($socket);

      Log::info("[MQTT] Published ke topic [{$this->topic}]: {$json}");
      return true;
    } catch (\Throwable $e) {
      Log::error('[MQTT] Exception: ' . $e->getMessage());
      return false;
    }
  }

  // ────────────────────────────────────────────────────────────────
  //  Helper: build raw MQTT v3.1.1 packets
  // ────────────────────────────────────────────────────────────────

  private function buildConnect(string $clientId): string
  {
    $protocol = 'MQTT';
    $protoLevel = 0x04;
    $connectFlags = 0x02;

    $payload  = $this->encodeString($protocol);
    $payload .= chr($protoLevel);
    $payload .= chr($connectFlags);
    $payload .= chr(0x00) . chr(0x3C);
    $payload .= $this->encodeString($clientId);

    return chr(0x10) . $this->encodeRemainingLength(strlen($payload)) . $payload;
  }

  private function buildPublish(string $topic, string $message): string
  {
    $payload  = $this->encodeString($topic);
    $payload .= $message;

    return chr(0x30) . $this->encodeRemainingLength(strlen($payload)) . $payload;
  }

  private function encodeString(string $str): string
  {
    $len = strlen($str);
    return chr($len >> 8) . chr($len & 0xFF) . $str;
  }

  private function encodeRemainingLength(int $len): string
  {
    $output = '';
    do {
      $byte = $len % 128;
      $len  = intdiv($len, 128);
      if ($len > 0) {
        $byte |= 0x80;
      }
      $output .= chr($byte);
    } while ($len > 0);
    return $output;
  }
}
