@extends('layouts.master')

@section('content')
    <div class="content">
        <!-- Animated -->
        <div class="animated fadeIn">
            <!--  Traffic  -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="box-title">Grafik</h4>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    {{-- <div id="traffic-chart" class="traffic-chart"></div> --}}
                                    {{-- {!! $chart->renderHtml() !!} --}}
                                    <div id="chartTes" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>
                        </div> <!-- /.row -->
                        <div class="card-body"></div>
                    </div>
                </div><!-- /# column -->
            </div>
            <!--  /Traffic -->
            <div class="clearfix"></div>
        <!-- /#add-category -->
        </div>
        <!-- .animated -->
    </div>
@endsection

@section('javascript')
<script src="http://github.highcharts.com/master/highcharts.js"></script>
<script src="http://github.highcharts.com/master/modules/drilldown.js"></script>
<script>
    $(document).ready(function() {
        var drilldownTitle = 'Kembali';
        var options = {
            chart: {
                renderTo: 'chartTes',
                type: 'column',
            },
            title: {
                text: 'Laporan Pembelian Barang Per Tahun'
            },
            subtitle: {
                text: 'ini adalah grafik laporan pembelian barang ke supplier per tahun.'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Nominal'
                },
                labels: {
                    formatter: function () {
                        return IDRFormatter(this.value, 'Rp. ');
                    }
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return IDRFormatter(this.y, 'Rp. ');
                        }
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> nominal pembelian<br/>'
            },
            series: {},
            drilldown: {
                series: {}
            }
        };

        function IDRFormatter(angka, prefix) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function getDataPurchasing() {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('report.sumPurchasingByYear') }}",
                beforeSend: function () {
                },
                success: function (data) {
                    options.series = data.tahun;
                    options.drilldown.series = data.bulan;
                    var chart = new Highcharts.Chart(options);
                    //console.log('options', options)
                },
                error: function (txt) {
                    // Report errors here...
                }
            });
        }
        getDataPurchasing();
    });
</script>

@endsection