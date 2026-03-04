/**
 * dashboard-superadmin.js
 *
 * Perbaikan:
 *  1. DataTable — TIDAK diinisialisasi ulang di sini (sudah dihandle dashboard3.js di layout)
 *  2. Chart responsive — pakai window resize + percentage width
 *  3. Filter Tren per bulan via AJAX
 */
(function () {
    "use strict";

    var data = window.DashboardData || {};

    // ── Chart 1: Tren Pendaftaran (Bar — background + data) ─────────────────
    //
    // Teknik: dual series stacked
    //   series[0] = "sisa"  → max - nilai aktual → warna abu (background)
    //   series[1] = "aktual" → nilai asli         → warna biru
    // Hasilnya: setiap bar selalu penuh (abu), bagian biru menimpa dari bawah.
    //
    var TREND_MAX = 0; // akan dihitung dari data

    var trendChart = null;

    function calcMax(values) {
        // Ambil nilai tertinggi, minimal 5 agar chart tidak terlalu pipih
        var m = Math.max.apply(null, values.concat([5]));
        // Bulatkan ke atas ke kelipatan 5 agar terlihat rapi
        return Math.ceil(m / 5) * 5;
    }

    function buildTrendOptions(labels, values) {
        TREND_MAX = calcMax(values);
        var bgData = values.map(function (v) {
            return TREND_MAX - v;
        });

        return {
            series: [
                { name: "Pendaftar", data: values }, // biru — di bawah
                { name: "_bg", data: bgData }, // abu  — di atas (sisa ruang)
            ],
            chart: {
                height: 240,
                type: "bar",
                stacked: true,
                toolbar: { show: false },
                parentHeightOffset: 0,
                redrawOnWindowResize: true,
                offsetY: 10,
            },
            responsive: [
                {
                    breakpoint: 768,
                    options: { chart: { height: 200 } },
                },
            ],
            plotOptions: {
                bar: {
                    columnWidth: "52%",
                    borderRadius: 8,
                    borderRadiusApplication: "end",
                    borderRadiusWhenStacked: "last",
                    dataLabels: { position: "top" },
                },
            },
            colors: ["#265ed7", "#e8ecf5"],
            // Tampilkan label di series abu (index 1, paling atas),
            // tapi isi nilainya dari values (series biru) via custom formatter
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1],
                offsetY: -8,
                style: {
                    fontSize: "13px",
                    fontWeight: "700",
                    colors: ["#374151"],
                },
                background: { enabled: false },
                formatter: function (val, opts) {
                    // Ambil nilai aktual dari series biru (index 0)
                    var actual = opts.w.globals.series[0][opts.dataPointIndex];
                    return actual > 0 ? actual : "";
                },
            },
            legend: { show: false },
            xaxis: {
                categories: labels,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: "#8c9094", fontSize: "12px" } },
            },
            yaxis: { show: false, max: TREND_MAX },
            grid: { show: false },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val, opts) {
                        if (opts.seriesIndex === 1) return undefined;
                        return val + " pendaftar";
                    },
                    title: {
                        formatter: function (seriesName) {
                            return seriesName === "_bg" ? "" : seriesName + ":";
                        },
                    },
                },
            },
            states: {
                hover: { filter: { type: "none" } },
            },
        };
    }

    function updateTrendChart(labels, values) {
        var newMax = calcMax(values);
        var bgData = values.map(function (v) {
            return newMax - v;
        });

        trendChart.updateOptions(
            {
                yaxis: { show: false, max: newMax },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: "#8c9094", fontSize: "12px" } },
                },
            },
            false,
            false,
        );

        trendChart.updateSeries([
            { name: "Pendaftar", data: values },
            { name: "_bg", data: bgData },
        ]);
    }

    var elTrend = document.querySelector("#trend-pendaftaran-chart");
    if (elTrend) {
        trendChart = new ApexCharts(
            elTrend,
            buildTrendOptions(
                data.trendLabels || [
                    "Minggu 1",
                    "Minggu 2",
                    "Minggu 3",
                    "Minggu 4",
                    "Minggu 5",
                ],
                data.trendData || [0, 0, 0, 0, 0],
            ),
        );
        trendChart.render();
    }

    // ── Filter bulan (AJAX) ──────────────────────────────────────────────────
    var selectBulan = document.getElementById("filter-bulan");
    if (selectBulan && trendChart) {
        selectBulan.addEventListener("change", function () {
            var val = this.value; // "2026-3"
            var parts = val.split("-");
            var year = parts[0];
            var month = parts[1];

            selectBulan.disabled = true;

            fetch(
                "/superadmin/dashboard/trend?year=" + year + "&month=" + month,
                {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                },
            )
                .then(function (res) {
                    return res.json();
                })
                .then(function (json) {
                    updateTrendChart(json.labels, json.data);
                })
                .catch(function (err) {
                    console.error("Gagal memuat data trend:", err);
                })
                .finally(function () {
                    selectBulan.disabled = false;
                });
        });
    }

    // ── Chart 2: Sebaran Status Alarm (Donut) ───────────────────────────────
    var alarmMenunggu = data.alarmMenungguTotal || 0;
    var alarmDiproses = data.alarmDiprosesTotal || 0;
    var alarmSelesai = data.alarmSelesaiTotal || 0;
    var totalAlarm = data.totalSemuaAlarm || 0;

    var optionsDonut = {
        series: [alarmMenunggu, alarmDiproses, alarmSelesai],
        chart: {
            height: 280,
            type: "donut",
            redrawOnWindowResize: true,
        },
        labels: ["Menunggu", "Diproses", "Selesai"],
        colors: ["#e95959", "#265ed7", "#27ae60"],
        legend: {
            position: "bottom",
            horizontalAlign: "center",
            fontSize: "13px",
            markers: { width: 10, height: 10, radius: 5 },
            formatter: function (seriesName, opts) {
                return (
                    seriesName +
                    "&nbsp;&nbsp;<b>" +
                    opts.w.globals.series[opts.seriesIndex] +
                    "</b>"
                );
            },
        },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: "68%",
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: "Total Alarm",
                            fontSize: "13px",
                            color: "#8c9094",
                            formatter: function () {
                                return totalAlarm;
                            },
                        },
                        value: {
                            fontSize: "22px",
                            fontWeight: "700",
                            color: "#2d3748",
                        },
                    },
                },
            },
        },
        stroke: { width: 0 },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " alarm";
                },
            },
        },
    };

    var elDonut = document.querySelector("#alarm-status-chart");
    if (elDonut) {
        new ApexCharts(elDonut, optionsDonut).render();
    }
})();
