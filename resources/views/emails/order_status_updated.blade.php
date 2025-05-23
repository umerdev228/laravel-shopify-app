<p>Dear {{ $order->user->name ?? 'Customer' }},</p>

<p>Your order #{{ $order->order_number }} has been updated.</p>

<p><strong>New Status:</strong> {{ $order->current_stage }}</p>

<p>Thank you for shopping with us!</p>
