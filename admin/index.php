<?php
    include 'config/config.php';

    $jumlah_pengguna = $jumlah_produk = $jumlah_kategori = '';

    session_start();

    if($_SESSION['hak_akses'] !== 'admin'){
        header("location: login.php");
    }

    if (!isset($_SESSION['nama'])){
        header("location: login.php");
    }

    //Untuk mengambil data jumlah pengguna
    $ambil_jumlah_pengguna = "SELECT COUNT(*) FROM user WHERE hak_akses='pengguna'";
    
    $query1 = $db->prepare($ambil_jumlah_pengguna);
    
    $query1->execute();

    $hasil_jumlah_pengguna = $query1->fetch(PDO::FETCH_ASSOC);

    $jumlah_pengguna = $hasil_jumlah_pengguna["COUNT(*)"];

    //Untuk mengambil data jumlah admin
    $ambil_jumlah_admin = "SELECT COUNT(*) FROM user WHERE hak_akses='admin'";
    
    $query2 = $db->prepare($ambil_jumlah_admin);
    
    $query2->execute();

    $hasil_jumlah_admin = $query2->fetch(PDO::FETCH_ASSOC);

    $jumlah_admin = $hasil_jumlah_admin["COUNT(*)"];

    //Untuk mengambil data jumlah produk
    $ambil_jumlah_produk = "SELECT COUNT(*) FROM produk_kulit";
    
    $query3 = $db->prepare($ambil_jumlah_produk);
    
    $query3->execute();

    $hasil_jumlah_produk = $query3->fetch(PDO::FETCH_ASSOC);

    $jumlah_produk = $hasil_jumlah_produk["COUNT(*)"];

    //Untuk mengambil data jumlah kategori
    $ambil_jumlah_kategori = "SELECT COUNT(*) FROM kategori";

    $query4 = $db->prepare($ambil_jumlah_kategori);
    
    $query4->execute();

    $hasil_jumlah_kategori = $query4->fetch(PDO::FETCH_ASSOC);

    $jumlah_kategori = $hasil_jumlah_kategori["COUNT(*)"];
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Xtreme lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Xtreme admin lite design, Xtreme admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Xtreme Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Dashboard Admin | INTANA LEATHER COLLECTION</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/xtreme-admin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/logo.png">
    <!-- Custom CSS -->
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="dist/css/style.css" type="text/css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="index.php">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="assets/images/logo.png" alt="homepage" class="logo-navbar" />
                            INTANA LEATHER COLLECTION
                        </b>
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-start me-auto">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-end">
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31">
                            </a>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li>
                            <!-- User Profile-->
                            <div class="user-profile d-flex no-block dropdown m-t-20">
                                <div class="user-pic"><img src="assets/images/users/1.jpg" alt="users"
                                        class="rounded-circle" width="40" /></div>
                                <div class="user-content hide-menu m-l-10 d-flex align-items-center">
                                    <a href="#" class="" id="Userdd" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <h5 class="m-b-0 user-name font-medium"><?php echo $_SESSION['nama']; ?> <i
                                                class="fa fa-angle-down"></i></h5>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="Userdd">
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="pengaturan.php"><i
                                                class="ti-settings m-r-5 m-l-5"></i> Pengaturan</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/utils/logout.php"><i
                                                class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a> 
                                    </div>
                                </div>
                            </div>
                            <!-- End User Profile-->
                        </li>
                        <!-- User Profile-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="index.php" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="pengguna.php" aria-expanded="false"><i
                                    class="mdi mdi-account"></i><span class="hide-menu">Pengguna</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="kategori.php" aria-expanded="false"><i
                                    class="mdi mdi-group"></i><span class="hide-menu">Kategori</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="produk.php" aria-expanded="false"><i class="mdi mdi-package"></i><span
                                    class="hide-menu">Produk</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="rating.php" aria-expanded="false"><i class="mdi mdi-star-circle"></i><span
                                    class="hide-menu">Rating</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="pengaturan.php" aria-expanded="false"><i class="mdi mdi-settings"></i><span
                                    class="hide-menu">Pengaturan</span></a>
                        </li>
                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-5">
                        <h4 class="page-title">Dashboard</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Sales chart -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-success">
                            <div class="card-body">
                                <h4 class="text-white">Jumlah Pengguna</h4>
                                <span class="text-white"><?php echo $jumlah_pengguna; ?> Orang</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h4 class="text-white">Jumlah Admin</h4>
                                <span class="text-white"><?php echo $jumlah_admin; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger">
                            <div class="card-body">
                                <h4 class="text-white">Jumlah Produk</h4>
                                <span class="text-white"><?php echo $jumlah_produk; ?> Unit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning">
                            <div class="card-body">
                                <h4 class="text-white">Jumlah Kategori</h4>
                                <span class="text-white"><?php echo $jumlah_kategori; ?> Buah</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Sales chart -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Table -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- column -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- title -->
                                <div class="d-md-flex">
                                    <div>
                                        <h4 class="card-title">Hasil rating tertinggi pada produk</h4>
                                        <h5 class="card-subtitle">Tabel di bawah ini menunjukan rating tertinggi pada produk yang diulas oleh pengguna</h5>
                                    </div>
                                </div>
                                <!-- title -->
                            </div>
                            <div class="table-responsive">
                                <table class="table v-middle">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="border-top-0">No</th>
                                            <th class="border-top-0">Produk</th>
                                            <th class="border-top-0">Rating</th>
                                            <th class="border-top-0">Jumlah pengguna pemberi rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Sampel Jaket 1</td>
                                            <td>4.7</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Sampel Sepatu 1</td>
                                            <td>4.3</td>
                                            <td>4</td>
                                        </tr>  
                                        <tr>
                                            <td>3</td>
                                            <td>Sampel Sepatu 1</td>
                                            <td>4.3</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Sampel Sepatu 2</td>
                                            <td>4.1</td>
                                            <td>2</td>
                                        </tr>   
                                        <tr>
                                            <td>5</td>
                                            <td>Sampel Dompet 1</td>
                                            <td>3.5</td>
                                            <td>3</td>
                                        </tr>   
                                        <tr>
                                            <td>6</td>
                                            <td>Sampel Dompet 2</td>
                                            <td>3.3</td>
                                            <td>7</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Table -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            Copyright &copy; 2021 - Powered by Intana Leather Collection 
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.js"></script>
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="dist/js/pages/dashboards/dashboard1.js"></script>
</body>
</html>