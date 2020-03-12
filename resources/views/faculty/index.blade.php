@extends('layouts.master')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Fakultas</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li>Home</li>
                            <li class="active"><a href="/faculty">Fakultas</a></li>
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
                        {{-- <strong class="card-title">Data Faculty</strong> --}}
                        <div class="pull-left">
                            <a class="btn btn-success" href="javascript:void(0)" id="createNewFaculty"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode</th>
                                    <th width="35%">Nama</th>
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
            <form id="facultyForm" name="facultyForm" class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="modelHeading"></h3>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <div class="card-body card-block">
                        <input type="hidden" name="faculty_id" id="faculty_id">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Nama Fakultas</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Fakultas">
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
            ajax: "{{ route('faculty.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'faculty_code', name: 'faculty_code'},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        $('#createNewFaculty').click(function () {
            $('#saveBtn').val("create-faculty");
            $("#saveBtn").removeClass("btn-primary");
            $("#saveBtn").addClass("btn-success");
            $('#faculty_id').val('');
            $('#facultyForm').trigger("reset");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
            $('#form_result_table').html('');
            $('#form_result').html('');
        });
        
        $('body').on('click', '.editFaculty', function () {
            var faculty_id = $(this).data('id');
            $('#form_result_table').html('');
            $('#form_result').html('');
            $.get("{{ route('faculty.index') }}" + '/' + faculty_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Data");
                $('#saveBtn').val("edit-faculty");
                $("#saveBtn").removeClass("btn-success");
                $("#saveBtn").addClass("btn-primary");
                $('#ajaxModel').modal('show');
                $('#faculty_id').val(data.id);
                $('#faculty_code').val(data.faculty_code);
                $('#name').val(data.name);
            });
        });
        
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            $.ajax({
                data: $('#facultyForm').serialize(),
                url: "{{ route('faculty.store') }}",
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
                        $('#facultyForm').trigger("reset");
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

        $('body').on('click', '.deleteFaculty', function () {
            var faculty_id = $(this).data('id');
            $('#confirmModal').modal('show');

            $('#ok_button').click(function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('faculty.store') }}" + '/' + faculty_id,
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
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
        
    });

</script> 
@endsection