<?php
require_once 'includes/auth.php';
?>
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
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #D32F2F;
            --accent-gold: #D4A017;
            --secondary-color: #2D3436;
            --body-bg: #f5f7fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            --white: #ffffff;
        }

        body {
            font-family: 'Rubik', sans-serif;
            background-color: var(--body-bg);
            overflow-x: hidden;
        }

        /* --- Layout Wrapper --- */
        .d-flex.wrapper {
            overflow-x: hidden;
        }
        
        #page-content-wrapper {
            width: 100%;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- Solid Color KPI Cards --- */
        .kpi-card-solid {
            border-radius: 12px;
            padding: 25px;
            height: 120px; /* Taller for the solid look */
            position: relative;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .kpi-card-solid:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        /* Content Z-Index to stay above watermark */
        .kpi-content-solid {
            position: relative;
            z-index: 2;
        }

        .kpi-value-solid {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
            display: block;
        }

        .kpi-label-solid {
            font-size: 0.95rem;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Watermark Icon */
        .kpi-icon-watermark {
            position: absolute;
            right: -10px;
            bottom: -15px;
            font-size: 80px;
            opacity: 0.25;
            transform: rotate(15deg); /* Dynamic tilt */
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .kpi-card-solid:hover .kpi-icon-watermark {
            transform: rotate(0deg) scale(1.1);
        }

        /* Solid Gradients */
        .bg-gradient-blue   { background: linear-gradient(135deg, #42a5f5, #1976d2); }
        .bg-gradient-purple { background: linear-gradient(135deg, #ab47bc, #7b1fa2); }
        .bg-gradient-indigo { background: linear-gradient(135deg, #5c6bc0, #303f9f); }
        .bg-gradient-green  { background: linear-gradient(135deg, #66bb6a, #388e3c); }
        .bg-gradient-orange { background: linear-gradient(135deg, #ffa726, #f57c00); }
        .bg-gradient-red    { background: linear-gradient(135deg, #ef5350, #c62828); }
        
        /* Table Styles override */
        .table thead th {
            font-weight: 600;
            color: var(--secondary-color);
            border-bottom-width: 1px;
            background-color: #f8f9fa;
        }

        /* --- Scrollbar Customization --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #bbb; }
    </style>
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
                
                <!-- 6-Column KPI Grid (Solid Design) -->
                <div class="row g-4 mt-4 mb-4">
                    
                    <!-- 1. Total Users -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-blue">
                            <i class="fas fa-users kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">4,250</span>
                                <span class="kpi-label-solid">Total Users</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Total Orders -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-purple">
                            <i class="fas fa-shopping-bag kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">1,250</span>
                                <span class="kpi-label-solid">Total Orders</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Total Products -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-indigo">
                            <i class="fas fa-box-open kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">350</span>
                                <span class="kpi-label-solid">Total Products</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Total Revenue -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-green">
                            <i class="fas fa-file-invoice-dollar kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">$125k</span>
                                <span class="kpi-label-solid">Total Revenue</span>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Pending Orders -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-orange">
                            <i class="fas fa-clock kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">45</span>
                                <span class="kpi-label-solid">Pending Orders</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Out of Stock -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-red">
                            <i class="fas fa-exclamation-triangle kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">12</span>
                                <span class="kpi-label-solid">Out of Stock</span>
                            </div>
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
    
</body>

</html>
