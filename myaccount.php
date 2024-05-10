<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>DE Shop | Upadte Page</title>
    
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

    if (!isset($_SESSION['account'])) 
    {
        echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
        exit(); 
    }

    include "db.php";
    
    try 
    {
        $stmt = $link->prepare("SELECT * FROM members WHERE account = :account");
        $stmt->bindParam(':account', $_SESSION['account']);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) 
        {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $role = $user['role'];
            $account = $user['account'];
            $email = $user['email'];
            $phone = $user['phone'];
        } 
    } 
    catch (PDOException $e) 
    {
        die("Database error: " . $e->getMessage());
    }
    ?>

    <?php
    if (($_SERVER['REQUEST_METHOD'] === "POST")&&(isset($_POST['update']))){ //update stands for the field name
        include "db.php";
        $fieldToUpdate = $_POST['update'];
        $updateValue = $_POST[$fieldToUpdate]?? '';
        // echo "<script>alert('".$fieldToUpdate.$updateValue."');</script>";

        if ($fieldToUpdate === 'password') 
        { //處理更改密碼需要加密的部分
            if (($_POST['password'] === $_POST['confirmpassword']) && (strlen($_POST['password']) >= 4 && strlen($_POST['password']) <= 50)) 
            {
                $updateValue = $_POST['password'];
            } 
            else 
            {
                echo "<script>alert('密碼與確認密碼不相同或是密碼長度低於4個字元或高於50個字元'); window.history.back();</script>";
                exit();
            }
        }

        if ($fieldToUpdate === 'account')
        {
            $checkEmail = $link->prepare("SELECT COUNT(*) FROM members WHERE email = :email");
            $checkEmail -> bindParam(':email', $_POST['email']);
            $checkEmail -> execute();
  
            if($checkEmail->fetchColumn() > 0) 
            {
                echo "<script>alert('電子郵箱已經被使用'); window.history.back();</script>";
                exit();
            }
        }

        if ($fieldToUpdate === 'account')
        {
            $checkAccount = $link->prepare("SELECT COUNT(*) FROM members WHERE account = :account");
            $checkAccount -> bindParam(':account', $_POST['account']);
            $checkAccount -> execute();
  
            if($checkAccount->fetchColumn() > 0) 
            {
                echo "<script>alert('帳號已經被使用'); window.history.back();</script>";
                exit();
            }
        }

        if ($fieldToUpdate === 'email')
        {
            $checkEmail = $link->prepare("SELECT COUNT(*) FROM members WHERE email = :email");
            $checkEmail -> bindParam(':email', $_POST['email']);
            $checkEmail -> execute();
  
            if($checkEmail->fetchColumn() > 0) 
            {
                echo "<script>alert('電子郵箱已經被使用'); window.history.back();</script>";
                exit();
            }
        }  

        try { //更新資料庫
            $stmt = $link->prepare("UPDATE members SET `$fieldToUpdate` = :updateValue WHERE ID = :ID");
            $stmt->bindParam(':updateValue', $updateValue);
            $stmt->bindParam(':ID', $_SESSION['ID']);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) 
            {
                echo "<script>alert('更新成功'); window.location.href = 'myaccount.php';</script>";
                if($fieldToUpdate == "account") $_SESSION['account'] = $updateValue; 
            } else {
                echo "<script>alert('無變更導致的未更新'); window.history.back();</script>";
            }
        } 
        
        catch (PDOException $e) {
            die("Database error during update: " . $e->getMessage());
        }
    }

    ?>
    <style>
        table {
            width: 100%;        /* 表格寬度佔滿父元素 */
            border-collapse: collapse; /* 邊框合併為單一邊框 */
            margin: 20px 0;     /* 上下邊距為 20px，左右為 0 */
            font-family: Arial, sans-serif; /* 使用 Arial 或無襯線字體 */
            color: #333;        /* 字體顏色 */
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1); /* 輕微陰影效果 */
            background-color: #ffffff; /* 白色背景 */
        }

        /* 表格標頭 */
        th {
            background-color: #f2f2f2; /* 標頭背景顏色 */
            color: #333;        /* 標頭文字顏色 */
            font-weight: bold;  /* 粗體文字 */
            padding: 12px 15px; /* 內距 */
            text-align: center;   /* 文字對齊 */
        }

        /* 表格行與單元格 */
        tr {
            border-bottom: 1px solid #ddd; /* 行底部邊框 */
        }

        td {
            padding: 12px 15px; /* 單元格內距 */
            text-align: center;   /* 文字對齊 */
        }

        /* 滑過行變色效果 */
        tr:hover {
            background-color: #f5f5f5; /* 滑過時的背景顏色 */
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
              </div>
              <!-- / logo  -->       
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
        <h2>Update Page</h2>
        <ol class="breadcrumb">
          <li><a href="menu.php">Home</a></li>         
          <li class="active">Update</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
  <div class="container">
  <div class="bg-light rounded h-100 d-flex align-items-center p-5">
        <table>
            <tr>
                <th>Role</th>
                <td><input type="text" class="form-control border-0" value="<?php echo $role;?>" readonly></td>
                <td></td>
            </tr>
            <tr>
                <form action="myaccount.php" method="post" autocomplete="off">
                    <th>Account</th>
                    <td><input type="text" name="account"  class="form-control border-0" value="<?php echo $account;?>" ></td>
                    <td><button type="submit" name="update" value="account">Update Account</button></td>
                </form>
            </tr>
            <tr>
                <form action="myaccount.php" method="post" autocomplete="off">
                    <th>Email</th>
                    <td><input type="email" name="email"  class="form-control border-0" value="<?php echo $email;?>" ></td>
                    <td><button type="submit" name="update" value="email">Update Email</button></td>
                </form>
            </tr>
            <tr>
                <form action="myaccount.php" method="post" autocomplete="off">
                    <th>Phone</th>
                    <td><input type="tel" name="phone" class="form-control border-0" value="<?php echo $phone;?>" ></td>
                    <td><button type="submit" name="update" value="phone">Update Phone</button></td>
                </form>
            </tr>
            <form action="myaccount.php" method="post" autocomplete="off">
            <tr>
                <th>Password</th>
                <td><input type="password" name="password" class="form-control border-0"></td>
                <td rowspan="2" ><button type="submit" name="update" value="password">Update Password</button></td>
            </tr>
            <tr>
                <th>ConfirmPassword</th>
                <td><input type="password" name="confirmpassword" class="form-control border-0"></td>
            </tr>
            </form>
        </table>
    </div>
    </div>
  <!-- / catg header banner section -->

 <!-- Cart view section -->
 
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