<?php
session_start();
?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title>New collection | KickLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="assets/styles.css" rel="stylesheet">
</head>

<body>

    <div class="promo-banner">
        MINI HOLIDAY! EXTRA -20% on spring products with code in cart |
        <a href="#">WOMEN</a>
        <a href="#">MEN</a>
        <a href="#">KIDS</a>
    </div>

    <?php include 'navbar.php'; ?>

    <div class="container-fluid my-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 ps-4">
                <h6 class="fw-bold">Run</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">Sneakers for asphalt <span class="badge bg-light text-dark">595</span></li>
                    <li>Sneakers for mountain running <span class="badge bg-light text-dark">331</span></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <!-- Filter Bar -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button class="btn btn-outline-secondary">Sort by ▼</button>
                    <button class="btn btn-outline-secondary">Price</button>
                    <button class="btn btn-outline-secondary">Size</button>
                    <button class="btn btn-outline-secondary">Brand</button>
                    <button class="btn btn-outline-secondary">Colour</button>
                    <button class="btn btn-outline-secondary">Product type</button>
                    <button class="btn btn-outline-secondary">Surface</button>
                    <button class="btn btn-outline-success">🔧 More filters</button>
                </div>

                <!-- Product Grid -->
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="col">
                            <div class="card product-card p-2 h-100">
                                <div class="position-relative">
                                    <img src="assets/shoe<?php echo $i; ?>.jpg" class="card-img-top"
                                        alt="Shoe <?php echo $i; ?>">
                                    <span
                                        class="position-absolute top-0 end-0 bg-white text-danger px-2 py-1 small border border-danger">-<?php echo rand(5, 25); ?>%</span>
                                </div>
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-1">Brand <?php echo $i; ?></h6>
                                    <p class="small text-muted mb-1">Sneakers for running</p>
                                    <p class="text-danger fw-bold mb-0"><?php echo rand(80, 330); ?>,00 USD.</p>
                                    <p class="small text-muted">Lowest price <?php echo rand(90, 370); ?>,00 USD.</p>
                                    <!-- Color dots -->
                                    <div class="d-flex justify-content-center gap-1 mt-2">
                                        <div class="rounded-circle" style="width:12px; height:12px; background:black;">
                                        </div>
                                        <div class="rounded-circle" style="width:12px; height:12px; background:purple;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Pagination -->
                <nav class="mt-5 d-flex justify-content-center">
                    <ul class="pagination">
                        <li class="page-item disabled"><a class="page-link" href="#">Back</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Forward</a></li>
                    </ul>
                </nav>

                <!-- Footer Description -->
                <div class="mt-5 px-3">
                    <h5 class="fw-bold">What running shoes to choose? A quick guide</h5>
                    <p class="text-muted">
                        „Running is the key that opens the doors to a better life." Remember, however, that any sport -
                        professional or amateur - requires proper training...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>