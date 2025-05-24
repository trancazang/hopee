@extends(backpack_view('blank'))

@section('content')
<div class="container" style="margin-top: 80px;">

    <h3 class="mb-4">📊 Thống kê hệ thống</h3>
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label>Tháng</label>
            <select name="month" class="form-select">
                <option value="">-- Tất cả --</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label>Năm</label>
            <select name="year" class="form-select">
                <option value="">-- Tất cả --</option>
                @for ($y = 2023; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>
    
    {{-- Tổng quan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    👤 Người dùng: <strong>{{ $userCount }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    🧵 Chủ đề: <strong>{{ $threadCount }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    📝 Bài viết: <strong>{{ $postCount }}</strong>
                </div>
            </div>
        </div>
    </div>
    <span> </span>
    <div class="row">
        {{-- Bài viết theo tháng --}}
        <div class="col-md-6 mb-4">
            <h5>📅 Bài viết theo tháng</h5>
            <div class="card shadow-sm p-3">
                <canvas id="postsChart" height="230"></canvas>
            </div>
        </div>
        {{-- Word Cloud --}}
        <div class="col-md-6 mb-4">
            <h5>☁️ Từ khoá phổ biến (Word Cloud)</h5>
            <div id="wordCloud" style="width: 100%; height: 350px;"></div>
        </div>
    </div>
    
    <div class="row">
        {{-- Vote theo người dùng --}}
        <div class="col-md-6 mb-4">
            <h5>👍 Lượt vote theo người dùng</h5>
            <div class="card shadow-sm p-3">
                <canvas id="votesChart" height="230"></canvas>
            </div>
        </div>
    
        {{-- Bài viết theo chủ đề --}}
        <div class="col-md-6 mb-4">
            <h5>🧵 Bài viết theo chủ đề</h5>
            <div class="card shadow-sm p-3">
                <canvas id="threadsChart" height="230"></canvas>
            </div>
        </div>
    </div>

    {{-- Chủ đề nổi bật --}}
    <div class="mt-5">
        <h5>🔥 Chủ đề được thảo luận nhiều nhất</h5>
        <ul class="list-group shadow-sm">
            @foreach ($topThreads as $thread)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $thread['title'] }}
                    <span class="badge bg-primary rounded-pill">{{ $thread['count'] }} bài viết</span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Bài viết có ảnh hưởng --}}
    <div class="mt-5">
        <h5>🌟 Bài viết có sức ảnh hưởng</h5>
        <ul class="list-group shadow-sm">
            @foreach ($topPosts as $post)
                <li class="list-group-item">
                    {!! \Illuminate\Support\Str::limit(strip_tags($post->content), 100) !!}<br>
                    <span class="text-muted">Điểm ảnh hưởng: {{ $post->score }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Người dùng ảnh hưởng --}}
    <div class="mt-5">
        <h5>👨‍🏫 Người dùng ảnh hưởng nhất</h5>
        <ul class="list-group shadow-sm">
            @foreach ($topUsers as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $user['name'] }}
                    <span class="badge bg-success rounded-pill">{{ $user['score'] }} điểm</span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Từ khoá phổ biến dạng danh sách --}}
    <div class="mt-5 mb-5">
        <h5>🔍 Từ khoá tâm lý phổ biến</h5>
        <ul class="list-group shadow-sm">
            @foreach ($topKeywords as $word => $count)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $word }}
                    <span class="badge bg-secondary rounded-pill">{{ $count }} lần</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-cloud/1.2.5/d3.layout.cloud.min.js"></script>

<script>
    const ctx = document.getElementById('postsChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'],
                datasets: [{
                    label: 'Bài viết theo tháng',
                    data: {!! json_encode(array_values($postsByMonth)) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.5,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    } else {
        console.error("Không tìm thấy canvas #postsChart");
    }
    //biểu đồ user vote
    const voteCtx = document.getElementById('votesChart')?.getContext('2d');
    if (voteCtx) {
        new Chart(voteCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($topUsers)->pluck('name')) !!},
                datasets: [{
                    label: 'Điểm vote',
                    data: {!! json_encode(collect($topUsers)->pluck('score')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        title: { display: true, text: 'Điểm tổng' }
                    }
                }
            }
        });
    }
    //biểu đồ từ khoá
    const keywordData = {!! json_encode(
        collect($topKeywords)->map(function ($count, $word) {
            return ['text' => $word, 'size' => 10 + $count * 2];
        })->values()
    ) !!};

    const width = document.getElementById('wordCloud').clientWidth;
    const height = 400;

    const fill = d3.scaleOrdinal(d3.schemeCategory10);

    d3.layout.cloud()
        .size([width, height])
        .words(keywordData)
        .padding(5)
        .rotate(() => ~~(Math.random() * 2) * 90)
        .font("Arial")
        .fontSize(d => d.size)
        .on("end", draw)
        .start();

    function draw(words) {
        d3.select("#wordCloud")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")")
            .selectAll("text")
            .data(words)
            .enter().append("text")
            .style("font-size", d => d.size + "px")
            .style("fill", (d, i) => fill(i))
            .attr("text-anchor", "middle")
            .attr("transform", d =>
                `translate(${[d.x, d.y]})rotate(${d.rotate})`
            )
            .text(d => d.text);
    }
    //số lượng bài theo chủ đề
    const threadCtx = document.getElementById('threadsChart')?.getContext('2d');
    if (threadCtx) {
        new Chart(threadCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($postsByThread)) !!},
                datasets: [{
                    label: 'Số bài viết',
                    data: {!! json_encode(array_values($postsByThread)) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endsection
