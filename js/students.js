document.addEventListener('DOMContentLoaded', function() {
    loadStudents();
});

async function loadStudents() {
    try {
        const data = await getData();
        const students = data.students;
        const programs = data.programs;
        const studentsList = document.querySelector('.students-list');

        students.forEach(student => {
            const program = programs.find(p => p.program_id === student.program_id);
            const studentDiv = document.createElement('div');
            studentDiv.className = 'student-card';
            studentDiv.onclick = () => window.location.href = `student-details.php?id=${student.student_id}`;

            studentDiv.innerHTML = `
                <div class="student-name">${student.first_name} ${student.last_name}</div>
                <div class="student-id">ID: ${student.student_id}</div>
                <div class="student-program">${program ? program.name : 'Unknown Program'}</div>
            `;

            studentsList.appendChild(studentDiv);
        });
    } catch (error) {
        console.error('Error loading students:', error);
    }
}