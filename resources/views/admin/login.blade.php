<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }} | Login</title>
  <!-- Google Fonts -->
  
  <link rel="shortcut icon" href="{{ asset('images/pln-batam.png') }}" type="image/png">
  <link rel="shortcut icon" href="{{ asset('images/pln-batam.png') }}" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #6d5dfc, #47b2ff);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .container {
      display: flex;
      max-width: 900px;
      width: 100%;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .login-box {
      width: 50%;
      padding: 2rem;
      text-align: center;
    }
    .login-box img {
      width: 80px;
      margin-bottom: 1rem;
    }
    .login-box h1 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 1rem;
    }
    .input-group {
      position: relative;
      margin-bottom: 1.5rem;
    }
    .input-group input {
      width: 100%;
      padding: 0.75rem; /* Konsisten dengan tombol */
      border: 1px solid #ccc;
      border-radius: 8px; /* Konsisten dengan tombol */
      font-size: 1rem;
      box-sizing: border-box;
    }
    .input-group input:focus {
      border-color: #6d5dfc;
      outline: none;
    }
    .input-group .input-group-text {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      font-size: 1.2rem;
      color: #6d5dfc;
      cursor: pointer;
    }
    .btn {
      background: #6d5dfc;
      color: #fff;
      padding: 0.75rem; /* Konsisten dengan input */
      border: none;
      border-radius: 8px; /* Konsisten dengan input */
      width: 100%; /* Konsisten dengan input */
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn:hover {
      background: #5746db;
      transform: translateY(-2px);
    }
    .btn:active {
      transform: translateY(0);
    }
    .social-auth-links {
      margin-top: 1rem;
    }
    .social-auth-links p {
      font-size: 0.9rem;
      color: #666;
    }
    .social-auth-links p a {
      color: #6d5dfc;
      text-decoration: none;
    }
    .social-auth-links p a:hover {
      text-decoration: underline;
    }
    .image-box {
      width: 50%;
      background: url('{{ asset('images/pltgu-batam.jpg') }}') no-repeat center center;
      background-size: cover;
      position: relative;
    }
    .image-box::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(109, 93, 252, 0.5); /* Warna biru dengan transparansi */
  z-index: 1; /* Pastikan overlay berada di atas gambar */
}
  </style>
</head>
<body>
  <div class="container">
    <div class="login-box">
      <img src="{{ asset('images/pln-batam.png') }}" alt="Logo">
      <h1>Stock Opname PLTGU Tanjung Uncang</h1>
      <form action="{{ route('login.auth') }}" method="post" id="form-login">
        @csrf
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required>
          <span class="input-group-text fas fa-user"></span>
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" required>
          <span class="input-group-text fas fa-eye" id="togglePassword"></span>
        </div>
        <button type="submit" class="btn">Log In</button>
      </form>
      <div class="social-auth-links">
        <p>Forgot your password? <a href="#">Chat Admin</a></p>
      </div>
    </div>
    <div class="image-box"></div>
  </div>

  <!-- JavaScript -->
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = this.previousElementSibling;
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
