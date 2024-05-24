<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>DE Shop | Checkout Page</title>
    
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
if (!isset($_SESSION['account'])) 
{
  echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
  exit();
} 

// 處理管理員調出使用者清單
include "database.php";

$html = "";
$total = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  // Retrieve form data
  $recipient = isset($_POST['recipient']) ? $_POST['recipient'] : '';
  $address = isset($_POST['address']) ? $_POST['address'] : '';
  
  // Validate form data
  if (empty($recipient) || empty($address)) {
      echo "<script>alert('請填寫所有欄位');</script>";
  } else {
      // Calculate total pay amount
      $sql = "SELECT SUM(t1.price * t2.quantity) AS total FROM product t1
              JOIN cart t2 ON t1.PID = t2.PID
              WHERE t2.ID = :ID";
      $stmtTotal = $link->prepare($sql);
      $stmtTotal->bindParam(':ID', $_SESSION['ID']);
      $stmtTotal->execute();
      $totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);
      $total = $totalRow['total'];

      if ($total == 0) {
          echo "<script>alert('請選取商品');</script>";
      } else {
          // Begin transaction
          $link->beginTransaction();

          try {
              // Insert into orders table
              $sql = "INSERT INTO `orders` (`recipient`, `ID`, `address`, `method`, `payamount`, `date`) VALUES (:recipient, :ID, :address, :method, :payamount, :date)";
              $stmta = $link->prepare($sql);
              $stmta->bindParam(':recipient', $recipient);
              $stmta->bindParam(':ID', $_SESSION['ID']);
              $stmta->bindParam(':address', $address);
              $stmta->bindParam(':method', $_POST['method']);
              $stmta->bindParam(':payamount', $total);
              $date = date('Y-m-d H:i:s');
              $stmta->bindParam(':date', $date);
              $stmta->execute();

              // Retrieve the last inserted order ID
              $orderID = $link->lastInsertId();

              // Insert into orderDetail table
              $sql = "INSERT INTO `orderDetail` (`OID`, `PID`, `ID`, `quantity`) SELECT :orderID, `PID`, `ID`, `quantity` FROM `cart` WHERE `ID` = :ID";
              $stmtd = $link->prepare($sql);
              $stmtd->bindParam(':orderID', $orderID);
              $stmtd->bindParam(':ID', $_SESSION['ID']);
              $stmtd->execute();

              // Commit transaction
              $link->commit();

              // Clear cart for the current user
              $sql = "DELETE FROM cart WHERE ID = :ID";
              $stmtClearCart = $link->prepare($sql);
              $stmtClearCart->bindParam(':ID', $_SESSION['ID']);
              $stmtClearCart->execute();

              echo "<script>alert('已送出訂單');</script>";
          } 
          catch(PDOException $e) 
          {
              // Rollback transaction on error
              $link->rollback();
              echo "<script>alert('送出訂單失敗: " . $e->getMessage() . "');</script>";
          }
      }
  }
}

        $sql = "SELECT * FROM product t1
                JOIN cart t2 ON t1.PID = t2.PID
                WHERE t2.ID = :ID";
        $stmt = $link->prepare($sql);
        $stmt -> bindParam(':ID', $_SESSION['ID']);
        $stmt->execute();
        $total = 0;
        
        $html ="<tbody>";
        while ($clothes = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $html .= "<tr>";
            $html .= "<td>" . htmlspecialchars($clothes['quantity']) ." x ". htmlspecialchars($clothes['type']) ."-". htmlspecialchars($clothes['name'])."</td>";
            $subtotal = $clothes['price'] * $clothes['quantity'];
            $html .= "<td>$" . $subtotal . "</td>"; 
            $total += $subtotal;
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= '<tfoot>                        
            <tr>
              <th>Total</th>
              <td>$' . $total . '</td>
            </tr>
          </tfoot>';
?>

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
                  <li><a href="myaccount.php" class="nav-item nav-link active"><?php echo "Welcome，". $_SESSION['account'];?></a></li>
                  <li><a href="logout.php" class="btn btn-danger rounded-0 py-4 px-lg-5 d-none d-lg-block" style="background-color: #ff6666; color: white;">Logout<i class="fa fa-arrow-right ms-3"></i></a></li>
                  <li class="hidden-xs"><a href="wishlist.php">Wishlist</a></li>
                  <li class="hidden-xs"><a href="cart.php">My Cart</a></li>
                  <li class="hidden-xs"><a href="checkout.php">Checkout</a></li>
                  <li class="hidden-xs"><a href="order.php">My Order</a></li>
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
                <a href="menu.php">
                  <span class="fa fa-shopping-cart"></span>
                  <p>DE<strong>Shop</strong> <span>Your Shopping Partner</span></p>
                </a>
                <!-- img based logo -->
                <!-- <a href="index.html"><img src="img/logo.jpg" alt="logo img"></a> -->
              </div>
              <!-- / logo  -->
               <!-- cart box -->
  <div class="aa-cartbox">
    <a class="aa-cart-link" href="#">
        <span class="fa fa-shopping-basket"></span>
        <span class="aa-cart-title">SHOPPING CART</span>
        <?php
        $sql = "SELECT COUNT(*) as total FROM product t1
                  JOIN cart t2 ON t1.PID = t2.PID
                  WHERE t2.ID = :ID";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':ID', $_SESSION['ID']);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        ?>
        <span class="aa-cart-notify"><?php echo $count; ?></span>
    </a>

    <div class='aa-cartbox-summary'>
        <ul>
            <?php
            $sql = "SELECT * FROM product t1
                    JOIN cart t2 ON t1.PID = t2.PID
                    WHERE t2.ID = :ID";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':ID', $_SESSION['ID']);
            $stmt->execute();

            $displayed_count = 0; // 初始化已顯示計數器

            while ($clothes = $stmt->fetch(PDO::FETCH_ASSOC)) 
            {
                if ($displayed_count < 3) 
                {
                    echo "<li>";
                    echo "<a class='aa-cartbox-img' href='#'><img src='data:image/jpeg;base64," . base64_encode($clothes['image']) . "' alt='Product Image'></a>";
                    echo "<div class='aa-cartbox-info'>";
                    echo "<h4><a>" . htmlspecialchars($clothes['type']) . "</a></h4>";
                    echo "<h4><a>" . htmlspecialchars($clothes['name']) . "</a></h4>";
                    echo "<p>" . htmlspecialchars($clothes['quantity']) ." x $". htmlspecialchars($clothes['price']) . "</p>";

                    echo "</div></li>";

                    $displayed_count++; // 每顯示一個商品，計數器加1
                } 
                else 
                {
                    // 如果已顯示計數器超過3，則跳出迴圈
                    break;
                }
            }
            ?>
        </ul>
        <ul>
          <li>
    <span class="aa-cartbox-total-title">
        Total
    </span>
    <span class="aa-cartbox-total-price">
        <?php echo '$' . $total; ?>
    </span>
          </li>
          </ul>
        <?php
        // 計算剩餘未顯示商品數量
        $remaining_count = $count - $displayed_count;
        if ($remaining_count > 0) 
        {
          echo "<a style='color: #ff6666;'>$remaining_count items not shown.</a>";
        }
        ?>
        <a class='aa-cartbox-checkout aa-primary-btn' href='cart.php'>Check Cart</a>
    </div>
</div>
              <!-- / cart box -->
              <!-- search box -->
              <div class="aa-search-box">
                <form action="productall.php" method="GET">
                  <input type="text" name="search" placeholder="Search here ex. 'T-shirts'">
                    <button type="submit"><span class="fa fa-search"></span></button>
                </form>
              </div>
              <!-- / search box -->             
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / header bottom  -->
  </header>
  <!-- / header section -->
  <!-- menu -->
  <section id="menu">
    <div class="container">
      <div class="menu-area">
        <!-- Navbar -->
        <div class="navbar navbar-default" role="navigation">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>          
          </div>
          <div class="navbar-collapse collapse">
            <!-- Left nav -->
            <ul class="nav navbar-nav">
              <li><a href="menu.php"><img src="img/home.jpg" alt="Home" style="margin-top: -8px; filter: brightness(0) invert(1);"></a></li>
              <li><a href="productall.php">ALL</a></li> 
              <li><a href="product1.php">Short Sleeves <span class="caret"></span></a>
                <ul class="dropdown-menu">                
                  <li><a href="product1-1.php">Shirts</a></li>
                  <li><a href="product1-2.php">T-Shirts</a></li>
                </ul>
              </li>
              <li><a href="product2.php">Long Sleeve Top <span class="caret"></span></a>
                <ul class="dropdown-menu">  
                  <li><a href="product2-1.php">Shirts</a></li>                                                                
                  <li><a href="product2-2.php">T-Shirts</a></li>              
                </ul>
              </li>
              <li><a href="product3.php">Pants <span class="caret"></span></a>
                <ul class="dropdown-menu">                
                  <li><a href="product3-1.php">Shorts</a></li>
                  <li><a href="product3-2.php">Trousers</a></li>
                </ul>
              </li>
              <li><a href="product4.php">Coat</a></li>            

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div> 
      </div>
    </div>
  </section>
  <!-- / menu -->  
 
  <!-- catg header banner section -->
  <section id="aa-catg-head-banner">
    <img src="img/fashion/clothes.jpg" alt="fashion img">
    <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Checkout Page</h2>
        <ol class="breadcrumb">
          <li><a href="menu.php">Home</a></li>                   
          <li class="active">Checkout</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
  <!-- / catg header banner section -->

 <!-- Cart view section -->
 <section id="checkout">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
        <div class="checkout-area">
          <form action="checkout.php" method="POST">
            <div class="row">
              <div class="col-md-8">
                <div class="checkout-left">
                  <div class="panel-group" id="accordion">
                    <div class="panel panel-default aa-checkout-billaddress">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                            Shippping Address
                          </a>
                        </h4>
                      </div>
                      <div id="collapseFour" class="panel-collapse collapse">
                        <div class="panel-body">
                         <div class="row">
                            <div class="col-md-12">
                              <div class="aa-checkout-single-bill">
                                <input type="text" name="recipient" placeholder="Name*">
                              </div>
                            </div>
                          </div>                     
                          <div class="row">
                            <div class="col-md-12">
                              <div class="aa-checkout-single-bill">
                                <input type="text" name="address" placeholder="Address*">
                              </div>                             
                            </div>                            
                          </div>                  
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="checkout-right">
                  <h4>Order Summary</h4>
                  <div class="aa-order-summary-area">
                    <table class="table table-responsive">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                        <?php echo $html;?>
                    </table>
                  </div>
                  <h4>Payment Method</h4>
                  <div class="aa-payment-method">                    
                    <label for="CashOnDelivery"><input type="radio" id="CashOnDelivery" value="Cash on Delivery" name="method" checked> Cash on Delivery </label>
                    <label for="CreditCardPayment"><input type="radio" id="CreditCardPayment" value="Credit card payment" name="method"> Credit card payment </label>
                    <input type="submit" value="Place Order" class="aa-browse-btn">                
                  </div>
                </div>
              </div>
            </div>
          </form>
         </div>
       </div>
     </div>
   </div>
 </section>
 <!-- / Cart view section -->

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