<?php
    // Get current user ID for the form
    $currentUserId = (new \App\Core\Auth())->id();
?>
<style>
    .pipeline-stage {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .deal-card {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>

<h1>Sales Pipeline</h1>
<button id="addDealBtn" class="btn btn-primary mb-3">Add Deal</button>

<div class="container-fluid">
    <div class="row flex-nowrap overflow-auto" id="pipelineContainer">
        <?php foreach ($dealsByStage as $stageId => $stageData): ?>
            <div class="col-md-3">
                <div class="pipeline-stage">
                    <h5 class="text-center"><?= htmlspecialchars($stageData['name']) ?></h5>
                    <hr>
                    <div class="deals-container" data-stage-id="<?= $stageId ?>">
                        <?php foreach ($stageData['deals'] as $deal): ?>
                            <div class="deal-card" data-deal-id="<?= $deal->id ?>">
                                <h6><?= htmlspecialchars($deal->name) ?></h6>
                                <p class="mb-1"><strong>Budget:</strong> $<?= number_format($deal->budget, 2) ?></p>
                                <p class="mb-1"><strong>Contact:</strong> <?= htmlspecialchars($deal->contact_name) ?></p>
                                <p class="mb-0"><strong>Manager:</strong> <?= htmlspecialchars($deal->user_name) ?></p>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($stageData['deals'])): ?>
                            <p class="text-center text-muted">No deals in this stage</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Deal Modal -->
<div class="modal fade" id="dealModal" tabindex="-1" aria-labelledby="dealModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dealModalLabel">Add New Deal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dealForm">
                    <div class="mb-3">
                        <label for="dealName" class="form-label">Deal Name</label>
                        <input type="text" class="form-control" id="dealName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dealBudget" class="form-label">Budget</label>
                        <input type="number" class="form-control" id="dealBudget" name="budget" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="dealContact" class="form-label">Contact</label>
                        <select class="form-select" id="dealContact" name="contact_id" required>
                            <!-- Options loaded via JS -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dealStage" class="form-label">Pipeline Stage</label>
                        <select class="form-select" id="dealStage" name="stage_id" required>
                             <?php foreach ($dealsByStage as $stageId => $stageData): ?>
                                <option value="<?= $stageId ?>"><?= htmlspecialchars($stageData['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $currentUserId ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="dealForm" class="btn btn-primary">Save Deal</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dealContainers = document.querySelectorAll('.deals-container');

        dealContainers.forEach(container => {
            new Sortable(container, {
                group: 'deals',
                animation: 150,
                onEnd: function (evt) {
                    const dealId = evt.item.dataset.dealId;
                    const newStageId = evt.to.dataset.stageId;
                    
                    fetch(`/api/deals/${dealId}/move`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ new_stage_id: newStageId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error('Failed to move deal');
                            // Optionally move the card back to its original position
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // --- Add Deal Logic ---
        const dealModal = new bootstrap.Modal(document.getElementById('dealModal'));
        const dealForm = document.getElementById('dealForm');
        const addDealBtn = document.getElementById('addDealBtn');
        const toastElement = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastElement);

        const showToast = (message, success = true) => {
            toastElement.querySelector('.toast-body').textContent = message;
            toastElement.classList.remove('bg-success', 'bg-danger');
            toastElement.classList.add(success ? 'bg-success' : 'bg-danger');
            toast.show();
        };

        addDealBtn.addEventListener('click', () => {
            dealForm.reset();
            dealModal.show();
        });

        const loadContactsForSelect = async () => {
            try {
                const response = await fetch('/api/contacts');
                const contacts = await response.json();
                const select = document.getElementById('dealContact');
                select.innerHTML = '<option value="">Select a contact</option>';
                contacts.forEach(contact => {
                    const option = document.createElement('option');
                    option.value = contact.id;
                    option.textContent = contact.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Failed to load contacts:', error);
                showToast('Failed to load contacts.', false);
            }
        };

        const createDealCard = (deal) => {
            const card = document.createElement('div');
            card.className = 'deal-card';
            card.dataset.dealId = deal.id;
            card.innerHTML = `
                <h6>${escapeHTML(deal.name)}</h6>
                <p class="mb-1"><strong>Budget:</strong> $${Number(deal.budget).toFixed(2)}</p>
                <p class="mb-1"><strong>Contact:</strong> ${escapeHTML(deal.contact_name)}</p>
                <p class="mb-0"><strong>Manager:</strong> ${escapeHTML(deal.user_name)}</p>
            `;
            return card;
        };
        
        const escapeHTML = (str) => {
            if (str === null || str === undefined) return '';
            return String(str).replace(/[&<>"']/g, (m) => ({'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'}[m]));
        };

        dealForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(dealForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/deals', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    const error = new Error(errorData.message || 'Server error');
                    error.response = errorData; // Attach full response
                    throw error;
                }

                const newDeal = await response.json();
                
                const targetStageContainer = document.querySelector(`.deals-container[data-stage-id="${newDeal.stage_id}"]`);
                if(targetStageContainer) {
                    const emptyText = targetStageContainer.querySelector('.text-muted');
                    if (emptyText) emptyText.remove();
                    targetStageContainer.appendChild(createDealCard(newDeal));
                }

                dealModal.hide();
                showToast('Deal created successfully!');
            } catch (error) {
                console.error('Failed to create deal:', error.response || error);
                showToast(`Error: ${error.message}`, false);
            }
        });

        loadContactsForSelect();
    });
</script> 