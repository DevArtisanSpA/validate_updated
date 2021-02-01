<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentsLoadEmployeeBase extends Mailable
{
    use Queueable, SerializesModels;
    public $service;
    public $employees;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service,$employees)
    {
        $this->service=$service;
        $this->employees=$employees;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Carga de archivos bases de los empleados para el servicio " . strtoupper($this->service->description))
        ->view('emails.load_documents_employee_base');
    }
}
