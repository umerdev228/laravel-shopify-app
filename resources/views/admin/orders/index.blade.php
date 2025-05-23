@extends('layouts.main')

@section('title', 'Admin - Order Management')

@section('content')
    <h2>Admin Order Management</h2>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Default Table</h5>

            <!-- Default Table -->
            <table class="table">
                <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Current Stage</th>
                    <th>Update Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ $order->currency }} {{ number_format($order->total_price, 2) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->current_stage }}</td>
                        <td width="500">
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                <select name="current_stage" class="form-select">
                                    <option
                                        value="Order Received" {{ $order->current_stage == 'Order Received' ? 'selected' : '' }}>
                                        Order Received
                                    </option>
                                    <option
                                        value="Processing" {{ $order->current_stage == 'Processing' ? 'selected' : '' }}>
                                        Processing
                                    </option>
                                    <option
                                        value="Shipped" {{ $order->current_stage == 'Shipped' ? 'selected' : '' }}>
                                        Shipped
                                    </option>
                                    <option
                                        value="Delivered" {{ $order->current_stage == 'Delivered' ? 'selected' : '' }}>
                                        Delivered
                                    </option>
                                </select>
                                    <button class="btn btn-success" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Order Cancellation Requests</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    @if ($order->cancellation_status == 'Pending')
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>{{ $order->cancellation_reason }}</td>
                            <td>{{ $order->cancellation_status }}</td>
                            <td>
                                <form action="{{ route('orders.cancel.approve', $order->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit">Approve</button>
                                </form>
                                <form action="{{ route('orders.cancel.reject', $order->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
