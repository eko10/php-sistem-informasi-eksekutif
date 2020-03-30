@extends('layouts.master')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Barang</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li>Home</li>
                            <li class="active"><a href="/product">Barang</a></li>
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
                        {{-- <strong class="card-title">Data Product</strong> --}}
                        <div class="pull-left">
                            <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode</th>
                                    <th width="25%">Nama</th>
                                    <th width="20%">Kategori</th>
                                    <th width="15%">Harga</th>
                                    <th width="10%">Stok</th>
                                    <th width="10%">Gambar</th>
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

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="modelHeading"></h3>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <div class="card-body card-block">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Nama Barang</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Barang">
                        </div>
                        <div class="form-group">
                            <label for="category" class="form-control-label">Kategori Barang</label>
                            <select name="category_id" id="category" class="form-control" style="width: 100%;"></select>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-control-label">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="Harga Barang">
                        </div>
                        <div class="form-group">
                            <label for="stock" class=" form-control-label">Stok</label>
                            <input type="number" class="form-control" id="stock" name="stock" placeholder="Stok Barang">
                        </div>
                        <div class="form-group" id="form_style">
                            <div id="image_view"></div>
                        </div>
                        <div class="form-group">
                            <label for="image_file" class="form-control-label" id="form_title"></label>
                            <input type="file" class="form-control" id="image_file" name="image_file">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" id="saveBtn" value="create">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
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

        $('#category').select2({
            placeholder: 'pilih kategori',
            allowClear: true,
            ajax: {
                url: '{{ route("category.search") }}',
                dataType: 'json',
            },
        });
    
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('product.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'product_number', name: 'product_number'},
                {data: 'name', name: 'name'},
                {data: 'category_id', name: 'categories.name'},
                {data: 'price', name: 'price'},
                {data: 'stock', name: 'stock'},
                {
                    'data': 'image_file',
                    'name': 'image_file',
                    'render': function (data, type, full, meta) {
                        data = (data == '') ? 'no_image.png' : data;
                        return "<img src=\"{{ asset('images/product/') }}/" + data + "\" height=\"50\"/>";
                    },
                },
                {data: 'action', name: 'action', orderable: false, searchable: false, exportable: true, printable: true},
            ]
        });
        
        $('#createNewProduct').click(function () {
            $('#saveBtn').val("create-product");
            $("#saveBtn").removeClass("btn-primary");
            $("#saveBtn").addClass("btn-success");
            $('#product_id').val('');
            $('#productForm').trigger("reset");
            $('#form_style').css('display', 'none');
            $('#form_title').html("Gambar Barang");
            $("#category").val('').trigger("change");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
            $('#form_result_table').html('');
            $('#form_result').html('');
        });
        
        $('body').on('click', '.editProduct', function () {
            var product_id = $(this).data('id');
            $('#form_result_table').html('');
            $('#form_result').html('');
            $.get("{{ route('product.index') }}" + '/' + product_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Data");
                $('#saveBtn').val("edit-product");
                $("#saveBtn").removeClass("btn-success");
                $("#saveBtn").addClass("btn-primary");
                $('#ajaxModel').modal('show');
                $('#form_title').html("Ubah Gambar");
                $('#product_id').val(data.id);
                $('#product_number').val(data.product_number);
                $('#name').val(data.name);
                $("#category").data('select2').trigger('select', {
                    data: {"id": data.category_id, "text": data.category.name}
                });
                $('#price').val(data.price);
                $('#stock').val(data.stock);
                let image = (data.image_file == '') ? 'no_image.png' : data.image_file;
                $("#form_style").css("display", "block");
                $('#image_view').html('<img src="{{ asset("images/product") }}/'+ image +'" width="100" height="100" />')
            });
        });
        
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            let formData = new FormData($('#productForm')[0]);
            $.ajax({
                url: "{{ route('product.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
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
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#form_result_table').html(html);
                        $('#productForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Simpan');
                        table.draw();
                    }
                    $('#form_result').html(html);
                    $('#saveBtn').html('Simpan');
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Simpan');
                }
            });
        });

        $('body').on('click', '.deleteProduct', function () {
            var product_id = $(this).data('id');
            $('#confirmModal').modal('show');
            $('#form_result_table').html('');

            $('#ok_button').click(function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('product.store') }}" + '/' + product_id,
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
                            }, 500);
                        }
                        $('#form_result_table').html(html);
                    },
                    error: function(xhr, status, error) {
                        let json = JSON.parse(xhr.responseText);
                        let message = json.message;
                        // let search = 'Integrity constraint violation: 1451';
                        // if(message.includes(search)){
                        //     html = '<div class="alert alert-danger"><b>Error</b>, data yang anda hapus masih terkait dengan tabel lain.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        //     $('#form_result_table').html(html);
                        // }else{
                        //     html = '<div class="alert alert-danger">' + message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        //     $('#form_result_table').html(html);
                        // }
                        console.log(message);
                        html = '<div class="alert alert-danger"><b>Error</b>, data yang anda hapus masih terkait dengan tabel lain.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        $('#form_result_table').html(html);
                        $('#ok_button').removeAttr('disabled');
                        $('#ok_button').text('Yakin');
                        $('#confirmModal').modal('hide');
                    }
                });
            });
        });
        
    });

</script> 
@endsection