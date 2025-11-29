document.addEventListener('DOMContentLoaded', function() {
    loadAnnouncements();
});

async function loadAnnouncements() {
    try {
        const data = await getData();
        const announcements = data.announcements;
        const announcementsList = document.getElementById('announcements-list');

        announcements.forEach(announcement => {
            const announcementDiv = document.createElement('div');
            announcementDiv.className = 'announcement-item';

            announcementDiv.innerHTML = `
                <div class="announcement-title">${announcement.title}</div>
                <div class="announcement-content">${announcement.content}</div>
                <div class="announcement-date">Posted on: ${announcement.date}</div>
            `;

            announcementsList.appendChild(announcementDiv);
        });
    } catch (error) {
        console.error('Error loading announcements:', error);
    }
}