<?php

namespace App\Jobs;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail; 
use App\Mail\MailNotify; 

class SendMail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;   // Chứa message của mail
    protected $users;  // Chứa thông tin người dùng - chứa email mà mình gửi đến

    /**
     * Create a new job instance.
     */
    public function __construct($data, $users)
    {
        $this->data = $data;   // Chứa message của mail
        $this->users = $users;  // Chứa thông tin người dùng - chứa email mà mình gửi đến
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->users)->send(new MailNotify($this->data));
    }			
}
