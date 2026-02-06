<!--begin::Footer-->
<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">Politeknik Takumi</div>
    <strong>
        Copyright &copy; 2026&nbsp;
        <a href="#" class="text-decoration-none">Tools Management</a>.
    </strong>
</footer>
<!--end::Footer-->

</div> <!-- end::App Wrapper -->

<!--begin::Scripts-->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>

{{-- sweeet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.min.js"></script>

<!-- Required Plugin (Bootstrap 5 without integrity error) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>




<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js">
</>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && window.OverlayScrollbarsGlobal?.OverlayScrollbars) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: 'os-theme-light',
                    autoHide: 'leave',
                    clickScroll: true
                },
            });
        }
    });
</script>

<!-- Optional Plugins -->
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.connectedSortable').forEach(el => {
            new Sortable(el, {
                group: 'shared',
                handle: '.card-header'
            });
            el.querySelectorAll('.card-header').forEach(header => header.style.cursor = 'move');
        });
    });
</script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartEl = document.querySelector('#revenue-chart');
        if (chartEl) {
            const options = {
                series: [{
                        name: 'Digital Goods',
                        data: [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        name: 'Electronics',
                        data: [65, 59, 80, 81, 56, 55, 40]
                    }
                ],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#0d6efd', '#20c997'],
                stroke: {
                    curve: 'smooth'
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    type: 'datetime',
                    categories: ['2023-01-01', '2023-02-01', '2023-03-01', '2023-04-01', '2023-05-01',
                        '2023-06-01', '2023-07-01'
                    ]
                },
                tooltip: {
                    x: {
                        format: 'MMMM yyyy'
                    }
                },
                legend: {
                    show: false
                }
            };
            new ApexCharts(chartEl, options).render();
        }
    });
</script>

<!-- Sparkline Example -->
<script>
    const sales_chart = new ApexCharts(document.querySelector('#revenue-chart'), {
        series: [{
                name: 'Digital Goods',
                data: [28, 48, 40, 19, 86, 27, 90]
            },
            {
                name: 'Electronics',
                data: [65, 59, 80, 81, 56, 55, 40]
            }
        ],
        chart: {
            height: 300,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        legend: {
            show: false
        },
        colors: ['#0d6efd', '#20c997'],
        stroke: {
            curve: 'smooth'
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            type: 'datetime',
            categories: ['2023-01-01', '2023-02-01', '2023-03-01', '2023-04-01', '2023-05-01', '2023-06-01',
                '2023-07-01'
            ],
        },
        tooltip: {
            x: {
                format: 'MMMM yyyy'
            }
        }
    });
    sales_chart.render();
</script>

<!-- jsVectorMap -->
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mapEl = document.querySelector('#world-map');
        if (mapEl) {
            new jsVectorMap({
                selector: '#world-map',
                map: 'world'
            });
        }
    });
</script>
<!--end::Scripts-->
