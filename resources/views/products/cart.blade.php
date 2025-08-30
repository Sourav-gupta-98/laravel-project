<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Cart Products</title>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="{{url('customer/dashboard')}}">My Shop</a>
    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3">Hello,
                <span>Welcome, {{ Auth::guard('customer')->user()->name }} (Customer)</span>
        </span>
        <form action="{{ route('customer.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container my-5">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="mb-4">🛒 My Cart</h2>

    @if($carts->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($carts as $key => $cart)
                    @php
                        $product = $cart->product; // Assuming Cart belongsTo Product
                        $total = $product->price * $cart->quantity;
                        $grandTotal += $total;
                    @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $product->name ?? 'Unknown Product' }}</td>
                        <td>
                            @if(!empty($product->image))
                                <img src="{{ asset('storage/'.$product->image) }}" alt="Product" class="img-thumbnail"
                                     width="80">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <!-- Minus Button -->
                                <form action="{{ url('customer/cart/'.$product->unique_id) }}" method="POST"
                                      class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </form>

                                <!-- Quantity -->
                                <span class="mx-3 fw-bold">{{ $cart->quantity }}</span>

                                <!-- Plus Button -->
                                <form action="{{ url('customer/cart/'.$product->unique_id) }}" method="POST"
                                      class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success px-2">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                        <td>₹{{ number_format($product->price, 2) }}</td>
                        <td>₹{{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="table-info fw-bold">
                    <td colspan="5" class="text-end">Grand Total</td>
                    <td colspan="2">₹{{ number_format($grandTotal, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        {{--        <div class="text-end mt-3">--}}
        {{--            <a href="{{ url('/checkout') }}" class="btn btn-success btn-lg">--}}
        {{--                <i class="fas fa-bolt me-2"></i> Proceed to Checkout--}}
        {{--            </a>--}}
        {{--        </div>--}}

        <!-- Shipping & Payment Form -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-dark text-white">
                Shipping & Payment Information
            </div>
            <div class="card-body">
                <form action="{{ url('customer/orders') }}" method="POST">
                    @csrf
                    <!-- Shipping Address -->
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Shipping Address <span
                                class="text-danger">*</span></label>
                        <textarea name="shipping_address" id="shipping_address" rows="2" class="form-control"
                                  required>{{old('shipping_address')}}</textarea>
                    </div>

                    <!-- Billing Address -->
                    <div class="mb-3">
                        <label for="billing_address" class="form-label">Billing Address <span
                                class="text-danger">*</span></label>
                        <textarea name="billing_address" id="billing_address" rows="2" class="form-control"
                                  required>{{old('billing_address')}}</textarea>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="">-- Select Payment Method --</option>
                            <option value="COD">Cash on Delivery</option>
                            <option value="ONLINE" disabled>Credit/Debit Card</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-bolt me-2"></i> Proceed to Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @else
        <div class="alert alert-info">Your cart is empty.</div>
    @endif
</div>
</body>
</html>
