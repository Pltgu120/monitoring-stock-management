@extends('layouts.app')
@section('title', __("Edit Barang Kimia PLTGU Tanjung Uncang"))
@section('content')
<x-head-datatable/>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header">
                    <h4>{{ __("Edit Goods") }}</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('barang-kimia.update', $itemChemical->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="part_name" class="form-label">{{ __("Part Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="part_name" class="form-control" value="{{ $itemChemical->part_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="part_number" class="form-label">{{ __("Part Number") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="part_number" class="form-control" value="{{ $itemChemical->part_number }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="kode_rak" class="form-label">{{ __("Kode Rak") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_rak" class="form-control" value="{{ $itemChemical->kode_rak }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="date_items_chemical" class="form-label">{{ __("Keterangan") }} <span class="text-danger">*</span></label>
                                    <input type="date" name="date_items_chemical" class="form-control" value="{{ $itemChemical->date_items_chemical }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="person_name" class="form-label">{{ __("Person Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="person_name" class="form-control" value="{{ $itemChemical->person_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="unit_name" class="form-label">{{ __("Unit Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_name" class="form-control" value="{{ $itemChemical->unit_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="brand_name" class="form-label">{{ __("Brand Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="brand_name" class="form-control" value="{{ $itemChemical->brand_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="quantity" class="form-label">{{ __("Quantity") }} <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" value="{{ $itemChemical->quantity }}" min="0" required>
                                </div>
                                

                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __("Photo") }} <span class="text-danger">*</span></label>
                                    
                                    <!-- Display the existing PDF file if available -->
                                    @if($itemChemical->image)
                                        <embed src="{{ asset('storage/barang_kimia/' . $itemChemical->image) }}" width="100%" height="300px" class="mb-2" />
                                    @endif
                                    <!-- File input for new photo -->
                                    <input class="form-control mt-5" id="GetFile" name="image" type="file" accept=".png,.jpeg,.jpg,.svg">
                                    <small class="form-text text-muted">{{ __("Upload a new photo if you want to change it.") }}</small>
                                </div>

                                <!-- File Reference Upload -->
                                <div class="form-group">
                                    <label for="file_reference" class="form-label">{{ __("File Reference (PDF)") }} <span class="text-danger">*</span></label>
                                    
                                    <!-- Display the existing PDF file if available -->
                                    @if($itemChemical->file_reference)
                                        <embed src="{{ asset('storage/pdf_files_kimia/' . $itemChemical->file_reference) }}" width="100%" height="300px" class="mb-2" />
                                    @endif

                                    <input class="form-control" id="GetFileReference" name="file_reference" type="file" accept=".pdf" onchange="displayFileReferenceName()">
                                    <small class="form-text text-muted">{{ __("Upload a PDF file as a reference.") }}</small>
                                    <div id="fileReferenceName" class="mt-2">
                                        <strong id="fileReferenceDisplay">{{ __("No file chosen") }}</strong>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('barang-kimia') }}" class="btn btn-secondary">{{ __("Back") }}</a>
                            <button type="submit" class="btn btn-success">{{ __("Update") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500
        });
    @endif
</script>


<script>
    function displayFileReferenceName() {
        const fileInput = document.getElementById('GetFileReference');
        const fileReferenceDisplay = document.getElementById('fileReferenceDisplay');
        const noFileMessage = document.getElementById('noFileMessage');
        
        if (fileReferenceDisplay && fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            fileReferenceDisplay.textContent = fileName;
            if (noFileMessage) noFileMessage.textContent = ""; // Clear no file message
        } else if (noFileMessage) {
            fileReferenceDisplay.textContent = ""; // Clear file name
            noFileMessage.textContent = "{{ __('No file chosen') }}";
        }
    }

</script>
@endsection
