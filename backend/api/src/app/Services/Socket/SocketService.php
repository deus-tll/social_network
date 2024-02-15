<?php

namespace App\Services\Socket;
use Exception;
use Redis;
use RedisException;
use SocketIO\Emitter;
use function Laravel\Prompts\error;

class SocketService
{
    protected Redis $redis;
    protected Emitter $emitter;

    public function __construct()
    {
        try {
            $this->redis = new Redis();
            $this->redis->connect(env('REDIS_SOCKET_HOST', 'db.redis.socket.connections'), env('REDIS_SOCKET_PORT', 6379));
            $this->emitter = new Emitter($this->redis);
        }
        catch (Exception $e){
            error($e->getMessage());
        }
    }

    public function emit($userId, string $messageName, mixed $messageBody): void
    {
        $this->emitter->to('userId_' . $userId)->emit($messageName, json_encode($messageBody));
    }
}
