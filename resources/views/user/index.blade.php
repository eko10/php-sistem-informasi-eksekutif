@extends('layouts.master')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>User</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li>Home</li>
                            <li class="active"><a href="{{ url('user') }}">User</a></li>
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
                        {{-- <strong class="card-title">Data User</strong> --}}
                        <div class="pull-left">
                            <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">Role</th>
                                    <th width="25%">Aksi</th>
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
            <form id="userForm" name="userForm" class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="modelHeading"></h3>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <div class="card-body card-block">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-control-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-control-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="role" class="form-control-label">Role</label>
                            <select name="role" id="role" class="form-control" style="width: 100%;">
                                <option value=""></option>
                                <option value="eksekutif">eksekutif</option>
                                <option value="admin">admin</option>
                            </select>
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

<div class="modal fade" id="ajaxModelEditUser" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" name="editUserForm" class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="modelHeadingEditUser"></h3>
                </div>
                <div class="modal-body">
                    <span id="form_result_edit_user"></span>
                    <div class="card-body card-block">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="form-group">
                            <label for="edit_name" class="form-control-label">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" placeholder="Nama">
                        </div>
                        <div class="form-group">
                            <label for="edit_email" class="form-control-label">Email</label>
                            <input type="text" class="form-control" id="edit_email" name="edit_email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="edit_password" class="form-control-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="edit_password" placeholder="Password">
                            <small id="edit_password" class="text-muted">
                                Kosongkan password jika tidak diubah.
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="edit_role" class="form-control-label">Role</label>
                            <select name="edit_role" id="edit_role" class="form-control" style="width: 100%;">
                                <option value=""></option>
                                <option value="eksekutif">eksekutif</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" id="updateBtn" value="create">Simpan</button>
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

        $('#role').select2({
            placeholder: 'pilih role',
            allowClear: true
        });

        $('#edit_role').select2({
            placeholder: 'pilih role',
            allowClear: true
        });
    
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        $('#createNewUser').click(function () {
            $('#saveBtn').val("create-user");
            $("#saveBtn").removeClass("btn-primary");
            $("#saveBtn").addClass("btn-success");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $("#role").val('').trigger("change");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
            $('#form_result_table').html('');
            $('#form_result').html('');
        });

        $('body').on('click', '.editUser', function () {
            var user_id = $(this).data('id');
            $('#form_result_table').html('');
            $('#form_result_edit_user').html('');
            $.get("{{ route('user.index') }}" + '/' + user_id + '/edit', function (data) {
                $('#modelHeadingEditUser').html("Edit Data");
                $('#updateBtn').val("edit-user");
                $("#updateBtn").removeClass("btn-success");
                $("#updateBtn").addClass("btn-primary");
                $('#ajaxModelEditUser').modal('show');
                $('#user_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_email').val(data.email);
                $('#edit_password').val(data.password);
                $("#edit_role").data('select2').trigger('select', {
                    data: {"id": data.role, "text": data.role}
                });
            });
        });
        
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            $.ajax({
                data: $('#userForm').serialize(),
                url: "{{ route('user.store') }}",
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
                        $('#userForm').trigger("reset");
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

        $('#updateBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Proses..');
            $.ajax({
                data: $('#editUserForm').serialize(),
                url: "{{ route('user.edit') }}",
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
                        $('#editUserForm').trigger("reset");
                        $('#ajaxModelEditUser').modal('hide');
                        $('#updateBtn').html('Simpan');
                        table.draw();
                    }
                    $('#form_result_edit_user').html(html);
                    $('#updateBtn').html('Simpan');
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Simpan');
                }
            });
        });

        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data('id');
            $('#confirmModal').modal('show');
            $('#form_result_table').html('');

            $('#ok_button').click(function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('user.store') }}" + '/' + user_id,
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