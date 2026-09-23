<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Services\MqttService;
use Illuminate\Console\Command;

class MqttReceiveCommand extends Command
{
    protected $signature = 'mqtt:listen';

    protected $description = 'Listen for MQTT status updates from ESP32 devices';

    public function __construct(private MqttService $mqttService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Listening for MQTT status updates. Press Ctrl+C to stop.');

        while (true) {
            try {
                $this->mqttService->listenStatus(function (string $topic, string $payload) {
                    $data = json_decode($payload, true);

                    if (! is_array($data)) {
                        return;
                    }

                    Device::where('device_uid', $data['device_uid'] ?? null)
                        ->whereNotNull('device_uid')
                        ->update([
                            'status' => $data['online'] ?? false,
                            'current_state' => $data['state'] ?? false,
                            'last_seen_at' => now(),
                        ]);

                    $this->info("Updated status for {$data['device_uid']} from $topic");
                });
            } catch (\Throwable $e) {
                $this->error("MQTT receive error: {$e->getMessage()}");
                sleep(5);
            }
        }
    }
}
