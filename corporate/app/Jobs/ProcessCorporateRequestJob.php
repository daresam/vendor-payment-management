<?php
namespace App\Jobs;

use App\Models\Corporate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;

class ProcessCorporateRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->onQueue('corporate_request_queue');
    }

    public function handle()
    {
        $corp_id = $this->data['corp_id'] ?? null;
        $correlation_id = $this->data['correlation_id'] ?? null;
        $reply_to = $this->data['reply_to'] ?? null;

        if (!$corp_id || !$correlation_id || !$reply_to) {
            Log::error('Invalid request data', ['data' => $this->data]);
            return;
        }

        try {
            $corporate = Corporate::findOrFail($corp_id);
            $response = [
                'id' => $corporate->id,
                'name' => $corporate->name,
                'email' => $corporate->email,
                'phone' => $corporate->phone,
                'address' => $corporate->address,
            ];
        } catch (\Exception $e) {
            $response = ['error' => 'Corporate not found'];
        }

        try {
            $channel = app('queue')->connection('rabbitmq')->getConnection()->channel();
            $msg = new AMQPMessage(json_encode($response), [
                'correlation_id' => $correlation_id,
            ]);
            $channel->basic_publish($msg, '', $reply_to);
            $channel->close();
        } catch (\Exception $e) {
            Log::error('Failed to send response', [
                'corp_id' => $corp_id,
                'correlation_id' => $correlation_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}