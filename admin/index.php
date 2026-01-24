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
        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card.primary .icon-box { background: rgba(211, 47, 47, 0.1); color: var(--primary-color); }
        .stat-card.success .icon-box { background: rgba(46, 204, 113, 0.1); color: #2ecc71; }
        .stat-card.warning .icon-box { background: rgba(241, 196, 15, 0.1); color: #f1c40f; }
        .stat-card.info .icon-box { background: rgba(52, 152, 219, 0.1); color: #3498db; }

        .stat-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--secondary-color);
        }

        .stat-card p {
            color: #888;
            margin: 0;
            font-size: 14px;
        }
        
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
    
</body>

</html>
