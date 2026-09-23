<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Client as MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    public function client(): MqttClient
    {
        $settings = (new ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setConnectTimeout((int) config('mqtt.connect_timeout', 3));

        $client = new MqttClient(
            config('mqtt.host', '127.0.0.1'),
            (int) config('mqtt.port', 1883),
            config('mqtt.client_id', 'smart-lighting').'-'.bin2hex(random_bytes(4)),
            MqttClient::MQTT_3_1_1
        );

        $client->connect($settings, true);

        return $client;
    }

    public function publishCommand(string $deviceUid, string $action): bool
    {
        try {
            $client = $this->client();

            $payload = json_encode([
                'action' => $action,
                'device_uid' => $deviceUid,
                'timestamp' => now()->toIso8601String(),
            ], JSON_THROW_ON_ERROR);

            $topic = $this->getTopic($deviceUid, 'command');

            $client->publish($topic, $payload, MqttClient::QOS_AT_LEAST_ONCE, true);
            $client->disconnect();

            Log::info('MQTT command published', [
                'topic' => $topic,
                'action' => $action,
                'device_uid' => $deviceUid,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('MQTT publish failed', [
                'device_uid' => $deviceUid,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function listenStatus(callable $handler): void
    {
        try {
            $client = $this->client();
            $topic = 'smart-home/+/status';

            $client->subscribe($topic, function ($receivedTopic, $message) use ($handler) {
                $handler($receivedTopic, $message->getPayload());
            }, MqttClient::QOS_AT_LEAST_ONCE);

            while (true) {
                $client->loop(true);
                usleep(100000);
            }
        } finally {
            if (isset($client)) {
                $client->disconnect();
            }
        }
    }

    public function getTopic(string $deviceUid, string $type): string
    {
        return 'smart-home/'.$deviceUid.'/'.$type;
    }
}
