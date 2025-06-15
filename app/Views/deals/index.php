<?php
    use App\Core\Config;
    // Get current user ID for the form
    $currentUserId = (new \App\Core\Auth())->id();

    // --- Currency Conversion Setup ---
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    $currencyService = new \App\Services\CurrencyService();
    $baseCurrency = Config::get('base_currency');
    $displayCurrency = $_SESSION['currency'] ?? $baseCurrency;
    $currencies = Config::get('currencies');
    $displaySymbol = $currencies[$displayCurrency] ?? '$';

    // Fetch rates if not in session or if base has changed
    $rates = $_SESSION['currency_rates'] ?? null;
    if ($rates === null || ($_SESSION['currency_rates_base'] ?? '') !== $baseCurrency) {
        $rates = $currencyService->getRates($baseCurrency);
        $_SESSION['currency_rates'] = $rates;
        $_SESSION['currency_rates_base'] = $baseCurrency;
    }

    // Helper function for display
    function formatDisplayCurrency($amountInBase, $displayCurrency, $displaySymbol, $rates) {
        if (!isset($rates[$displayCurrency])) { return $displaySymbol . number_format($amountInBase, 2); } // Fallback
        $convertedAmount = $amountInBase * $rates[$displayCurrency];
        return $displaySymbol . number_format($convertedAmount, 2);
    }
    // --- End Currency Setup ---
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
        cursor: grab;
    }
    .deal-card-link, .deal-card-link:hover {
        text-decoration: none;
        color: inherit;
    }
</style>

<h1><?= __('sales_pipeline') ?></h1>
<button id="addDealBtn" class="btn btn-primary mb-3"><?= __('add_deal') ?></button>

<div class="container-fluid">
    <div class="row flex-nowrap overflow-auto" id="pipelineContainer">
        <?php foreach ($dealsByStage as $stageId => $stageData): ?>
            <div class="col-md-3">
                <div class="pipeline-stage">
                    <h5 class="text-center"><?= htmlspecialchars($stageData['name']) ?></h5>
                    <hr>
                    <div class="deals-container" data-stage-id="<?= $stageId ?>">
                        <?php foreach ($stageData['deals'] as $deal): ?>
                            <a href="/deals/<?= $deal->id ?>" class="deal-card-link">
                                <div class="deal-card" data-deal-id="<?= $deal->id ?>">
                                    <h6><?= htmlspecialchars($deal->name) ?></h6>
                                    <p class="mb-1"><strong><?= __('budget') ?>:</strong> <?= formatDisplayCurrency($deal->budget, $displayCurrency, $displaySymbol, $rates) ?></p>
                                    <p class="mb-1"><strong><?= __('contact') ?>:</strong> <?= htmlspecialchars($deal->contact_name) ?></p>
                                    <p class="mb-0"><strong><?= __('manager') ?>:</strong> <?= htmlspecialchars($deal->user_name) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <?php if (empty($stageData['deals'])): ?>
                            <p class="text-center text-muted no-deals"><?= __('no_deals_in_stage') ?></p>
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
                <h5 class="modal-title" id="dealModalLabel"><?= __('add_deal') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('close') ?>"></button>
            </div>
            <div class="modal-body">
                <form id="dealForm">
                    <div class="mb-3">
                        <label for="dealName" class="form-label"><?= __('deal_name') ?></label>
                        <input type="text" class="form-control" id="dealName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dealBudget" class="form-label"><?= __('budget') ?></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="dealBudget" name="budget" step="0.01">
                            <select class="form-select" name="currency" id="dealCurrency" style="max-width: 100px;">
                                <?php foreach ($currencies as $code => $symbol): ?>
                                    <option value="<?= $code ?>" <?= $code === $displayCurrency ? 'selected' : '' ?>><?= $code ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="dealContact" class="form-label"><?= __('contact') ?></label>
                        <select class="form-select" id="dealContact" name="contact_id" required>
                            <!-- Options loaded via JS -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dealStage" class="form-label"><?= __('pipeline_stage') ?></label>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('close') ?></button>
                <button type="submit" form="dealForm" class="btn btn-primary"><?= __('save_deal') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto"><?= __('notification') ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="<?= __('close') ?>"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
const translations = {
    select_contact: "<?= __('select_contact') ?>",
    failed_to_load_contacts: "<?= __('failed_to_load_contacts') ?>",
    failed_to_move_deal: "<?= __('failed_to_move_deal') ?>",
    deal_created_successfully: "<?= __('deal_created_successfully') ?>",
    failed_to_create_deal: "<?= __('failed_to_create_deal') ?>",
    server_error: "<?= __('server_error') ?>",
    budget: "<?= __('budget') ?>",
    contact: "<?= __('contact') ?>",
    manager: "<?= __('manager') ?>",
};

const currencyInfo = {
    rates: <?= json_encode($rates) ?>,
    base: '<?= $baseCurrency ?>',
    display: '<?= $displayCurrency ?>',
    displaySymbol: '<?= $displaySymbol ?>'
};

document.addEventListener('DOMContentLoaded', function () {
    const dealContainers = document.querySelectorAll('.deals-container');
    new Sortable(document.getElementById('pipelineContainer'), { group: 'stages', animation: 150 });

    dealContainers.forEach(container => {
        new Sortable(container, {
            group: 'deals',
            animation: 150,
            onEnd: function (evt) {
                const dealId = evt.item.dataset.dealId;
                const newStageId = evt.to.dataset.stageId;
                
                fetch(`/api/deals/${dealId}/move`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ new_stage_id: newStageId })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error(translations.failed_to_move_deal);
                        showToast(translations.failed_to_move_deal, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast(translations.server_error, false);
                });
            }
        });
    });

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
            select.innerHTML = `<option value="">${translations.select_contact}</option>`;
            contacts.forEach(contact => {
                const option = document.createElement('option');
                option.value = contact.id;
                option.textContent = contact.name;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Failed to load contacts:', error);
            showToast(translations.failed_to_load_contacts, false);
        }
    };

    const formatCurrencyJS = (amount, symbol, rates) => {
        if (!amount) return 'N/A';
        const rate = rates[currencyInfo.display] || 1;
        const convertedAmount = parseFloat(amount) * rate;
        return `${symbol}${convertedAmount.toFixed(2)}`;
    };

    const createDealCard = (deal) => {
        const card = document.createElement('div');
        card.className = 'deal-card';
        card.dataset.dealId = deal.id;
        const budget = formatCurrencyJS(deal.budget, currencyInfo.displaySymbol, currencyInfo.rates);
        card.innerHTML = `
            <h6>${escapeHTML(deal.name)}</h6>
            <p class="mb-1"><strong>${translations.budget}:</strong> ${budget}</p>
            <p class="mb-1"><strong>${translations.contact}:</strong> ${escapeHTML(deal.contact_name)}</p>
            <p class="mb-0"><strong>${translations.manager}:</strong> ${escapeHTML(deal.user_name)}</p>
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
                throw new Error(errorData.message || translations.server_error);
            }

            const newDeal = await response.json();
            
            const targetStageContainer = document.querySelector(`.deals-container[data-stage-id="${newDeal.stage_id}"]`);
            if(targetStageContainer) {
                const emptyText = targetStageContainer.querySelector('.no-deals');
                if (emptyText) emptyText.remove();
                targetStageContainer.appendChild(createDealCard(newDeal));
            }

            dealModal.hide();
            showToast(translations.deal_created_successfully);
        } catch (error) {
            console.error('Failed to create deal:', error);
            showToast(`${translations.failed_to_create_deal}: ${error.message}`, false);
        }
    });

    loadContactsForSelect();
});
</script> 