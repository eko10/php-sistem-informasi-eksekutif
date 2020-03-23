@extends('layouts.master')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Pembelian Barang</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li>Home</li>
                            <li class="active"><a href="/purchasing">Pembelian Barang</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                @php
                    $msg_save = Cookie::get('save_purchasing');
                    $msg_save = ($msg_save != '') ? $msg_save : '';
                    Cookie::queue(Cookie::forget('save_purchasing'));
                @endphp
                <input type="hidden" value="{{ $msg_save }}" id="msg_save">
                {{-- <input type="hidden" value="{{ $msg_update }}" id="msg_update"> --}}
                <div id="form_result_table"></div>
                <div class="card">
                    <div class="card-header">
                        {{-- <strong class="card-title">Data Product</strong> --}}
                        <div class="pull-left">
                        <a class="btn btn-success" href="{{ route('purchasing.create') }}" id="createNewProduct"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode Pembelian</th>
                                    <th width="20%">Nama Supplier</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Total Harga</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Konfirmasi</h3>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Apakah anda yakin ingin menghapus data ini?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" name="ok_button" id="ok_button">Yakin</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if ($('#msg_save').val() != ''){
            let msg_save = $('#msg_save').val();
            let html = '<div class="alert alert-success">'+ msg_save +'</div>';
            $('#form_result_table').html(html);
        } 
        // else if($('#msg_update').val() != '') {
        //     let msg_update = $('#msg_update').val();
        //     let html = '<div class="alert alert-success">'+ msg_update +'</div>';
        //     $('#form_result_table').html(html);
        // }
    
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('purchasing.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'trans_number', name: 'trans_number'},
                {data: 'supplier_id', name: 'suppliers.supplier_name'},
                {data: 'product_id', name: 'products.name'},
                {data: 'quantity', name: 'quantity'},
                {data: 'total_price', name: 'total_price'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('body').on('click', '.deletePurchasing', function () {
            var purchasing_id = $(this).data('id');
            $('#confirmModal').modal('show');
            $('#form_result_table').html('');

            $('#ok_button').click(function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('purchasing.store') }}" + '/' + purchasing_id,
                    beforeSend: function() {
                        $('#ok_button').text('Proses...');
                    },
                    success: function(data) {
                        var html = '';
                        if(data.errors) {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++) {
                                html += data.errors[count] + '<br>';
                            }
                            html += '</div>';
                        }
                        if(data.success) {
                            html = '<div class="alert alert-danger">' + data.success + '</div>';
                            setTimeout(function(){
                                $('#ok_button').text('Yakin');
                                $('#confirmModal').modal('hide');
                                table.draw();
                            }, 100);
                        }
                        $('#form_result_table').html(html);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
        
    });

</script> 
@endsection