<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use App\CustomerNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\OrderRefundDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Exceptions\InvalidRefundQuantityException;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;
use Illuminate\Support\Facades\Event;
use App\Services\FirebaseService;
use App\Jobs\SendFirebaseNotification;
use Webkul\Customer\Models\Customer;

class RefundController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected RefundRepository $refundRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(OrderRefundDataGrid::class)->process();
        }

        return view('admin::sales.refunds.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        return view('admin::sales.refunds.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storePayment(int $orderId)
    {
        // return request();
        $order = $this->orderRepository->findOrFail($orderId);
        $customer = Customer::find($order->customer_id);

        $deviceToken = $customer->device_token;

        $invoice = $order->invoices()->first();

        if (! $invoice) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.create.creation-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'refund.items' => 'array',
            'refund.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        // return $data['refund'];

        if (! isset($data['refund']['shipping'])) {
            $data['refund']['shipping'] = 0;
        }

        // return $data['refund']['shipping'];

        $amt = $order->grand_total_invoiced + $data['refund']['shipping'];
        $amt1 = $order->base_grand_total_invoiced + $data['refund']['shipping'];

        $order->grand_total_invoiced = $amt;

        $order->base_grand_total_invoiced = $amt1;
        $order->save();

        session()->flash('success', 'Payment have been created');

        Event::dispatch('sales.invoice.save.after', [$invoice, null, $data]);

        CustomerNotification::create([
            'customer_id' => $order->customer_id,
            'type'        => 'order_payment',
            'title'       => 'Payment Successful 💳',
            'message'     => "Your payment for order #{$order->increment_id} has been recorded successfully.",
            'url'         => '#',
            'order_id'    => $order->id,
        ]);

        if ($deviceToken) {
            SendFirebaseNotification::dispatch(
                $deviceToken,
                'Payment Successful 💳',
                "Your payment for order #{$order->increment_id} has been recorded successfully.",
                [
                    'type'     => 'simple',
                    'order_id' => (string) $order->id,
                ]
            );
        }

        return redirect()->route('admin.sales.orders.view', $orderId);
    }
    public function store(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $customer = Customer::find($order->customer_id);

        $deviceToken = $customer->device_token;


        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.create.creation-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'refund.items' => 'array',
            'refund.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        if (! isset($data['refund']['shipping'])) {
            $data['refund']['shipping'] = 0;
        }

        try {
            $totals = $this->refundRepository->getOrderItemsRefundSummary($data['refund'], $orderId);

            if (! $totals) {
                throw new InvalidRefundQuantityException(trans('admin::app.sales.refunds.create.invalid-qty'));
            }
        } catch (InvalidRefundQuantityException $invalidRefundQuantityException) {
            session()->flash('error', $invalidRefundQuantityException->getMessage());

            return redirect()->back();
        }

        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');

        $refundAmount = $totals['grand_total']['price'] - $totals['shipping']['price'] + $data['refund']['shipping'] + $data['refund']['adjustment_refund'] - $data['refund']['adjustment_fee'];

        if (! $refundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.create.invalid-refund-amount-error'));

            return redirect()->back();
        }

        if ($refundAmount > $maxRefundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.create.refund-limit-error', [
                'amount' => core()->formatBasePrice($maxRefundAmount),
            ]));

            return redirect()->back();
        }

        $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', trans('admin::app.sales.refunds.create.create-success'));


        if ($deviceToken) {

            SendFirebaseNotification::dispatch(
                $deviceToken,
                "Order Refunded",
                "Your refund for order #" . $order->increment_id . " has been processed successfully.",
                [
                    'type' => 'simple',
                ]
            );
        }

        return redirect()->route('admin.sales.orders.view', $orderId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse|mixed
     */
    public function updateTotals(int $orderId)
    {
        try {
            $data = $this->refundRepository->getOrderItemsRefundSummary(request()->input(), $orderId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json($data);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function view($id)
    {
        $refund = $this->refundRepository->findOrFail($id);

        return view('admin::sales.refunds.view', compact('refund'));
    }
}
