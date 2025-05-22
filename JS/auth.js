// Dummy user data (for demo only)
const users = [
  {
    username: "admin",
    password: "admin123",
    role: "systemadmin"
  },
  {
    username: "landadmin",
    password: "land123",
    role: "landadmin"
  },
  {
    username: "registrar",
    password: "reg123",
    role: "registrationofficer"
  },
  {
    username: "regulator",
    password: "regulator123",
    role: "regulatory"
  },
  {
    username: "user",
    password: "user123",
    role: "user"
  }
];

// Login handler
function login(event) {
  event.preventDefault();

  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value.trim();

  const user = users.find(
    (u) => u.username === username && u.password === password
  );

  if (user) {
    localStorage.setItem("role", user.role);
    localStorage.setItem("username", user.username);
    window.location.href = "dashboard.html";
  } else {
    alert("Invalid credentials. Try again.");
  }
}

// Logout handler (can also be used globally)
function logout() {
  localStorage.clear();
  window.location.href = "index.html";
}

// Get current role
function getUserRole() {
  return localStorage.getItem("role");
}

// Protect page from unauthenticated access
function protectPage() {
  const role = getUserRole();
  if (!role) {
    alert("Please log in first.");
    window.location.href = "index.html";
  }
}
