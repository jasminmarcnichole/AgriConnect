<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agi-Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
<div class="page-bg"></div>
<div class="container">
<header class="topbar">
  <h1 class="logo">Agi<span>Connect</span></h1>
  <nav>
    <a href="#home">Home</a>
    <a href="#about">About Us</a>
    <a href="products.php">Product Offer</a>
    <a href="#join">Become One of Us</a>
    <?php if (!isset($_SESSION['user'])): ?>
      <button class="ghost-btn" onclick="openModal('loginModal')">Login</button>
      <button class="primary-btn" onclick="openModal('signupModal')">Signup</button>
    <?php else: ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php endif; ?>
  </nav>
</header>

<section id="home" class="hero">
  <div class="overlay"></div>
  <div class="hero-content">
    <p class="kicker">GOOD FOOD</p>
    <h2>Experience Real<br/>Agricultural Connection</h2>
    <p>Buy quality farm products and connect directly with trusted sellers.</p>
    <a href="products.php" class="primary-btn">Explore Products</a>
  </div>
  <div class="feature-cards">
    <article>
      <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=900&q=80" alt="Greenhouse"/>
      <h3>Organic Fertilizers</h3>
    </article>
    <article>
      <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=900&q=80" alt="Vegetables"/>
      <h3>Expert Farm Partners</h3>
    </article>
  </div>
</section>

<section id="about" class="split-section">
  <div>
    <p class="kicker green">About Us</p>
    <h2>A New Way To Invest In Agriculture</h2>
    <p>Agi-Connect helps buyers and sellers meet in one marketplace, with product visibility, secure conversations, and role-based features.</p>
    <a href="#join" class="primary-btn">Read More</a>
  </div>
  <img src="https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?auto=format&fit=crop&w=1000&q=80" alt="Farm work"/>
</section>

<section id="join" class="join-section">
  <h2>Become One of Us</h2>
  <p>Join as a buyer to discover products, or as a seller to post and chat with potential buyers.</p>
  <div class="cta-wrap">
    <button class="ghost-btn" onclick="openModal('loginModal')">Login</button>
    <button class="primary-btn" onclick="openModal('signupModal')">Create Account</button>
  </div>
</section>
</div>

<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('loginModal')">&times;</span>
    <h3>Login</h3>
    <form method="POST" action="login.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button class="primary-btn" type="submit">Login</button>
    </form>
  </div>
</div>

<div id="signupModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('signupModal')">&times;</span>
    <h3>Sign Up</h3>
    <form method="POST" action="signup.php">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="role" required>
        <option value="buyer">Buyer</option>
        <option value="seller">Seller</option>
      </select>
      <button class="primary-btn" type="submit">Create Account</button>
    </form>
  </div>
</div>

<script>
function openModal(id){ document.getElementById(id).style.display='block'; }
function closeModal(id){ document.getElementById(id).style.display='none'; }
window.onclick = function(event) {
  ['loginModal','signupModal'].forEach(id => {
    const modal = document.getElementById(id);
    if (event.target === modal) modal.style.display = 'none';
  });
}
</script>
</body>
</html>
