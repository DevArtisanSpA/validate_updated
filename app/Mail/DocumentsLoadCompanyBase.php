<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentsLoadCompanyBase extends Mailable
{
    use Queueable, SerializesModels;
    public $service;
    public $documents;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service,$documents)
    {
        $this->service = $service;
        $this->documents=$documents;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Carga de archivos bases del servicio " . strtoupper($this->service->description))
        ->view('emails.load_documents_company_base');
    }
}
