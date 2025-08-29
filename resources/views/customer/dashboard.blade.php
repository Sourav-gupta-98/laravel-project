<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
    <a class="navbar-brand fw-bold text-white" href="#">MyShop</a>

    <div class="ms-auto d-flex align-items-center">
            <span class="me-3 text-white">
                Hello, {{ auth('customer')->user()->name }}
            </span>
        <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="container my-5">
    <h3 class="mb-4">Dashboard</h3>

    <div class="row g-4">
        <!-- Products -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title"><a href="{{url('customer/product')}}">Products</a></h5>
                    <h3 class="fw-bold text-primary">{{ $productsCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Pending Orders</h5>
                    <h3 class="fw-bold text-warning">{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>

        <!-- Shipped Orders -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Shipped Orders</h5>
                    <h3 class="fw-bold text-info">{{ $shippedOrders }}</h3>
                </div>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Delivered Orders</h5>
                    <h3 class="fw-bold text-success">{{ $deliveredOrders }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
