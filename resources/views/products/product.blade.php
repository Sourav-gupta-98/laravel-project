<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">My Shop</a>
    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3">Hello, {{ Auth()->guard('admin')->name }}</span>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container mt-4">

    @if (session('message'))
        <div class="alert alert-success align-self-center">
            {{ session('message') }}
        </div>
    @endif

    <!-- Upload Excel Button -->
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
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    {{--    <div class="mt-4">--}}
    {{--        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}--}}
    {{--    </div>--}}
</div>

</body>
</html>
