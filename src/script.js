const apiUrl = 'api.php';

const form = document.getElementById('studentForm');
const resetBtn = document.getElementById('resetBtn');
const tableBody = document.getElementById('studentTableBody');

const idInput = document.getElementById('studentId');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const courseInput = document.getElementById('course');

async function fetchStudents() {
    const res = await fetch(apiUrl);
    const students = await res.json();
    renderTable(students);
}

function renderTable(students) {
    tableBody.innerHTML = '';
    students.forEach(student => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${student.id}</td>
            <td>${student.name}</td>
            <td>${student.email}</td>
            <td>${student.course}</td>
            <td>
                <button onclick="editStudent(${student.id}, '${student.name}', '${student.email}', '${student.course}')">Edit</button>
                <button onclick="deleteStudent(${student.id})">Delete</button>
            </td>
        `;

        tableBody.appendChild(tr);
    });
}

window.editStudent = (id, name, email, course) => {
    idInput.value = id;
    nameInput.value = name;
    emailInput.value = email;
    courseInput.value = course;
};

window.deleteStudent = async (id) => {
    if (!confirm('Are you sure you want to delete this student?')) return;

    await fetch(`${apiUrl}?id=${id}`, {
        method: 'DELETE'
    });
    fetchStudents();
};

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = idInput.value;
    const payload = {
        name: nameInput.value,
        email: emailInput.value,
        course: courseInput.value
    };

    if (id) {
        // update
        payload.id = id;
        await fetch(apiUrl, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
    } else {
        // create
        const res=await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const text = await res.text();
        console.log('POST response:', text);
    }

    form.reset();
    idInput.value = '';
    fetchStudents();
});

resetBtn.addEventListener('click', () => {
    form.reset();
    idInput.value = '';
});

// Initial load
fetchStudents();
