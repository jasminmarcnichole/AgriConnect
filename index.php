<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agi-Connect</title>
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
<header>
  <h1>Agi-Connect</h1>
  <nav>
    <a href="#home">Home</a>
    <a href="#about">About Us</a>
    <a href="products.php">Product Offer</a>
    <a href="#join">Become One of Us</a>
    <?php if (!isset($_SESSION['user'])): ?>
      <button onclick="openModal('loginModal')">Login</button>
      <button onclick="openModal('signupModal')">Signup</button>
    <?php else: ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php endif; ?>
  </nav>
</header>

<section id="home"><h2>Home</h2><p>Buy and sell agricultural products easily.</p></section>
<section id="about"><h2>About Us</h2><p>We connect buyers and sellers in one trusted platform.</p></section>
<section id="join"><h2>Become One of Us</h2><p>Register now as Buyer or Seller and start growing with us.</p></section>

<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('loginModal')">&times;</span>
    <h3>Login</h3>
    <form method="POST" action="login.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
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
      <button type="submit">Create Account</button>
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
