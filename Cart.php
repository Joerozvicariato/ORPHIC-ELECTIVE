<?php
require_once 'connection.php';


$user = $pdo->prepare("SELECT * FROM tbl_user");
$user->execute();
$selUser= $user->fetch(PDO::FETCH_ASSOC);

$cart = $pdo->prepare("SELECT * FROM tbl_cart a
JOIN tbl_product b ON a.product_id = b.product_id
 WHERE a.user_id =?");
$cart->execute([$selUser['user_id']]);
$selcart= $cart->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>My Cart - ORPHIC</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef1f6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            width: 100%;
            background-color: #141d4e;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 15rem;
        }

        nav img {
            max-height: 60px;
            margin: 0 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0 15px;
            font-weight: bold;
        }

        .navbar-links {
            display: flex;
            gap: 5rem;
        }

        .searchandcart {
            margin: 0 15rem;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            gap: 40px;
        }

        .search-bar {
            width: 950px;
            height: 50px;
        }

        .search-bar input[type="text"] {
            width: 100%;
            height: 100%;
            padding: 0 15px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 1em;
        }

        .cart-icon {
            font-size: 26px;
            cursor: pointer;
            color: #333;
            background-color: yellow;
            border-radius: 50%;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
        }

        h2 {
            margin: 2rem 15rem;
            font-size: 28px;
            color: #333;
        }

        .cart-container {
            margin: 0 15rem;
            flex: 1;
        }

        .cart-item {
            background-color: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-right: 20px;
        }

        .cart-details {
            flex: 1;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .rating {
            color: gold;
            margin: 5px 0;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }

        .checkout-button {
            padding: 10px 20px;
            background-color: #3b5b88;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .checkout-button:hover {
            background-color: #27416a;
        }

        .cart-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-left: 20px;
        }

        .remove-button {
            background-color: #d9534f;
        }

        .remove-button:hover {
            background-color: #c9302c;
        }

        .orphic-footer {
            background-color: #f9f9f9;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 40px;
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 0 60px 40px;
        }

        .footer-section {
            flex: 1 1 220px;
            margin: 15px;
        }

        .footer-section h3 {
            font-size: 16px;
            margin-bottom: 12px;
            color: #222;
        }

        .footer-section p {
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 8px;
        }

        .footer-section ul li a {
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-section ul li a:hover {
            text-decoration: underline;
        }

        .footer-icons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer-icons img {
            height: 25px;
            max-width: 100px;
        }

        .footer-bottom {
            background-color: #e9eef6;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            font-size: 14px;
        }

        .footer-bottom .social {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-bottom .social span {
            font-weight: 600;
            margin-right: 5px;
        }

        .footer-bottom .social img {
            height: 20px;
            width: 20px;
        }

        #accountModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        #accountModalContent {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
        }

        #accountModal .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #accountModal .close:hover,
        #accountModal .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav>
        <div class="navbar">
            <img src="https://c.animaapp.com/maksq8u46pByZ6/img/logo.png" alt="Orphic Logo">
            <div class="navbar-links">
                <div>
                    <a href="Home.php">Home</a>
                    <a href="Home.html#product-list">Today's Deals</a>
                    <a href="orders.html">Orders</a>
                </div>
                <div>
                    <a href="#" id="openAccountModal">You</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="searchandcart">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search...">


        </div>
        <a href="Cart.php">
            <span class="cart-icon">&#128722;</span>
        </a>
    </div>
    <div><br>
<h2>My Cart</h2>
<div class="cart-container" id="cart-items">
    <form method="post" action="Cart.php">
    <table style="width:100%; background:white; border-radius:12px; box-shadow:2px 2px 5px rgba(0,0,0,0.08); border-collapse:separate; border-spacing:0 15px;">
        <thead>
            <tr style="background:#f5f7fa;">
                <th style="padding:12px; text-align:left;">Image</th>
                <th style="padding:12px; text-align:left;">Product Name</th>
                <th style="padding:12px; text-align:left;">Price</th>
                <th style="padding:12px; text-align:left;">Rating</th>
                <th style="padding:12px; text-align:left;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Handle delete action
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cart_id'])) {
                $deleteStmt = $pdo->prepare("DELETE FROM tbl_cart WHERE cart_id = ?");
                $deleteStmt->execute([$_POST['delete_cart_id']]);
                echo "<script>window.location='Cart.php';</script>";
                exit;
            }
            // Handle single item checkout
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout_cart_id'])) {
                // Example: Remove only the checked out item from cart
                $checkoutStmt = $pdo->prepare("DELETE FROM tbl_cart WHERE cart_id = ?");
                $checkoutStmt->execute([$_POST['checkout_cart_id']]);
                echo "<script>alert('Item checked out!');window.location='Cart.php';</script>";
                exit;
            }
            // Handle checkout all action
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
                $checkoutStmt = $pdo->prepare("DELETE FROM tbl_cart WHERE user_id = ?");
                $checkoutStmt->execute([$selUser['user_id']]);
                echo "<script>alert('Checkout successful!');window.location='Cart.php';</script>";
                exit;
            }
            $total = 0;
            foreach ($selcart as $row):
                $total += $row['product_price'];
            ?>
            <tr style="background:#fff;">
                <td style="padding:12px;">
                    <img src="photos/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width:80px; height:80px; object-fit:contain; border-radius:8px;">
                </td>
                <td style="padding:12px; font-weight:600;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td style="padding:12px; color:#111; font-weight:bold;">₱<?php echo htmlspecialchars($row['product_price']); ?></td>
                <td style="padding:12px; color:gold;"><?php echo htmlspecialchars($row['product_rating']); ?></td>
                <td style="padding:12px;">
                    <button type="submit" name="delete_cart_id" value="<?php echo $row['cart_id']; ?>" class="checkout-button remove-button" onclick="return confirm('Remove this item?')">Delete</button>
                    <button type="submit" name="checkout_cart_id" value="<?php echo $row['cart_id']; ?>" class="checkout-button" style="background-color:#28a745; margin-left:8px;" onclick="return confirm('Check out this item only?')">Check Out</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:18px; font-weight:bold;">Total: ₱<?php echo number_format($total, 2); ?></div>
        <?php if (count($selcart) > 0): ?>
            <button type="submit" name="checkout" class="checkout-button" onclick="return confirm('Proceed to checkout?')">Checkout All</button>
        <?php endif; ?>
    </div>
    </form>
</div>

</div>

<!-- Account Modal -->
<div id="accountModal">
  <div id="accountModalContent">
    <span class="close" onclick="closeAccountModal()">&times;</span>
    <h2>Your Account</h2>
    <p><strong>Name:</strong> Joeroz</p>
    <p><strong>Email:</strong> joerozvicariato@gmail.com</p>
    <p><strong>Member Since:</strong> 2023-10-26</p>
    <p><strong>Address:</strong> Lingion, Manolo Fortich Bukidnon</p>
    <p><strong>Phone:</strong> 09123456789</p>
    <button onclick="logout()">Log Out</button>
  </div>
</div>

<!-- JavaScript -->

  <!-- Orders Section -->
    <!-- Cart Items -->
    

    <!-- Footer -->
    <footer class="orphic-footer">
        <div class="footer-container">
            <div class="footer-section about">
                <h3>ABOUT US</h3>
                <p>ORPHIC is an eCommerce platform based in the Philippines, designed to serve the growing community of
                    PC builders, gamers, and tech enthusiasts. It empowers users to build their own custom PCs with
                    confidence by offering essential tools. ORPHIC aims to become the go-to destination for reliable,
                    beginner-friendly PC building solutions that combine both function and convenience.</p>
            </div>

            <div class="footer-section payments">
                <h3>PAYMENT METHODS</h3>
                <div class="footer-icons">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/gcash-logo-500x281-1.png" alt="GCash">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/image-5.png" alt="PayMaya">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/image-6.png" alt="BPI">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal">
                </div>
            </div>

            <div class="footer-section logistics">
                <h3>LOGISTICS</h3>
                <div class="footer-icons">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/flash-express-malaysia-1634103999-removebg-preview-1.png"
                        alt="Flash Express">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/648px-j-t-express-logo-svg-removebg-preview-1.png"
                        alt="J&T Express">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/ninjavan-svg-1.png" alt="Ninja Van">
                    <img src="https://c.animaapp.com/maixeniyECsT9y/img/2go-travel-logo--2018--svg-1.png"
                        alt="2GO Express">
                </div>
            </div>

            <div class="footer-section service">
                <h3>CUSTOMER SERVICE</h3>
                <ul>
                    <li><a href="#">Help Centre</a></li>
                    <li><a href="#">Payment Methods</a></li>
                    <li><a href="#">Order Tracking</a></li>
                    <li><a href="#">Free Shipping</a></li>
                    <li><a href="#">Return & Refund</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social">
                <span>FOLLOW US</span>
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png"
                        alt="Instagram"></a>
                <a href="#"><img src="https://c.animaapp.com/maixeniyECsT9y/img/image-9.png" alt="Twitter"></a>
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png"
                        alt="LinkedIn"></a>
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/1/1b/Facebook_icon.svg"
                        alt="Facebook"></a>
            </div>
            <p>&copy; Orphic2025. All rights reserved.</p>
        </div>
    </footer>
