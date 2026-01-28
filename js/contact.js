document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !subject || !message) {
        alert('Të gjitha fushat janë të detyrueshme.');
        return;
    }

    if (name.length < 2) {
        alert('Emri duhet të jetë të paktën 2 shkronja gjatë.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Ju lutemi shkruani një adresë email të vlefshme.');
        return;
    }

    if (subject.length < 5) {
        alert('Tema duhet të jetë të paktën 5 shkronja gjatë.');
        return;
    }

    if (message.length < 10) {
        alert('Mesazhi duhet të jetë të paktën 10 shkronja gjatë.');
        return;
    }

    alert('Mesazhi dërguar me sukses! (Kjo është një demonstrim - mesazhi nuk dërgohet në të vërtetë)');
    document.getElementById('contact-form').reset();
});