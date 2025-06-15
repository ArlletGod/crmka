<?php $currentUserId = (new \App\Core\Auth())->id(); ?>

<h1>Tasks</h1>
<button id="addTaskBtn" class="btn btn-primary mb-3">Add Task</button>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Status</th>
            <th>Name</th>
            <th>Due Date</th>
            <th>Assigned To</th>
            <th>Related To</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="tasksTableBody"></tbody>
</table>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Add Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <input type="hidden" id="taskId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">Assigned To</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <!-- Users loaded via JS -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_id" class="form-label">Related Contact (Optional)</label>
                            <select class="form-select" id="contact_id" name="contact_id">
                                <!-- Contacts loaded via JS -->
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="taskForm" class="btn btn-primary">Save Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><strong class="me-auto">Notification</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div>
        <div class="toast-body"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    // --- Modal & Form Elements ---
    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
    const taskForm = document.getElementById('taskForm');
    const taskModalLabel = document.getElementById('taskModalLabel');
    const tableBody = document.getElementById('tasksTableBody');

    // --- Toast Notifications ---
    const toastElement = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastElement);
    const showToast = (message, success = true) => {
        toastElement.querySelector('.toast-body').textContent = message;
        toastElement.classList.remove('bg-success', 'bg-danger');
        toastElement.classList.add(success ? 'bg-success' : 'bg-danger', 'text-white');
        toastElement.querySelector('.toast-header').classList.remove('bg-success', 'bg-danger');
        toastElement.querySelector('.toast-header').classList.add(success ? 'bg-success' : 'bg-danger', 'text-white');
        toast.show();
    };

    // --- API & Data ---
    const api = { tasks: '/api/tasks', contacts: '/api/contacts', users: '/api/users' };
    let relatedDataCache = { users: [], contacts: [] };

    // --- Utility Functions ---
    const escapeHTML = (str) => String(str || '').replace(/[&<>"']/g, m => ({'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'})[m]);
    
    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    // --- Rendering ---
    const renderRow = (task) => {
        const row = document.createElement('tr');
        row.dataset.id = task.id;
        row.innerHTML = `
            <td><span class="badge ${task.status === 'completed' ? 'bg-success' : 'bg-warning'}">${escapeHTML(task.status)}</span></td>
            <td>${escapeHTML(task.name)}</td>
            <td>${formatDate(task.due_date)}</td>
            <td>${escapeHTML(task.user_name)}</td>
            <td>${escapeHTML(task.contact_name || task.deal_name || 'N/A')}</td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
            </td>
        `;
        return row;
    };

    // --- Data Loading ---
    const loadSelectOptions = async (url, selectId, placeholder) => {
        try {
            const response = await fetch(url);
            const items = await response.json();
            const select = document.getElementById(selectId);
            select.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(item => {
                select.innerHTML += `<option value="${item.id}">${escapeHTML(item.name)}</option>`;
            });
            return items;
        } catch (error) {
            console.error(`Failed to load ${selectId}:`, error);
            showToast(`Failed to load data for form.`, false);
        }
    };
    
    const loadTasks = async () => {
        try {
            const response = await fetch(api.tasks);
            const tasks = await response.json();
            tableBody.innerHTML = '';
            tasks.forEach(task => tableBody.appendChild(renderRow(task)));
        } catch (error) {
            console.error('Failed to load tasks:', error);
            showToast('Failed to load tasks.', false);
        }
    };
    
    // --- Event Handlers ---
    document.getElementById('addTaskBtn').addEventListener('click', () => {
        taskForm.reset();
        document.getElementById('taskId').value = '';
        taskModalLabel.textContent = 'Add Task';
        document.getElementById('status').value = 'pending';
        document.getElementById('user_id').value = '<?= $currentUserId ?>';
        taskModal.show();
    });

    tableBody.addEventListener('click', async (e) => {
        const target = e.target;
        const row = target.closest('tr');
        if (!row) return;
        const id = row.dataset.id;

        if (target.classList.contains('edit-btn')) {
            try {
                const response = await fetch(`${api.tasks}/${id}`);
                const task = await response.json();
                
                Object.keys(task).forEach(key => {
                    const el = taskForm.elements[key];
                    if (el) {
                        if (key === 'due_date' && task[key]) {
                            el.value = task[key].slice(0, 16); // Format for datetime-local
                        } else {
                            el.value = task[key];
                        }
                    }
                });
                document.getElementById('taskId').value = task.id;
                taskModalLabel.textContent = 'Edit Task';
                taskModal.show();
            } catch (error) {
                 console.error('Failed to fetch task for editing:', error);
                 showToast('Failed to load task data.', false);
            }
        }

        if (target.classList.contains('delete-btn')) {
            if (confirm('Are you sure you want to delete this task?')) {
                try {
                    const response = await fetch(`${api.tasks}/${id}`, { method: 'DELETE' });
                    if (!response.ok) throw new Error('Server error');
                    row.remove();
                    showToast('Task deleted successfully!');
                } catch (error) {
                    console.error('Failed to delete task:', error);
                    showToast('Failed to delete task.', false);
                }
            }
        }
    });

    taskForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(taskForm);
        const id = formData.get('id');
        const data = Object.fromEntries(formData.entries());
        // Clean up optional fields
        if (!data.contact_id) data.contact_id = null;
        if (!data.deal_id) data.deal_id = null;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${api.tasks}/${id}` : api.tasks;

        try {
            const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
            if (!response.ok) throw new Error(await response.text());
            const savedTask = await response.json();
            
            const newRow = renderRow(savedTask);
            if (id) {
                tableBody.querySelector(`tr[data-id="${id}"]`).replaceWith(newRow);
            } else {
                tableBody.prepend(newRow);
            }
            
            taskModal.hide();
            showToast(`Task ${id ? 'updated' : 'created'} successfully!`);
        } catch (error) {
            console.error('Failed to save task:', error);
            showToast('Failed to save task.', false);
        }
    });

    // --- Initial Load ---
    relatedDataCache.users = await loadSelectOptions(api.users, 'user_id', 'Select a user');
    relatedDataCache.contacts = await loadSelectOptions(api.contacts, 'contact_id', 'None');
    await loadTasks();
});
</script> 