<script src="{{ asset('js/report-purchasing-by-month.js') }}"></script>
$(document).ready(function() {
    $('#pilihPurchasingByMonth').on('change', function(){
        var select = $('#pilihPurchasingByMonth').val();
        var res = select.split(" ");
        var tahun = res[0];
        var today = new Date();
        var tahunNow = (tahun == '') ? today.getFullYear() : tahun;
        var semester = res[2];
        getDataPurchasingByMonth(tahunNow, semester);
    });
    var options = {
        chart: {
            renderTo: 'chartPurchasingByMonth',
            type: 'column'
        },
        lang: {
            drillUpText: '<< Kembali'
        },
        title: {},
        subtitle: {
            text: 'ini adalah grafik laporan pembelian barang ke supplier per semester.'
        },
        xAxis: {
            type: 'category',
            crosshair: true,
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [],
        drilldown: {
            allowPointDrilldown: false,
            series: []
        }
    };
    function getDataPurchasingByMonth(tahun, semester) {
        var today = new Date();
        var tahun = tahun;
        var semesterNow = (today.getMonth() <= 6) ? '1' : '2';
        var semester = (semester == undefined) ? semesterNow : semester;
        var year = (tahun == undefined) ? today.getFullYear() : tahun;
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('report.sumPurchasingByMonth') }}",
            data: {
                'tahun': tahun,
                'semester': semester
            },
            beforeSend: function () {
            },
            success: function (data) {
                options.series = data.bulan;
                options.drilldown.series = data.tanggal;
                options.title.text = 'Laporan Pembelian Barang Semester '+ semester +' Tahun ' + year;
                var chart = new Highcharts.Chart(options);
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
    getDataPurchasingByMonth();
});