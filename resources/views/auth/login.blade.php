<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-sans antialiased">
  <div style="max-width:420px;margin:60px auto;">
    <h1>Login</h1>
    <form id="loginForm">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button type="submit">Login</button>
    </form>
    <pre id="out"></pre>
  </div>
  <script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(e.target);
      const r = await fetch('/api/auth/login', { method:'POST', headers:{'Accept':'application/json'}, body: fd });
      document.getElementById('out').textContent = await r.text();
    });
  </script>
</body>
</html>
