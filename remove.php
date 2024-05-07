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
    if (!isset($_SESSION['account'])) 
    {
      echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
      exit();
    } 
    else if ($_SESSION['role'] != "admin") 
    {
      echo "<script>alert('無權訪問'); window.location.href = 'logout.php';</script>";
      exit();
    }

    // 處理管理員調出使用者清單
    include "db.php";
    
    // Pagination variables
    $limit = 10; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for SQL query

    // Query to fetch products with pagination
    $stmt = $link->prepare("SELECT * FROM `product` LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $html = "<table><tr><th></th><th>Image</th><th>Type</th><th>Name</th><th>Price</th></tr>";
    while ($clothes = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $html .= "<tr>";
        $html .= "<td><form action=\"remove.php\" method=\"post\" onsubmit=\"return confirmDelete();\">"."<input type=\"hidden\" name=\"deletePID\" value=\"".$clothes['PID']."\">"."<button type=\"submit\" style=\"border: none; background-color: transparent; color: white;\" onmouseover=\"this.querySelector('fa.fa-close').style.color='black'\" onmouseout=\"this.querySelector('fa.fa-close').style.color='red'\"><fa class=\"fa fa-close\" style=\"color: red\"></fa></button></form></td>";
        $html .= '<td><img src="data:image/jpeg;base64,'.base64_encode($clothes['image']).'" alt="Product Image" width="80" height="100"></td>';
        $html .= "<td>" . htmlspecialchars($clothes['type']) . "</td>";
        $html .= "<td>" . htmlspecialchars($clothes['name']) . "</td>";
        $html .= "<td>$" . htmlspecialchars($clothes['price']) . "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";

    // Count total number of products
    $stmt_count = $link->prepare("SELECT COUNT(*) as total FROM `product`");
    $stmt_count->execute();
    $total_results = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];

    // Calculate total number of pages
    $total_pages = ceil($total_results / $limit);

    // Pagination links
    $pagination_html = '';
    if ($total_pages > 1) {
        $pagination_html .= '<nav><ul class="pagination">';
        $prev_page = $page - 1;
        $next_page = $page + 1;
        $pagination_html .= '<li>';
        if ($page > 1) {
            $pagination_html .= '<a href="?page=' . $prev_page . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
        } else {
            $pagination_html .= '<span aria-hidden="true">&laquo;</span>';
        }
        $pagination_html .= '</li>';
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination_html .= '<li';
            if ($i == $page) {
                $pagination_html .= ' class="active"';
            }
            $pagination_html .= '><a href="?page=' . $i . '">' . $i . '</a></li>';
        }
        $pagination_html .= '<li>';
        if ($page < $total_pages) {
            $pagination_html .= '<a href="?page=' . $next_page . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
        } else {
            $pagination_html .= '<span aria-hidden="true">&raquo;</span>';
        }
        $pagination_html .= '</li>';
        $pagination_html .= '</ul></nav>';
    }
?>
    
    <?php
        if (($_SERVER['REQUEST_METHOD'] === "POST")&&(isset($_POST['deletePID']))){
            include "db.php";
            $deleteProductID = $_POST['deletePID'];
            $stmt = $link -> prepare("DELETE FROM `product` WHERE PID = :deletePID");
            $stmt->bindParam(':deletePID', $deleteProductID);
            $stmt->execute();
            echo "<script>alert('已從上架中移除');</script>";
            echo '<script>window.location.href="remove.php";</script>';
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
              <div class="aa-logo">
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
  </header>

  <section id="aa-catg-head-banner">
   <img src="img/fashion/clothes.jpg" alt="fashion img">
   <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Remove Page</h2>
        <ol class="breadcrumb">
          <li><a href="manageAccounts.php">Home</a></li>                   
          <li class="active">Remove</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
  <div class="container">
    <div class="bg-light rounded h-100 d-flex align-items-center p-5"><?php echo $html;?></div>
</div>

<div class="aa-product-catg-pagination" style="text-align: center;">
    <?php echo $pagination_html; ?>
</div>

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