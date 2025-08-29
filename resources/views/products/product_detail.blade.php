<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="{{ url('/') }}">Product Admin</a>
    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3">Welcome,
        @if(auth()->guard('admin')->check())
                <span>Welcome, {{ Auth::guard('admin')->user()->name }} (Admin)</span>
            @elseif(auth()->guard('customer')->check())
                <span>Welcome, {{ Auth::guard('customer')->user()->name }} (Customer)</span>
            @endif
        </span>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>Edit Product</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('admin/product/'.$product->unique_id.'/edit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}"
                           required>
                </div>

                <!-- Stock -->
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"
                              required>{{ $product->description }}</textarea>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ $product->category }}" required>
                </div>

                <!-- Image -->
{{--                <div class="mb-3">--}}
{{--                    <label class="form-label">Product Image</label>--}}
{{--                    <input type="file" name="image" class="form-control">--}}
{{--                    @if($product->image)--}}
{{--                        <div class="mt-2">--}}
{{--                            <img src="{{ asset('storage/'.$product->image) }}" width="150" class="img-thumbnail">--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                </div>--}}

                <button type="submit" class="btn btn-success w-100">Update Product</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
