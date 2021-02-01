<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentsLoadCompanyMonthly extends Mailable
{
    use Queueable, SerializesModels;
    public $service;
    public $documents;
    public $periodo;
    public $wrong;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service,$documents,$periodo=null)
    {
        $this->service = $service;
        $this->documents=$documents;
        $this->periodo=$periodo;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Carga de archivos mensuales del servicio " . strtoupper($this->service->description))
        ->view('emails.load_documents_company_monthly');
    }
}
