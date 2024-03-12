<?php
session_start();

require_once 'core/Database.php';
require_once 'models/FoodModel.php';

$database = new Database();
$db = $database->getConnection();
$foodModel = new FoodModel($db);
$dishes = $foodModel->getAllFoods();
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Morristown Pizza & Pasta</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Custom Styles -->
    
    <!-- Custom Google Font CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abel&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   
    <style>
        /* Custom styles here */
        .parallax-section {
            /* Parallax background styling */
        }
        
        @media (max-width: 768px) {
    .dish {
        width: 50%; /* Show 2 items per slide on smaller screens */
    }
    
    }

    @media (max-width: 480px) {
        .dish {
            width: 100%; /* Show 1 item per slide on very small screens */
        }
    }
        
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/images/deluxepizzaandpasta2.png" alt="Deluxe Pasta and Pizza Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="views/menu/menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about-us">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact-us">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/admin/dashboard.php">Admin</a>
                    </li>
                    <li><a href="views/cart/cart.php" class="cart-icon" style="padding-left: 20px;">
                        <i class="fas fa-shopping-cart"></i>
                            <?php if (count($_SESSION['cart']) > 0): ?>
                                <span class="cart-badge"><?= count($_SESSION['cart']) ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <!-- Parallax Section -->
    <section class="parallax-section">
        <h1 class="main-header-name">
            <span>M</span><span>o</span><span>r</span><span>r</span><span>i</span><span>s</span><span>t</span><span>o</span><span>w</span><span>n</span> <span>P</span><span>a</span><span>s</span><span>t</span><span>a</span> & <span>P</span><span>i</span><span>z</span><span>z</span><span>a</span>
        </h1>

    </section>
    
    <div>
        <h3 style="text-align: center; font-size: 3em;">Check Out Our Featured Dishes!<h3>
    </div>
   
      <div id="dishCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
        <?php
        $firstItem = true;
        $counter = 0;
        foreach ($dishes as $index => $dish) {
            if ($counter % 3 == 0) {
                $activeClass = $firstItem ? 'active' : '';
                echo $counter > 0 ? "</div>" : "";
                echo "<div class='carousel-item {$activeClass}'>";
                $firstItem = false;
            }

            $imageUrl = $dish['ImageUrl'] ?: 'path/to/default-image.jpg';
            $dishName = htmlspecialchars($dish['Name']);

            echo "<div class='dish'>";
            echo "<img src='{$imageUrl}' alt='{$dishName}'>";
            echo "<h5>{$dishName}</h5>";
            echo "</div>";

            $counter++;
        }
        echo "</div>"; // Close the last item div
        ?>
    </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#dishCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#dishCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#dishCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#dishCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <div class="container my-4 text-center about-section" id="about-us">
        
        <h2>About Us</h2>
        <p>Welcome to Morristown Pizza & Pasta - a traditional Italian experience nestled in the heart of Morristown, Tennessee. At Bella Italia, we believe in celebrating the rich culture and flavors of Italy. Our culinary journey began in the quaint streets of Naples and has found a home here in Morristown.</p>
        <p>Our chefs, hailing from the heart of Italy, bring authentic and delectable Italian cuisine to your table. From hand-tossed, wood-fired pizzas to rich, savory pastas, each dish is crafted with the freshest ingredients and a touch of Italian love. Our cozy ambiance, inspired by the rustic charm of Italian villages, provides the perfect backdrop for family dinners, romantic evenings, and gatherings with friends.</p>
        <p>Join us at Morristown Pizza & Pasta, where every meal is a celebration of life, family, and the timeless taste of Italy. Buon Appetito!</p>

        <!-- Logo Image -->
        <img src="assets/images/deluxepizzaandpasta2.png" alt="Morristown Pasta & Pizza Logo" class="about-logo">
    </div>
    
    <div class="video-container" style="position: relative; overflow: hidden; width: 100%; height: 50vh;">
        <video autoplay loop muted plays-inline class="background-clip">
            <source src="assets/videos/Pizza_cooking.mp4" type="video/mp4">
        </video>
        <div class="overlay-text">
            <h2 class="video_overlay_text">The Best Italian Food East of the Mississippi</h2>
            <a href="views/menu/menu.php" class="order-now-btn">Order Online</a>
        </div>
    </div>
   
    <div class="container my-4 contact-section text-center" id="contact-us">
    <h2>Contact Us</h2>
    <div class="row">
            <div class="col-md-6">
                <p>
                    <a href="mailto:morristownpasta&pizza@outlook.com" class="contact-link">
                        morristownpasta&pizza@outlook.com
                    </a>
                </p>
                <p>
                    <a href="tel:+14235551234" class="contact-link">
                        (423) 555-1234
                    </a>
                </p>
            </div>
            <div class="col-md-6">
                <p>Follow us on social media:</p>
                <a href="https://www.facebook.com/" class="social-icon">
                    <i class="fab fa-facebook fa-2x"></i>
                </a>
                <a href="https://www.instagram.com/" class="social-icon">
                    <i class="fab fa-instagram fa-2x"></i>
                </a>
                <a href="https://www.tiktok.com/" class="social-icon">
                    <i class="fab fa-tiktok fa-2x"></i>
                </a>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start">
        <div class="text-center p-3">
            &copy; <span id="year"></span> Morristown Pasta & Pizza
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <!-- Dynamic Year Script -->
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const letters = document.querySelectorAll('.main-header-name span');
    let index = 0;
    const expandTime = 150; // Time in milliseconds for each letter to expand
    const stayTime = 2000; // Time in milliseconds for all letters to stay expanded

    function animateLetters() {
        setTimeout(function () {
            if (index < letters.length) {
                // Expand and color the current letter
                letters[index].style.transform = 'scale(1.5)';
                letters[index].style.color = 'red';

                // Move to the next letter
                index++;
                animateLetters();
            } else {
                // All letters have been expanded, start the revert process
                setTimeout(revertLetters, stayTime);
            }
        }, expandTime);
    }

    function revertLetters() {
        for (let i = 0; i < letters.length; i++) {
            letters[i].style.transform = 'scale(1)';
            letters[i].style.color = '#fff';
        }
        // Reset the index and start the animation again
        index = 0;
        setTimeout(animateLetters, expandTime);
    }

    animateLetters(); // Start the animation
});
</script>
    
</body>
</html>

