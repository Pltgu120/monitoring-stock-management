@extends('layouts.app')
@section('title',__('Transaksi Masuk Mekanik'))
@section('content')
<x-head-datatable/>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header row">
                    @if(Auth::user()->role->name != 'staff')
                    <div class="d-flex justify-content-end align-items-center w-100">
                        <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#TambahData" id="modal-button"><i class="fas fa-plus m-1"></i> {{__('add data')}} </button>
                    </div>
                    @endif
                </div>

                <!-- Modal Barang -->
            <div class="modal fade" id="modal-barang" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog  modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">{{__('select items')}}</h5>
                            <button type="button" class="close" id="close-modal-barang" >
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-barang" width="100%"  class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0" width="8%">{{__('no')}}</th>
                                            <th class="border-bottom-0">{{__('photo')}}</th>
                                            {{-- <th class="border-bottom-0">{{__('item code')}}</th>
                                            <th class="border-bottom-0">{{__('name')}}</th> --}}
                                            <th class="border-bottom-0">{{__('part name')}}</th>
                                            <th class="border-bottom-0">{{__('part number')}}</th>
                                            {{-- <th class="border-bottom-0">{{__('type')}}</th> --}}
                                            {{-- <th class="border-bottom-0">{{__('unit')}}</th> --}}
                                            {{-- <th class="border-bottom-0">{{__('brand')}}</th> --}}
                                            <th class="border-bottom-0">{{__('Unit')}}</th>
                                            <th class="border-bottom-0">{{__('Brand')}}</th>
                                            <th class="border-bottom-0">{{__('first stock')}}</th>
                                            {{-- <th class="border-bottom-0">{{__('price')}}</th> --}}
                                            @if(Auth::user()->role->name != 'staff')
                                            <th class="border-bottom-0" width="1%">{{__('action')}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>



                <!-- Modal -->
                <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="TambahDataModalLabel">{{__("create an incoming transaction")}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"  >&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="kode" class="form-label">{{__("incoming item code")}}<span class="text-danger">*</span></label>
                                        <input type="text" name="kode" readonly class="form-control">
                                        <input type="hidden" name="id"/>
                                        <input type="hidden" name="id_barang"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_masuk" class="form-label">{{__("date of entry")}} <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_masuk" class="form-control">
                                    </div>
                                    {{-- Upload image --}}
                                    <div class="form-group">
                                        <label for="image" class="form-label">{{ __("photo") }}</label>
                                        <input class="form-control" id="image" name="image" type="file" accept=".png,.jpeg,.jpg,.svg">
                                        <!-- Image preview -->
                                        <img id="preview-image" src="{{ asset('default.png') }}" width="80%" alt="profile-user" class="text-center">
                                    </div>
                                    
                                </div>
                                <div class="col-md-5">
                                    <label for="part_number" class="form-label">{{__('part number')}} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="part_number" class="form-control">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button" id="cari-barang"><i class="fas fa-search"></i></button>
                                            <button class="btn btn-success" type="button" id="barang"><i class="fas fa-box"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="part_name" class="form-label">{{__("part name")}}</label>
                                        <input type="text" name="part_name" id="part_name" readonly class="form-control">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="unit_name" class="form-label">{{__("unit")}}</label>
                                                <input type="text" name="unit_name" readonly class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="brand_name" class="form-label">{{__("brand")}}</label>
                                                <input type="text" name="brand_name" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah" class="form-label">{{__("incoming amount")}}<span class="text-danger">*</span></label>
                                        <input type="number" name="jumlah"  class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="kembali">Batalkan</button>
                            <button type="button" class="btn btn-success" id="simpan">Simpan</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-tabel" width="100%"  class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" width="8%">{{__("no")}}</th>
                                    <th class="border-bottom-0">{{__("photo")}}</th>
                                    <th class="border-bottom-0">{{__("date")}}</th>
                                    <th class="border-bottom-0">{{__("incoming item code")}}</th>
                                    <th class="border-bottom-0">{{__("part number")}}</th>
                                    <th class="border-bottom-0">{{__("part name")}}</th>
                                    <th class="border-bottom-0">{{__("incoming amount")}}</th>
                                    <th class="border-bottom-0" width="1%">{{__("action")}}</th>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function load(){
        $('#data-barang').DataTable({
            lengthChange: true,
            processing:true,
            serverSide:true,
            ajax:`{{route('barang.transaksi.masuk.list.in')}}`,
            columns:[
                {
                    "data":null,"sortable":false,
                    render:function(data,type,row,meta){
                        return meta.row + meta.settings._iDisplayStart+1;
                    }
                },
                {
                    data:'img',
                    name:'img'
                },{
                    data:'part_number',
                    name:'part_number'
                },{
                    data:'part_name',
                    name:'part_name'
                },
                {
                    data: 'unit_name',
                    name: 'unit_name'
                },
                {
                    data: 'brand_name',
                    name: 'brand_name'
                },
                {
                    data:'quantity',
                    name:'quantity'
                },
                {
                    data:'tindakan',
                    render:function(data){
                        const pattern = /id='(\d+)'/;
                        const matches = data.match(pattern);
                        return `<button class='pilih-data-barang btn btn-success' data-id='${matches[1]}'>{{__("select")}}</button>`;
                    }
                }
            ]
        }).buttons().container();
    }




    $(document).ready(function(){
        load();

        // pilih data barang
        $(document).on("click",".pilih-data-barang",function(){
            id = $(this).data("id");
            $.ajax({
                url:"{{route('barang-mekanik.detail')}}",
                type:"post",
                data:{
                    id:id,
                    "_token":"{{csrf_token()}}"
                },
                success:function({data}){
                    $("input[name='part_number']").val(data.part_number);
                    $("input[name='id_barang']").val(data.id);
                    $("input[name='part_name']").val(data.part_name);
                    $("input[name='unit_name']").val(data.unit_name);
                    $("input[name='brand_name']").val(data.brand_name);
                    $('#modal-barang').modal('hide');
                    $('#TambahData').modal('show');
                }
             });
        });
    });

</script>
<script>
    function detail(){
        const part_number = $("input[name='part_number']").val();
        $.ajax({
            url:`{{route('barang-mekanik.part_number')}}`,
            type:'post',
            data:{
                part_number:part_number
            },
            success:function({data}){
                $("input[name='id_barang']").val(data.id);
                $("input[name='part_name']").val(data.part_name);
                $("input[name='unit_name']").val(data.unit_name);
                $("input[name='brand_name']").val(data.brand_name);
            }
        });

    }




    function simpan(){
        const item_mechanical_id =  $("input[name='id_barang']").val();
        const user_id = `{{Auth::user()->id}}`;
        const date_received_mechanical = $("input[name='tanggal_masuk']").val();
        const invoice_number = $("input[name='kode'").val();
        const quantity = $("input[name='jumlah'").val();
        const image = $("input[name='image'")[0].files[0];

        const Form = new FormData();
        Form.append('user_id',user_id);
        Form.append('item_mechanical_id',item_mechanical_id);
        Form.append('date_received_mechanical', date_received_mechanical );
        Form.append('quantity', quantity );
        Form.append('invoice_number', invoice_number );
        Form.append('image', image );
        $.ajax({
            url:`{{route('transaksi.masuk.mekanik.save')}}`,
            type:"post",
            processData: false,
            contentType: false,
            dataType: 'json',
            data:Form,
            success:function(res){
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#kembali').click();
                    $("input[name='id_barang']").val(null);
                    $("input[name='tanggal_masuk']").val(null);
                    $("input[name='part_name']").val(null);
                    $("input[name='part_number']").val(null);
                    $("input[name='jumlah']").val(0);
                    $("input[name='unit_name']").val(null);
                    $("input[name='brand_name']").val(null);
                    $("input[name='image']").val(null);

                    $('#data-tabel').DataTable().ajax.reload();
            },
            statusCode:{
                400:function(res){
                    const  {message} =res.responseJSON;
                    Swal.fire({
                        position: "center",
                        icon: "warning",
                        title: "Oops...",
                        text:message,
                        showConfirmButton: false,
                        timer: 1900
                    });
                }
            }

        })
    }

    document.getElementById('image').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Set the image source to the selected file
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    });

    // If you want to display an image from the storage (example after submission)
    function updateImagePreview(imagePath) {
        const imageUrl = "{{ asset('storage/barang_masuk') }}/" + imagePath;
        document.getElementById('preview-image').src = imageUrl;
    }


    function ubah() {
    const id = $("input[name='id']").val();
    const item_mechanical_id = $("input[name='id_barang']").val();
    const user_id = `{{Auth::user()->id}}`;
    const date_received_mechanical = $("input[name='tanggal_masuk']").val();
    const invoice_number = $("input[name='kode']").val();
    const quantity = $("input[name='jumlah']").val();
    const image = $("input[name='image']")[0].files[0];

    // Gunakan FormData untuk mengirimkan data termasuk file
    const formData = new FormData();
    formData.append('id', id);
    formData.append('item_mechanical_id', item_mechanical_id);
    formData.append('user_id', user_id);
    formData.append('date_received_mechanical', date_received_mechanical);
    formData.append('invoice_number', invoice_number);
    formData.append('quantity', quantity);
    if (image) {
        formData.append('image', image);
    }

    $.ajax({
        url: `{{route('transaksi.masuk.mekanik.update')}}`,
        type: "POST",
        processData: false, // Jangan proses data
        contentType: false, // Jangan set konten secara manual
        data: formData,
        success: function (res) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: res.message,
                showConfirmButton: false,
                timer: 1500
            });
            $('#kembali').click();
            $("input[name='id']").val(null);
            $("input[name='id_barang']").val(null);
            $("input[name='part_name']").val(null);
            $("input[name='tanggal_masuk']").val(null);
            $("input[name='part_number']").val(null);
            $("input[name='unit_name']").val(null);
            $("input[name='brand_name']").val(null);
            $("input[name='jumlah']").val(0);
            $("input[name='image']").val(null);
            $('#data-tabel').DataTable().ajax.reload();
        },
        error: function (err) {
            console.log(err);
        },
    });
}


    $(document).ready(function(){
        $('#data-tabel').DataTable({
            lengthChange: true,
            processing:true,
            serverSide:true,
            ajax:`{{route('transaksi.masuk.mekanik.list')}}`,
            columns:[
                {
                    "data":null,"sortable":false,
                    render:function(data,type,row,meta){
                        return meta.row + meta.settings._iDisplayStart+1;
                    }
                },
                {
                    data: 'img',
                    name: 'img'
                },
                {
                    data: "date_received_mechanical",
                    name: "date_received_mechanical",
                    render: function (data, type, row) {
                        // Assuming data is in the format 'YYYY-MM-DD'
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        const date = new Date(data);
                        return date.toLocaleDateString('id-ID', options); // Indonesian locale for '14 Oktober 2024'
                    }
                },
               {
                data:"invoice_number",
                name:"invoice_number"
               },{
                data:"part_number",
                name:"part_number"
               },
               {
                    data: 'part_name',
                    name: 'part_name',
                    render: function (data) {
                        if (data) {
                            const words = data.split(' ');
                            const chunkedWords = [];
                            for (let i = 0; i < words.length; i += 3) {
                                chunkedWords.push(words.slice(i, i + 3).join(' '));
                            }
                            return '<span style="font-size: 15px;">' + chunkedWords.join('<br/>') + '</span>';
                        }
                        return '';
                    }
                },
               {
                data:"quantity",
                name:"quantity"
               },
               {
                data:"tindakan",
                name:"tindakan"
               }
            ]
        });
        $("#barang").on("click",function(){
            $('#modal-barang').modal('show');
            $('#TambahData').modal('hide');
        });
        $("#close-modal-barang").on("click",function(){
            $('#modal-barang').modal('hide');
            $('#TambahData').modal('show');
        });
        $("#cari-barang").on("click",detail);

        $('#simpan').on('click',function(){
            if($(this).text() === 'Simpan Perubahan'){
                ubah();
            }else{
                simpan();
            }
        });

        $("#modal-button").on("click",function(){
            if($(this).attr('data-target')==='alert'){
                return Swal.fire({
                        position: "center",
                        icon: "warning",
                        title: "Oops...",
                        text:"Barang Stok Masuk Kosong",
                        showConfirmButton: false,
                        timer: 1900
                });
            }

            $('#TambahData').modal('show');

            id = new Date().getTime();
            $("input[name='kode']").val("BRGMSKMEKANIK-"+id);
            $("input[name='id']").val(null);
            $("input[name='id_barang']").val(null);
            $("input[name='part_name']").val(null);
            $("input[name='tanggal_masuk']").val(null);
            $("input[name='part_number']").val(null);
            $("input[name='unit_name']").val(null);
            $("input[name='brand_name']").val(null);
            $("input[name='jumlah']").val(null);
            $("input[name='image']").val(null);
            $('#simpan').text("Simpan");
        });


    });



    $(document).on("click",".ubah",function(){
        $("#modal-button").click();
        $("#simpan").text("Simpan Perubahan");
        let id = $(this).attr('id');
        $.ajax({
            url:"{{route('transaksi.masuk.mekanik.detail')}}",
            type:"post",
            data:{
                id:id,
            },
            success:function({data}){
                $("input[name='id']").val(data.id);
                $("input[name='kode']").val(data.invoice_number);
                $("input[name='id_barang']").val(data.id_barang);
                // $("select[name='supplier']").val(data.supplier_id);
                $("input[name='part_name']").val(data.part_name);
                $("input[name='tanggal_masuk']").val(data.date_received_mechanical);
                $("input[name='part_number']").val(data.part_number);
                // $("input[name='jenis_barang']").val(data.jenis_barang);
                // $("input[name='satuan_barang']").val(data.satuan_barang);
                //input_name
                $("input[name='unit_name']").val(data.unit_name);
                $("input[name='brand_name']").val(data.brand_name);
                $("input[name='jumlah']").val(data.quantity);
                $("input[name='image']").val(null);
            }
        });

    });

    $(document).on("click",".hapus",function(){
        let id = $(this).attr('id');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success m-1",
                cancelButton: "btn btn-danger m-1"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Anda Yakin ?",
            text: "Data Ini Akan Di Hapus",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya,Hapus",
            cancelButtonText: "Tidak, Kembali!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"{{route('transaksi.masuk.mekanik.delete')}}",
                    type:"delete",
                    data:{
                        id:id
                    },
                    success:function(res){
                        Swal.fire({
                                position: "center",
                                icon: "success",
                                title: res.message,
                                showConfirmButton: false,
                                timer: 1500
                        });
                        $('#data-tabel').DataTable().ajax.reload();
                    }
                });
            }
        });


    });


</script>
@endsection