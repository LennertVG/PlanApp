// Home page pop-up logic
// Get the modal
var modal = document.getElementById("taskModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Get all task elements
var tasks = document.getElementsByClassName("task");

// When the user clicks on a task, open the modal 
for (var i = 0; i < tasks.length; i++) {
    tasks[i].onclick = function() {
        modal.style.display = "block";
        document.getElementById("modalCourse").innerText = this.dataset.course;
        document.getElementById("modalTaskType").innerText = this.dataset.tasktype;
        document.getElementById("modalTaskName").innerText = this.dataset.name;
        document.getElementById("modalDeadline").innerText = this.dataset.deadline;
        document.getElementById("modalDescription").innerText = this.dataset.description;
        document.getElementById("modalCreatedBy").innerText = "Created by: " + this.dataset.createdby;
    }
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}