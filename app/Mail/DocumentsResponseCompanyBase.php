<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentsResponseCompanyBase extends Mailable
{
    use Queueable, SerializesModels;
    public $service;
    public $documents;
    public $wrong;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service,$documents,$wrong)
    {
        $this->service = $service;
        $this->documents=$documents;
        $this->wrong=$wrong;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Actualización de estados de los documentos base del servicio " . strtoupper($this->service->description))
        ->view('emails.response_documents_company_base');
    }
}
