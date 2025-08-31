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
                                        @if($admin->logged_in_status == 'ACTIVE')
                                            <span class="badge bg-success">Online</span>
                                        @else
                                            <span class="badge bg-danger">Offline</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->logged_in_time ? date('d-m-Y h:i s', strtotime($admin->logged_in_time)) : 'N/A' }}</td>
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
                                <tr id="customer-{{ $customer->id }}" data-status="{{ $customer->logged_in_status }}"
                                    data-time="{{ $customer->logged_in_time }}">
                                    <td>{{ $customer->name }}</td>
                                    <td class="status-cell">
                                        @if($customer->logged_in_status == 'ACTIVE')
                                            <span class="badge bg-success">Online</span>
                                        @else
                                            <span class="badge bg-danger">Offline</span>
                                        @endif
                                    </td>
                                    <td class="status-time">{{ $customer->logged_in_time ? date('d-m-Y h:i s', strtotime($customer->logged_in_time)) : 'N/A' }}</td>
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
    // Connect to Workerman server
    let ws = new WebSocket("ws://127.0.0.1:2346");

    ws.onopen = function () {
        console.log("Connected to WebSocket");

        // Authenticate user (replace with real logged-in user ID)
        const params = {
            type: "auth",
            user_type: "{{auth()->guard('admin')->check() ? 'ADMIN': 'CUSTOMER'}}",
            user_id: "{{ auth()->guard('admin')->check() ? (auth()->guard('admin')->user()->id) :( auth()->guard('customer')->user()->id) }}"
        }
        console.log(params)
        ws.send(JSON.stringify(params));
    };

    ws.onmessage = function (event) {
        let data = JSON.parse(event.data);
        console.log(data)
        if (data.type === 'STATUS_UPDATE') {
            console.log('update data ', data, 'order_' + data.order_id)
            let statusField = document.getElementById('order_' + data.order_id);
            statusField.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);
        }
        // if (data.type === "presence") {
        //     console.log("Presence Update:", data.user_id, data.status,  data);
        // }

        // if (data.type === "message") {
        //     console.log("New message from " + data.from_user + ": " + data.message, data);
        //     // Update your chat UI here
        // }
    };

    function updateStatus(e, orderId, customerId) {
        e.preventDefault();
        let status = document.getElementById('status_' + orderId).value;
        const params = {
            type: "STATUS_UPDATE",
            order_id: orderId,
            customer_id: customerId,
            status: status
        };
        console.log(params)
        ws.send(JSON.stringify(params));
        document.getElementById('show_status_' + orderId).innerText = status.charAt(0).toUpperCase() + status.slice(1);
    }

    function changeCustomerStatus(id, newStatus, newTime) {
        let row = document.getElementById('customer-' + id);
        let cell = row.querySelector('.status-cell');
        let time = row.querySelector('.status-time')
        time.innerHTML = `<span>${newTime}</span>`;
        if (newStatus === 'ACTIVE') {
            row.dataset.status = 'ACTIVE';  // update dataset
            cell.innerHTML = '<span class="badge bg-success">Online</span>';
        } else {
            row.dataset.status = 'INACTIVE';
            cell.innerHTML = '<span class="badge bg-danger">Offline</span>';
        }
    }


    function sendMessage(toUser, message) {
        ws.send(JSON.stringify({
            type: "message",
            to_user: toUser,
            message: message
        }));
    }
</script>


</body>
</html>
