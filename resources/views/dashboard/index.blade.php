@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-flat-color-1">
                        <div class="card-body">
                            <div class="card-left pt-1 float-left">
                                <h3 class="mb-0 fw-r">
                                    {{-- <span class="currency float-left mr-1">$</span> --}}
                                    <span>{{ formatRupiah($revenue) }}</span>
                                </h3>
                                <p class="text-light mt-1 m-0">Pendapatan</p>
                            </div>
                            <div class="card-right float-right text-right">
                                <i class="icon fade-5 icon-lg pe-7s-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card text-white bg-flat-color-3">
                        <div class="card-body">
                            <div class="card-left pt-1 float-left">
                                <h3 class="mb-0 fw-r">
                                    <span class="count">{{ $customer }}</span>
                                </h3>
                                <p class="text-light mt-1 m-0">Total Pelanggan</p>
                            </div><!-- /.card-left -->

                            <div class="card-right float-right text-right">
                                <i class="icon fade-5 icon-lg pe-7s-users"></i>
                            </div><!-- /.card-right -->

                        </div>

                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card text-white bg-flat-color-2">
                        <div class="card-body">
                            <div class="card-left pt-1 float-left">
                                <h3 class="mb-0 fw-r">
                                    <span class="count">{{ $supplier }}</span>
                                </h3>
                                <p class="text-light mt-1 m-0">Total Supplier</p>
                            </div>
                            <div class="card-right float-right text-right">
                                <i class="icon fade-5 icon-lg pe-7s-car"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            
            <div class="row">
                <div class="col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title box-title">Lokasi Kami</h4>
                            <div class="card-content">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.6345959588375!2d112.7927366141487!3d-7.282350673589602!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa1323221a93%3A0x306c3c99adedb258!2sInstitut%20Teknologi%20Sepuluh%20Nopember!5e0!3m2!1sid!2sid!4v1586063758560!5m2!1sid!2sid" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="calender-cont widget-calender">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--<div class="clearfix"></div>-->

            <!--<div class="row">-->
            <!--    <div class="col-md-12 col-lg-12">-->
            <!--        <div class="card">-->
            <!--            <div class="card-body">-->
            <!--                <div class="calender-cont widget-calender">-->
            <!--                    <div id="calendar"></div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            
        </div>
    </div>
@endsection