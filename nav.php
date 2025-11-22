<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>navbar</title>
    

  <link rel="stylesheet" href="styles/profile.css" />
     

     <link rel="stylesheet" href="styles/general.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/queries.css" />
    
      <link
      href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
  <script
      type="module"
      src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule=""
      src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"
    ></script>

    <script
      defer
      src="https://unpkg.com/smoothscroll-polyfill@0.4.4/dist/smoothscroll.min.js"
    ></script>
     <script
      defer
      src="js/script"
    ></script>
    

</head>
<body>
      <header class="header">
      <a href="index.php">
        <img class="logo" alt="chaicraft logo" src="pic/logo.png" />
      </a>

      <nav class="main-nav">
        <ul class="main-nav-list">
          <li><a class="main-nav-link" href="index.php">Home</a></li>
          <li><a class="main-nav-link" href="product.php">Product</a></li>
          <li>
            <a class="main-nav-link" href="aboutUs.php">About Us</a>
          </li>
          <li><a class="main-nav-link" href="cart.php">Cart</a></li>
         <div class="profile-dropdown">
                  <button class="nav-link profile-login-btn" onclick="toggleProfileMenu()" aria-haspopup="true" aria-expanded="false">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" style="vertical-align:middle;margin-right:8px;"><circle cx="12" cy="8" r="4" stroke="#eb984e" stroke-width="2"/><path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" stroke="#eb984e" stroke-width="2"/></svg>
                    
                    <!-- <svg width="18" height="18" viewBox="0 0 20 20" fill="none" style="vertical-align:middle;margin-left:6px;"><path d="M6 8l4 4 4-4" stroke="#eb984e" stroke-width="2"/></svg> -->
                  </button>
                  <div id="profileMenu" class="profile-menu">
                    <?php if (isset($_SESSION['auth_user'])): ?>
                      <span class="profile-welcome">👋 <?php echo htmlspecialchars($_SESSION['auth_user']['name'] ?? 'User'); ?></span>
                      <a href="my-profile.php" class="profile-link"><svg width="18" height="18" style="vertical-align:middle;margin-right:6px;" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#eb984e" stroke-width="1.5"/><path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" stroke="#eb984e" stroke-width="1.5"/></svg>My Profile</a>
                      <a href="logout.php" class="profile-link logout-link"><svg width="18" height="18" style="vertical-align:middle;margin-right:6px;" fill="none" viewBox="0 0 24 24"><path d="M16 17l5-5-5-5M21 12H9M13 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-2" stroke="#eb984e" stroke-width="1.5"/></svg>Logout</a>
                    <?php else: ?>
                      <a href="admin/login.php" class="profile-link"><svg width="18" height="18" style="vertical-align:middle;margin-right:6px;" fill="none" viewBox="0 0 24 24"><path d="M16 17l5-5-5-5M21 12H9M13 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-2" stroke="#eb984e" stroke-width="1.5"/></svg>Login</a>
                      <a href="admin/sign_up.php" class="profile-link"><svg width="18" height="18" style="vertical-align:middle;margin-right:6px;" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#eb984e" stroke-width="1.5"/><path d="M12 14v6m3-3h-6" stroke="#eb984e" stroke-width="1.5"/></svg>Sign Up</a>
                    <?php endif; ?>
                  </div>
                </div>
                <script>
                function toggleProfileMenu() {
                  var menu = document.getElementById('profileMenu');
                  var btn = document.querySelector('.profile-login-btn');
                  var expanded = btn.getAttribute('aria-expanded') === 'true';
                  menu.style.display = expanded ? 'none' : 'block';
                  btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                }
                document.addEventListener('click', function(e) {
                  var menu = document.getElementById('profileMenu');
                  var btn = document.querySelector('.profile-login-btn');
                  if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = 'none';
                    btn.setAttribute('aria-expanded', 'false');
                  }
                });
                </script>
        </ul>
      </nav>

      <button class="btn-mobile-nav">
        <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
        <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
      </button>
    </header>

        
               
</body>
</html>