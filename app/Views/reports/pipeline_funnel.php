<h1>Pipeline Funnel</h1>

<div style="width: 75%; margin: auto;">
    <canvas id="pipelineChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pipelineChart').getContext('2d');
    const pipelineChart = new Chart(ctx, {
        type: 'bar', // Using a bar chart to represent the funnel stages
        data: {
            labels: <?= $chartLabels ?>,
            datasets: [{
                label: '# of Deals',
                data: <?= $chartData ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            indexAxis: 'y', // To make the bar chart horizontal, resembling a funnel
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Deals per Stage'
                }
            }
        }
    });
</script> 