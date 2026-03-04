(function () {
    "use strict";

    var kd = window.KinerjaData || {};

    var kinerjaChart = null;

    var NAMA_BULAN = [
        "",
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    function renderChart(categories, series, year, month) {
        if (kinerjaChart) {
            kinerjaChart.destroy();
            kinerjaChart = null;
        }

        kinerjaChart = Highcharts.chart("kinerja-chart", {
            chart: {
                type: "column",
                style: { fontFamily: "Inter, sans-serif" },
                height: 380,
            },
            title: {
                text: "Alarm Diselesaikan per Hari",
                style: {
                    fontSize: "15px",
                    fontWeight: "600",
                    color: "#2d3748",
                },
            },
            subtitle: {
                text: NAMA_BULAN[month] + " " + year,
                style: { fontSize: "12px", color: "#8c9094" },
            },
            xAxis: {
                categories: categories,
                crosshair: true,
                title: { text: "Tanggal", style: { color: "#8c9094" } },
                labels: { style: { color: "#8c9094", fontSize: "11px" } },
                lineColor: "#e5e7eb",
                tickColor: "#e5e7eb",
            },
            yAxis: {
                min: 0,
                allowDecimals: false,
                title: {
                    text: "Jumlah Alarm",
                    style: { color: "#8c9094" },
                },
                labels: { style: { color: "#8c9094" } },
                gridLineColor: "#f3f4f6",
            },
            tooltip: {
                headerFormat:
                    '<span style="font-size:12px;font-weight:600">Tgl {point.key}</span><table>',
                pointFormat:
                    "<tr>" +
                    '<td style="color:{series.color};padding:4px 6px 0 0">{series.name}:</td>' +
                    '<td style="padding:4px 0 0"><b>{point.y} alarm</b></td>' +
                    "</tr>",
                footerFormat: "</table>",
                shared: true,
                useHTML: true,
                backgroundColor: "#fff",
                borderColor: "#e5e7eb",
                borderRadius: 8,
                shadow: false,
            },
            legend: {
                align: "center",
                verticalAlign: "bottom",
                layout: "horizontal",
                itemStyle: {
                    fontWeight: "500",
                    color: "#374151",
                    fontSize: "12px",
                },
                symbolRadius: 3,
            },
            plotOptions: {
                column: {
                    pointPadding: 0.15,
                    borderWidth: 0,
                    borderRadius: 3,
                    groupPadding: 0.1,
                },
            },
            series: series,
            credits: { enabled: false },
            responsive: {
                rules: [
                    {
                        condition: { maxWidth: 600 },
                        chartOptions: {
                            chart: { height: 280 },
                            legend: { itemStyle: { fontSize: "11px" } },
                            xAxis: { labels: { style: { fontSize: "10px" } } },
                        },
                    },
                ],
            },
        });
    }

    function updateTable(summaryList) {
        var tbody = document.getElementById("tbody-kinerja");
        if (!tbody) return;

        if (!summaryList || summaryList.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="3" class="text-center text-muted py-20">' +
                "Belum ada data kinerja bulan ini</td></tr>";
            return;
        }

        var html = "";
        summaryList.forEach(function (row) {
            var jam = Math.floor(row.avg_menit / 60);
            var menit = row.avg_menit % 60;
            var waktu =
                row.avg_menit > 0
                    ? (jam > 0 ? jam + " jam " : "") + menit + " menit"
                    : "—";

            html +=
                "<tr>" +
                "<td>" +
                '<span style="display:inline-block;width:12px;height:12px;border-radius:3px;' +
                "background:" +
                row.color +
                ';vertical-align:middle;margin-right:6px;"></span>' +
                '<span class="weight-600">' +
                row.name +
                "</span>" +
                "</td>" +
                "<td>" +
                row.total +
                " alarm</td>" +
                "<td>" +
                waktu +
                "</td>" +
                "</tr>";
        });
        tbody.innerHTML = html;
    }

    renderChart(
        kd.categories || [],
        kd.series || [],
        kd.currentYear || new Date().getFullYear(),
        kd.currentMonth || new Date().getMonth() + 1,
    );

    var selectBulan = document.getElementById("filter-bulan-kinerja");
    if (selectBulan) {
        selectBulan.addEventListener("change", function () {
            var parts = this.value.split("-");
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);

            selectBulan.disabled = true;

            fetch(
                "/superadmin/kinerja-admin/chart?year=" +
                    year +
                    "&month=" +
                    month,
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
                    renderChart(json.categories, json.series, year, month);
                    updateTable(json.summaryList);
                })
                .catch(function (err) {
                    console.error("Gagal memuat data kinerja:", err);
                })
                .finally(function () {
                    selectBulan.disabled = false;
                });
        });
    }
})();
