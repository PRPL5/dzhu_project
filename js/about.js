document.addEventListener('DOMContentLoaded', function() {
    loadDepartments();
});

async function loadDepartments() {
    try {
        const data = await getData();
        const departments = data.departments;
        const departmentsList = document.getElementById('departments-list');

        departments.forEach(department => {
            const departmentDiv = document.createElement('div');
            departmentDiv.className = 'department-card';

            departmentDiv.innerHTML = `
                <div class="department-name">${department.name}</div>
                <div class="department-head">Head: ${department.head_of_department}</div>
            `;

            departmentsList.appendChild(departmentDiv);
        });
    } catch (error) {
        console.error('Error loading departments:', error);
    }
}