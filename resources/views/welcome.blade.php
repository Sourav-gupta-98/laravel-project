<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <title>HomePage</title>
</head>
<body>

    <div class="container-fluid min-vh-100 d-flex flex-column justify-content-center align-items-center bg-light">
        <div class="text-center">
            <!-- Heading -->
            <h1 class="display-4 fw-bold mb-3 text-primary">Welcome to Our Marketplace</h1>
            <p class="lead text-secondary mb-4">
                Sellers can upload products and Customers can explore & purchase them easily.
            </p>

            <!-- Card Section -->
            <div class="row justify-content-center">
                <!-- Customer -->
                <div class="col-md-5 col-lg-4 mb-3">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle text-success" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">Customer</h3>
                            <p class="text-muted">Browse and buy amazing products uploaded by our sellers.</p>
                            <a href="{{ route('customer.login') }}" class="btn btn-success w-100">Customer Login</a>
                        </div>
                    </div>
                </div>

                <!-- Seller / Admin -->
                <div class="col-md-5 col-lg-4 mb-3">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-shop text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">Seller</h3>
                            <p class="text-muted">Upload your products and manage your sales efficiently.</p>
                            <a href="{{ route('admin.login') }}" class="btn btn-primary w-100">Seller Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>l
