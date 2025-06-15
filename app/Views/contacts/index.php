<h1>Contacts</h1>

<!-- Add Contact Button -->
<button id="addContactBtn" class="btn btn-primary mb-3">Add Contact</button>

<!-- Contacts Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Company</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="contactsTableBody">
        <!-- Rows will be inserted here by JavaScript -->
    </tbody>
</table>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Add Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contactForm">
                    <input type="hidden" id="contactId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select class="form-select" id="company_id" name="company_id">
                            <!-- Options will be loaded by JavaScript -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="contactForm" class="btn btn-primary">Save Contact</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Notification</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      <!-- Message will be inserted here -->
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const contactModal = new bootstrap.Modal(document.getElementById('contactModal'));
    const contactForm = document.getElementById('contactForm');
    const contactModalLabel = document.getElementById('contactModalLabel');
    const tableBody = document.getElementById('contactsTableBody');
    const companySelect = document.getElementById('company_id');
    const toastElement = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastElement);

    const apiContactsUrl = '/api/contacts';
    const apiCompaniesUrl = '/api/companies';
    let companiesCache = [];

    // --- Utility Functions ---
    const showToast = (message, success = true) => {
        toastElement.querySelector('.toast-body').textContent = message;
        toastElement.classList.remove('bg-success', 'bg-danger');
        toastElement.classList.add(success ? 'bg-success' : 'bg-danger');
        toast.show();
    };
    
    const escapeHTML = (str) => {
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"']/g, function (m) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[m];
        });
    };
    
    const getCompanyName = (companyId) => {
        const company = companiesCache.find(c => c.id == companyId);
        return company ? company.name : 'N/A';
    }

    const renderRow = (contact) => {
        const companyName = getCompanyName(contact.company_id);
        const row = document.createElement('tr');
        row.setAttribute('data-id', contact.id);
        row.innerHTML = `
            <td>${contact.id}</td>
            <td>${escapeHTML(contact.name)}</td>
            <td>${escapeHTML(contact.email)}</td>
            <td>${escapeHTML(contact.phone)}</td>
            <td>${escapeHTML(companyName)}</td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
            </td>
        `;
        return row;
    };

    const loadCompaniesForSelect = async () => {
        try {
            const response = await fetch(apiCompaniesUrl);
            companiesCache = await response.json();
            companySelect.innerHTML = '<option value="">Select a company</option>';
            companiesCache.forEach(company => {
                const option = document.createElement('option');
                option.value = company.id;
                option.textContent = company.name;
                companySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Failed to load companies for select:', error);
        }
    };
    
    // --- Load Initial Data ---
    const loadContacts = async () => {
        try {
            const response = await fetch(apiContactsUrl);
            const contacts = await response.json();
            tableBody.innerHTML = '';
            contacts.forEach(contact => tableBody.appendChild(renderRow(contact)));
        } catch (error) {
            console.error('Failed to load contacts:', error);
            showToast('Failed to load contacts.', false);
        }
    };

    // --- Event Handlers ---
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData.entries());
        const contactId = data.id;
        const method = contactId ? 'PUT' : 'POST';
        const url = contactId ? `${apiContactsUrl}/${contactId}` : apiContactsUrl;

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (!response.ok) throw new Error('Server error');

            const savedContact = await response.json();
            
            // Refetch company name if it was changed
            if (contactId && savedContact.company_id !== data.company_id) {
                 await loadCompaniesForSelect();
            }

            if (contactId) {
                const oldRow = tableBody.querySelector(`tr[data-id="${contactId}"]`);
                if (oldRow) oldRow.replaceWith(renderRow(savedContact));
            } else {
                tableBody.appendChild(renderRow(savedContact));
            }
            
            contactModal.hide();
            showToast(`Contact ${contactId ? 'updated' : 'created'} successfully!`);
        } catch (error) {
            console.error('Failed to save contact:', error);
            showToast('Failed to save contact.', false);
        }
    });

    document.getElementById('addContactBtn').addEventListener('click', () => {
        contactForm.reset();
        document.getElementById('contactId').value = '';
        contactModalLabel.textContent = 'Add Contact';
        companySelect.value = '';
        contactModal.show();
    });

    tableBody.addEventListener('click', async (e) => {
        const target = e.target;
        const row = target.closest('tr');
        if (!row) return;
        const contactId = row.dataset.id;

        if (target.classList.contains('edit-btn')) {
            try {
                const response = await fetch(`${apiContactsUrl}/${contactId}`);
                const contact = await response.json();
                
                document.getElementById('contactId').value = contact.id;
                document.getElementById('name').value = contact.name || '';
                document.getElementById('email').value = contact.email || '';
                document.getElementById('phone').value = contact.phone || '';
                document.getElementById('company_id').value = contact.company_id || '';

                contactModalLabel.textContent = 'Edit Contact';
                contactModal.show();
            } catch (error) {
                 console.error('Failed to fetch contact for editing:', error);
                 showToast('Failed to load contact data.', false);
            }
        }

        if (target.classList.contains('delete-btn')) {
            if (confirm('Are you sure you want to delete this contact?')) {
                try {
                    const response = await fetch(`${apiContactsUrl}/${contactId}`, { method: 'DELETE' });
                    if (!response.ok) {
                        const err = await response.json();
                        throw new Error(err.message || 'Server error');
                    }
                    row.remove();
                    showToast('Contact deleted successfully!');
                } catch (error) {
                    console.error('Failed to delete contact:', error);
                    showToast(`Failed to delete contact. ${error.message}`, false);
                }
            }
        }
    });

    // --- Initial Load ---
    await loadCompaniesForSelect();
    await loadContacts();
});
</script> 