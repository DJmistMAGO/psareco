<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewSaleNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $buyerName,
        public float $total,
        public string $saleDate,
        public int $itemCount
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'sale',
            'title'      => 'New Sale Recorded',
            'message'    => "{$this->buyerName} made a purchase worth ₱" . number_format($this->total, 2) . ".",
            'buyer_name' => $this->buyerName,
            'total'      => $this->total,
            'sale_date'  => $this->saleDate,
            'item_count' => $this->itemCount,
        ];
    }
}
