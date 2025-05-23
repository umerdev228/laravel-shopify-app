@extends('layouts.main')
@section('content')
    <h2>My Orders</h2>

    @if ($orders->isEmpty())
        <p>No orders found.</p>
    @else

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Default Table</h5>

                <!-- Default Table -->
                <table class="table">
                    <thead>
                    <th>Order #</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->currency }} {{ number_format($order->total_price, 2) }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td><a href="{{ route('customer.order.details', $order->id) }}">View Details</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- End Default Table Example -->
            </div>
        </div>
    @endif
@endsection
