<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>DE Shop | Account Page</title>
    
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
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

		session_start();

		include "database.php";

		if ($_SERVER['REQUEST_METHOD'] === "POST")
    {
      $account = $_POST['account']?? '';
			$password = $_POST['password'] ?? '';
      $confirmpassword = $_POST['confirmpassword'] ?? '';
			$email = $_POST['email'] ?? '';
      $phone = $_POST['phone'] ?? '';
			$errors = '';

      if (empty($account)) 
      {
				$errors .= "使用者帳號不得為空\\n";
			} 
      else if (strlen($account) < 4 || strlen($account) > 20) 
      {
				$errors .= "使用者帳號的長度必須至少4個字元且少於20個字元\\n";
			}
			
			if (empty($password)) 
      {
				$errors .= "你的密碼不得為空\\n";
			} 
      else if (strlen($password) < 4 || strlen($password) > 50) 
      {
				$errors .= "密碼的長度必須至少4個字元且少於50個字元\\n";
			}
			
      if (empty($confirmpassword)) 
      {
				$errors .= "再次輸入密碼不得為空\\n";
			} 
      else if ($password != $confirmpassword) 
      {
				$errors .= "你的密碼與再次確認密碼不同，請確保他們是相同的\\n";
			} 
      else if (strlen($confirmpassword) < 4 || strlen($confirmpassword) > 50) 
      {
				$errors .= "確認密碼的長度必須至少4個字元且少於50個字元\\n";
			}

      if (empty($email)) 
      {
				$errors .= "電子郵箱不得為空\\n";
			} 
      else if (strlen($email) < 4 || strlen($email) > 50) 
      {
				$errors .= "電子郵箱的長度必須至少4個字元且少於50個字元\\n";
			}

      if (empty($phone)) 
      {
				$errors .= "手機號碼不得為空\\n";
			} 
      else if (strlen($phone) != 10) 
      {
				$errors .= "手機號碼的長度必須等於10個字元\\n";
			}

			if(empty($errors))
      {
        // Prepare and execute the query to check if the account already exists
        $checkUser = $link->prepare("SELECT COUNT(*) FROM members WHERE account = :account");
        $checkUser->bindParam(':account', $account);
        $checkUser->execute();
    
        // Fetch the count of rows returned by the query
        if($checkUser->fetchColumn() > 0) 
        {
            $errors .= "使用者名稱已經被註冊\\n";
        }

        $checkEmail = $link->prepare("SELECT COUNT(*) FROM members WHERE email = :email");
				$checkEmail -> bindParam(':email', $email);
				$checkEmail -> execute();

				if($checkEmail->fetchColumn() > 0) $errors.= "電子郵箱已經被註冊\\n";
      }
    
    // Check if there are any errors
    if (!empty($errors)) 
    {
        echo "<script>alert('$errors');</script>";
    } 
    else 
    {
        try 
        {
            // Prepare the insert statement to add a new member
            $stmt = $link->prepare("INSERT INTO members (role, account, password, email, phone) VALUES (:role, :account, :password, :email ,:phone)");
            
            // Define values for parameters
            $role = 'user';
            
            // Bind parameters
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':account', $account);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            
            // Execute the statement
            $stmt->execute();
            
            // Display success message and redirect
            echo "<script>
                    alert('使用者註冊成功');
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 0);
                </script>";
    
        } 
        catch (PDOException $e) 
        {
            // Handle database errors
            echo "資料庫錯誤: " . $e->getMessage();
        }
    }
    
		}
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
                  <li><a href="login.php">Login</a></li>
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
                <a href="login.php">
                  <span class="fa fa-shopping-cart"></span>
                  <p>DE<strong>Shop</strong> <span>Your Shopping Partner</span></p>
                </a>
                <!-- img based logo -->
                <!-- <a href="index.html"><img src="img/logo.jpg" alt="logo img"></a> -->
              </div>
              <!-- / logo  -->
               <!-- cart box -->
              
              <!-- / cart box -->
              <!-- search box -->
              
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
  
  <!-- / menu -->  
 
  <!-- catg header banner section -->
  <section id="aa-catg-head-banner">
    <img src="img/fashion/clothes.jpg" alt="fashion img">
    <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Account Page</h2>
        <ol class="breadcrumb">
          <li><a href="login.php">Home</a></li>                   
          <li class="active">Account</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
  <!-- / catg header banner section -->

 <!-- Cart view section -->
 <section id="aa-myaccount">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
        <div class="aa-myaccount-area">         
            <div class="row">
              <div class="col-md-6">
                <div class="aa-myaccount-register">                 
                 <h4>Register</h4>
                 <form action="register.php" class="aa-login-form" method="post">
                    <label for="">Account<span>*</span></label>
                    <input type="text" class="form-control border-0" name="account" placeholder="Account">
                    <label for="">Email<span>*</span></label>
                    <input type="email" class="form-control border-0" name="email" placeholder="Email">
                    <label for="">Phone<span>*</span></label>
                    <input type="text" class="form-control border-0" name="phone" placeholder="Phone">
                    <label for="">Password<span>*</span></label>
                    <input type="password" class="form-control border-0" name="password"placeholder="Password">
                    <label for="">Confirmpassword<span>*</span></label>
                    <input type="password" class="form-control border-0" name="confirmpassword"placeholder="Confirmpassword">
                    <button type="submit" class="aa-browse-btn">Register</button>                    
                  </form>
                </div>
              </div>
            </div>          
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