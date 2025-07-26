<div x-data="manage">
    @include ('forum::components.loading-overlay')
    @include ('forum::components.breadcrumbs')

    <div class="flex justify-center items-center">
        <div class="grow max-w-screen-lg">
            <h1>{{ trans('forum::general.manage') }}</h1>
                        
            @can ('createCategories')
                <div class="mb-6 text-right">
                    <x-forum::link-button
                        :label="trans('forum::categories.create')"
                        icon="squares-plus-outline"
                        :href="Forum::route('category.create')" />
                </div>
            @endcan

            <div class="bg-white dark:bg-slate-700 rounded-md shadow-md my-2 p-6">
                <ol id="category-tree">
                    @include ('forum::components.category.draggable-items', ['categories' => $categories])
                </ol>

                <div class="mt-4 text-right">
                    <x-forum::button
                        id="save"
                        :label="trans('forum::general.save')"
                        x-ref="button"
                        @click="save"
                        disabled />
                </div>
            </div>
            <div class="container py-5">
                <h3 class="mb-4">📊 Tổng quan hệ thống</h3>
            
                {{-- Tổng quan hệ thống --}}
                <div class="row g-4 text-center mb-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="text-muted small mb-2"><i class="la la-user text-primary"></i> Người dùng</div>
                                <div class="display-5 fw-bold text-primary" id="userCount">...</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="text-muted small mb-2"><i class="la la-comments text-success"></i> Chủ đề</div>
                                <div class="display-5 fw-bold text-success" id="threadCount">...</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="text-muted small mb-2"><i class="la la-file-alt text-warning"></i> Bài viết</div>
                                <div class="display-5 fw-bold text-warning" id="postCount">...</div>
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
                                    <div class="display-6 fw-bold text-primary" id="newUsersThisWeek">...</div>
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
                                    <div class="display-6 fw-bold text-info" id="newPostsThisWeek">...</div>
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
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>👤 Họ tên</th>
                                    <th>📧 Email</th>
                                    <th>🕐 Thời gian tạo</th>
                                </tr>
                            </thead>
                            <tbody id="newUsersList">
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            
                {{-- Danh sách các thống kê chi tiết --}}
                <div class="row g-4">
                    <div class="col-md-4">
                        <h5>🔥 Chủ đề được thảo luận nhiều nhất</h5>
                        <ul class="list-group shadow-sm" id="topThreads">
                                {{-- Li sẽ được render bằng JS --}}

                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>🌟 Bài viết có sức ảnh hưởng</h5>
                        <ul class="list-group shadow-sm" id="topPosts">
                                    {{-- Li sẽ được render bằng JS --}}

                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>👨‍🏫 Người dùng ảnh hưởng nhất</h5>
                        <ul class="list-group shadow-sm" id="topUsers">
                                {{-- Li sẽ được render bằng JS --}}

                        </ul>
                    </div>
                </div>
            
                <div class="mt-5">
                    <h5>🔍 Từ khoá tâm lý phổ biến</h5>
                    <ul class="list-group shadow-sm" id="topKeywords">
                               {{-- Li sẽ được render bằng JS --}}

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('manage', () => {
        return {
            nestedSort: null,
            init () {
                this.initialiseNestedSort();
            },
            initialiseNestedSort() {
                this.nestedSort = new NestedSort({
                    propertyMap: {
                        id: 'id',
                        parent: 'parent_id',
                        text: 'title',
                    },
                    actions: {
                        onDrop: () => $refs.button.disabled = false
                    },
                    el: '#category-tree',
                    listItemClassNames: 'border border-slate-300 rounded-md text-lg p-4 my-2'
                });
            },
            getItems (ol) {
                let tree = [];
                for (let i = 0; i < ol.children.length; ++i) {
                    let item = { id: ol.children[i].dataset.id, children: [] };
                    for (let j = 0; j < ol.children[i].children.length; ++j) {
                        if (ol.children[i].children[j].tagName == 'OL') {
                            item.children = this.getItems(ol.children[i].children[j]);
                        }
                    }
    
                    tree.push(item);
                }
    
                return tree;
            },
            async save () {
                let tree = this.getItems(document.getElementById('category-tree'));
                $wire.tree = tree;
                const result = await $wire.save();
                $dispatch('alert', result);
                this.initialiseNestedSort();
            }
        }
    });
    </script>
@endscript
@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-cloud/1.2.5/d3.layout.cloud.min.js"></script>

<script>


function drawPostsByMonth(postsByMonth) {
    const ctx = document.getElementById('postsChart')?.getContext('2d');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'],
            datasets: [{
                label: 'Bài viết theo tháng',
                data: Object.values(postsByMonth),
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
                    ticks: { precision: 0 }
                }
            }
        }
    });
}

function drawPostsByWeek(labels, values) {
    const ctx = document.getElementById('postsChartWeek')?.getContext('2d');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bài viết theo tuần',
                data: values,
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.raw} bài viết`
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: { display: true, text: 'Số lượng bài viết' }
                }
            }
        }
    });
}

function drawVotesChart(topUsers) {
    const ctx = document.getElementById('votesChart')?.getContext('2d');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: topUsers.map(u => u.name),
            datasets: [{
                label: 'Điểm vote',
                data: topUsers.map(u => u.score),
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

function drawThreadChart(postsByThread) {
    const ctx = document.getElementById('threadsChart')?.getContext('2d');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(postsByThread),
            datasets: [{
                label: 'Số bài viết',
                data: Object.values(postsByThread),
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

function drawWordCloud(topKeywords) {
    const width = document.getElementById('wordCloud').clientWidth;
    const height = 400;
    const fill = d3.scaleOrdinal(d3.schemeCategory10);

    d3.layout.cloud()
        .size([width, height])
        .words(topKeywords)
        .padding(5)
        .rotate(() => ~~(Math.random() * 2) * 90)
        .font("Arial")
        .fontSize(d => d.size)
        .on("end", draw)
        .start();

    function draw(words) {
        const svg = d3.select("#wordCloud").html("")
            .append("svg")
            .attr("width", width)
            .attr("height", height);

        const g = svg.append("g")
            .attr("transform", `translate(${width / 2},${height / 2})`);

        g.selectAll("text")
            .data(words)
            .enter().append("text")
            .style("font-size", d => `${d.size}px`)
            .style("fill", (d, i) => fill(i))
            .attr("text-anchor", "middle")
            .attr("transform", d => `translate(${[d.x, d.y]})rotate(${d.rotate})`)
            .text(d => d.text)
            .append("title")
            .text(d => `${d.text} (${d.count} lần)`);
    }
}
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('/admin/statistics');
        const data = await res.json();

        // Cập nhật số lượng tổng quan
        document.getElementById('userCount').innerText = data.userCount;
        document.getElementById('postCount').innerText = data.postCount;
        document.getElementById('threadCount').innerText = data.threadCount;
        document.getElementById('newPostsThisWeek').innerText = data.newPostsThisWeek;
        document.getElementById('newUsersThisWeek').innerText = data.newUsersThisWeek;

        // Gọi các hàm vẽ biểu đồ đúng dữ liệu
        drawPostsByMonth(data.postsByMonth);
        drawPostsByWeek(data.labelsByWeek, Object.values(data.postsByWeek));
        drawVotesChart(data.topUsers);
        drawThreadChart(data.postsByThread);
        drawWordCloud(data.topKeywords);

    } catch (error) {
        console.error("❌ Lỗi khi tải dữ liệu thống kê:", error);
    }
});
</script>
@endpush



