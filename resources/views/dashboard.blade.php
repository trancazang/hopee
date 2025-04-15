@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Thống kê tổng quan
        </h1>
    </section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header font-weight-bold">Bài viết tuần này: {{ $postThisWeek }} <small class="text-muted">(so với tuần trước: {{ $postChange }})</small></div>
            <div class="card-body">
                <canvas id="postsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header font-weight-bold">Người dùng mới tuần này: {{ $userThisWeek }} <small class="text-muted">(so với tuần trước: {{ $userChange }})</small></div>
            <div class="card-body">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const weeks = @json($weeks);

    const labels = weeks.map(w => w.label);
    const postsData = weeks.map(w => w.posts);
    const usersData = weeks.map(w => w.users);

    new Chart(document.getElementById('postsChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bài viết',
                data: postsData,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
    });

    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Người dùng',
                data: usersData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
    });
</script>
@endsection
