@extends('layouts.master')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Jurusan</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li>Home</li>
                            <li class="active"><a href="/major">Jurusan</a></li>
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
                        {{-- <strong class="card-title">Data Major</strong> --}}
                        <div class="pull-left">
                            <a class="btn btn-success" href="javascript:void(0)" id="createNewMajor"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode</th>
                                    <th width="35%">Nama</th>
                                    <th width="30%">Fakultas</th>
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
            <form id="majorForm" name="majorForm" class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="modelHeading"></h3>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <div class="card-body card-block">
                        <input type="hidden" name="major_id" id="major_id">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Nama Jurusan</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Jurusan">
                        </div>
                        <div class="form-group">
                            <label for="faculty" class="form-control-label">Fakultas</label>
                            <select name="faculty_id" id="faculty" class="form-control" style="width: 100%;"></select>
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

        $('#faculty').select2({
            placeholder: 'pilih fakultas',
            allowClear: true,
            ajax: {
                url: '{{ route("faculty.search") }}',
                dataType: 'json',
            },
        });
    
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('major.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'major_code', name: 'major_code'},
                {data: 'name', name: 'name'},
                {data: 'faculty_id', name: 'faculties.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        $('#createNewMajor').click(function () {
            $('#saveBtn').val("create-major");
            $("#saveBtn").removeClass("btn-primary");
            $("#saveBtn").addClass("btn-success");
            $('#major_id').val('');
            $('#majorForm').trigger("reset");
            $("#faculty").val('').trigger("change");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
            $('#form_result_table').html('');
            $('#form_result').html('');
        });
        
        $('body').on('click', '.editMajor', function () {
            var major_id = $(this).data('id');
            $('#form_result_table').html('');
            $('#form_result').html('');
            $.get("{{ route('major.index') }}" + '/' + major_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Data");
                $('#saveBtn').val("edit-major");
                $("#saveBtn").removeClass("btn-success");
                $("#saveBtn").addClass("btn-primary");
                $('#ajaxModel').modal('show');
                $('#major_id').val(data.id);
                $('#major_code').val(data.major_code);
                $("#faculty").data('select2').trigger('select', {
                    data: {"id": data.faculty_id, "text": data.faculty.name}
                });
                $('#name').val(data.name);
            });
        });
        
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            $.ajax({
                data: $('#majorForm').serialize(),
                url: "{{ route('major.store') }}",
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
                        $('#majorForm').trigger("reset");
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

        $('body').on('click', '.deleteMajor', function () {
            var major_id = $(this).data('id');
            $('#confirmModal').modal('show');
            $('#form_result_table').html('');

            $('#ok_button').click(function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('major.store') }}" + '/' + major_id,
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