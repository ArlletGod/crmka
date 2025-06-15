<h1><?= __('companies') ?></h1>

<!-- Add Company Button -->
<button id="addCompanyBtn" class="btn btn-primary mb-3"><?= __('add_company') ?></button>

<!-- Companies Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th><?= __('name') ?></th>
            <th><?= __('address') ?></th>
            <th><?= __('phone') ?></th>
            <th><?= __('actions') ?></th>
        </tr>
    </thead>
    <tbody id="companiesTableBody">
        <!-- Rows will be inserted here by JavaScript -->
    </tbody>
</table>

<!-- Company Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companyModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('close') ?>"></button>
            </div>
            <div class="modal-body">
                <form id="companyForm">
                    <input type="hidden" id="companyId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label"><?= __('name') ?></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label"><?= __('address') ?></label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label"><?= __('phone') ?></label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('close') ?></button>
                <button type="submit" form="companyForm" class="btn btn-primary"><?= __('save_company') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto"><?= __('notification') ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="<?= __('close') ?>"></button>
    </div>
    <div class="toast-body">
      <!-- Message will be inserted here -->
    </div>
  </div>
</div>

<script>
const translations = {
    edit: "<?= __('edit_company') ?>",
    add: "<?= __('add_company') ?>",
    confirm_delete: "<?= __('confirm_delete_company') ?>",
    created_successfully: "<?= __('company_created_successfully') ?>",
    updated_successfully: "<?= __('company_updated_successfully') ?>",
    deleted_successfully: "<?= __('company_deleted_successfully') ?>",
    failed_to_load: "<?= __('failed_to_load_companies') ?>",
    failed_to_save: "<?= __('failed_to_save_company') ?>",
    failed_to_load_data: "<?= __('failed_to_load_company_data') ?>",
    failed_to_delete: "<?= __('failed_to_delete_company') ?>",
    server_error: "<?= __('server_error') ?>",
    edit_btn: "<?= __('edit') ?>",
    delete_btn: "<?= __('delete') ?>",
};

document.addEventListener('DOMContentLoaded', function () {
    const companyModal = new bootstrap.Modal(document.getElementById('companyModal'));
    const companyForm = document.getElementById('companyForm');
    const companyModalLabel = document.getElementById('companyModalLabel');
    const tableBody = document.getElementById('companiesTableBody');
    const toastElement = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastElement);

    const apiUrl = '/api/companies';

    // --- Utility Functions ---
    const showToast = (message, success = true) => {
        toastElement.querySelector('.toast-body').textContent = message;
        toastElement.classList.remove('bg-success', 'bg-danger');
        toastElement.classList.add(success ? 'bg-success' : 'bg-danger');
        toast.show();
    };
    
    const escapeHTML = (str) => {
        if (str === null || str === undefined) {
            return '';
        }
        const p = document.createElement('p');
        p.appendChild(document.createTextNode(String(str)));
        return p.innerHTML;
    };

    const renderRow = (company) => {
        const row = document.createElement('tr');
        row.setAttribute('data-id', company.id);
        row.innerHTML = `
            <td>${company.id}</td>
            <td>${escapeHTML(company.name)}</td>
            <td>${escapeHTML(company.address)}</td>
            <td>${escapeHTML(company.phone)}</td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn">${translations.edit_btn}</button>
                <button class="btn btn-sm btn-danger delete-btn">${translations.delete_btn}</button>
            </td>
        `;
        return row;
    };
    
    // --- Load Initial Data ---
    const loadCompanies = async () => {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) throw new Error(translations.failed_to_load);
            const companies = await response.json();
            tableBody.innerHTML = '';
            companies.forEach(company => tableBody.appendChild(renderRow(company)));
        } catch (error) {
            console.error('Failed to load companies:', error);
            showToast(error.message, false);
        }
    };

    // --- Event Handlers ---
    companyForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(companyForm);
        const data = Object.fromEntries(formData.entries());
        const companyId = data.id;
        const method = companyId ? 'PUT' : 'POST';
        const url = companyId ? `${apiUrl}/${companyId}` : apiUrl;

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (!response.ok) throw new Error(translations.server_error);

            const savedCompany = await response.json();
            
            if (companyId) {
                const oldRow = tableBody.querySelector(`tr[data-id="${companyId}"]`);
                if (oldRow) {
                    oldRow.replaceWith(renderRow(savedCompany));
                }
            } else {
                tableBody.appendChild(renderRow(savedCompany));
            }
            
            companyModal.hide();
            const message = companyId ? translations.updated_successfully : translations.created_successfully;
            showToast(message);
        } catch (error) {
            console.error('Failed to save company:', error);
            showToast(translations.failed_to_save, false);
        }
    });

    document.getElementById('addCompanyBtn').addEventListener('click', () => {
        companyForm.reset();
        document.getElementById('companyId').value = '';
        companyModalLabel.textContent = translations.add;
        companyModal.show();
    });

    tableBody.addEventListener('click', async (e) => {
        const target = e.target;
        const row = target.closest('tr');
        if (!row) return;
        const companyId = row.dataset.id;

        if (target.classList.contains('edit-btn')) {
            try {
                const response = await fetch(`${apiUrl}/${companyId}`);
                if (!response.ok) throw new Error(translations.failed_to_load_data);
                const company = await response.json();
                
                document.getElementById('companyId').value = company.id;
                document.getElementById('name').value = company.name || '';
                document.getElementById('address').value = company.address || '';
                document.getElementById('phone').value = company.phone || '';

                companyModalLabel.textContent = translations.edit;
                companyModal.show();
            } catch (error) {
                 console.error('Failed to fetch company for editing:', error);
                 showToast(error.message, false);
            }
        }

        if (target.classList.contains('delete-btn')) {
            if (confirm(translations.confirm_delete)) {
                try {
                    const response = await fetch(`${apiUrl}/${companyId}`, { method: 'DELETE' });
                    if (!response.ok) throw new Error(translations.server_error);
                    
                    row.remove();
                    showToast(translations.deleted_successfully);
                } catch (error) {
                    console.error('Failed to delete company:', error);
                    showToast(translations.failed_to_delete, false);
                }
            }
        }
    });

    // --- Initial Load ---
    loadCompanies();
});
</script> 