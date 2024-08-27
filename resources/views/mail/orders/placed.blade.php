<!-- This is where we can configurate the message to be shown to the user when he orders something, the reference to this blade can be found in OrderPlaced.php -->
<x-mail::message>
# Order Placed Successfully

Thank you for your order. Your order number is: {{ $order->id }}.

<!-- url variable comes from OrderPlaced.php -->
<x-mail::button :url="$url">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
