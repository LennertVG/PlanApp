document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("taskModal");
    const span = document.getElementsByClassName("close")[0];
    const tasks = Array.from(document.getElementsByClassName("task"));
    const addTaskButton = document.getElementById("addTaskButton");
    const taskForm = document.getElementById("taskForm");
    const taskFormClose = document.getElementById("taskFormClose");

    tasks.forEach(task => {
        task.onclick = function(event) {
            event.stopPropagation(); 
            modal.style.display = "block";
            document.getElementById("modalCourse").innerText = this.dataset.course;
            document.getElementById("modalTaskType").innerText = this.dataset.tasktype;
            document.getElementById("modalTaskName").innerText = this.dataset.name;
            document.getElementById("modalDeadline").innerText = this.dataset.deadline;
            document.getElementById("modalDescription").innerText = this.dataset.description;
            document.getElementById("modalCreatedBy").innerText = "Created by: " + this.dataset.createdby;
        }
    });

    span.onclick = function(event) {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        } else if (event.target == taskForm) {
            taskForm.style.display = "none";
        }
    }

    function toggleTaskComponent() {
        taskForm.style.display = taskForm.style.display === "none" ? "block" : "none";
    }

    addTaskButton.onclick = function(event) {
        event.stopPropagation(); 
        toggleTaskComponent();
    }

    taskFormClose.onclick = function(event) {
        event.stopPropagation();
        taskForm.style.display = "none";
    }

    document.addEventListener('click', function(event) {
        if (!taskForm.contains(event.target) && event.target !== addTaskButton) {
            taskForm.style.display = "none";
        }
    });
});
