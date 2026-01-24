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
        .kpi-row {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 5px 2px 15px 2px; /* Bottom padding for scrollbar space */
            margin-bottom: 20px;
            scrollbar-width: thin; /* Firefox */
        }

        .kpi-row::-webkit-scrollbar {
            height: 6px;
        }
        .kpi-row::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .kpi-card {
            background: var(--white);
            border-radius: 8px;
            min-width: 160px; /* Ensure visibility */
            flex: 1;
            height: 70px; /* Minimal height */
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-left: 4px solid #ccc; /* Default fallback */
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
            cursor: pointer;
            position: relative;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            background-color: #fafafa;
        }

        .kpi-icon {
            font-size: 1.2rem;
            margin-right: 12px;
            opacity: 0.8;
            width: 25px;
            text-align: center;
        }

        .kpi-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.1;
        }

        .kpi-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .kpi-label {
            font-size: 0.75rem;
            color: #888;
            white-space: nowrap;
            margin-top: 2px;
        }

        /* Accent & Border Colors */
        .border-blue    { border-left-color: #3498db; }
        .border-purple  { border-left-color: #9b59b6; }
        .border-indigo  { border-left-color: #6610f2; }
        .border-teal    { border-left-color: #1abc9c; }
        .border-green   { border-left-color: #27ae60; }
        .border-red     { border-left-color: #e74c3c; }
        .border-orange  { border-left-color: #e67e22; }
        .border-emerald { border-left-color: #2ecc71; }

        .text-blue    { color: #3498db; }
        .text-purple  { color: #9b59b6; }
        .text-indigo  { color: #6610f2; }
        .text-teal    { color: #1abc9c; }
        .text-green   { color: #27ae60; }
        .text-red     { color: #e74c3c; }
        .text-orange  { color: #e67e22; }
        .text-emerald { color: #2ecc71; }
        
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
                <div class="kpi-row">
                    
                    <!-- 1. Total Users (Blue) -->
                    <div class="kpi-card border-blue" title="Active registered users">
                        <i class="fas fa-users kpi-icon text-blue"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">4,250</span>
                            <span class="kpi-label">Total Users</span>
                        </div>
                    </div>

                    <!-- 2. Total Orders (Purple) -->
                    <div class="kpi-card border-purple" title="All time orders">
                        <i class="fas fa-shopping-bag kpi-icon text-purple"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">1,250</span>
                            <span class="kpi-label">Total Orders</span>
                        </div>
                    </div>

                    <!-- 3. Total Products (Indigo) -->
                    <div class="kpi-card border-indigo" title="Available products">
                        <i class="fas fa-box-open kpi-icon text-indigo"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">350</span>
                            <span class="kpi-label">Products</span>
                        </div>
                    </div>

                    <!-- 4. Avg Monthly Sales (Teal) -->
                    <div class="kpi-card border-teal" title="Average sales last 30 days">
                        <i class="fas fa-chart-line kpi-icon text-teal"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">$8.5k</span>
                            <span class="kpi-label">Avg. Sales</span>
                        </div>
                    </div>

                    <!-- 5. Total Revenue (Green) -->
                    <div class="kpi-card border-green" title="Total earnings">
                        <i class="fas fa-file-invoice-dollar kpi-icon text-green"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">$125k</span>
                            <span class="kpi-label">Revenue</span>
                        </div>
                    </div>

                    <!-- 6. Out of Stock (Red) -->
                    <div class="kpi-card border-red" title="Products with 0 inventory">
                        <i class="fas fa-exclamation-circle kpi-icon text-red"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">12</span>
                            <span class="kpi-label">Out of Stock</span>
                        </div>
                    </div>

                    <!-- 7. Pending Orders (Orange) -->
                    <div class="kpi-card border-orange" title="Orders waiting processing">
                        <i class="fas fa-clock kpi-icon text-orange"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">45</span>
                            <span class="kpi-label">Pending</span>
                        </div>
                    </div>

                    <!-- 8. Completed Orders (Emerald) -->
                    <div class="kpi-card border-emerald" title="Specifically completed orders">
                        <i class="fas fa-check-circle kpi-icon text-emerald"></i>
                        <div class="kpi-content">
                            <span class="kpi-value">890</span>
                            <span class="kpi-label">Completed</span>
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
