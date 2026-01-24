<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Amadika Admin Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="d-flex wrapper" id="wrapper">
        
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Main Content -->
            <div class="container-fluid px-4">
                
                <!-- Quick Stats Row -->
                <div class="row g-3 my-2">
                    <div class="col-md-3">
                        <div class="stat-card primary">
                            <div class="icon-box">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h3>120</h3>
                            <p>New Orders</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card success">
                            <div class="icon-box">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>4,250</h3>
                            <p>Total Users</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card warning">
                            <div class="icon-box">
                                <i class="fas fa-box"></i>
                            </div>
                            <h3>350</h3>
                            <p>Products</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card info">
                            <div class="icon-box">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h3>$12,500</h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders / Charts (Placeholder) -->
                <div class="row my-5">
                    <h3 class="fs-4 mb-3 text-secondary">Recent Orders</h3>
                    <div class="col">
                        <div class="table-responsive bg-white rounded shadow-sm p-4">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" width="50">#</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>King Size Bed</td>
                                        <td>Jon Doe</td>
                                        <td>$450</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Office Chair</td>
                                        <td>Jane Smith</td>
                                        <td>$120</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Modern Lamp</td>
                                        <td>Mike Ross</td>
                                        <td>$65</td>
                                        <td><span class="badge bg-danger">Cancelled</span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">4</th>
                                        <td>Sofa Set</td>
                                        <td>Rachel Green</td>
                                        <td>$850</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->

</body>

</html>
