<?php $currentUserId = (new \App\Core\Auth())->id(); ?>

<h1><?= __('tasks') ?></h1>
<button id="addTaskBtn" class="btn btn-primary mb-3"><?= __('add_task') ?></button>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th><?= __('status') ?></th>
            <th><?= __('name') ?></th>
            <th><?= __('due_date') ?></th>
            <th><?= __('assigned_to') ?></th>
            <th><?= __('related_to') ?></th>
            <th><?= __('actions') ?></th>
        </tr>
    </thead>
    <tbody id="tasksTableBody"></tbody>
</table>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('close') ?>"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <input type="hidden" id="taskId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label"><?= __('task_name') ?></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label"><?= __('description') ?></label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label"><?= __('due_date') ?></label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label"><?= __('status') ?></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending"><?= __('pending') ?></option>
                                <option value="completed"><?= __('completed') ?></option>
                            </select>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label"><?= __('assigned_to') ?></label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <!-- Users loaded via JS -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_id" class="form-label"><?= __('related_contact_optional') ?></label>
                            <select class="form-select" id="contact_id" name="contact_id">
                                <!-- Contacts loaded via JS -->
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('close') ?></button>
                <button type="submit" form="taskForm" class="btn btn-primary"><?= __('save_task') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><strong class="me-auto"><?= __('notification') ?></strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="<?= __('close') ?>"></button></div>
        <div class="toast-body"></div>
    </div>
</div>

<script>
const translations = {
    add: "<?= __('add_task') ?>",
    edit: "<?= __('edit_task') ?>",
    confirm_delete: "<?= __('confirm_delete_task') ?>",
    created_successfully: "<?= __('task_created_successfully') ?>",
    updated_successfully: "<?= __('task_updated_successfully') ?>",
    deleted_successfully: "<?= __('task_deleted_successfully') ?>",
    failed_to_load: "<?= __('failed_to_load_tasks') ?>",
    failed_to_save: "<?= __('failed_to_save_task') ?>",
    failed_to_load_data: "<?= __('failed_to_load_task_data') ?>",
    failed_to_delete: "<?= __('failed_to_delete_task') ?>",
    failed_to_load_form: "<?= __('failed_to_load_form_data') ?>",
    server_error: "<?= __('server_error') ?>",
    edit_btn: "<?= __('edit') ?>",
    delete_btn: "<?= __('delete') ?>",
    select_user: "<?= __('select_user') ?>",
    select_contact_optional: "<?= __('select_contact_optional') ?>",
};

document.addEventListener('DOMContentLoaded', async function () {
    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
    const taskForm = document.getElementById('taskForm');
    const taskModalLabel = document.getElementById('taskModalLabel');
    const tableBody = document.getElementById('tasksTableBody');
    const toastElement = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastElement);
    const showToast = (message, success = true) => {
        toastElement.querySelector('.toast-body').textContent = message;
        toastElement.classList.remove('bg-success', 'bg-danger', 'text-white');
        toastElement.classList.add(success ? 'bg-success' : 'bg-danger', 'text-white');
        toast.show();
    };

    const api = { tasks: '/api/tasks', contacts: '/api/contacts', users: '/api/users' };

    const escapeHTML = (str) => String(str || '').replace(/[&<>"']/g, m => ({'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'})[m]);
    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    };

    const renderRow = (task) => {
        const row = document.createElement('tr');
        row.dataset.id = task.id;
        const statusClass = task.status === 'completed' ? 'bg-success' : 'bg-warning';
        const statusText = task.status === 'completed' ? '<?= __('completed') ?>' : '<?= __('pending') ?>';
        row.innerHTML = `
            <td><span class="badge ${statusClass}">${statusText}</span></td>
            <td>${escapeHTML(task.name)}</td>
            <td>${formatDate(task.due_date)}</td>
            <td>${escapeHTML(task.user_name)}</td>
            <td>${escapeHTML(task.contact_name || 'N/A')}</td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn">${translations.edit_btn}</button>
                <button class="btn btn-sm btn-danger delete-btn">${translations.delete_btn}</button>
            </td>
        `;
        return row;
    };

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
            showToast(translations.failed_to_load_form, false);
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
            showToast(translations.failed_to_load, false);
        }
    };
    
    document.getElementById('addTaskBtn').addEventListener('click', () => {
        taskForm.reset();
        document.getElementById('taskId').value = '';
        taskModalLabel.textContent = translations.add;
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
                    if (el) el.value = (key === 'due_date' && task[key]) ? task[key].slice(0, 16) : task[key];
                });
                taskModalLabel.textContent = translations.edit;
                taskModal.show();
            } catch (error) {
                 console.error('Failed to fetch task:', error);
                 showToast(translations.failed_to_load_data, false);
            }
        }

        if (target.classList.contains('delete-btn')) {
            if (confirm(translations.confirm_delete)) {
                try {
                    const response = await fetch(`${api.tasks}/${id}`, { method: 'DELETE' });
                    if (!response.ok) throw new Error(translations.server_error);
                    row.remove();
                    showToast(translations.deleted_successfully);
                } catch (error) {
                    console.error('Failed to delete task:', error);
                    showToast(translations.failed_to_delete, false);
                }
            }
        }
    });

    taskForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('taskId').value;
        const formData = new FormData(taskForm);
        const data = Object.fromEntries(formData.entries());
        data.contact_id = data.contact_id || null;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${api.tasks}/${id}` : api.tasks;

        try {
            const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
            if (!response.ok) throw new Error(await response.text());
            const savedTask = await response.json();
            
            const newRow = renderRow(savedTask);
            if (id) {
                const oldRow = tableBody.querySelector(`tr[data-id="${id}"]`);
                if(oldRow) oldRow.replaceWith(newRow);
            } else {
                tableBody.prepend(newRow);
            }
            
            taskModal.hide();
            const message = id ? translations.updated_successfully : translations.created_successfully;
            showToast(message);
        } catch (error) {
            console.error('Failed to save task:', error);
            showToast(translations.failed_to_save, false);
        }
    });

    // Initial Load
    loadTasks();
    loadSelectOptions(api.users, 'user_id', translations.select_user);
    loadSelectOptions(api.contacts, 'contact_id', translations.select_contact_optional);
});
</script>
