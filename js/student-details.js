document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('id');
    if (studentId) {
        loadStudentDetails(studentId);
    } else {
        document.getElementById('student-info').innerHTML = '<p>Student not found.</p>';
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
            document.getElementById('student-info').innerHTML = '<p>Student not found.</p>';
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
                <span class="info-label">Phone:</span>
                <span class="info-value">${student.phone}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">${student.address}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Program:</span>
                <span class="info-value">${program ? program.name : 'Unknown'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Year of Study:</span>
                <span class="info-value">${student.year_of_study}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GPA:</span>
                <span class="info-value">${student.gpa}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Courses:</span>
                <span class="info-value">${studentCourses || 'None'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Exam Results:</span>
                <span class="info-value">${studentExams || 'None'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment History:</span>
                <span class="info-value">${studentPayments || 'None'}</span>
            </div>
        `;
    } catch (error) {
        console.error('Error loading student details:', error);
    }
}