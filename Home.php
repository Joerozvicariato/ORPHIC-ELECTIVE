<?php
require_once "connection.php";

if (!isset($_SESSION['username'])|| !isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}
echo "Welcome, " . htmlspecialchars($_SESSION['user_id']) . "!";


$product = $pdo->prepare("SELECT * FROM tbl_product");
$product->execute();
$selProduct= $product->fetchAll(PDO::FETCH_ASSOC);
?>
<script>src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</script>
<!DOCTYPE html>
<html>

<head>
    <title>Orphic - Your Online Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }


        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        nav {
            width: 100%;
            background-color: #141d4e;
        }

        .navbar {
            align-items: center;
            display: flex;
            justify-content: space-between;
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
            justify-content: space-between;
            align-items: flex-start;
            padding: 2rem 15rem;
            height: 400px;
            position: relative;
            color: white;
            background:
                linear-gradient(to bottom, #0d1d4bcc, #ffffffcc),
                url('photos/DesignImage.png') no-repeat center center;
            background-size: cover;
        }


        .search-bar {
            flex: 1;
            margin-right: 2rem;
        }

        .search-bar input[type="text"] {
            width: 100%;
            height: 50px;
            padding: 0 20px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
        }

        .cart-icon {
            font-size: 26px;
            cursor: pointer;
            color: black;
            background-color: yellow;
            border-radius: 50%;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            z-index: 2;
        }

        .slogan {
            position: absolute;
            bottom: 40px;
            left: 15rem;
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            text-shadow: 0 2px 1px rgba(0, 0, 0, 100);
        }

        .slogan .orange {
            color: #ff8c00;
            /* Bright gamer-style orange */
        }

        section {
            margin: 0 15rem;
            padding-left: 20px;
        }

        .deals {
            color: #141d4e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }


        main {
            margin: 0 15rem;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
        }

        .product-card {
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .product-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
            background-color: #e2e2e2;
        }

        .product-title {
            font-weight: bold;
            height: 50px;
            margin-top: 10px;
        }

        .product-price {
            color: #007bff;
            font-weight: bold;
            margin-top: 5px;
        }

        .product-rating {
            margin-top: 5px;
        }

        .buy-button,
        .add-to-cart-button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 5px;
        }

        .add-to-cart-button {
            background-color: #28a745;
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

    <nav>
        <div class="navbar">
            <img src="https://c.animaapp.com/maksq8u46pByZ6/img/logo.png" alt="Orphic Logo">
            <div class="navbar-links">
                <div>
                    <a href="Home.php">Home</a>
                    <a href="#product-list">Today's Deals</a>
                    <a href="orders.html">Orders</a>
                </div>
                <div>
                    <a href="#" id="openAccountModal">You</a>
                </div>
            </div>
        </div>
    </nav>
    <script>
        document.getElementById('openAccountModal').onclick = function(e) {
            e.preventDefault();
            document.getElementById('accountModal').style.display = 'block';
        };
    </script>
    <div class="searchandcart">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterProducts()">
        </div>
        <script>
        function filterProducts() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var cards = document.querySelectorAll('.product-card');
            cards.forEach(function(card) {
                var title = card.querySelector('.product-title').textContent.toLowerCase();
                if (title.includes(input)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        </script>
        <a href="Cart.php">
            <span class="cart-icon">&#128722;</span>
        </a>
        <div class="slogan">
            <h2>
                Crafted for <br>
                Gamers. <span class="orange">Powered.</span><br>
                for <span class="orange">Victory.</span>
            </h2>
        </div>

    </div>

    <section class="deals">
        <h2>Today's deals</h2>
    </section>
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
<script>
  function closeAccountModal() {
    document.getElementById("accountModal").style.display = "none";
  }

  function logout() {
    // Optional: clear session/local storage
    // sessionStorage.clear(); localStorage.clear();

    // Redirect to your existing login form/page
    window.location.href = "login.php"; // change to your actual login page
  }
</script>

  <!-- Orders Section -->

    <main id="product-list">
        <!-- Product cards will be dynamically added here -->
        <?php foreach ($selProduct as $row): ?>
    <div class="product-card">
        <img src="photos/<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_image']; ?>" class="product-image">
        <div class="product-title"><?php echo $row['product_name']; ?></div>
        <div class="product-price">₱<?php echo $row['product_price']; ?></div>
        <div class="product-rating"><?php echo $row['product_rating']; ?></div>
        <button class="add-to-cart-button" onclick="addToCart('<?php echo $row['product_id']; ?>')">Add to Cart</button>
        <button class="buy-button" onclick="goToProductPage('<?php echo $row['product_id']; ?>')">Buy Now</button>
    </div>
<?php endforeach; ?>

</main>
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

    <script>
    function addToCart(productId) {
  fetch('addToCart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'productId=' + encodeURIComponent(productId) // Make sure the key is "productId"
  })
  .then(response => response.text())
  .then(data => {
    alert(data); // You can replace this with a toast notification
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

</script>


</body>

</html>