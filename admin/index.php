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

        /* --- Dashboard Cards --- */
        /* --- KPI Cards Redesign --- */
        /* --- Ultra Compact 6-Card Grid --- */
        .kpi-card-new {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .kpi-card-new:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-color: rgba(0,0,0,0.1);
        }

        .icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 10px; /* Soft square */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .kpi-data {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .kpi-value {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .kpi-label {
            font-size: 0.8rem;
            color: #7f8c8d;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Color Variations */
        .bg-soft-blue { background: rgba(52, 152, 219, 0.1); color: #3498db; }
        .bg-soft-purple { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
        .bg-soft-indigo { background: rgba(102, 16, 242, 0.1); color: #6610f2; }
        .bg-soft-green { background: rgba(46, 204, 113, 0.1); color: #2ecc71; }
        .bg-soft-orange { background: rgba(230, 126, 34, 0.1); color: #e67e22; }
        .bg-soft-red { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
        
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
                
                <!-- Ultra-Compact KPI Row -->
                <!-- 6-Column KPI Grid -->
                <div class="row g-3 mb-4">
                    
                    <!-- 1. Total Users -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="kpi-card-new">
                            <div class="icon-circle bg-soft-blue">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="kpi-data">
                                <span class="kpi-value">4,250</span>
                                <span class="kpi-label">Users</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Total Orders -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="kpi-card-new">
                            <div class="icon-circle bg-soft-purple">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="kpi-data">
                                <span class="kpi-value">1,250</span>
                                <span class="kpi-label">Orders</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Total Products -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="kpi-card-new">
                            <div class="icon-circle bg-soft-indigo">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div class="kpi-data">
                                <span class="kpi-value">350</span>
                                <span class="kpi-label">Products</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Total Revenue -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="kpi-card-new">
                            <div class="icon-circle bg-soft-green">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="kpi-data">
                                <span class="kpi-value">$125k</span>
                                <span class="kpi-label">Revenue</span>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Pending Orders -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="kpi-card-new">
                            <div class="icon-circle bg-soft-orange">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="kpi-data">
                                <span class="kpi-value">45</span>
                                <span class="kpi-label">Pending</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Out of Stock -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="kpi-card-new">
                            <div class="icon-circle bg-soft-red">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="kpi-data">
                                <span class="kpi-value">12</span>
                                <span class="kpi-label">Out of Stock</span>
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
