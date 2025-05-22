<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FetchCorporateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $corpId;
    protected $correlationId;
    protected $replyQueue;

    public function __construct($corpId)
    {
        $this->corpId = $corpId;
        $this->correlationId = Str::uuid()->toString();
        $this->replyQueue = 'reply_' . $this->correlationId;
    }

    public function handle()
    {
        $channel = $this->getChannel();
        
        // Declare the exchange
        $exchange = config('queue.connections.rabbitmq.options.exchange.name', 'corporate_exchange');
        $channel->exchange_declare($exchange, 'direct', true, false, false);
        
        // Declare and bind the queue
        $queue = config('queue.connections.rabbitmq.queue', 'corporate_request_queue');
        $channel->queue_declare($queue, false, true, false, false);
        $channel->queue_bind($queue, $exchange, $queue);

        // Declare the reply queue
        $channel->queue_declare($this->replyQueue, false, false, true, false);

        $message = new \PhpAmqpLib\Message\AMQPMessage(json_encode(['corp_id' => $this->corpId]), [
            'correlation_id' => $this->correlationId,
            'reply_to' => $this->replyQueue,
        ]);

        $channel->basic_publish($message, $exchange, $queue);

        $response = null;
        $callback = function ($msg) use (&$response) {
            if ($msg->get('correlation_id') === $this->correlationId) {
                $response = json_decode($msg->body, true);
            }
        };

        $channel->basic_consume($this->replyQueue, '', false, true, false, false, $callback);

        $startTime = time();
        $timeout = config('queue.connections.rabbitmq.options.timeout', 10);
        while (!$response && (time() - $startTime) < $timeout) {
            try {
                $channel->wait(null, false, $timeout);
            } catch (\Exception $e) {
                Log::error('RabbitMQ wait error', ['error' => $e->getMessage()]);
                break;
            }
        }

        $channel->queue_delete($this->replyQueue);
        $channel->close();

        if (!$response) {
            throw new \Exception('Corporate Service unavailable or timed out', 500);
        }

        if (isset($response['error'])) {
            throw new \Exception($response['error'], 404);
        }

        Cache::put("corporate_{$this->corpId}", $response, 3600);
        return $response;
    }

    protected function getChannel()
    {
        $connection = app('queue')->connection('rabbitmq')->getConnection();
        return $connection->channel();
    }
}