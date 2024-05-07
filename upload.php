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
    else if ($_SESSION['role'] != "admin") 
    {
      echo "<script>alert('無權訪問'); window.location.href = 'logout.php';</script>";
      exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include "db.php";
        
        $type = $_POST['type'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = $_FILES['image'];
        
        if (empty($name) || empty($price) || $type == "" || $image['error'] !== UPLOAD_ERR_OK) 
        {
            echo "<script>alert('請填寫所有欄位並選擇一個圖片');</script>";
        } 
        else 
        {
            $check = getimagesize($image["tmp_name"]);
            if ($check !== false) {
                $imageContent = file_get_contents($image["tmp_name"]); 

                if ($imageContent !== false) 
                {
                    $sql = "INSERT INTO product (name, ID, price, type, image) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $link->prepare($sql);
                    $stmt->bindParam(1, $name);
                    $stmt->bindParam(2, $_SESSION['ID']);
                    $stmt->bindParam(3, $price);
                    $stmt->bindParam(4, $type);
                    $stmt->bindParam(5, $imageContent, PDO::PARAM_LOB);

                    if ($stmt->execute()) 
                    {
                        echo "<script>alert('產品已成功上傳');</script>";
                    } 
                    else 
                    {
                        echo "<script>alert('產品上傳失敗：" . $stmt->errorInfo()[2] . "');</script>";
                    }
                } 
                else 
                {
                    echo "<script>alert('圖片讀取失敗');</script>";
                }
            } 
            else 
            {
                echo "<script>alert('所上傳文件非有效的圖片');</script>";
            }
        }
        // $db->close();
    }
    ?>
  <style>
    form {
        background-color: white; /* 表單背景設為白色 */
        padding: 20px;
        border-radius: 8px; /* 圓角邊框 */
        box-shadow: 0 0 10px rgba(0,0,0,0.1); /* 輕微陰影效果 */
        width: 80%; /* 表單寬度 */
        max-width: 500px; /* 最大寬度為500px */
        margin: 0 auto; /* 水平置中 */
    }

    h1 {
        color: #333; /* 深灰色標題 */
        text-align: center; /* 標題文字置中 */
    }

    label {
        margin-top: 10px; /* 每個標籤上方留白 */
        display: block; /* 確保每個元素佔滿一整行 */
        color: #666; /* 文字顏色 */
        font-size: 16px; /* 字體大小 */
    }

    input[type="text"],
    textarea,
    input[type="file"] {
        width: calc(100% - 22px); /* 輸入框寬度為容器寬度減去邊框 */
        padding: 10px; /* 內邊距 */
        margin-top: 5px; /* 上邊距 */
        border: 1px solid #ddd; /* 邊框顏色 */
        border-radius: 4px; /* 圓角邊框 */
    }

    textarea {
        height: 100px; /* 文本域高度 */
        resize: vertical; /* 允許垂直調整大小 */
    }

    input[type="submit"] {
        background-color: #ff6666; /* 提交按鈕背景色 */
        color: white; /* 文字顏色 */
        padding: 10px 20px; /* 內邊距 */
        border: none; /* 無邊框 */
        border-radius: 4px; /* 圓角邊框 */
        cursor: pointer; /* 滑鼠指針變為手型 */
        display: block; /* 確保占滿整行 */
        width: 100%; /* 寬度 */
        margin-top: 20px; /* 上邊距 */
    }

    input[type="submit"]:hover {
        background-color: #8b0000; /* 鼠標懸停時的背景色 */

    
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
  <section id="aa-catg-head-banner">
    <img src="img/fashion/clothes.jpg" alt="fashion img">
    <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Upload Page</h2>
        <ol class="breadcrumb">
          <li><a href="manageAccounts.php">Home</a></li>         
          <li class="active">Upload</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
 
  <!-- catg header banner section -->
  

 <section id="cart-view" style="margin-top: 20px; margin-bottom: 20px;">
   <div class="container">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <h3>Upload product</h3>
            <label for="name">Enter name:</label>
            <input type="text" name="name" id="name" required><br><br>

            <label for="price">Set price:</label>
            <input type="text" name="price" id="price" required><br><br>

            <label for="price">Type:</label>
            <select name="type" class="form-control border-0" >
                <option value="" selected>Please choose</option>
                <option value="Short Sleeves">Short Sleeves</option>
                <option value="Long Sleeve Top">Long Sleeve Top</option>
                <option value="Pants">Pants</option>
                <option value="Coat">Coat</option>
            </select>

            <label for="image">Upload image:</label>
            <input type="file" name="image" id="image" required><br><br>

            <input type="submit" value="Upload product">
        </form>
    </div>
  </section>

 
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