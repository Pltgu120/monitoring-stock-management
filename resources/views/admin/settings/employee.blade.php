@extends('layouts.app')
@section('title', __("messages.setting-label", ["name" => __("user")]))
@section('content')
<x-head-datatable/>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header row">
                    <div class="d-flex justify-content-end align-items-center w-100">
                        <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#TambahData" id="modal-button"><i class="fas fa-plus m-1"></i>{{ __("add data") }}</button>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="TambahDataModalLabel">{{ __("add data") }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" onclick="clear()" >&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="name">{{ __("name") }}</label>
                                <input type="text" class="form-control" id="name" autocomplete="off">
                                <input type="hidden" name="id" id="id">
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone_number">{{ __("username") }}</label>
                                <input type="text" class="form-control" id="username" autocomplete="off">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">{{ __("password") }}</label>
                                <input type="password" class="form-control" id="password"></textarea>
                            </div>
                            {{-- email --}}
                            <div class="form-group mb-3">
                                <label for="email">{{ __("email") }}</label>
                                <input type="email" class="form-control" id="email"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="role">{{ __("role") }}</label>
                                <select class="form-control" id="role">
                                    <option selected value="-- {{ __('role') }} --">-- {{ __("role") }} --</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="status">{{ __("status") }}</label>
                                <select class="form-control" id="status">
                                    <option value="1">{{ __("Aktif") }}</option>
                                    <option value="0">{{ __("Tidak Aktif") }}</option>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="kembali">{{ __("back") }}</button>
                            <button type="button" class="btn btn-success" id="simpan">{{ __("save") }}</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-tabel" width="100%"  class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" width="4%">{{ __("no") }}</th>
                                    <th class="border-bottom-0">NAME</th>
                                    <th class="border-bottom-0">USERNAME</th>
                                    <th class="border-bottom-0">EMAIL</th>
                                    <th class="border-bottom-0">ROLE</th>
                                    <th class="border-bottom-0">STATUS</th>
                                    <th class="border-bottom-0" width="1%">{{ __("action") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-data-table/>
<script>
    function isi(){
        $('#data-tabel').DataTable({
            responsive: true, lengthChange: true, autoWidth: false,
            processing:true,
            serverSide:true,
            ajax:`{{route('settings.employee.list')}}`,
            columns:[
                {
                    "data":null,"sortable":false,
                    render:function(data,type,row,meta){
                        return meta.row + meta.settings._iDisplayStart+1;
                    }
                },
                {
                    data:'name',
                    name:'name'
                },
                {
                    data:'username',
                    name:'username',
                },
                {
                    data:'email',
                    name:'email',
                },
                {
                    data:'role_name',
                    name:'role_name',
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if (data === 1) {
                            return '<span class="badge bg-success text-white">Aktif</span>';
                        } else {
                            return '<span class="badge bg-danger text-white">Tidak Aktif</span>';
                        }
                    }
                },

                {
                    data:'tindakan',
                    name:'tindakan'
                }
            ]
        }).buttons().container();
    }

    function simpan(){
            $.ajax({
                url:`{{route('settings.employee.save')}}`,
                type:"post",
                data:{
                    name:$("#name").val(),
                    username:$("#username").val(),
                    password:$("#password").val(),
                    email:$("#email").val(),
                    role_id:$("#role").val(),
                    status:$("#status").val(),
                    "_token":"{{csrf_token()}}"
                },
                success:function(res){
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#kembali').click();
                    $("#name").val(null);
                    $("#username").val(null);
                    $("#password").val(null);
                    $("#email").val(null);
                    $("#role").val("-- {{__('role')}} --");
                    //status
                    $("#status").val(1);
                    $('#data-tabel').DataTable().ajax.reload();
                },
                error:function(err){
                    console.log(err);
                },

            });
    }

    function ubah(){
    $.ajax({
        url: `{{ route('settings.employee.update') }}`,
        type: "put",
        data: {
            id: $("#id").val(),
            name: $("#name").val(),
            username: $("#username").val(),
            password: $("#password").val(),
            email: $("#email").val(),
            role_id: $("#role").val(), // Fetching the selected value
            status: $("#status").val(),
            "_token": "{{ csrf_token() }}"
        },
        success: function(res) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: res.message,
                showConfirmButton: false,
                timer: 1500
            });
            $('#kembali').click();
            $("#name").val(null);
            $("#username").val(null);
            $("#password").val(null);
            $("#email").val(null);
            $("#role").val("-- {{ __('role') }} --");
            //status
            $("#status").val(1);
            $('#data-tabel').DataTable().ajax.reload();
            $('#simpan').text("{{ __('save') }}");
        },
        error: function(err) {
            console.log(err.responseJSON.message); // Log the error message correctly
        }
    });
}


    $(document).ready(function(){
        isi();

        $('#simpan').on('click',function(){
            if($(this).text() === "{{__('update')}}"){
                ubah();
            }else{
                simpan();
            }
        });

        $("#modal-button").on("click",function(){
            $("#name").val(null);
            $("#username").val(null);
            $("#password").val(null);
            $("#email").val(null);
            $("#role").val("-- {{ __('role') }} --");
            //status
            $("#status").val(1);
            $("#simpan").text("{{__('save')}}");
            $("#TambahDataModalLabel").text("{{__('add data')}}");
        });


    });



    $(document).on("click",".ubah",function(){
        let id = $(this).attr('id');
        $("#modal-button").click();
        $("#simpan").text("{{__('update')}}");
        $("#TambahDataModalLabel").text("Ubah Profile Staff");
        $.ajax({
            url:"{{route('settings.employee.detail')}}",
            type:"post",
            data:{
                id:id,
                "_token":"{{csrf_token()}}"
            },
            success:function({data}){
                $("#id").val(data.id);
                $("#name").val(data.name);
                $("#username").val(data.username);
                $("#email").val(data.email);    
                $("#role").val(data.role_id);
                $("#status").val(data.status);
            }
        });

    });

    $(document).on("click", ".hapus", function() {
    let id = $(this).attr("id");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success m-1",
            cancelButton: "btn btn-danger m-1"
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "{{ __('Are you sure?') }}",
        text: "{{ __('This data will be deleted') }}",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "{{ __('Yes, delete') }}",
        cancelButtonText: "{{ __('No, cancel') }}",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('settings.employee.destroy', ':id') }}".replace(':id', id),
                type: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#data-tabel').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "{{ __('An error occurred') }}",
                        text: xhr.responseJSON?.message || "{{ __('Please try again') }}",
                        showConfirmButton: true
                    });
                }
            });
        }
    });
});

</script>
@endsection
