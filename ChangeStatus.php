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

  <title>Confirm New Order</title>

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
  var poId= document.getElementById('po_id').innerHTML;
  // api url
  const api_url = "https://web.cs.dal.ca/~zhaohe/CompanyAgentService/POG11/" + poId;
  const api_url2 = "https://web.cs.dal.ca/~zhaohe/CompanyAgentService/POFG11/" + poId;

  // Defining async function
  async function getapi(url, url2) {

    // Storing response
    const response = await fetch(url);

    // Storing data in form of JSON
    var data = await response.json();
    console.log(data);


    // Storing response for the second api to get PO detail
    const response2 = await fetch(url2);

    // Storing data in form of JSON
    var data2 = await response2.json();
    console.log(data2);

    var clientId = data2[0]["Client Company ID"];

    const url3 = "https://web.cs.dal.ca/~zhaohe/CompanyAgentService/ClientsG11/" + clientId;

    // Storing response for the third api to get client location and balance
    const response3 = await fetch(url3);

    // Storing data in form of JSON
    var data3 = await response3.json();
    console.log(data3);

    show(data, data2, data3);

  }

  // Calling that async function
  getapi(api_url, api_url2);

  function show(data, data2, data3) {
    let status = "";
    if (data2[0].Status == "processing") {
      status = "placed";
    }
    else if (data2[0].Status == "confirmed") {
      status = "filled";
      document.getElementById("cfmBtn").style.display = "none";
      document.getElementById("cncBtn").style.display = "none";
      
    }
    let tab2= `<tr>
      <td>${data2[0]["PO Number"]}</td>
      <td>${data2[0]["Client Company ID"]}</td>
      <td>${data2[0].Date}</td>
      <td>${status} </td>
      <td>${data3[0].clientCity}</td>
      <td>${data3[0].clientBalance}</td>
      </tr>`;
    // Setting innerHTML as tab variable
    document.getElementById("ourTable2").innerHTML = tab2;


    let tab = ``;
    // Loop to access all rows
    for (let r of data) {
      tab += `<tr>
      <td>${r["Line Number"]}</td>
      <td>${r["Part Number"]}</td>
      <td>${r["Purchase Order"]}</td>
      <td>${r.Quantity} </td>
      <td>${r["Unit Price"]}</td>
      <td>${r.Stock}</td>
      </tr>`;
      if(r.Quantity > r.Stock){
        document.getElementById("cfmBtn").style.display = "none";
        document.getElementById("cncBtn").style.display = "none";
        document.getElementById("wrnBtn").style.display = "";
      }
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
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Agent Index</div>
    </a>



    <!-- modify parts -->
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
      <a class="nav-link" href="CompanyAgent.php">
        <span style="font-size: 20px; color: white">Back</span></a>
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
        <p style="display: none;" id="po_id"><?php echo $_POST['PO']; ?></p>

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Tables</h1>
        <p class="mb-4">Please Confirm New Purchase Order Here</p>

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
                  <th>PO Number</th>
                  <th>Client Company ID</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Location</th>
                  <th>Balance</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th>PO Number</th>
                  <th>Client Company ID</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Location</th>
                  <th>Balance</th>
                </tr>
                </tfoot>
                <tbody id="ourTable2">
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Order Table</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                  <th>Line Number</th>
                  <th>Part Number</th>
                  <th>Purchase Order</th>
                  <th>Quantity</th>
                  <th>Unit Price</th>
                  <th>Stock</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th>Line Number</th>
                  <th>Part Number</th>
                  <th>Purchase Order</th>
                  <th>Quantity</th>
                  <th>Unit Price</th>
                  <th>Stock</th>
                </tr>
                </tfoot>
                <tbody id="ourTable">
                </tbody>
              </table>
            </div>
          </div>
        </div>


        <button class="btn btn-primary" onclick="confirm()" id="cfmBtn">Confirm</button>
        <a class="btn btn-danger" href="CompanyAgent.php" id="cncBtn">Cancel</a>
        <button class="btn btn-warning" onclick="warn()" id="wrnBtn" style="display: none;">Cannot Fulfill</a>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>Copyright &copy; Your Website 2020</span>
        </div>
      </div>
    </footer>
    <!-- End of Footer -->

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
<script>
  function confirm(){
    var poId= document.getElementById('po_id').innerHTML;
    var parameters = "{\"id\":" + poId + "," + "\"status\":\"confirmed\"}";

    var resp = "";
    fetch('https://web.cs.dal.ca/~zhaohe/CompanyAgentService/POStG11', {
      method: "PUT",
      body: parameters,
      headers: {"Content-type": "application/json"}
    })
            .then(response => response.json())
            .then(function(json){
              if(JSON.parse(json).Rows > 0) {
                alert("Confirmed!");
                location.replace("CompanyAgent.php");
              }
              else {
                alert("Confirm Failed!");
              }
            })
            .catch(err => console.log(err));
  }

  function warn(){
    alert("Can not fulfill this order: Inadequate Stock");
    location.replace("CompanyAgent.php");
  }
</script>
</body>

</html>