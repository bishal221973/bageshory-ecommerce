<!DOCTYPE html>
<html>
<head>
    <title>eSewa Payment</title>
</head>
<body>

<form id="esewa-form"
      action="https://rc-epay.esewa.com.np/api/epay/main/v2/form"
      method="POST">

    <input type="hidden" name="amount" value="{{ $data['amount'] }}">

    <input type="hidden" name="tax_amount" value="{{ $data['tax_amount'] }}">

    <input type="hidden" name="total_amount" value="{{ $data['total_amount'] }}">

    <input type="hidden" name="transaction_uuid" value="{{ $data['transaction_uuid'] }}">

    <input type="hidden" name="product_code" value="{{ $data['product_code'] }}">

    <input type="hidden" name="product_service_charge"
           value="{{ $data['product_service_charge'] }}">

    <input type="hidden" name="product_delivery_charge"
           value="{{ $data['product_delivery_charge'] }}">

    <input type="hidden" name="success_url"
           value="{{ $data['success_url'] }}">

    <input type="hidden" name="failure_url"
           value="{{ $data['failure_url'] }}">

    <input type="hidden"
           name="signed_field_names"
           value="total_amount,transaction_uuid,product_code">

    <input type="hidden"
           name="signature"
           value="{{ $data['signature'] }}">

    <button type="submit">
        Pay with eSewa
    </button>
    <!-- {{Webkul\Checkout\Facades\Cart::getCart()}} -->
</form>

<script>
    document.getElementById('esewa-form').submit();
</script>

</body>
</html>