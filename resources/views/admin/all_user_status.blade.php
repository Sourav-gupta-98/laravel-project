<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>All Users</title>
</head>
<body>

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


<div class="container mt-4">
    <div class="row">
        <!-- Admins -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Admins</h5>
                </div>
                <div class="card-body">
                    @if($admins->count())
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>
                                        <span
                                            class="badge {{$admin->logged_in_status == 'ACTIVE' ? 'bg-success': 'bg-danger'}}"
                                            id="admin_status_{{$admin->id}}">{{$admin->logged_in_status == 'ACTIVE' ? 'Online' : 'Offline'}}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            id="admin_time_{{$admin->id}}">
                                            {{$admin->logged_in_time ? $admin->logged_in_time : 'N/A'}}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No admins found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Customers</h5>
                </div>
                <div class="card-body">
                    @if($customers->count())
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>
                                        <span
                                            class="badge {{$customer->logged_in_status == 'ACTIVE' ? 'bg-success': 'bg-danger'}}"
                                            id="customer_status_{{$customer->id}}">{{$customer->logged_in_status == 'ACTIVE' ? 'Online' : 'Offline'}}</span>
                                    </td>
                                    <td>
                                        <span
                                            id="customer_time_{{$customer->id}}">
                                            {{$customer->logged_in_time ? $customer->logged_in_time : 'N/A'}}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No customers found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let ws = new WebSocket("ws://127.0.0.1:2346");

    ws.onopen = function () {

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
            document.getElementById('admin_time_' + id).innerText = newTime;
            document.getElementById('admin_status_' + id).classList.remove('bg-danger', 'bg-success');
            if (newStatus === "Offline") {
                document.getElementById('admin_status_' + id).innerText = 'Offline';
                document.getElementById('admin_status_' + id).classList.add('badge', 'bg-danger');
            } else if (newStatus === "Online") {
                document.getElementById('admin_status_' + id).innerText = 'Online'
                document.getElementById('admin_status_' + id).classList.add('badge', 'bg-success');
            }
        } else if (userType === 'CUSTOMER') {
            document.getElementById('customer_time_' + id).innerText = newTime;
            document.getElementById('customer_status_' + id).classList.remove('bg-danger', 'bg-success');
            if (newStatus === "Offline") {
                document.getElementById('customer_status_' + id).innerText = 'Offline'
                document.getElementById('customer_status_' + id).classList.add('badge', 'bg-danger');
            } else if (newStatus === "Online") {
                document.getElementById('customer_status_' + id).innerText = 'Online'
                document.getElementById('customer_status_' + id).classList.add('badge', 'bg-success');
            }
        }
    }
</script>


</body>
</html>
