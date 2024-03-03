<?php

namespace App\Jobs\Profile;

use App\Services\Profile\AvatarService;
use App\Services\Socket\SocketService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function Laravel\Prompts\error;

class GenerateInitialAvatarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected readonly int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }


    /**
     * Execute the job.
     */
    public function handle(
        AvatarService $avatarService,
        SocketService $socketService): void
    {
        try {
            $avatars = $avatarService->generateAvatar($this->userId);

            $socketService->emit($this->userId, 'avatars.stored', $avatars);
        }
        catch (Exception $e){
            error($e->getMessage());
        }
    }
}
