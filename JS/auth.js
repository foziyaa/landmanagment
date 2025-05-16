// js/auth.js
document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
  
    
    let role = '';
    if (username === 'admin' && password === 'admin123') role = 'admin';
    else if (username === 'landadmin' && password === 'land123') role = 'landadmin';
    else if (username === 'owner' && password === 'owner123') role = 'owner';
    else {
      document.getElementById('loginError').textContent = 'Invalid credentials';
      return;
    }
  
    // Simulate login and redirect
    localStorage.setItem('role', role);
    window.location.href = 'dashboard.html';
  });
  