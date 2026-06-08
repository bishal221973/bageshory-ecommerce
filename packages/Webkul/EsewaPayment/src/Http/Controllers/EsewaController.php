<?php

namespace Webkul\EsewaPayment\Http\Controllers;

use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Paypal\Helpers\Ipn;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

use Webkul\Checkout\Repositories\CartRepository;
use Webkul\EsewaPayment\Payment\EsewaPayment;
use Webkul\PayU\Payment\PayU;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
class EsewaController extends Controller
{
    public const PAYMENT_SUCCESS = 'success';
    public function __construct(
        protected OrderRepository $orderRepository,
        protected Ipn $ipnHelper,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository,

    ) {}
    public function redirect()
    {
        $cart = Cart::getCart();
        // 1. eSewa requires amounts to have exactly 2 decimal points (e.g., "100.00")
        $amount = number_format($cart->grand_total, 2, '.', '');

        $uuid = time() . rand(1000, 9999);
        $productCode = "EPAYTEST"; // Official active sandbox product code

        $data = [
            'amount' => $amount,
            'tax_amount' => "0.00",
            'product_service_charge' => "0.00",
            'product_delivery_charge' => "0.00",
            'total_amount' => $amount,
            'transaction_uuid' => $uuid,
            'product_code' => $productCode,
        ];

        // Official sandbox secret key for EPAYTEST
        $secretKey = '8gBm/:&EnhH.1/q';

        // FIX: Parameters MUST have commas, but absolutely NO spaces.
        $message = "total_amount={$amount},transaction_uuid={$uuid},product_code={$productCode}";

        // Generate the raw HMAC-SHA256 binary hash, then base64 encode it
        $data['signature'] = base64_encode(
            hash_hmac('sha256', $message, $secretKey, true)
        );

        // Dynamic routing URLs for local development
        $data['success_url'] = url('/esewa/success');
        $data['failure_url'] = url('/esewa/fail');

        return view('esewapayment::redirect', compact('data'));
    }


    public function success()
    {
        $response = request()->all();
        // return session()->get('cart_id');
        $cart = Cart::getCart();

        $data = (new OrderResource($cart))->jsonSerialize();

        $order = $this->orderRepository->create($data);
        $this->orderRepository->update(['status' => 'processing'], $order->id);

        if ($order->canInvoice()) {
            $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

            $this->orderTransactionRepository->create([
                'transaction_id' => $response['txnid'] ?? '',
                'status' => self::PAYMENT_SUCCESS,
                'type' => $order->payment->method,
                'payment_method' => $order->payment->method,
                'order_id' => $order->id,
                'invoice_id' => $invoice->id,
                'amount' => $response['amount'] ?? $order->base_grand_total,
                'data' => json_encode($response),
            ]);
        }

        Cart::deActivateCart();

        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
        // return 'Payment Success';
    }

    public function failure()
    {
        return 'Payment Failed';
    }

    protected function prepareInvoiceData($order)
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
