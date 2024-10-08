@extends('layouts.master')

@section('css')
<style>
    #container {
        height: 400px; 
    }

    .highcharts-figure, .highcharts-data-table table {
        min-width: 310px; 
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }
    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }
    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }
    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
        padding: 0.5em;
    }
    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }
    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="animated fadeIn">
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Custom Tab</h4>
                    </div>
                    <div class="card-body">
                        <div class="custom-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link" id="custom-nav-home-tab" data-toggle="tab" href="#custom-nav-home" role="tab" aria-controls="custom-nav-home" aria-selected="false">Home</a>
                                    <a class="nav-item nav-link" id="custom-nav-profile-tab" data-toggle="tab" href="#custom-nav-profile" role="tab" aria-controls="custom-nav-profile" aria-selected="false">Profile</a>
                                    <a class="nav-item nav-link active show" id="custom-nav-contact-tab" data-toggle="tab" href="#custom-nav-contact" role="tab" aria-controls="custom-nav-contact" aria-selected="true">Contact</a>
                                </div>
                            </nav>
                            <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                <div class="tab-pane fade" id="custom-nav-home" role="tabpanel" aria-labelledby="custom-nav-home-tab">
                                    <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone. Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
                                </div>
                                <div class="tab-pane fade" id="custom-nav-profile" role="tabpanel" aria-labelledby="custom-nav-profile-tab">
                                    <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone. Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
                                </div>
                                <div class="tab-pane fade active show" id="custom-nav-contact" role="tabpanel" aria-labelledby="custom-nav-contact-tab">
                                    <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone. Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <strong class="card-title">Grafik</strong> --}}
                        <div class="pull-right">
                            <select id="pilihSaleByMonth" class="form-control">
                                <option value="" selected>Tahun & Semester Saat Ini</option>
                                @php
                                    $firstYear = (int)date('Y') - 5;
                                    $lastYear = $firstYear + 5;
                                    for($i=$firstYear;$i<=$lastYear;$i++){
                                        $yearNow = date('Y');
                                        $monthNow = date('M');
                                        if($monthNow <= 6){
                                            $semester = '1';
                                        }else{
                                            $semester = '2';
                                        }
                                        $selected = ($i == $yearNow) ? 'selected' : ' ';
                                        echo '
                                            <option value="'.$i.' - 1">Tahun '.$i.' - Semester 1</option>
                                            <option value="'.$i.' - 2">Tahun '.$i.' - Semester 2</option>
                                        ';
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-body">
                                <figure class="highcharts-figure">
                                    <div id="chartSaleByMonth" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-right">
                            <select id="pilihTahunSale" class="form-control">
                                @php
                                    $firstYear = (int)date('Y') - 10;
                                    $lastYear = $firstYear + 10;
                                    for($i=$firstYear;$i<=$lastYear;$i++){
                                        $yearNow = date('Y');
                                        $selected = ($i == $yearNow) ? 'selected' : ' ';
                                        echo '<option value='.$i.' '.$selected.'>'.$i.'</option>';
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <figure class="highcharts-figure">
                                <div id="chartSaleByYear"></div>
                            </figure>
                        </div>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <strong class="card-title">Grafik</strong> --}}
                        <div class="pull-right">
                            <select id="pilihPurchasingByMonth" class="form-control">
                                <option value="" selected>Tahun & Semester Saat Ini</option>
                                @php
                                    $firstYear = (int)date('Y') - 5;
                                    $lastYear = $firstYear + 5;
                                    for($i=$firstYear;$i<=$lastYear;$i++){
                                        $yearNow = date('Y');
                                        $monthNow = date('M');
                                        if($monthNow <= 6){
                                            $semester = '1';
                                        }else{
                                            $semester = '2';
                                        }
                                        $selected = ($i == $yearNow) ? 'selected' : ' ';
                                        echo '
                                            <option value="'.$i.' - 1">Tahun '.$i.' - Semester 1</option>
                                            <option value="'.$i.' - 2">Tahun '.$i.' - Semester 2</option>
                                        ';
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-body">
                                <figure class="highcharts-figure">
                                    <div id="chartPurchasingByMonth" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-right">
                            <select id="pilihTahunPurchasing" class="form-control">
                                @php
                                    $firstYear = (int)date('Y') - 10;
                                    $lastYear = $firstYear + 10;
                                    for($i=$firstYear;$i<=$lastYear;$i++){
                                        $yearNow = date('Y');
                                        $selected = ($i == $yearNow) ? 'selected' : ' ';
                                        echo '<option value='.$i.' '.$selected.'>'.$i.'</option>';
                                    }
                                @endphp
                            </select>
                        </div>
                        {{-- &nbsp;
                        <div class="pull-right">
                            <select id="pilihTypeChartTahunPurchasing" class="form-control">
                                <option value="column">Column</option>
                                <option value="line">Line</option>
                            </select>
                        </div> --}}
                    </div>
                    <div class="card-body">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <figure class="highcharts-figure">
                                <div id="chartPurchasingByYear"></div>
                            </figure>
                        </div>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@endsection

@section('javascript')
<script src="http://github.highcharts.com/master/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<script>
    $(document).ready(function() {

        $('#pilihSaleByMonth').on('change', function(){
            var select = $('#pilihSaleByMonth').val();
            var res = select.split(" ");
            var tahun = res[0];
            var today = new Date();
            var tahunNow = (tahun == '') ? today.getFullYear() : tahun;
            var semester = res[2];
            getDataSaleByMonth(tahunNow, semester);
        });

        var options = {
            chart: {
                renderTo: 'chartSaleByMonth',
                type: 'column'
            },
            lang: {
                drillUpText: '<< Kembali'
            },
            title: {},
            subtitle: {
                text: 'ini adalah grafik laporan penjualan barang per semester.'
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

        function getDataSaleByMonth(tahun, semester) {
            var today = new Date();
            var tahun = tahun;
            var semesterNow = (today.getMonth() <= 6) ? '1' : '2';
            var semester = (semester == undefined) ? semesterNow : semester;
            var year = (tahun == undefined) ? today.getFullYear() : tahun;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('report.sumSaleByMonth') }}",
                data: {
                    'tahun': tahun,
                    'semester': semester
                },
                beforeSend: function () {
                },
                success: function (data) {
                    options.series = data.bulan;
                    options.drilldown.series = data.tanggal;
                    options.title.text = 'Laporan Penjualan Barang Semester '+ semester +' Tahun ' + year;
                    var chart = new Highcharts.Chart(options);
                    //console.log(chart);
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
        getDataSaleByMonth();
    });
</script>
<script>
    $(document).ready(function() {

        $('#pilihTahunSale').on('change', function(){
            var tahun = $('#pilihTahunSale').val();
            getDataSale(tahun);
        });

        var options = {
            chart: {
                renderTo: 'chartSaleByYear',
                type: 'bar',
            },
            lang: {
                drillUpText: '<< Kembali'
            },
            title: {},
            subtitle: {
                text: 'ini adalah grafik laporan penjualan barang per tahun.'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Jumlah'
                },
                labels: {
                    formatter: function () {
                        return this.value;
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
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> total pembelian<br/>'
            },
            series: {},
            drilldown: {
                series: {}
            }
        };

        function getDataSale(tahun) {
            var tahun = tahun;
            var today = new Date();
            var year = (tahun == undefined) ? today.getFullYear() : tahun;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('report.sumSaleByYear') }}",
                data: {
                    'tahun': tahun
                },
                beforeSend: function () {
                },
                success: function (data) {
                    options.series = data.kategori;
                    options.drilldown.series = data.barang;
                    options.title.text = 'Laporan Penjualan Barang Tahun ' + year;
                    var chart = new Highcharts.Chart(options);
                    //console.log(chart);
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
        getDataSale();
    });
</script>
<script>
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
</script>
<script>
    $(document).ready(function() {

        $('#pilihTahunPurchasing').on('change', function(){
            var tahun = $('#pilihTahunPurchasing').val();
            //var typeChart = $('#pilihtypeChartTahunPurchasing').val();
            getDataPurchasing(tahun, typeChart);
        });

        var options = {
            chart: {
                renderTo: 'chartPurchasingByYear',
                type: 'line'
            },
            lang: {
                drillUpText: '<< Kembali'
            },
            title: {},
            subtitle: {
                text: 'ini adalah grafik laporan pembelian barang ke supplier per tahun.'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Jumlah'
                },
                labels: {
                    formatter: function () {
                        return this.value;
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
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> total pembelian<br/>'
            },
            series: {},
            drilldown: {
                series: {}
            }
        };

        function getDataPurchasing(tahun) {
            var tahun = tahun;
            var typeChart = typeChart;
            var today = new Date();
            var year = (tahun == undefined) ? today.getFullYear() : tahun;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('report.sumPurchasingByYear') }}",
                data: {
                    'tahun': tahun
                },
                beforeSend: function () {
                },
                success: function (data) {
                    //options.chart.type = typeChart;
                    // options.update({
                    //     chart: { type: typeChart }
                    // });
                    options.series = data.kategori;
                    options.drilldown.series = data.barang;
                    options.title.text = 'Laporan Pembelian Barang Tahun ' + year;
                    var chart = new Highcharts.Chart(options);
                    //console.log(chart);
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
        getDataPurchasing();
    });
</script>

@endsection