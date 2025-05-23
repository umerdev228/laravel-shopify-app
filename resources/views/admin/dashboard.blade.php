@extends('layouts.main')
@section('title', 'Admin Dashboard')
@section('content')
    <!-- Left side columns -->
    <div class="col-lg-">
        <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>

                        <div class="d-flex align-items-center">
                            <div class="ps-3">
                                <h2>{{$totalOrders}}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                    <div class="card-body">
                        <h5 class="card-title">Pending Orders</h5>

                        <div class="d-flex align-items-center">
                            <div class="ps-3">
                                <h2>{{$pendingOrders}}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                    <div class="card-body">
                        <h5 class="card-title">Complete Orders</h5>

                        <div class="d-flex align-items-center">
                            <div class="ps-3">
                                <h2>{{$completedOrders}}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                    <div class="card-body">
                        <h5 class="card-title">Cancelled Orders</h5>

                        <div class="d-flex align-items-center">
                            <div class="ps-3">
                                <h2>{{$cancelledOrders}}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                    <div class="card-body">
                        <h5 class="card-title">Total Customer</h5>

                        <div class="d-flex align-items-center">
                            <div class="ps-3">
                                <h2>{{$totalCustomers}}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

        </div>
    </div>

    <h3>Recent Orders</h3>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Default Table</h5>

            <!-- Default Table -->
            <table class="table">
                <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->currency }} {{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        <td><a href="{{ route('customer.order.details', $order->id) }}">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
