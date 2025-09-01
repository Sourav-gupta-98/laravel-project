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
            <div class="card shadow-sm border-0 text-center cursor-pointer">
                <div class="card-body">
                    <h5 class="card-title"><a href="{{url('admin/product')}}">Products</a></h5>
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
                    <h6 class="card-title"><a href="{{url('admin/orders')}}">Orders Pending</a></h6>
                    <h3>{{ $ordersPending ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Orders Shipped -->
        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="card-title"><a href="{{url('admin/orders')}}">Orders Shipped</a></h6>
                    <h3>{{ $ordersShipped ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Orders Delivered -->
        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="card-title"><a href="{{url('admin/orders')}}">Orders Delivered</a></h6>
                    <h3>{{ $ordersDelivered ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="card-title"><a href="{{url('admin/all-users')}}">Online Admins & Customers</a></h6>
                    <h3>{{ $ordersDelivered ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    let ws = new WebSocket("ws://127.0.0.1:2346");

    ws.onopen = function () {
        console.log("Connected to WebSocket");

        // Authenticate user
        const params = {
            type: "auth",
            user_type: "{{auth()->guard('admin')->check() ? 'ADMIN': 'CUSTOMER'}}",
            user_id: "{{ auth()->guard('admin')->check() ? (auth()->guard('admin')->user()->id) :( auth()->guard('customer')->user()->id) }}"
        }
        ws.send(JSON.stringify(params));
    };

    ws.onmessage = function (event) {
        let data = JSON.parse(event.data);
        if (data.type === "presence") {
            changeStatus(data.user_id, data.status, data.time, data.user_type)
        }
    };

    function changeStatus(id, newStatus, newTime, userType) {
        if (userType === 'ADMIN') {
            console.log('inside admin')
            document.getElementById('admin_time_' + id).innerText = newTime;
            document.getElementById('admin_status_' + id).classList.remove('bg-danger', 'bg-success');
            if (newStatus === "Offline") {
                console.log('admin offline')
                document.getElementById('admin_status_' + id).innerText = 'Offline';
                document.getElementById('admin_status_' + id).classList.add('badge', 'bg-danger');
            } else if (newStatus === "Online") {
                console.log('admin online')
                document.getElementById('admin_status_' + id).innerText = 'Online'
                document.getElementById('admin_status_' + id).classList.add('badge', 'bg-success');
            }
        } else if (userType === 'CUSTOMER') {
            console.log('inside customer')
            document.getElementById('customer_time_' + id).innerText = newTime;
            document.getElementById('customer_status_' + id).classList.remove('bg-danger', 'bg-success');
            if (newStatus === "Offline") {
                console.log('customer Offline')
                document.getElementById('customer_status_' + id).innerText = 'Offline'
                document.getElementById('customer_status_' + id).classList.add('badge', 'bg-danger');

            } else if (newStatus === "Online") {
                console.log('customer Online')
                document.getElementById('customer_status_' + id).innerText = 'Online'
                document.getElementById('customer_status_' + id).classList.add('badge', 'bg-success');
            }
        }
    }
</script>
</body>
</html>
