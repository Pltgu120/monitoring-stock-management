@extends('layouts.app')
@section('title', __("Data Barang Bekas Pakai PLTGU Tanjung Uncang"))
@section('content')
<x-head-datatable/>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header row">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        @if(Auth::user()->role->name != 'staff')
                        <a href="{{ route('barang-bekas-pakai.export') }}" class="btn btn-outline-success font-weight-bold m-1">
                            <i class="fas fa-file-excel m-1"></i>{{ __("messages.export-to", ["file" => "excel"]) }}
                        </a>
                        
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#TambahData" id="modal-button">
                            <i class="fas fa-plus"></i> {{ __("add data") }}
                        </button>
                        @endif
                    </div>
                    
                </div>
                


                <!-- Modal -->
                <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="TambahDataModalLabel">{{ __("add barang rusak") }}</h5>
                                
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-7">

                                        <div class="form-group">
                                            <label for="part_name" class="form-label">{{ __("Part Name") }} <span class="text-danger">*</span></label>
                                            <input type="text" name="part_name" class="form-control">
                                        </div>

                                        {{-- Part Number --}}
                                        <div class="form-group">
                                            <label for="part_number" class="form-label">{{ __("Part Number") }} <span class="text-danger">*</span></label>
                                            <input type="text" name="part_number" class="form-control">
                                        </div>

                                        {{-- Kode Rak --}}
                                        <div class="form-group">
                                            <label for="kode_rak" class="form-label">{{ __("Kode Rak") }} <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_rak" class="form-control">
                                        </div>

                                        {{-- Date Items input type date --}}
                                        <div class="form-group">
                                            <label for="date_consumable_items" class="form-label">{{ __("Keterangan") }} <span class="text-danger">*</span></label>
                                            <input type="date" name="date_consumable_items" class="form-control">
                                        </div>

                                        {{-- Kode Rak --}}
                                        <div class="form-group">
                                            <label for="person_name" class="form-label">{{ __("Person Name") }} <span class="text-danger">*</span></label>
                                            <input type="text" name="person_name" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="unit_name" class="form-label">{{ __("Unit Name") }} <span class="text-danger">*</span></label>
                                            <input type="text" name="unit_name" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="brand_name" class="form-label">{{ __("Brand Name") }} <span class="text-danger">*</span></label>
                                            <input type="text" name="brand_name" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="jumlah" class="form-label">{{ __("Quantity") }} <span class="text-danger">*</span></label>
                                            <input type="number" value="0" name="jumlah" class="form-control">
                                        </div>

                                       


                                    </div>
                                    <div class="col-md-5">
                                        {{-- <div class="form-group">
                                            <label for="file_reference" class="form-label">{{ __("Upload File (PDF)") }}</label>
                                            <input type="file" name="file_reference" class="form-control" accept=".pdf" onchange="previewPDF(event)">
                                        </div>
                                        
                                        <div id="pdfPreviewContainer" style="margin-top: 10px;">
                                            <!-- Placeholder for PDF preview -->
                                            <object id="pdfPreview" type="application/pdf" width="100%" height="500px" style="display: none;"></object>
                                        </div> --}}


                                        <div class="form-group">
                                            {{-- <label for="title" class="form-label">{{ __("photo") }}</label> --}}
                                            <label for="photo" class="form-label">{{ __("photo") }}</label>
                                            <input class="form-control" id="GetFile" name="photo" type="file" accept=".png,.jpeg,.jpg,.svg" onchange="previewImage(event)">
                                            <img src="{{asset('default.png')}}" width="80%" alt="profile-user" id="outputImg" class="text-center">
                                        </div>
                                    </div>
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
                                    <th class="border-bottom-0" width="8%">{{ __("no") }}</th>
                                    <th class="border-bottom-0">{{ __("photo") }}</th>
                                    <th class="border-bottom-0">{{ __("Part Name") }}</th>
                                    <th class="border-bottom-0">{{ __("Part Number") }}</th>
                                    <th class="border-bottom-0">{{ __("Qty (Pcs)") }}</th>
                                    <th class="border-bottom-0">{{ __("Satuan") }}</th>
                                    <th class="border-bottom-0">{{ __("Brand (Merek)") }}</th>
                                    <th class="border-bottom-0">{{ __("Kode Rak") }}</th>
                                    <th class="border-bottom-0">{{ __("Keterangan") }}</th>
                                    <th class="border-bottom-0">{{ __("Update By") }}</th> 
                                    {{-- <th class="border-bottom-0">{{ __("Document") }}</th> --}}
                                    @if(Auth::user()->role->name != 'staff')
                                    <th class="border-bottom-0" width="1%">{{ __("action") }}</th>
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
<!-- Modal -->
{{-- <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Ukuran modal Bootstrap terbesar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">PDF Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <embed id="pdfViewer" src="" type="application/pdf" style="width: 100%; height: 700px;" />
            </div>
        </div>
    </div>
</div> --}}
{{-- 
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">{{ __("Import Data Barang") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">{{ __("Choose Excel File") }}</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
                        <button type="submit" class="btn btn-success">{{ __("Upload") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}



<x-data-table/>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function previewImage(event) {
        const outputImg = document.getElementById('outputImg');
        outputImg.src = URL.createObjectURL(event.target.files[0]);
        outputImg.onload = function() {
            URL.revokeObjectURL(outputImg.src); // free memory
        }
    }

    function isi() {
    $('#data-tabel').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        ajax: `{{ route('barang-bekas-pakai.list') }}`,
        columns: [
            {
                "data": null, "sortable": false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'img',
                name: 'img'
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
                data: 'part_number',
                name: 'part_number',
                render: function (data) {
                    return '<span style="font-size: 15px;">' + data + '</span>';
                }
            },
            {
                data: 'quantity',
                name: 'quantity',
                render: function (data) {
                    return '<span style="font-size: 15px;">' + data + '</span>';
                }
            },
            {
                data: 'unit_name',
                name: 'unit_name',
                render: function (data) {
                    return '<span style="font-size: 15px;">' + data + '</span>';
                }
            },
            {
                data: 'brand_name',
                name: 'brand_name',
                render: function (data) {
                    return '<span style="font-size: 15px;">' + data + '</span>';
                }
            },
            {
                data: 'kode_rak',
                name: 'kode_rak',
                render: function (data) {
                    return '<span style="font-size: 15px;">' + data + '</span>';
                }
            },
            {
                data: 'date_consumable_items',
                name: 'date_consumable_items',
                render: function (data) {
                    if (data) {
                        const date = new Date(data);
                        const options = { day: 'numeric', month: 'long', year: 'numeric', locale: 'id-ID' };
                        return '<span style="font-size: 15px;">' + date.toLocaleDateString('id-ID', options) + '</span>';
                    }
                    return '';
                }
            },
            {
                data: 'person_name',
                name: 'person_name',
                render: function (data) {
                    return '<span style="font-size: 15px;">' + data + '</span>';
                }
            },

            @if(Auth::user()->role->name != 'staff')
            {
                data: 'tindakan',
                name: 'tindakan'
            }
            @endif
        ]
    }).buttons().container();

    // Event listener untuk menghandle click pada link PDF
    // $(document).on('click', '.pdf-link', function (e) {
    //     e.preventDefault(); // Mencegah perilaku default anchor tag
    //     let pdfUrl = $(this).data('pdf-url'); // Ambil URL PDF dari data atribut
    //     $('#pdfViewer').attr('src', pdfUrl); // Set URL PDF ke embed
    //     $('#pdfModal').modal('show'); // Tampilkan modal
    // });
}




    function simpan() {
    const Form = new FormData();
    Form.append('image', $("#GetFile")[0].files[0]);
    Form.append('quantity', $("input[name='jumlah']").val());
    Form.append('part_name', $("input[name='part_name']").val());
    Form.append('part_number', $("input[name='part_number']").val());
    Form.append('kode_rak', $("input[name='kode_rak']").val());
    Form.append('date_consumable_items', $("input[name='date_consumable_items']").val());
    Form.append('person_name', $("input[name='person_name']").val());
    Form.append('unit_name', $("input[name='unit_name']").val());
    Form.append('brand_name', $("input[name='brand_name']").val());

    $.ajax({
        url: `{{ route('barang-bekas-pakai.save') }}`,
        type: "POST",
        data: Form,
        processData: false,
        contentType: false,
        success: function (res) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: res.message,
                showConfirmButton: false,
                timer: 1500
            });
            resetForm();
            $('#TambahData').modal('hide');
            $('#data-tabel').DataTable().ajax.reload();
        },
        error: function (err) {
            console.error(err);
        }
    });
}


function resetForm() {
    // $("input[name='nama']").val('');
    $("input[name='kode']").val('');
    $("#GetFile").val(null);
    // $("select[name='jenisbarang']").val('');
    // $("select[name='satuan']").val('');
    // $("select[name='merk']").val('');
    $("input[name='jumlah']").val(0);
    // $("input[name='harga']").val('');
    $("input[name='part_name']").val('');
    $("input[name='part_number']").val('');
    $("input[name='kode_rak']").val('');
    $("input[name='date_consumable_items']").val('');
    $("input[name='person_name']").val('');
    // $("input[name='file_reference']").val('');
    $("input[name='unit_name']").val('');
    $("input[name='brand_name']").val('');
    
}





$(document).ready(function() {
    // Removed the harga function call
    isi();

    $('#simpan').on('click', function() {
        if ($(this).text() === 'Simpan Perubahan') {
            // ubah();
        } else {
            simpan();
        }
    });

    $("#modal-button").on("click", function() {
        $("#item-count").hide();
        // $("input[name='nama']").val(null);
        $("input[name='id']").val(null);
        // $("input[name='kode']").val(null);
        $("#GetFile").val(null);
        // $("select[name='jenisbarang']").val(null);
        // $("select[name='satuan']").val(null);
        // $("select[name='merk']").val(null);
        $("input[name='jumlah']").val(0);
        // Removed the harga input reset
        $("input[name='part_name']").val(null);
        $("input[name='part_number']").val(null);
        $("input[name='kode_rak']").val(null);
        $("input[name='date_consumable_items']").val(null);
        $("input[name='person_name']").val(null);
        // $("input[name='file_reference']").val(null);
        $("input[name='unit_name']").val(null);
        $("input[name='brand_name']").val(null);
        $("#simpan").text("Simpan");
        id = new Date().getTime();
        $("input[name='kode']").val("BRG-" + id);
    });
});




    // $(document).on("click",".ubah",function(){
    //     let id = $(this).attr('id');
    //     $("#modal-button").click();
    //     $("#item-count").show();
    //     $("#simpan").text("Simpan Perubahan");
    //     $.ajax({
    //         url:"{{route('barang.detail')}}",
    //         type:"post",
    //         data:{
    //             id:id,
    //             "_token":"{{csrf_token()}}"
    //         },
    //         success:function({data}){
    //             $("input[name='id']").val(data.id);
    //             // $("input[name='nama']").val(data.name);
    //             // $("input[name='kode']").val(data.code);
    //             $("select[name='jenisbarang']").val(data.category_id);
    //             $("select[name='satuan']").val(data.unit_id);
    //             $("select[name='merk']").val(data.brand_id);
    //             $("input[name='jumlah']").val(data.quantity);
    //             $("input[name='harga']").val(data.price);
    //             $("input[name='part_name']").val(data.part_name);
    //             $("input[name='part_number']").val(data.part_number);
    //             $("input[name='kode_rak']").val(data.kode_rak);
    //             $("input[name='date_items']").val(data.date_items);
    //             $("input[name='person_name']").val(data.person_name);
    //             // $("input[name='file_reference']").val(data.file_reference);
                
    //         }
    //     });


    // });
    $(document).on("click", ".hapus", function () {
    const form = $(this).closest('.delete-form'); // Get the closest form

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
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Tidak, Kembali!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // Submit the form if confirmed
        }
    });
});




</script>

@endsection