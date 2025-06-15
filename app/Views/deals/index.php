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
<a href="/deals/create" class="btn btn-primary mb-3">Add Deal</a>

<div class="container-fluid">
    <div class="row flex-nowrap overflow-auto">
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
    });
</script> 