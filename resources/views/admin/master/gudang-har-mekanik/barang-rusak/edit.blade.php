@extends('layouts.app')
@section('title', __("Edit Barang Rusak Mekanik PLTGU Tanjung Uncang"))
@section('content')
<x-head-datatable/>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header">
                    <h4>{{ __("Edit Damaged Goods") }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang-rusak-mekanik.update', $damaged_item_mechanical->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-7">

                                <div class="form-group">
                                    <label for="part_name" class="form-label">{{ __("Part Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="part_name" class="form-control" value="{{ $damaged_item_mechanical->part_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="part_number" class="form-label">{{ __("Part Number") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="part_number" class="form-control" value="{{ $damaged_item_mechanical->part_number }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="kode_rak" class="form-label">{{ __("Kode Rak") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_rak" class="form-control" value="{{ $damaged_item_mechanical->kode_rak }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="date_damaged_items" class="form-label">{{ __("Keterangan") }} <span class="text-danger">*</span></label>
                                    <input type="date" name="date_items" class="form-control" value="{{ $damaged_item_mechanical->date_damaged_items }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="person_name" class="form-label">{{ __("Person Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="person_name" class="form-control" value="{{ $damaged_item_mechanical->person_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="unit_name" class="form-label">{{ __("Unit Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_name" class="form-control" value="{{ $damaged_item_mechanical->unit_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="brand_name" class="form-label">{{ __("Brand Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="brand_name" class="form-control" value="{{ $damaged_item_mechanical->brand_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="quantity" class="form-label">{{ __("Quantity") }} <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" value="{{ $damaged_item_mechanical->quantity }}" min="0" required>
                                </div>
                                

                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __("Photo") }} <span class="text-danger">*</span></label>
                                    
                                    <!-- Display the existing PDF file if available -->
                                    @if($damaged_item_mechanical->image)
                                        <embed src="{{ asset('storage/barang_rusak_mekanik/' . $damaged_item_mechanical->image) }}" width="100%" height="300px" class="mb-2" />
                                    @endif
                                    <!-- File input for new photo -->
                                    <input class="form-control mt-5" id="GetFile" name="image" type="file" accept=".png,.jpeg,.jpg,.svg">
                                    <small class="form-text text-muted">{{ __("Upload a new photo if you want to change it.") }}</small>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('barang-rusak') }}" class="btn btn-secondary">{{ __("Back") }}</a>
                            <button type="submit" class="btn btn-success">{{ __("Update") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function displayFileReferenceName() {
        const fileInput = document.getElementById('GetFileReference');
        const fileReferenceDisplay = document.getElementById('fileReferenceDisplay');
        const noFileMessage = document.getElementById('noFileMessage');
        
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            // Get the selected file name
            const fileName = fileInput.files[0].name;
            // Display the file name
            fileReferenceDisplay.textContent = fileName;
            noFileMessage.textContent = ""; // Clear the no file message
        } else {
            // If no file is selected, show a default message
            fileReferenceDisplay.textContent = ""; // Clear the file name
            noFileMessage.textContent = "{{ __('No file chosen') }}";
        }
    }
</script>
@endsection
