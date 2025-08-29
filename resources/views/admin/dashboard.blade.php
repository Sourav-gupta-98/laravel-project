<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">My Dashboard</a>
    <div class="ms-auto d-flex align-items-center">
            <span class="text-white me-3">
                👤 {{ auth()->guard('admin')->user()->name ?? 'Guest' }}
            </span>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">Welcome, {{  auth()->guard('admin')->user()->name ?? 'User' }}</h2>

    <div class="row g-4">
        <!-- Products Card -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <h2>{{ $productsCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    <h2>{{ $customersCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Orders Pending -->
        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="card-title">Orders Pending</h6>
                    <h3>{{ $ordersPending ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Orders Shipped -->
        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="card-title">Orders Shipped</h6>
                    <h3>{{ $ordersShipped ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Orders Delivered -->
        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="card-title">Orders Delivered</h6>
                    <h3>{{ $ordersDelivered ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
