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
                        <strong class="card-title">#{{ $purchasing->trans_number }}</strong>
                    </div>
                    <div class="card-body">
                        <form id="purchasingForm" name="purchasingForm">
                            <input type="hidden" value="{{ $purchasing->id }}" name="purchasing_id" id="purchasing_id">
                            <input type="hidden" value="{{ $purchasing->product_id }}" name="product_id" id="product_id">
                            <div class="row">
                                <div class="col md-2">
                                    <label for="code" class="form-control-label">Kode Barang</label>
                                    <div class="input-group">
                                        <input type="text" value="{{ $purchasing->product->product_number }}" name="code" id="code" class="form-control" placeholder="Klik icon search.." autocomplete="off">
                                        <div class="input-group-addon" id="loadProduct" style="background-color: #337ab7; width: 50px">
                                            <span class="fa fa-search fa-lg" style="color: #ffffff"></span>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col md-6">
                                    <div class="form-group">
                                        <label for="product" class="form-control-label">Nama Barang</label>
                                        <input type="text" value="{{ $purchasing->product->name }}" class="form-control" id="namaBarang" name="name" placeholder="Nama Barang" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="price" class="form-control-label">Harga</label>
                                        <input type="number" value="{{ $purchasing->product->price }}" class="form-control" id="hargaBarang" name="price" placeholder="Harga Barang" readonly>
                                    </div>
                                </div>
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="stock" class=" form-control-label">Stok</label>
                                        <input type="number" value="{{ $purchasing->product->stock }}" class="form-control" id="stokBarang" name="stock" placeholder="Stok Barang" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="quantity" class="form-control-label">Quantity Barang</label>
                                        <input type="number" value="{{ $purchasing->quantity }}" min="1" class="form-control" id="quantity" name="quantity" placeholder="Quantity Barang">
                                    </div>
                                </div>
                                <div class="col md-4">
                                    <div class="form-group">
                                        <label for="supplier" class="form-control-label">Supplier</label>
                                        <select name="supplier_id" id="supplier" class="form-control" style="width: 100%;">
                                        <option value="{{ $purchasing->supplier_id }}">{{ $purchasing->supplier->supplier_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total_price" class="form-control-label">Total Harga</label>
                                <input type="number" value="{{ $purchasing->total_price }}" class="form-control" id="totalHargaBarang" name="total_price" placeholder="Harga Barang" readonly>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary" id="updateBtn" value="create">Simpan</button>
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
            var product_id = $(this).data('id');
            var code = $(this).data('code');
            var name = $(this).data('name');
            var price = $(this).data('price');
            var stock = $(this).data('stock');
            var quantity = $('#quantity').val();
            var total = price * quantity;
            $('#product_id').val(product_id);
            $('#code').val(code);
            $('#namaBarang').val(name);
            $('#hargaBarang').val(price);
            $('#stokBarang').val(stock);
            $("#totalHargaBarang").val(total);
            $('#productModal').modal('hide');
            $('#quantity').focus();
        });

        $('#loadProduct').click(function () {
            $('#modelHeading').html("Data Barang");
            $('#productModal').modal('show');
        });

        $("#quantity").keyup(function() {
            var quantity  = $("#quantity").val();
            var harga = $("#hargaBarang").val();

            var total = parseInt(quantity) * parseInt(harga);
            $("#totalHargaBarang").val(total);
        });

        $("#quantity").change(function() {
            var quantity  = $("#quantity").val();
            var harga = $("#hargaBarang").val();

            var total = parseInt(quantity) * parseInt(harga);
            $("#totalHargaBarang").val(total);
        });

        $('body').on('click', '.editPurchasing', function () {
            var purchasing_id = $(this).data('id');
            $('#form_result_table').html('');
            $('#form_result').html('');
            $.get("{{ route('purchasing.index') }}" + '/' + purchasing_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Data");
                $('#saveBtn').val("edit-purchasing");
                $("#saveBtn").removeClass("btn-success");
                $("#saveBtn").addClass("btn-primary");
                $('#ajaxModel').modal('show');
                $('#purchasing_id').val(data.id);
                $('#trans_number').val(data.trans_number);
                $('#name').val(data.name);
                $("#supplier").data('select2').trigger('select', {
                    data: {"id": data.supplier_id, "text": data.supplier.supplier_name}
                });
                $('#price').val(data.price);
                $('#stock').val(data.stock);
            });
        });
        
        $('#updateBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            $.ajax({
                data: $('#purchasingForm').serialize(),
                url: "{{ route('purchasing.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    var html = '';
                    if(data.errors) {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++) {
                            html += data.errors[count] + '<br>';
                        }
                        html += '</div>';
                    }
                    if(data.success) {
                        //html = '<div class="alert alert-success">' + data.success + '</div>';
                        //$('#form_result_table').html(html);
                        window.location='/purchasing';
                        $('#purchasingForm').trigger("reset");
                        $("#supplier").val('').trigger("change");
                        $('#updateBtn').html('Simpan');
                        // location.reload(true);
                    }
                    $('#form_result_table').html(html);
                    $('#updateBtn').html('Simpan');
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#updateBtn').html('Simpan');
                }
            });
        });
        
    });

</script> 
@endsection