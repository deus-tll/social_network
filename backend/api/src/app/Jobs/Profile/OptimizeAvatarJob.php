<?php

namespace App\Jobs\Profile;

use App\Services\Profile\AvatarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function Laravel\Prompts\error;

class OptimizeAvatarJob implements ShouldQueue
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
    public function handle(AvatarService $avatarService): void
    {
        try {
            $avatars = $avatarService->optimizeAvatar($this->userId);

            info('avatars: ', $avatars);
            //socket
        }
        catch (\Exception $e){
            error($e->getMessage());
        }
    }
}
