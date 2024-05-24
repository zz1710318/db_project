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
        $sql = "SELECT * FROM product t1
                JOIN wishlist t2 ON t1.PID = t2.PID
                WHERE t2.ID = :ID";
        $stmt = $link->prepare($sql);
        $stmt -> bindParam(':ID', $_SESSION['ID']);
        $stmt->execute();
        
        $html = "<table><tr><th></th><th>Image</th><th>Type</th><th>Name</th><th>Price</th><th></th></tr>";
        while ($clothes = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $html .= "<tr>";
            $html .= "<td><form action=\"wishlist.php\" method=\"post\" onsubmit=\"return confirmDelete();\">"."<input type=\"hidden\" name=\"deletePID\" value=\"".$clothes['PID']."\">"."<button type=\"submit\" style=\"border: none; background-color: transparent; color: white;\" onmouseover=\"this.querySelector('fa.fa-close').style.color='black'\" onmouseout=\"this.querySelector('fa.fa-close').style.color='red'\"><fa class=\"fa fa-close\" style=\"color: red\"></fa></button></form></td>";
            $html .= '<td><img src="data:image/jpeg;base64,'.base64_encode($clothes['image']).'" alt="Product Image" width="80" height="100"></td>';
            $html .= "<td>" . htmlspecialchars($clothes['type']) . "</td>";
            $html .= "<td>" . htmlspecialchars($clothes['name']) . "</td>";
            $html .= "<td>$" . htmlspecialchars($clothes['price']) . "</td>";
            $html .= '<td><form action="wishlist.php" method="post"><input type="hidden" name="addToCartPID" value="'.$clothes['PID'].'"><button type="submit" name="addToCart" value="true" style="padding: 10px 20px; background-color: transparent; border: 2px solid #000; color: #000; cursor: pointer;" onmouseover="this.style.borderColor=\'#ff6666\'; this.style.color=\'#ff6666\'" onmouseout="this.style.borderColor=\'#000\'; this.style.color=\'#000\'">Add To Cart</button></form></td>';
            $html .= "</tr>";
        }
        $html .= "</table>";
    ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['addToCart'])) {
        $PID = $_POST['addToCartPID'];
        $userID = $_SESSION['ID'];

        // 检查购物车中是否已存在相同产品
        $checkCartExists = $link->prepare("SELECT * FROM cart WHERE PID = :PID AND ID = :userID");
        $checkCartExists->bindParam(':PID', $PID);
        $checkCartExists->bindParam(':userID', $userID);
        $checkCartExists->execute();
        $existingCartItem = $checkCartExists->fetch(PDO::FETCH_ASSOC);

        if ($existingCartItem) {
            // 如果购物车中已存在相同产品，则更新数量
            $quantity = $existingCartItem['quantity'] + 1;
            $updateQuantityStmt = $link->prepare("UPDATE cart SET quantity = :quantity WHERE PID = :PID AND ID = :userID");
            $updateQuantityStmt->bindParam(':quantity', $quantity);
            $updateQuantityStmt->bindParam(':PID', $PID);
            $updateQuantityStmt->bindParam(':userID', $userID);
            $updateQuantityStmt->execute();
        } else {
            // 如果购物车中不存在相同产品，则插入新记录
            $stmt = $link->prepare("INSERT INTO `cart`(`ID`, `PID`, `quantity`) VALUES (:userID, :PID, 1)");
            $stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':PID', $PID);
            $stmt->execute();
        }

        // 提示商品已加入购物车
        echo "<script>alert('商品已加入購物車');</script>";
        echo '<script>window.location.href="wishlist.php";</script>';
    }
?>

    <?php
        if (($_SERVER['REQUEST_METHOD'] === "POST")&&(isset($_POST['deletePID']))){
            include "database.php";
            $deleteProductID = $_POST['deletePID'];
            $stmt = $link -> prepare("DELETE FROM `wishlist` WHERE PID = :deletePID");
            $stmt->bindParam(':deletePID', $deleteProductID);
            $stmt->execute();

            echo '<script>window.location.href="wishlist.php";</script>';
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
              
            $total=0;
            $displayed_count = 0; // 初始化已顯示計數器

            while ($clothes = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $subtotal = $clothes['price'] * $clothes['quantity']; 
                $total += $subtotal;
                if ($displayed_count < 3) {
                    echo "<li>";
                    echo "<a class='aa-cartbox-img' href='#'><img src='data:image/jpeg;base64," . base64_encode($clothes['image']) . "' alt='Product Image'></a>";
                    echo "<div class='aa-cartbox-info'>";
                    echo "<h4><a>" . htmlspecialchars($clothes['type']) . "</a></h4>";
                    echo "<h4><a>" . htmlspecialchars($clothes['name']) . "</a></h4>";
                    echo "<p>" . htmlspecialchars($clothes['quantity']) ." x $". htmlspecialchars($clothes['price']) . "</p>";
                    echo "</div></li>";

                    $displayed_count++; // 每顯示一個商品，計數器加1
                } else {
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
        <h2>Wishlist Page</h2>
        <ol class="breadcrumb">
          <li><a href="menu.php">Home</a></li>                   
          <li class="active">Wishlist</li>
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