@extends('layouts.main')
@section('title', 'Admin Reports')
@section('content')

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Sales & Order Reports</h2>

            <div>
                <h3 class="card-title">Overview</h3>
                <p><strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }}</p>
                <p><strong>Total Orders:</strong> {{ $totalOrders }}</p>
                <p><strong>Total Customers:</strong> {{ $totalCustomers }}</p>
                <p><strong>Sales This Month:</strong> ${{ number_format($monthlySales, 2) }}</p>
            </div>

            <h3 class="card-title">Top 5 Customers</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Orders</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topCustomers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->orders_count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
