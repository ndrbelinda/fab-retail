<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Perangkat;

class PerangkatStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $perangkat;
    public $status;
    public $keterangan;

    /**
     * Create a new message instance.
     *
     * @param Perangkat $perangkat
     * @param string $status
     * @param string|null $keterangan
     */
    public function __construct(Perangkat $perangkat, $status, $keterangan = null)
    {
        $this->perangkat = $perangkat;
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
        return $this->subject('Perangkat ' . ucfirst($this->status))
                    ->view('emails.perangkat_status'); // Satu view untuk semua status
    }
}