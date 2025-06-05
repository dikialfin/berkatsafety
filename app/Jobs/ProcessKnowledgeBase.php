<?php

namespace App\Jobs;

use AdminNotificationEvent;
use App\Models\KnowledgeBase;
use App\Models\User;
use App\Traits\AdminNotificationHelper;
use App\Traits\KnowledgeBaseHelper;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessKnowledgeBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use AdminNotificationHelper;
    use KnowledgeBaseHelper;
    public $timeout = 0;
    protected KnowledgeBase $kb;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(KnowledgeBase $kb)
    {
        //
        $this->kb = $kb;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('id', $this->kb->user_id)->first();
        try {
            //
            Log::info("[ProcessKnowledgeBase] Process Knowledge Base start");
            // sleep(1);
            $this->sendToProcessor($this->kb);
            Log::info("[ProcessKnowledgeBase] Let's say the process is finished");
            $this->sendNotification("Document Finished Processing", "{$this->kb->name} has been finished processing", $user);
        } catch (Exception $e) {
            Log::error("[ProcessKnowledgeBase] {$this->kb->name} processing has been failed : {$e}");
            $this->kb->doc_status = 'FAILED';
            $this->kb->update();
            $this->sendNotification("Document Processing Failed", "{$this->kb->name} processing has been failed", $user);
        }
    }
}
