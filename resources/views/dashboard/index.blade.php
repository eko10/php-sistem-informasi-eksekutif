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
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="calender-cont widget-calender">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection