<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Order\InvoicedNotification;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class Invoice extends Base
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderTransactionRepository $orderTransactionRepository,
    ) {}

    /**
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Invoice  $invoice
     * @return void
     */
    public function afterCreated($invoice,$duplicateEmail=null, $data = null)
    {
        $this->sendMail($invoice);

        if(isset($data['refund']['shipping'])){
            $this->createTransaction($invoice, $data);

        }else{

            if ($data['payment_status'] == 'paid' || $data['payment_status'] == 'partial') {
                $this->createTransaction($invoice, $data);
            }
        }
    }

    /**
     * Send Transaction mail.
     *
     * @param  \Webkul\Sale\Contracts\Invoice  $invoice
     * @return void
     */
    public function sendMail($invoice)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_invoice_mail_to_admin')) {
                return;
            }

            $this->prepareMail($invoice, new InvoicedNotification($invoice));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Create the transaction data for Money-transfer and Cash-on-delivery.
     *
     * @param  \Webkul\Sale\Contracts\Invoice  $invoice
     * @return void
     */
    public function createTransaction($invoice, $data = null)
    {
        $transactionId = md5(uniqid());
        $amt = 0;

        if(isset($data['refund']['shipping'])){
            $amt=$data['refund']['shipping'];
        }else{

            if (($data['payment_status'] ?? null) === 'paid') {
                $amt = $invoice->grand_total;
            } elseif (($data['payment_status'] ?? null) === 'partial') {
                $amt = $data['paid_amount'] ?? 0;
            }
        }

        
        

        $transactionData = [
            'transaction_id' => $transactionId,
            'status' => $invoice->state,
            'type' => $invoice->order->payment->method,
            'payment_method' => $invoice->order->payment->method,
            'order_id' => $invoice->order->id,
            'invoice_id' => $invoice->id,
            'amount' => $amt ?? 0
        ];

        $this->orderTransactionRepository->create($transactionData);
    }
}
