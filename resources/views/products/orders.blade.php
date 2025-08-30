<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Orders</title>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    @if(auth()->guard('admin')->check())
        <a class="navbar-brand" href="{{url('admin/dashboard')}}">My Shop</a>
    @else
        <a class="navbar-brand" href="{{url('customer/dashboard')}}">My Shop</a>
    @endif
    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3">
            Hello, {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name.' (Admin)' : Auth::guard('customer')->user()->name.' (Customer)' }}
        </span>
        <form action="{{ Auth::guard('admin')->check() ? route('admin.logout') : route('customer.logout') }}"
              method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container my-5">

    <h2 class="mb-4">
        @if(auth()->guard('admin')->check())
            📦 Incoming Orders
        @else
            🛒 My Orders
        @endif
    </h2>

    <!-- Orders Table -->
    @if(auth()->guard('admin')->check())
        <!-- Admin View -->
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Total Items</th>
                        <th>Total Qty</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $key => $order)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->total_items }}</td>
                            <td>{{ $order->total_qty }}</td>
                            <td>₹{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->order->status ?? 'N/A' }}</td>
                            <td>
                                @if($order->order)
                                    <form action="{{ url('admin/orders/'.$order->order_id) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select me-2" required>
                                            @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $status)
                                                <option value="{{ $status }}" {{ $order->order->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                @else
                                    <span class="text-muted">No Order Found</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Product details row --}}
                        @if($order->order && $order->order->order_details)
                            <tr>
                                <td colspan="7">
                                    <div class="p-2">
                                        <h6 class="fw-bold">Products in this order:</h6>
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Product ID</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($order->order->order_details as $i => $detail)
                                                <tr>
                                                    <td>{{ $i+1 }}</td>
                                                    <td>{{ $detail->product_id }}</td>
                                                    <td>{{ $detail->quantity }}</td>
                                                    <td>₹{{ number_format($detail->price, 2) }}</td>
                                                    <td>₹{{ number_format($detail->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="alert alert-info">No incoming orders yet.</div>
        @endif

    @elseif(auth()->guard('customer')->check())
        <!-- Customer View -->
        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <span>Order #{{ $order->order_number }}</span>
                        <span>Status: <strong>{{ ucfirst($order->status) }}</strong></span>
                    </div>
                    <div class="card-body">
                        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                        <p><strong>Billing Address:</strong> {{ $order->billing_address }}</p>

                        <!-- Order Details -->
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->order_details as $i => $detail)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $detail->product_id }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>₹{{ number_format($detail->price, 2) }}</td>
                                    <td>₹{{ number_format($detail->total, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">You don’t have any orders yet.</div>
        @endif
    @endif

</div>
</body>
</html>
