document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("taskModal");
    const taskForm = document.getElementById("taskForm");
    const tasks = Array.from(document.getElementsByClassName("task"));
    const addTaskButton = document.getElementById("addTaskButton");
    const taskFormClose = document.getElementById("taskFormClose");
    const modalCloseButtons = document.querySelectorAll(".modal .close");

    // Hide modals initially
    modal.style.display = "none";
    taskForm.style.display = "none";

    // Function to show the modal
    const showModal = (modal) => {
        modal.style.display = "block";
    };

    // Function to hide the modal
    const hideModal = (modal) => {
        modal.style.display = "none";
    };

    // Event listener to open task modal
    tasks.forEach(task => {
        task.addEventListener('click', (event) => {
            event.stopPropagation();
            const taskId = task.dataset.taskid;
            console.log('Clicked task dataset:', task.dataset);
            document.getElementById("modalCourse").innerText = task.dataset.course;
            document.getElementById("modalTaskType").innerText = task.dataset.tasktype;
            document.getElementById("modalTaskName").innerText = task.dataset.name;
            document.getElementById("modalDeadline").innerText = task.dataset.deadline;
            document.getElementById("modalDescription").innerText = task.dataset.description;
            const form = document.querySelector("#taskModal form");
            document.getElementById("hiddenTaskId").value = taskId;

            showModal(modal);
        });
    });

    // Event listener to close modals
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            hideModal(event.target.closest('.modal'));
        });
    });

    // Event listener for clicking outside the modals
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            hideModal(modal);
        } else if (event.target == taskForm) {
            hideModal(taskForm);
        }
    });

    // Event listener to toggle task form
    addTaskButton.addEventListener('click', (event) => {
        event.stopPropagation();
        if (taskForm.style.display === "block") {
            hideModal(taskForm);
        } else {
            showModal(taskForm);
        }
    });

    // Close task form when clicking outside of it
    document.addEventListener('click', (event) => {
        if (!taskForm.contains(event.target) && event.target !== addTaskButton) {
            hideModal(taskForm);
        }
    });

    // Prevent the need to click elsewhere before opening the add task form
    addTaskButton.addEventListener('click', () => {
        taskForm.style.display = "block";
    });

    // Close task form using the close button inside the form
    taskFormClose.addEventListener('click', (event) => {
        event.stopPropagation();
        hideModal(taskForm);
    });
});


// Calendar
function generateCalendar() {
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    const currentDate = today.getDate();

    const monthYear = document.getElementById('monthYear');
    monthYear.innerText = today.toLocaleString('default', { month: 'long' }) + ' ' + currentYear;

    const daysContainer = document.getElementById('days');
    daysContainer.innerHTML = '';

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Add empty divs for days of the previous month
    for (let i = 0; i < firstDay; i++) {
        daysContainer.appendChild(document.createElement('div'));
    }

    // Add divs for each day of the current month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement('div');
        dayDiv.innerText = day;
        dayDiv.classList.add('day');
        if (day === currentDate) {
            dayDiv.classList.add('today');
        }
        daysContainer.appendChild(dayDiv);
    }
}
generateCalendar();