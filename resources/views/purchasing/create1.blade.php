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
                <div id="form_result_table"></div>
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Tambah Data</strong>
                    </div>
                    <div class="card-body">

                        <form id="purchasingForm" name="purchasingForm" method="POST" action="{{ route('purchasing.insert') }}">
                            {{ csrf_field() }}
                            <input type="text" name="product_id" id="product_id">
                            <div class="row">
                                <div class="col md-2">
                                    <label for="product_code" class="form-control-label">Kode Barang</label>
                                    <div class="input-group">
                                        <input name="product_code" id="product_code" class="form-control" placeholder="Ketik kode atau nama barang / klik icon search" autocomplete="off" autofocus>
                                        <div class="input-group-addon" id="loadProduct" style="background-color: #337ab7; width: 50px">
                                            <span class="fa fa-search fa-lg" style="color: #ffffff"></span>
                                        </div>
                                    </div>
                                    <div id="product_list"></div> 
                                </div>
                                <div class="col md-6">
                                    <div class="form-group">
                                        <label for="product" class="form-control-label">Nama Barang</label>
                                        <input type="text" class="form-control" id="namaBarang" name="name" placeholder="Nama Barang" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="price" class="form-control-label">Harga</label>
                                        <input type="number" class="form-control" id="hargaBarang" name="price" placeholder="Harga Barang" readonly>
                                    </div>
                                </div>
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="stock" class=" form-control-label">Stok</label>
                                        <input type="number" class="form-control" id="stokBarang" name="stock" placeholder="Stok Barang" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="quantity" class="form-control-label">Quantity Barang</label>
                                        <input type="number" min="1" class="form-control" id="quantity" name="quantity" placeholder="Quantity Barang">
                                    </div>
                                </div>
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="supplier" class="form-control-label">Supplier</label>
                                        <select name="supplier_id" id="supplier" class="form-control" style="width: 100%;"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total_price" class="form-control-label">Total Harga</label>
                                <input type="number" class="form-control" id="totalHargaBarang" name="total_price" placeholder="Harga Barang" readonly>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success" id="saveBtn" value="create">Simpan</button>
                                <a href="/purchasing" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="productModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title" id="modelHeading"></h3>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered data-table">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="20%">Kode Barang</th>
                            <th width="41%">Nama Barang</th>
                            <th width="8%">Harga</th>
                            <th width="5%">Stok</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#supplier').select2({
            placeholder: 'pilih supplier',
            allowClear: true,
            ajax: {
                url: '/supplierSearch',
                dataType: 'json',
            },
        });

        $('#product_code').on('keyup',function() {
            var query = $(this).val();
            $.ajax({
                url:"/productSearchByCode",
                type:"GET",
                data:{'product':query},
                success:function (data) {
                    $('#product_list').html(data);
                }
            });
        });

        $(document).on('click', 'li', function(){
            let value = $(this).text();
            let product_id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            let price = $(this).data('price');
            let stock = $(this).data('stock');
            let quantity = $('#quantity').val(1);
            let total = price * 1;
            $('#product_id').val(product_id);
            $('#product_code').val(code);
            $('#namaBarang').val(name);
            $('#hargaBarang').val(price);
            $('#stokBarang').val(stock);
            $("#totalHargaBarang").val(total);
            $('#quantity').focus();
            $('#product_list').html("");
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('purchasing.create') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'product_number', name: 'product_number'},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price'},
                {data: 'stock', name: 'stock'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('body').on('click', '.selectProduct', function() {
            let product_id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            let price = $(this).data('price');
            let stock = $(this).data('stock');
            let quantity = $('#quantity').val(1);
            let total = price * 1;
            $('#product_id').val(product_id);
            $('#product_code').val(code);
            $('#namaBarang').val(name);
            $('#hargaBarang').val(price);
            $('#stokBarang').val(stock);
            $("#totalHargaBarang").val(total);
            $('#productModal').modal('hide');
            //$("#quantity").attr('maxlength','1');
            $('#quantity').focus();
        });

        $('#loadProduct').click(function () {
            $('#modelHeading').html("Data Barang");
            $('#product_list').html('');
            $('#productModal').modal('show');
        });

        $("#quantity").keyup(function() {
            let quantity  = $("#quantity").val();
            let harga = $("#hargaBarang").val();
            let total = parseInt(quantity) * parseInt(harga);
            $("#totalHargaBarang").val(total);
        });

        $("#quantity").change(function() {
            let quantity  = $("#quantity").val();
            let harga = $("#hargaBarang").val();
            let total = parseInt(quantity) * parseInt(harga);
            $("#totalHargaBarang").val(total);
        });
        
        // $('#saveBtn').click(function (e) {
        //     e.preventDefault();
        //     $(this).html('Proses..');
        //     $.ajax({
        //         data: $('#purchasingForm').serialize(),
        //         url: "{{ route('purchasing.store') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         success: function (data) {
        //             var html = '';
        //             if(data.errors) {
        //                 html = '<div class="alert alert-danger">';
        //                 for(var count = 0; count < data.errors.length; count++) {
        //                     html += data.errors[count] + '<br>';
        //                 }
        //                 html += '</div>';
        //             }
        //             if(data.success) {
        //                 //html = '<div class="alert alert-success">' + data.success + '</div>';
        //                 //$('#form_result_table').html(html);
        //                 window.location='/purchasing';
        //                 // let options = {
        //                 //     "showDuration": "8000",
        //                 // };
        //                 //setTimeout(toastr.info('Data berhasil disimpan.'), 80000);
        //                 $('#purchasingForm').trigger("reset");
        //                 $("#supplier").val('').trigger("change");
        //                 $('#saveBtn').html('Simpan');
        //                 // location.reload(true);
        //             }
        //             $('#form_result_table').html(html);
        //             $('#saveBtn').html('Simpan');
        //         },
        //         error: function (data) {
        //             console.log('Error:', data);
        //             $('#saveBtn').html('Simpan');
        //         }
        //     });
        // });
        
    });

</script> 
@endsection