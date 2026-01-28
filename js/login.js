document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
        alert('Email dhe fjalëkalim janë të detyrueshme.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Ju lutemi shkruani një adresë email të vlefshme.');
        return;
    }

    if (password.length < 6) {
        alert('Fjalëkalimi duhet të jetë të paktën 6 shkronja gjatë.');
        return;
    }

    if (email === 'admin@smis.com' && password === 'admin123') {
        alert('Hyrja e suksesshme! Mirë se vini Admin.');
        window.location.href = 'pages/main.php';
    } else {
        alert('Kredenciale të pavlefshme. (Demonstrim: provoni admin@smis.com / admin123)');
    }
});