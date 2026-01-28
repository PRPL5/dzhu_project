document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('id');
    if (studentId) {
        loadStudentDetails(studentId);
    } else {
        document.getElementById('student-info').innerHTML = '<p>Student i pamundur të gjetur.</p>';
    }
});

async function loadStudentDetails(studentId) {
    try {
        const data = await getData();
        const student = data.students.find(s => s.student_id === studentId);
        const programs = data.programs;
        const courses = data.courses;
        const examResults = data.exam_results;
        const payments = data.payment_history;

        if (!student) {
            document.getElementById('student-info').innerHTML = '<p>Student i pamundur të gjetur.</p>';
            return;
        }

        const program = programs.find(p => p.program_id === student.program_id);
        const studentCourses = student.courses.map(c => {
            const course = courses.find(co => co.course_id === c.course_id);
            return `${course ? course.name : c.course_id}: ${c.grade}`;
        }).join('<br>');

        const studentExams = examResults.filter(e => e.student_id === studentId).map(e => {
            const exam = data.exams.find(ex => ex.exam_id === e.exam_id);
            return `${exam ? exam.course_id : e.exam_id}: ${e.score}`;
        }).join('<br>');

        const studentPayments = payments.filter(p => p.student_id === studentId).map(p => 
            `${p.date}: ${p.amount} EUR - ${p.status}`
        ).join('<br>');

        const infoCard = document.getElementById('student-info');
        infoCard.innerHTML = `
            <h2>Student: ${student.first_name} ${student.last_name}</h2>
            <div class="info-row">
                <span class="info-label">ID:</span>
                <span class="info-value">${student.student_id}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">${student.email}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Telefoni:</span>
                <span class="info-value">${student.phone}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adresa:</span>
                <span class="info-value">${student.address}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Programi:</span>
                <span class="info-value">${program ? program.name : 'I PANJOHUR'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Viti i Studimit:</span>
                <span class="info-value">${student.year_of_study}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GPA:</span>
                <span class="info-value">${student.gpa}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kurse:</span>
                <span class="info-value">${studentCourses || 'Asnjë'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rezultatet e Provimeve:</span>
                <span class="info-value">${studentExams || 'Asnjë'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Historia e Pagesave:</span>
                <span class="info-value">${studentPayments || 'Asnjë'}</span>
            </div>
        `;
    } catch (error) {
        console.error('Error loading student details:', error);
    }
}