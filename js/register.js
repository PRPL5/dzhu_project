document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (!firstName || !lastName || !email || !password || !confirmPassword) {
        alert('Të gjitha fushat janë të detyrueshme.');
        return;
    }

    if (password !== confirmPassword) {
        alert('Fjalëkalimet nuk përputhen.');
        return;
    }

    if (password.length < 6) {
        alert('Fjalëkalimi duhet të jetë të paktën 6 shkronja gjatë.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Ju lutemi shkruani një adresë email të vlefshme.');
        return;
    }

    alert('Regjistrimi i suksesshëm! (Kjo është një demonstrim - të dhënat nuk ruhen në të vërtetë)');
});