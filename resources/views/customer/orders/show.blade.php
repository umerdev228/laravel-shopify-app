@extends('layouts.main')

@section('title', 'Order Details')

@section('content')

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Order #{{ $order->order_number }}</h2>

            <div class="row">
                <div class="col-lg-3 col-md-4 label">Total Price:</div>
                <div class="col-lg-9 col-md-8">{{ $order->currency }} {{ number_format($order->total_price, 2) }}</div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 label">Status:</div>
                <div class="col-lg-9 col-md-8">{{ ucfirst($order->status) }}</div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 label">Fulfillment Status:</div>
                <div class="col-lg-9 col-md-8">{{ ucfirst($order->fulfillment_status) }}</div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 label">Created At:</div>
                <div class="col-lg-9 col-md-8">{{ $order->created_at->format('d M Y, h:i A') }}</div>
            </div>

            <h3 class="card-title">Order Progress</h3>
            <p class="card-text"><strong>Current Stage:</strong> {{ $order->current_stage }}</p>

            <ul>
                <li {{ $order->current_stage == 'Order Received' ? 'style=color:green;' : '' }}>✅ Order Received</li>
                <li {{ $order->current_stage == 'Processing' ? 'style=color:green;' : '' }}>✅ Processing</li>
                <li {{ $order->current_stage == 'Shipped' ? 'style=color:green;' : '' }}>✅ Shipped</li>
                <li {{ $order->current_stage == 'Delivered' ? 'style=color:green;' : '' }}>✅ Delivered</li>
            </ul>


            <h3 class="card-title">Order Items</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                @foreach (json_decode($order->line_items, true) as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $order->currency }} {{ number_format($item['price'], 2) }}</td>
                        <td>{{ $item['quantity'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h3 class="card-title">Shipping Address</h3>
            @if ($order->shipping_address)
                @php $shipping = json_decode($order->shipping_address, true); @endphp
                <p class="card-text">{{ $shipping['name'] }}</p>
                <p class="card-text">{{ $shipping['address1'] }}, {{ $shipping['city'] }}
                    , {{ $shipping['country'] }}</p>
                <p class="card-text">Phone: {{ $shipping['phone'] }}</p>
            @else
                <p class="card-text">No shipping address provided.</p>
            @endif

            <h3 class="card-title">Billing Address</h3>
            @if ($order->billing_address)
                @php $billing = json_decode($order->billing_address, true); @endphp
                <p class="card-text">{{ $billing['name'] }}</p>
                <p class="card-text">{{ $billing['address1'] }}, {{ $billing['city'] }}, {{ $billing['country'] }}</p>
                <p class="card-text">Phone: {{ $billing['phone'] }}</p>
            @else
                <p class="card-text">No billing address provided.</p>
            @endif


            <h3 class="card-title">Upload Files</h3>
            @if (session('success'))
                <p class="card-text" style="color: green;">{{ session('success') }}</p>
            @endif
            <form action="{{ route('orders.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-sm-10">
                        <input class="form-control" type="file" name="file" required>
                        @error('file')
                        <p class="card-text text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit Form</button>
                    </div>
                </div>
            </form>

            <h3 class="card-title">Uploaded Files</h3>
            @if ($order->files->isEmpty())
                <p>No files uploaded.</p>
            @else
                <ul class="align-items-center">
                    @foreach ($order->files as $file)
                        <li>
                            {{ $file->file_name }}
                            <a href="{{ route('files.download', $file->id) }}">Download</a>
                            <form action="{{ route('files.delete', $file->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif


            <h3>Order Cancellation</h3>
            @if ($order->cancellation_status == 'None')
                <form action="{{ route('orders.cancel.request', $order->id) }}" method="POST">
                    @csrf
                    <label>Reason for cancellation:</label>
                    <textarea name="reason" required></textarea>
                    <button type="submit">Request Cancellation</button>
                </form>
            @elseif ($order->cancellation_status == 'Pending')
                <p style="color: orange;">Cancellation request is pending approval.</p>
            @elseif ($order->cancellation_status == 'Approved')
                <p style="color: green;">Order has been cancelled.</p>
            @elseif ($order->cancellation_status == 'Rejected')
                <p style="color: red;">Cancellation request was rejected.</p>
            @endif


            <a href="{{ route('customer.orders') }}">Back to Orders</a>

        </div>
    </div>
@endsection
