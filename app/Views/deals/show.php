<?php
use App\Core\Auth;
$auth = new Auth();
$currentUser = $auth->user();
?>

<div class="container">
    <!-- Deal Details -->
    <div class="card mb-4">
        <div class="card-header">
            <h2><?= htmlspecialchars($deal->name) ?></h2>
        </div>
        <div class="card-body">
            <p><strong>Status:</strong> <span class="badge bg-primary"><?= htmlspecialchars($deal->status) ?></span></p>
            <p><strong>Budget:</strong> <?= htmlspecialchars($deal->budget) ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($deal->company_name ?? 'N/A') ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($deal->contact_name ?? 'N/A') ?></p>
            <p><strong>Manager:</strong> <?= htmlspecialchars($deal->user_name ?? 'N/A') ?></p>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="card">
       
        <div class="card-header">
            <h4>Comments</h4>
        </div>
        <div class="card-body">
       
            <div class="mb-4">
                <form action="/comments" method="POST">
                    <input type="hidden" name="commentable_id" value="<?= $deal->id ?>">
                    <input type="hidden" name="commentable_type" value="<?= \App\Models\Deal::class ?>">
                    <div class="mb-3">
                        <textarea class="form-control" name="body" rows="3" placeholder="Write a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
            
            <!-- Existing Comments -->
            <hr>
            <?php if (!isset($comments) || empty($comments)): ?>
                <p>No comments yet.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <?= !empty($comment['user_name']) ? strtoupper(substr($comment['user_name'], 0, 1)) : '?' ?>
                            </div>
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0"><?= htmlspecialchars($comment['user_name'] ?? 'Anonymous') ?></h6>
                                <?php if (!empty($comment['created_at'])): ?>
                                    <small class="text-muted"><?= date('M d, Y H:i', strtotime($comment['created_at'])) ?></small>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($comment['body'] ?? '')) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div> 