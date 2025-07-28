@extends(backpack_view('blank'))

@section('content')
<div class="container py-5">
    <h3 class="mb-4">📊 Tổng quan hệ thống</h3>

    {{-- Tổng quan hệ thống --}}
    <div class="row g-4 text-center mb-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2"><i class="la la-user text-primary"></i> Người dùng</div>
                    <div class="display-5 fw-bold text-primary">{{ number_format($userCount) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2"><i class="la la-comments text-success"></i> Chủ đề</div>
                    <div class="display-5 fw-bold text-success">{{ number_format($threadCount) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2"><i class="la la-file-alt text-warning"></i> Bài viết</div>
                    <div class="display-5 fw-bold text-warning">{{ number_format($postCount) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Thống kê tuần --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-start border-4 border-success shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">🆕 Người dùng mới trong tuần</div>
                        <div class="display-6 fw-bold text-primary">{{ number_format($newUsersThisWeek) }}</div>
                    </div>
                    <i class="la la-user-plus la-3x text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-start border-4 border-info shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">📝 Bài viết mới trong tuần</div>
                        <div class="display-6 fw-bold text-info">{{ number_format($newPostsThisWeek) }}</div>
                    </div>
                    <i class="la la-file-alt la-3x text-info"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ thống kê --}}
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

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <h5>📅 Bài viết theo tháng</h5>
            <div class="card shadow-sm p-3"><canvas id="postsChart" height="230"></canvas></div>
        </div>
        <div class="col-md-6">
            <h5>📆 Bài viết theo tuần</h5>
            <div class="card shadow-sm p-3"><canvas id="postsChartWeek" height="230"></canvas></div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <h5>👍 Lượt vote theo người dùng</h5>
            <div class="card shadow-sm p-3"><canvas id="votesChart" height="230"></canvas></div>
        </div>
        <div class="col-md-6">
            <h5>🧵 Bài viết theo chủ đề</h5>
            <div class="card shadow-sm p-3"><canvas id="threadsChart" height="230"></canvas></div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <h5>☁️ Từ khoá phổ biến (Word Cloud)</h5>
            <div id="wordCloud" style="width: 100%; height: 350px;"></div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="la la-users"></i> Danh sách người dùng mới trong tuần
                </div>
                <div class="card-body p-0">
                    @if($newUsersList->isEmpty())
                        <div class="p-3 text-muted">Không có người dùng mới trong tuần này.</div>
                    @else
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>👤 Họ tên</th>
                                    <th>📧 Email</th>
                                    <th>🕐 Thời gian tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($newUsersList as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách các thống kê chi tiết --}}
    <div class="row g-4">
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        <div class="col-md-4">
            <h5>👨‍🏫 Chuyên gia có sức ảnh hưởng nhất</h5>
            <ul class="list-group shadow-sm">
                @foreach ($topUsers as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $user['name'] }}
                        <span class="badge bg-success rounded-pill">{{ $user['upvotes'] }} điểm</span>
                    </li>
                @endforeach
            </ul>
        </div>
        
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
    
    // Biểu đồ theo tuần
    const ctxWeek = document.getElementById('postsChartWeek')?.getContext('2d');
    if (ctxWeek) {
        new Chart(ctxWeek, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsByWeek) !!},
                datasets: [{
                    label: 'Bài viết theo tuần',
                    data: {!! json_encode(array_values($postsByWeek)) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y', // 🎯 Biểu đồ ngang
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.raw} bài viết`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng bài viết'
                        }
                    }
                }
            }
        });
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
    const keywordData = {!! json_encode($topKeywords) !!};

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
        const svg = d3.select("#wordCloud")
            .append("svg")
            .attr("width", width)
            .attr("height", height);

        const g = svg.append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        g.selectAll("text")
            .data(words)
            .enter().append("text")
            .style("font-size", d => d.size + "px")
            .style("fill", (d, i) => fill(i))
            .attr("text-anchor", "middle")
            .attr("transform", d =>
                `translate(${[d.x, d.y]})rotate(${d.rotate})`
            )
            .text(d => d.text)
            .append("title") // ⬅ Tooltip khi hover
            .text(d => `${d.text} (${d.count} lần)`);
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
