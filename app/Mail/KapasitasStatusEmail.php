<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Capacity;

class KapasitasStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $kapasitas;
    public $status;
    public $keterangan;

    /**
     * Create a new message instance.
     *
     * @param Capacity $kapasitas
     * @param string $status
     * @param string|null $keterangan
     */
    public function __construct(Capacity $kapasitas, $status, $keterangan = null)
    {
        $this->kapasitas = $kapasitas;
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
        return $this->subject('Kapasitas ' . ucfirst($this->status))
                    ->view('emails.kapasitas_status'); // Satu view untuk semua status
    }
}