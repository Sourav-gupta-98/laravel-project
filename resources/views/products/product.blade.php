<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    @if(auth()->guard('admin')->check())
        <a class="navbar-brand" href="{{url('admin/dashboard')}}">My Shop</a>
    @elseif(auth()->guard('customer')->check())
        <a class="navbar-brand" href="{{url('customer/dashboard')}}">My Shop</a>
    @endif

    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3">Hello,
            @if(auth()->guard('admin')->check())
                <span>Welcome, {{ Auth::guard('admin')->user()->name }} (Admin)</span>
            @elseif(auth()->guard('customer')->check())
                <span>Welcome, {{ Auth::guard('customer')->user()->name }} (Customer)</span>
            @endif
        </span>

        @if(auth()->guard('admin')->check())
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        @elseif(auth()->guard('customer')->check())
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        @endif
    </div>
</nav>

<div class="container mt-4">

    @if (session('message'))
        <div id="success-alert" class="alert alert-success align-self-center">
            {{ session('message') }}
        </div>

        <script>
            setTimeout(function () {
                let alert = document.getElementById('success-alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        </script>
    @endif


    <!-- Upload Excel Button -->
    @if(auth()->guard('admin')->check())
        <div class="d-flex justify-content-end mb-3">
            <form method="POST" action="{{ url('admin/product') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" class="d-none" id="fileUpload" name="file" accept=".xls,.xlsx,.csv"
                       onchange="this.form.submit()"/>
                <button type="button" class="btn btn-success" onclick="document.getElementById('fileUpload').click()">
                    Upload Excel
                </button>
            </form>
        </div>
    @endif

    <!-- Products Grid -->
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ $product->image ?? asset('images/dummy_product_image.png') }}"
                         class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">
                            <strong>Stock:</strong> {{ $product->stock }} <br>
                            <strong>Category:</strong> {{ $product->category }}
                        </p>
                    </div>
                    <div class="card-footer">
                        @if(auth()->guard('admin')->check())
                            <!-- Admin Buttons -->
                            <div class="d-flex justify-content-start gap-2">
                                <!-- Edit -->
                                <form action="{{ url('admin/product/'.$product->unique_id.'/detail') }}" method="GET"
                                      class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm bg-transparent border-0 text-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ url('admin/product/'.$product->unique_id) }}" method="POST"
                                      class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-transparent border-0 text-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @elseif(auth()->guard('customer')->check())
                            <!-- Customer Buttons -->
                            <div class="d-flex justify-content-between w-100">
                                <!-- Add to Cart -->
                                <form action="{{ url('customer/cart/'.$product->unique_id) }}" method="POST"
                                      class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-info w-100 me-2">
                                        <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                    </button>
                                </form>

                                <!-- Buy Now -->
                                <form action="{{ url('customer/cart/'.$product->unique_id) }}" method="POST"
                                      class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100 ms-2">
                                        <i class="fas fa-bolt me-2"></i> Buy Now
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 align-self-center card-body border-1">
        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>


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
</script>
</body>
</html>
