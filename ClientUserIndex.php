<?php
  session_start();
  if(!isset($_SESSION["loggedin"]) or $_SESSION["loggedin"] != true){
    $location = "location: ClientUserIndex.php";
    header($location);
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Client User</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

<script>
  window.onload = function(){
  // api url
  var cusId = document.getElementById("cus_id").innerHTML;
  const api_url = "https://web.cs.dal.ca/~zhaohe/ClientService/AllPOG11/" + cusId;
  console.log(api_url);

  // Defining async function
  async function getapi(url) {

    // Storing response
    const response = await fetch(url);

    // Storing data in form of JSON
    var data = await response.json();
    console.log(data);
    show(data);
  }
  // Calling that async function
  getapi(api_url);

  function show(data) {
    let tab =``;

    // Loop to access all rows
    for (let r of data) {
      let status = "";
      if (r.Status == "processing") {
        status = "placed";
      }
      else if (r.Status == "confirmed") {
        status = "filled";
      }
      tab += `<tr>
      <td>${r.Date} </td>
      <td>${r["PO Number"]}</td>
      <td>${status}</td>
      </tr>`;
    }
    // Setting innerHTML as tab variable
    document.getElementById("ourTable").innerHTML = tab;
  }
}
</script>

<!-- Page Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="ClientUserIndex.php">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Client Page</div>
    </a>


    <!-- modify parts -->
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
      <a class="nav-link" href="AddPurchaseOrder.php">
        <span style="font-size: 20px; color: white">New Purchase Order</span></a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
        <span style="font-size: 20px; color: white">Logout</span></a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


  </ul>
  <!-- End of Sidebar -->

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

      <!-- Begin Page Content -->
      <div class="container-fluid">
        <p style="display: none;" id="cus_id"><?php echo $_SESSION['cus_id']; ?></p>

        <!-- DataTales Example -->


        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Order Table</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                  <th>Date</th>
                  <th>PO Number</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th>Date</th>
                  <th>PO Number</th>
                  <th>Status</th>
                </tr>
                </tfoot>
                <tbody id="ourTable">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <form name= "jiushiwaner" method="post" action="PurchaseOrderDetails.php">
          <label for="PO">Purchase Order Details:</label>
          <input type="text" name="PO">
          <input class="btn btn-primary" type="submit" value="Submit">
        </form>
        </div>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

  </div>
  <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="logout_011.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

</body>

</html>