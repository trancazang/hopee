@extends(backpack_view('blank'))

@section('content')
<div class="container">
    <h3 class="mb-4">📊 Thống kê hệ thống</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    👤 Người dùng: <strong>{{ $userCount }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    🧵 Chủ đề: <strong>{{ $threadCount }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    📝 Bài viết: <strong>{{ $postCount }}</strong>
                </div>
            </div>
        </div>
    </div>
<canvas id="postsChart" class="mt-5"></canvas>
</div>
@endsection

@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('postsChart');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($postsByMonth->toArray())) !!},
            datasets: [{
                label: 'Bài viết theo tháng',
                data: {!! json_encode(array_values($postsByMonth->toArray())) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
@endsection
