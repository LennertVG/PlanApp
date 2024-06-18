<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $user;
    public $grade;
    public $class;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task, $user, $grade, $class)
    {
        $this->task = $task;
        $this->user = $user;
        $this->grade = $grade;
        $this->class = $class;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.task-submitted')
                    ->subject('Taak ingediend')
                    ->with([
                        'task' => $this->task,
                        'user' => $this->user,
                        'grade' => $this->grade,
                        'class' => $this->class
                    ]);
    }
}
