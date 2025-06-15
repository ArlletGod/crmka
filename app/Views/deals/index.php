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
                    <div class="deals-container">
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