<?php
include("../../connection.php");


if (isset($_GET['id'])) {
    $transaksi_id = $_GET['id'];

    // Selecting data from detail_maintenance table based on transaksi_id
    $query = "SELECT * FROM detail_maintenance WHERE transaksi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $transaksi_id);
    $stmt->execute();

    $result = $stmt->get_result();

    // You can also fetch data from the transaksi_maintenance table
    $transaksiQuery = "SELECT nama_client FROM transaksi_maintenance WHERE transaksi_id = ?";
    $transaksiStmt = $conn->prepare($transaksiQuery);
    $transaksiStmt->bind_param("i", $transaksi_id);
    $transaksiStmt->execute();

    $transaksiResult = $transaksiStmt->get_result();
} else {
    echo "ID not provided.";
}

$updateQuery = "UPDATE detail_maintenance SET kedatangan=?, cek_barang=?, berita_as=?, administrasi=?, pengiriman=?, no_resi=? WHERE detail_id=?";
$updateStmt = $conn->prepare($updateQuery);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form is submitted
    if (isset($_POST['submitForm'])) {
        // Loop through the submitted data and update the database
        foreach ($_POST['checkboxBarangDatang'] as $key => $value) {
            $detail_id = intval($key); // Convert to integer for security
            $checkboxBarangDatang = isset($_POST['checkboxBarangDatang'][$detail_id]) ? 1 : 0;
            $checkboxCekMasalah = isset($_POST['checkboxCekMasalah'][$detail_id]) ? 1 : 0;
            $checkboxBeritaAcara = isset($_POST['checkboxBeritaAcara'][$detail_id]) ? 1 : 0;
            $checkboxAdministrasi = isset($_POST['checkboxAdministrasi'][$detail_id]) ? 1 : 0;
            $checkboxPengiriman = isset($_POST['checkboxPengiriman'][$detail_id]) ? 1 : 0;
            $noResi = isset($_POST['no_resi'][$detail_id]) ? $_POST['no_resi'][$detail_id] : null;

            // Update the database with the new values for each row
            $updateStmt->bind_param("iiiiisi", $checkboxBarangDatang, $checkboxCekMasalah, $checkboxBeritaAcara, $checkboxAdministrasi, $checkboxPengiriman, $noResi, $detail_id);
            $updateStmt->execute();
        }
        header("Location: ../monitoring.php");
        // exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Transaksi</title>

    <link rel="icon" href="../../assets/adminlte/dist/img/OWLlogo.png" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../assets/adminlte/dist/css/adminlte.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <style>
        .lebar-kolom1 {
            width: 17%;
        }

        .lebar-kolom2 {
            width: 15%;
        }

        .lebar-kolom3 {
            width: 10%;
        }

        .lebar-kolom4 {
            width: 20%;
        }

        .lebar-kolom5 {
            width: 3%;
        }

        .lebar-kolom10 {
            width: 20%;
        }

        th {
            text-align: center;
            vertical-align: middle;
        }

        .column-name {
            font-weight: bold;
        }

        .column-description {
            font-size: 10px;
            color: #888;
        }

        .larger-checkbox {
            width: 20px;
            height: 20px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4 fixed">
            <!-- Brand Logo -->
            <a href="../../homepage.php" class="brand-link">
                <img src="../../assets/adminlte/dist/img/OWLlogo.png" alt="OWL Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-heavy">OWL RnD</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="../../homepage.php" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        </li>
                        <li class="nav-header">TRANSAKSI</li>
                        <li class="nav-item">
                            <a href="produksi.php" class="nav-link">
                                <i class="nav-icon fas fa-toolbox"></i>
                                <p>
                                    Produksi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../../produksi/produksi.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Produksi Device</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../../produksi/pengiriman.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengiriman Device</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="../../maintenance.php" class="nav-link active">
                                <i class="nav-icon fas fa-wrench"></i>
                                <p>
                                    Maintenance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../input.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Input</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../monitoring.php" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Monitoring</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../update.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Update</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="../../prototype.php" class="nav-link">
                                <i class="nav-icon fas fa-screwdriver"></i>
                                <p>
                                    Prototype
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../restock.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    Restock
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">TAMBAH DATA</li>
                        <li class="nav-item">
                            <a href="../../master_bahan.php" class="nav-link">
                                <i class="nav-icon fa fa-pen"></i>
                                <p>
                                    Master Bahan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../master_device.php" class="nav-link">
                                <i class="nav-icon fas fa-cube"></i>
                                <p>
                                    Master Device
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="perusahaan.php" class="nav-link">
                                <i class="nav-icon fas fa-industry"></i>
                                <p>
                                    Perusahaan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../../perusahaan/tambah_perusahaan.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tambah Perusahaan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../../perusahaan/list_perusahaan.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>List Perusahaan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">PELAPORAN</li>
                        <li class="nav-item">
                            <a href="../../stok_bahan.php" class="nav-link">
                                <i class="nav-icon ion ion-pie-graph"></i>
                                <p>
                                    Stok Bahan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../stok_produk.php" class="nav-link">
                                <i class="nav-icon fas fa-microchip"></i>
                                <p>
                                    Stok Produk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../histori_transaksi.php" class="nav-link">
                                <i class="nav-icon fas fa-history"></i>
                                <p>
                                    Histori Transaksi
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <?php
                            if (isset($_GET['id'])) {
                                $transaksi_id = $_GET['id'];
                                echo "<h1>Monitoring Transaksi #{$transaksi_id}</h1>";
                            } else {
                                echo "<h1>Monitoring Transaksi</h1>";
                                echo "ID not provided.";
                            }
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../homepage.php">Home</a></li>
                                <li class="breadcrumb-item active">Maintenance</li>
                                <li class="breadcrumb-item active"><a href="../monitoring.php">Monitoring</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <?php
                            if (isset($_GET['id'])) {
                                $transaksi_id = $_GET['id'];
                                // Assuming $result contains data from the query
                                $row = mysqli_fetch_assoc($transaksiResult);
                                $nama_client = $row["nama_client"];
                                echo "<h3 class='card-title'>Monitoring Transaksi {$nama_client} </h3>";
                            } else {
                                echo "<h3 class='card-title'>Monitoring Transaksi PT. Origin Wiracipta Lestari</h3>";
                                echo "ID not provided.";
                            }
                            ?>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="monitoringForm" method="post">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><b>Detail Transaksi</b></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="lebar-kolom5">ID</th>
                                                        <th class="lebar-kolom1" style="min-width:140px;">Nama Barang</th>
                                                        <th class="lebar-kolom2" style="min-width:100px;">Nomor SN</th>
                                                        <th class="lebar-kolom3">Garansi?</th>
                                                        <th class="lebar-kolom4">Kerusakan</th>
                                                        <th class="lebar-kolom5">
                                                            <div class="column-name">DTG</div>
                                                            <div class="column-description">Barang Datang</div>
                                                        </th>
                                                        <th class="lebar-kolom5">
                                                            <div class="column-name">CEK</div>
                                                            <div class="column-description">Buat Berita</div>
                                                        </th>
                                                        <th class="lebar-kolom5">
                                                            <div class="column-name">R</div>
                                                            <div class="column-description">Reparasi Barang</div>
                                                        </th>
                                                        <th class="lebar-kolom5">
                                                            <div class="column-name">SO/PO</div>
                                                            <div class="column-description">Proses Administrasi</div>
                                                        </th>
                                                        <th class="lebar-kolom5">
                                                            <div class="column-name">P</div>
                                                            <div class="column-description">Pengiriman Barang</div>
                                                        </th>
                                                        <th class="text-center lebar-kolom10" style="min-width:150px;">No. Resi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="transaksiTable">
                                                    <?php
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["detail_id"]; ?></td>
                                                            <td><?php echo $row["produk_mt"]; ?></td>
                                                            <td><?php echo $row["no_sn"]; ?></td>
                                                            <td><?php echo $row["garansi"] == 1 ? 'Yes' : 'No'; ?></td>
                                                            <td><?php echo $row["keterangan"]; ?></td>
                                                            <td style="text-align: center;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input larger-checkbox" type="checkbox" value="1" name="checkboxBarangDatang[<?php echo $row['detail_id']; ?>]" <?php echo $row["kedatangan"] == 1 ? 'checked' : ''; ?>>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input larger-checkbox" type="checkbox" value="1" name="checkboxCekMasalah[<?php echo $row['detail_id']; ?>]" <?php echo $row["cek_barang"] == 1 ? 'checked' : ''; ?>>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input larger-checkbox" type="checkbox" value="1" name="checkboxBeritaAcara[<?php echo $row['detail_id']; ?>]" <?php echo $row["berita_as"] == 1 ? 'checked' : ''; ?>>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input larger-checkbox" type="checkbox" value="1" name="checkboxAdministrasi[<?php echo $row['detail_id']; ?>]" <?php echo $row["administrasi"] == 1 ? 'checked' : ''; ?>>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input larger-checkbox" type="checkbox" value="1" name="checkboxPengiriman[<?php echo $row['detail_id']; ?>]" <?php echo $row["pengiriman"] == 1 ? 'checked' : ''; ?>>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" id="no_resi" name="no_resi[<?php echo $row['detail_id']; ?>]" min="0" value="<?php echo $row["no_resi"]; ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" name="submitForm">Submit</button>
                            </div>
                        </form>

                    </div>
                    <!-- general form elements -->
                    <!-- /.card -->

                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../../assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../../assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/adminlte/dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('.form-check-input');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    this.value = this.checked ? 1 : 0;
                });
            });
        });
    </script>

</body>

</html>