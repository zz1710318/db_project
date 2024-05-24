<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>DE Shop | Wishlist Page</title>
    
    <!-- Font awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">   
    <!-- SmartMenus jQuery Bootstrap Addon CSS -->
    <link href="css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
    <!-- Product view slider -->
    <link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.css">    
    <!-- slick slider -->
    <link rel="stylesheet" type="text/css" href="css/slick.css">
    <!-- price picker slider -->
    <link rel="stylesheet" type="text/css" href="css/nouislider.css">
    <!-- Theme color -->
    <link id="switcher" href="css/theme-color/default-theme.css" rel="stylesheet">
    <!-- Top Slider CSS -->
    <link href="css/sequence-theme.modern-slide-in.css" rel="stylesheet" media="all">

    <!-- Main style sheet -->
    <link href="css/style.css" rel="stylesheet">    

    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php
session_start();

// 處理越權查看以及錯誤登入
if (!isset($_SESSION['account'])) {
    echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
    exit();
} 

// 處理管理員調出使用者清單
include "database.php";

$member_id = $_SESSION['ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['viewdetails'])) {
    // Retrieve OID from the form submission
    $oid = $_POST['OIDDetail'];

    // Fetch order details
    $sql = "SELECT o.*, m.* FROM orders o
            JOIN members m ON o.ID = m.ID
            WHERE  o.OID = :oid";
    $stmt = $link->prepare($sql);
    $stmt->bindParam(':oid', $oid);
    $stmt->execute();

    $html = "<table><tr><th>OID</th><th>Account</th><th>Name</th><th>Address</th><th>Date</th><th>Amount</th><th>Method</th></tr>";
    while ($orders = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($orders['OID']) ."</td>";
        $html .= "<td>" . htmlspecialchars($orders['account']) ."</td>";
        $html .= "<td>" . htmlspecialchars($orders['recipient']) . "</td>";
        $html .= "<td>" . htmlspecialchars($orders['address']) . "</td>";
        $html .= "<td>" . htmlspecialchars($orders['date']) . "</td>";
        $html .= "<td>$" . htmlspecialchars($orders['payamount']) . "</td>";
        $html .= "<td>" . htmlspecialchars($orders['method']) . "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";

    // Output the second section
    $sql = "SELECT t1.*, t2.quantity FROM product t1
            JOIN orderDetail t2 ON t1.PID = t2.PID
            WHERE  t2.OID = :oid";
    $stmt = $link->prepare($sql);
    $stmt->bindParam(':oid', $oid);
    $stmt->execute();

    $html2 = "<table><tr><th>Image</th><th>Type</th><th>Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html2 .= "<tr>";
        $html2 .= '<td><img src="data:image/jpeg;base64,'.base64_encode($product['image']).'" alt="Product Image" width="80" height="100"></td>';
        $html2 .= "<td>" . htmlspecialchars($product['type']) . "</td>";
        $html2 .= "<td>" . htmlspecialchars($product['name']) . "</td>";
        $html2 .= "<td>$" . htmlspecialchars($product['price']) . "</td>";
        $html2 .= "<td>" . htmlspecialchars($product['quantity']) . "</td>";
        $subtotal = $product['price'] * $product['quantity'];
        $html2 .= "<td>$" . $subtotal . "</td>";           
        $html2 .= "</tr>";
    }
    $html2 .= "</table>";

    // Output HTML

} 
?>

    
  <style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-family: Arial, sans-serif;
        color: #333;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        border: 1px solid #ddd; /* 添加表格整体边框 */
    }

    /* 表格標頭 */
    th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ddd; /* 添加表头单元格边框 */
    }

    /* 表格行与单元格 */
    tr {
        border-bottom: 1px solid #ddd;
    }

    td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ddd; /* 添加单元格边框 */
    }

    /* 滑过行变色效果 */
    tr:hover {
        background-color: #f5f5f5;
    }
  </style>
  </head>
  <body>  
   <!-- wpf loader Two -->
    <div id="wpf-loader-two">          
      <div class="wpf-loader-two-inner">
        <span>Loading</span>
      </div>
    </div> 
    <!-- / wpf loader Two -->       
 <!-- SCROLL TOP BUTTON -->
    <a class="scrollToTop" href="#"><i class="fa fa-chevron-up"></i></a>
  <!-- END SCROLL TOP BUTTON -->


  <!-- Start header section -->
  <header id="aa-header">
    <!-- start header top  -->
    <div class="aa-header-top">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="aa-header-top-area">
              <!-- start header top left -->
              <div class="aa-header-top-left">
                <!-- start language -->
                <div class="aa-language">
                  <div class="dropdown">
                    <a class="btn dropdown-toggle" href="#" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      <img src="img/flag/english.jpg" alt="english flag">ENGLISH
                      <span class="caret"></span>
                    </a>
                  </div>
                </div>
                <!-- / language -->

                <!-- start currency -->
                <div class="aa-currency">
                  <div class="dropdown">
                    <a class="btn dropdown-toggle" href="#" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      <i class="fa fa-usd"></i>TWD
                      <span class="caret"></span>
                    </a>
                  </div>
                </div>
                <!-- / currency -->
                <!-- start cellphone -->
                <div class="cellphone hidden-xs">
                  <p><span class="fa fa-phone"></span>09-87-654-321</p>
                </div>
                <!-- / cellphone -->
              </div>
              <!-- / header top left -->
              <div class="aa-header-top-right">
                <ul class="aa-head-top-nav-right">
                  <li><a href="myaccount-admin.php" class="nav-item nav-link active"><?php echo "Welcome，". $_SESSION['account'];?></a></li>
                  <li><a href="logout.php" class="btn btn-danger rounded-0 py-4 px-lg-5 d-none d-lg-block" style="background-color: #ff6666; color: white;">Logout<i class="fa fa-arrow-right ms-3"></i></a></li>
                  <li class="hidden-xs"><a href="upload.php">Upload</a></li>
                  <li class="hidden-xs"><a href="remove.php">Remove</a></li>
                  <li class="hidden-xs"><a href="order-admin.php">Order</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / header top  -->
    <!-- start header bottom  -->
    <div class="aa-header-bottom">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="aa-header-bottom-area">
              <!-- logo  -->
              <div class="aa-logo">
                <!-- Text based logo -->
                <a href="manageAccounts.php">
                  <span class="fa fa-shopping-cart"></span>
                  <p>DE<strong>Shop</strong> <span>Your Shopping Partner</span></p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / header bottom  -->
  </header>
  <!-- / header section -->

  <!-- catg header banner section -->
  <section id="aa-catg-head-banner">
   <img src="img/fashion/clothes.jpg" alt="fashion img">
   <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Order Page</h2>
        <ol class="breadcrumb">
          <li><a href="manageAccounts.php">Home</a></li>
          <li><a href="order-admin.php">Order</a></li>                    
          <li class="active">Order Details</li>
        </ol>
      </div>
     </div>
   </div>
  </section>




  <section id="cart-view">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <div class="cart-view-area">
           <div class="cart-view-table">
              <div><?php echo $html;?></div>
                <div class="text-center">
                  <h3>Order Details</h3>
                </div>
              <div><?php echo $html2;?></div>
           </div>
         </div>
       </div>
     </div>
   </div>
  </section>






  <!-- / catg header banner section -->

 <!-- Cart view section -->
 
 <!-- / Cart view section -->


  <!-- Subscribe section -->
  
  <!-- / Subscribe section -->

  <!-- footer -->  
  <footer id="aa-footer">
    <!-- footer bottom -->
    <div class="aa-footer-top">
     <div class="container">
        <div class="row">
        <div class="col-md-12">
          <div class="aa-footer-top-area">
            <div class="row">
              <div class="col-md-3 col-sm-6">
                <div class="aa-footer-widget">
                  <h3>About us</h3>
                  <ul class="aa-footer-nav">
                    <li><a href="#">Brand Story</a></li>
                    <li><a href="#">Business negotiation</a></li>
                  </ul>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="aa-footer-widget">
                  <div class="aa-footer-widget">
                    <h3>Shopping Information</h3>
                    <ul class="aa-footer-nav">
                      <li><a href="#">Membership</a></li>
                      <li><a href="#">Payment & Shipping</a></li>
                      <li><a href="#">Return Policy</a></li>
                      <li><a href="#">Privacy Policy</a></li>
                      <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="aa-footer-widget">
                  <div class="aa-footer-widget">
                    <h3>Useful Links</h3>
                    <ul class="aa-footer-nav">
                      <li><a href="#">Site Map</a></li>
                      <li><a href="#">Search</a></li>
                      <li><a href="#">Advanced Search</a></li>
                      <li><a href="#">Suppliers</a></li>
                      <li><a href="#">FAQ</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="aa-footer-widget">
                  <div class="aa-footer-widget">
                    <h3>Contact Us</h3>
                    <address>
                      <p><span class="fa fa-phone"></span>+886 8765432</p>
                      <p><span class="fa fa-envelope"></span>DEshop@gmail.com</p>
                    </address>
                    <div class="aa-footer-social">
                      <a href="#"><span class="fa fa-facebook"></span></a>
                      <a href="#"><span class="fa fa-twitter"></span></a>
                      <a href="#"><span class="fa fa-google-plus"></span></a>
                      <a href="#"><span class="fa fa-youtube"></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     </div>
    </div>
    <!-- footer-bottom -->
    <div class="footer-payment-icons" style="text-align: center;">            
      <img src="https://static.shoplineapp.com/web/assets/payment/visa.svg" loading="lazy">
      <img src="https://static.shoplineapp.com/web/assets/payment/master.svg" loading="lazy">
      <img src="https://static.shoplineapp.com/web/assets/payment/jcb.svg" loading="lazy">          
      <img src="https://static.shoplineapp.com/web/assets/payment/unionpay.svg" loading="lazy">
      <img src="https://static.shoplineapp.com/web/assets/payment/family_mart.svg" loading="lazy">
      <img src="https://static.shoplineapp.com/web/assets/payment/7_eleven.svg" loading="lazy">
      <img src="https://static.shoplineapp.com/web/assets/payment/line.svg" loading="lazy">
      <img src="https://static.shoplineapp.com/web/assets/payment/sl_payment.svg" loading="lazy">  
    </div>
  </footer>
  <!-- / footer -->
  <!-- Login Modal -->  



  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.js"></script>  
  <!-- SmartMenus jQuery plugin -->
  <script type="text/javascript" src="js/jquery.smartmenus.js"></script>
  <!-- SmartMenus jQuery Bootstrap Addon -->
  <script type="text/javascript" src="js/jquery.smartmenus.bootstrap.js"></script>  
  <!-- To Slider JS -->
  <script src="js/sequence.js"></script>
  <script src="js/sequence-theme.modern-slide-in.js"></script>  
  <!-- Product view slider -->
  <script type="text/javascript" src="js/jquery.simpleGallery.js"></script>
  <script type="text/javascript" src="js/jquery.simpleLens.js"></script>
  <!-- slick slider -->
  <script type="text/javascript" src="js/slick.js"></script>
  <!-- Price picker slider -->
  <script type="text/javascript" src="js/nouislider.js"></script>
  <!-- Custom js -->
  <script src="js/custom.js"></script> 
  

  </body>
</html>