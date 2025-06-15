<style>
    .kpi-card {
        border-left: 5px solid;
        border-radius: .35rem;
        transition: all 0.3s;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .border-primary { border-color: #4e73df !important; }
    .border-success { border-color: #1cc88a !important; }
    .border-info { border-color: #36b9cc !important; }
    .text-primary { color: #4e73df !important; }
    .text-success { color: #1cc88a !important; }
    .text-info { color: #36b9cc !important; }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= __('dashboard') ?></h1>
</div>

<!-- KPI Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card kpi-card border-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?= __('new_contacts_last_30_days') ?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['new_contacts_last_30_days'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card kpi-card border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><?= __('deals_won_last_30_days') ?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['won_deals_last_30_days_count'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-trophy fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card kpi-card border-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><?= __('revenue_last_30_days') ?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($stats['won_deals_last_30_days_sum'], 2) ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Pipeline Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= __('pipeline_overview') ?></h6>
            </div>
            <div class="card-body">
                <div class="chart-bar"><canvas id="pipelineChart"></canvas></div>
            </div>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= __('my_upcoming_tasks') ?></h6>
            </div>
            <div class="card-body">
                <?php if (empty($tasks)): ?>
                    <p class="text-center"><?= __('no_upcoming_tasks') ?></p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($tasks as $task): ?>
                            <li class="list-group-item">
                                <a href="/tasks/<?= $task['id'] ?>/edit"><?= htmlspecialchars($task['name']) ?></a>
                                <div class="small text-muted">
                                    <?= __('due') ?>: <?= date('M d, Y', strtotime($task['due_date'])) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                 <a href="/tasks" class="btn btn-primary btn-sm mt-3"><?= __('view_all_tasks') ?></a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pipeline Chart
    const ctx = document.getElementById('pipelineChart').getContext('2d');
    const pipelineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $chartLabels ?>,
            datasets: [{
                label: '<?= __('number_of_deals') ?>',
                data: <?= $chartData ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.2)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" /> 