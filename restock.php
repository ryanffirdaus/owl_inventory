<?php
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "databaseinventory";

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stockQuantity = ""; // Default value, replace it with the actual stock quantity based on the selected item from the database

if (isset($_POST['quantity'])) {
    $selectedItemId = $_POST['selectedItem'];
    // Fetch the stock quantity from the database based on the selected item
    $query = "SELECT quantity FROM stokbahan WHERE stok_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selectedItemId);
    $stmt->execute();
    $stmt->bind_result($stockQuantity);
    $stmt->fetch();
    $stmt->close();

    $submittedQuantity = $_POST['quantity'];
    if ($submittedQuantity == "") {
        echo "$stockQuantity";
        exit();
    } elseif ($submittedQuantity <= 0) {
        echo "Kuantitas yang dimasukkan harus lebih besar dari 0";
        exit();
    }

    // Update the database with the new stock quantity
    $newStockQuantity = $stockQuantity + $submittedQuantity;
    $updateQueryStock = "UPDATE stokbahan SET quantity = ? WHERE stok_id = ?";
    $updateStmt = $conn->prepare($updateQueryStock);
    $updateStmt->bind_param("ii", $newStockQuantity, $selectedItemId);
    $updateStmt->execute();
    $updateStmt->close();

     // Insert a new record into the 'historis' table
     $insertQueryHistoris = "INSERT INTO historis (pengguna, stok_id, waktu, quantity, activity) VALUES (?, ?, NOW(), ?, 'Restock')";
     $insertStmt = $conn->prepare($insertQueryHistoris);
     $insertStmt->bind_param("sii", $pengguna, $selectedItemId, $submittedQuantity);
 
     // Replace 'your_username' with the actual username or identifier of the user performing the restock
     $pengguna = 'your_username';
 
     $insertStmt->execute();
     $insertStmt->close();

    // Return the updated stock quantity
    echo $stockQuantity;
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restock</title>

    <link rel="icon" href="assets/adminlte/dist/img/OWLlogo.png" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/adminlte/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
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
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="homepage.php" class="brand-link">
                <img src="assets/adminlte/dist/img/OWLlogo.png" alt="OWL Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-heavy">OWL Inventory</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="./homepage.php" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a href="produksi.php" class="nav-link">
                                <i class="nav-icon fas fa-toolbox"></i>
                                <p>
                                    Produksi
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="maintenance.php" class="nav-link">
                                <i class="nav-icon fas fa-wrench"></i>
                                <p>
                                    Maintenance
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="restock.php" class="nav-link active">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    Restock
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
                            <h1>Restock</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="homepage.php">Home</a></li>
                                <li class="breadcrumb-item active">Restock</li>
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
                            <h3 class="card-title">Menambah Stok Bahan</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="restockForm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleSelectBorderWidth2">Pilih Bahan :</label>
                                    <select class="custom-select form-control-border border-width-2" id="pilihBahanRestock" name="selectedItem">
                                        <option value="1">U101</option>
                                        <option value="10">R102</option>
                                        <option value="11">L203</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Kuantitas :</label>
                                    <div class="input-group">
                                        <!-- Input untuk kuantitas -->
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" value="">
                                        <!-- Tombol-tombol untuk menambah dan mengurangi kuantitas -->
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-block btn-danger" onclick="decreaseQuantity()">-</button>
                                            <button type="button" class="btn btn-primary" onclick="increaseQuantity()">+</button>
                                        </div>
                                    </div>
                                </div>
                                <p id="stockMessage">Stok Bahan Tersisa: <?php echo $stockQuantity; ?></p>
                                <p id="successMessage" style="display: none; color: green;">Stok berhasil ditambahkan</p>
                              </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" onclick="validateSuccess()">Submit</button>
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
    <script src="assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/adminlte/dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();

            // Add an event listener to the select element
            $("#pilihBahanRestock").change(function() {
                validateCurrentStock();
            });
        });

        function decreaseQuantity() {
            var quantityInput = document.getElementById("quantity");
            if (quantityInput.value > 0) {
                quantityInput.value--;
                updateStockMessage();
            }
        }

        function increaseQuantity() {
            var quantityInput = document.getElementById("quantity");
            quantityInput.value++;
            updateStockMessage();
        }

        function updateStockMessage() {
            var stockMessage = document.getElementById("stockMessage");
            var selectedQuantity = parseInt(document.getElementById("quantity").value, 10);

            // Update stock message dynamically based on the selected item's stock quantity
            stockMessage.innerText = "Stok Bahan Tersisa: " + (<?php echo $stockQuantity; ?> + selectedQuantity);
        }

        function validateCurrentStock() {
            // Get the form data
            var formData = $("#restockForm").serialize();

            // Use AJAX to submit the form data and fetch the updated stock quantity
            $.ajax({
                type: "POST",
                url: "restock.php",
                data: formData,
                success: function(response) {
                    // Hide the stock message
                    document.getElementById("successMessage").style.display = "none";
                    // Update the stock message with the fetched quantity
                    document.getElementById("stockMessage").innerText = "Stok Bahan Tersisa: " + response;
                },
                error: function(error) {
                    alert("Error fetching stock quantity.");
                }
            });
        }

        function validateSuccess() {
            // Get the form data
            var formData = $("#restockForm").serialize();

            // Use AJAX to submit the form data and fetch the updated stock quantity
            $.ajax({
                type: "POST",
                url: "restock.php",
                data: formData,
                success: function(response) {
                    // Hide the stock message
                    document.getElementById("stockMessage").style.display = "none";
                    // Update the stock message with success message
                    document.getElementById("successMessage").style.display = "block";
                },
                error: function(error) {
                    alert("Error fetching stock quantity.");
                }
            });
        }
    </script>
</body>

</html>