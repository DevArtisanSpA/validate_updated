<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentsResponseEmployeeMonthly extends Mailable
{
    use Queueable, SerializesModels;
    public $service;
    public $employees;
    public $wrong;
    public $periodo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service, $employees, $periodo=null,$wrong)
    {
        $this->service = $service;
        $this->employees = $employees;
        $this->wrong = $wrong;
        $this->periodo=$periodo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Actualización de estados de los
         documentos monthly de los empleados del servicio " .
            strtoupper($this->service->description))
            ->view('emails.response_documents_employee_monthly');
    }
}
