<?php

require("../DB/dbhelper.php");
$sql = "SELECT order_details.id,orders.id as id_order , user.name as fullname, user.email, user.phone,orders.order_date, orders.address_ship, num,order_details.price,`product`.name, orders.status FROM `order_details`, `orders` , `product`,`user` WHERE  orders.id = order_details.order_id AND order_details.product_id = product.id ORDER BY user.name   ASC";
$dataOrder = executeResult($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     // Retrieve the selected status value from the form
     $newStatus = $_POST["status"];

     // Assuming you have an order ID stored in a variable, replace $orderID with your order ID
     $orderID = $_POST["order_id"]; // Replace with your actual order ID

     // Prepare and execute the SQL query to update the order status
     $sql = "UPDATE orders SET status = '$newStatus' WHERE id = $orderID";
     execute($sql);
     header('location:order.php');
     // Close the database connection
     // $conn->close();
}

?>
<?php include("./component/header.php"); ?>

<body id="page-top">
     <!-- Page Wrapper -->
     <div id="wrapper">
          <!-- Sidebar -->
          <?php include("./component/sideBar.php") ?>

          <!-- Content Wrapper -->
          <div id="content-wrapper" class="d-flex flex-column">
               <!-- Main Content -->
               <div id="content position-relative">
                    <!-- Topbar -->
                    <?php include("./component/topNav.php") ?>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                         <!-- Page Heading -->
                         <h1 class="h3 mb-4 text-gray-800">Manager Order</h1>

                         <div class="row">
                              <div class="col-lg-12">
                                   <table class="table">
                                        <thead>
                                             <tr>
                                                  <th scope="col" class="text-center">STT</th>
                                                  <th scope="col" class="text-center">USer Name</th>
                                                  <th scope="col" class="text-center">Name Product</th>
                                                  <th scope="col" class="text-center">Quantity</th>
                                                  <th scope="col" class="text-center">Price</th>
                                                  <th scope="col" class="text-center">Email</th>
                                                  <th scope="col" class="text-center">Phone</th>
                                                  <th scope="col" class="text-center">Address</th>
                                                  <th scope="col" class="text-center">Status</th>
                                                  <th scope="col" class="text-center">Order Date</th>
                                                  <th scope="col">Delete</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $stt = 0;
                                             foreach ($dataOrder as $order) {
                                                  $totalPrice = $order["num"] * $order["price"];
                                                  echo '<tr>
                                                  <th scope="row" class="text-center">' . $order["id"] . '</th>
                                                  <td class="text-center">  ' . $order["fullname"] . '</td>
                                                  <td class="text-center">' . $order["name"] . '</td>
                                                  <td class="text-center">' . $order["num"] . '</td>
                                                  <td class="text-center">' . $totalPrice . '</td>
                                                  <td class="text-center">' . $order["email"] . '</td>
                                                  <td class="text-center">' . $order["phone"] . '</td>
                                                  <td class="text-center">' . $order["address_ship"] . '</td>
                                                  <td class="text-center">
                                                       <div class="btn-primary">    
                                                       <form method="POST">                                                         
                                                            <input type="text" name="order_id" value="' . $order["id_order"] . '" hidden>                      
                                                                 <select id="status" name="status" class="form-select">
                                                                      <option value="Prepare">' . $order["status"] . '</option>
                                                                      <option value="Shipped">Prepare</option>
                                                                      <option value="Shipped">Shipped</option>
                                                                      <option value="Completed">Completed</option>
                                                                 </select><br>
                                                                 <button class="btn-success" type="submit" name="submit">Update</button>
                                                            </div>
                                                       </form>                    
                                                                
                                                            
                                                       
                                                  </td>
                                                  <td class="text-center">' . $order["order_date"] . '</td>
                                                  <td>
                                                      <a  class="p-2 btn-danger text-justify rounded"
                                                       onclick="handlerDelete(' . $order['id'] . ')"
                                                       ><i
                                                                 class="fa-solid fa-eraser"></i></a>
                                                  </td>
                                             </tr>';
                                             }
                                             ?>
                                        </tbody>
                                        <select></select>
                                   </table>
                              </div>
                         </div>
                    </div>
                    <!-- /.container-fluid -->

               </div>
               <!-- End of Main Content -->

               <!-- Footer -->
               <?php include("./component/footer.php") ?>
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
     <?php include("./component/logoutModal.php") ?>

     <!-- Bootstrap core JavaScript-->
     <script src="vendor/jquery/jquery.min.js"></script>
     <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

     <!-- Core plugin JavaScript-->
     <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

     <!-- Custom scripts for all pages-->
     <script src="js/sb-admin-2.min.js"></script>
     <script>
          function handlerDelete(id) {
               alert("Bạn có chắc chắn muốn xóa order này!");
               $.ajax({
                    type: "POST",
                    url: "./deleteOrder.php",
                    data: {
                         idOrderDetail: id
                    },
                    success: function() {
                         location.reload();
                    }
               })
          }
     </script>
</body>

</html>