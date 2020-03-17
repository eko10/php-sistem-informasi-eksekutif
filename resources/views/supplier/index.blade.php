@extends('layouts.master')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Supplier</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li>Home</li>
                            <li class="active"><a href="/supplier">Supplier</a></li>
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
                        {{-- <strong class="card-title">Data Supplier</strong> --}}
                        <div class="pull-left">
                            <a class="btn btn-success" href="javascript:void(0)" id="createNewSupplier"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Kode</th>
                                    <th width="35%">Nama</th>
                                    <th width="15%">Telepon</th>
                                    <th width="20%">Alamat</th>
                                    <th width="15%">Aksi</th>
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
            <form id="supplierForm" name="supplierForm" class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="modelHeading"></h3>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <div class="card-body card-block">
                        <input type="hidden" name="supplier_id" id="supplier_id">
                        <div class="form-group">
                            <label for="supplier_name" class="form-control-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Nama Supplier">
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-control-label">No. Telepon</label>
                            <input type="number" class="form-control" id="phone" name="phone" placeholder="No. Telepon">
                        </div>
                        <div class="form-group">
                            <label for="address" class=" form-control-label">Alamat</label>
                            <textarea id="address" name="address" placeholder="Alamat" class="form-control"></textarea>
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
    
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('supplier.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'supplier_number', name: 'supplier_number'},
                {data: 'supplier_name', name: 'supplier_name'},
                {data: 'phone', name: 'phone'},
                {data: 'address', name: 'address'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        $('#createNewSupplier').click(function () {
            $('#saveBtn').val("create-supplier");
            $("#saveBtn").removeClass("btn-primary");
            $("#saveBtn").addClass("btn-success");
            $('#supplier_id').val('');
            $('#supplierForm').trigger("reset");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
            $('#form_result_table').html('');
            $('#form_result').html('');
        });
        
        $('body').on('click', '.editSupplier', function () {
            var supplier_id = $(this).data('id');
            $('#form_result_table').html('');
            $('#form_result').html('');
            $.get("{{ route('supplier.index') }}" + '/' + supplier_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Data");
                $('#saveBtn').val("edit-supplier");
                $("#saveBtn").removeClass("btn-success");
                $("#saveBtn").addClass("btn-primary");
                $('#ajaxModel').modal('show');
                $('#supplier_id').val(data.id);
                $('#supplier_number').val(data.supplier_number);
                $('#supplier_name').val(data.supplier_name);
                $('#phone').val(data.phone);
                $('#address').val(data.address);
            });
        });
        
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            $.ajax({
                data: $('#supplierForm').serialize(),
                url: "{{ route('supplier.store') }}",
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
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#form_result_table').html(html);
                        $('#supplierForm').trigger("reset");
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

        $('body').on('click', '.deleteSupplier', function () {
            var supplier_id = $(this).data('id');
            $('#confirmModal').modal('show');

            $('#ok_button').click(function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('supplier.store') }}" + '/' + supplier_id,
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
                            }, 300);
                        }
                        $('#form_result_table').html(html);
                    },
                    // error: function(data) {
                    //     console.log('Error:', data);
                    // }
                    error: function(xhr, status, error) {
                        let json = JSON.parse(xhr.responseText);
                        let message = json.message;
                        //console.log(xhr);
                        //console.log(status);
                        //console.log(error);
                        html = '<div class="alert alert-danger">' + message + '</div>';
                        $('#ok_button').text('Yakin');
                        $('#confirmModal').modal('hide');
                        $('#form_result_table').html(html);
                    }
                });
            });
        });
        
    });

</script> 
@endsection