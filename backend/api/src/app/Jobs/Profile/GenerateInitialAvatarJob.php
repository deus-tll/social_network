<?php

namespace App\Jobs\Profile;

use App\Services\Profile\AvatarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInitialAvatarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected readonly int $user_id;

    /**
     * Create a new job instance.
     */
    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }


    /**
     * Execute the job.
     */
    public function handle(AvatarService $avatarService): void
    {
        $avatarService->generateAvatar($this->user_id);
    }
}
