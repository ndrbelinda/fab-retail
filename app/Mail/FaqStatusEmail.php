<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Faq;

class FaqStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $faq;
    public $status;
    public $keterangan;

    /**
     * Create a new message instance.
     *
     * @param Faq $perangkat
     * @param string $status
     * @param string|null $keterangan
     */
    public function __construct(Faq $faq, $status, $keterangan = null)
    {
        $this->faq = $faq;
        $this->status = $status;
        $this->keterangan = $keterangan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Faq ' . ucfirst($this->status))
                    ->view('emails.faq_status'); // Satu view untuk semua status
    }
}