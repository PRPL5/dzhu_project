document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
        alert('Email and password are required.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    if (password.length < 6) {
        alert('Password must be at least 6 characters long.');
        return;
    }

    if (email === 'admin@smis.com' && password === 'admin123') {
        alert('Login successful! Welcome Admin.');
        window.location.href = 'pages/main.php';
    } else {
        alert('Invalid credentials. (Demo: try admin@smis.com / admin123)');
    }
});