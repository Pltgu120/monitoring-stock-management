@extends('layouts.app')
@section('title', __("Detail Sparepart Consumable Part PLTGU Tanjung Uncang"))
@section('content')
<x-head-datatable/>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header">
                    <h4>{{ __("Detail Goods") }}</h4>
                </div>
                <div class="card-body">
                    {{-- <form action="{{ route('barang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') --}}

                        <div class="row">
                            <div class="col-md-7">

                                <div class="form-group">
                                    <label for="part_name" class="form-label">{{ __("Part Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="part_name" class="form-control" value="{{ $item->part_name }}" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="part_number" class="form-label">{{ __("Part Number") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="part_number" class="form-control" value="{{ $item->part_number }}" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="kode_rak" class="form-label">{{ __("Kode Rak") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_rak" class="form-control" value="{{ $item->kode_rak }}" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="date_items" class="form-label">{{ __("Keterangan") }} <span class="text-danger">*</span></label>
                                    <input type="date" name="date_items" class="form-control" value="{{ $item->date_items }}" required readonly> 
                                </div>

                                <div class="form-group">
                                    <label for="person_name" class="form-label">{{ __("Person Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="person_name" class="form-control" value="{{ $item->person_name }}" required readonly>
                                </div>
                                
                                <!-- Category Dropdown -->
                                {{-- <div class="form-group">
                                    <label for="category_id" class="form-label">{{ __("Types of Goods") }} <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required @readonly(true)>
                                        <option value="">-- {{ __("Select Category") }} --</option>
                                        @foreach ($categories as $jb)
                                            <option value="{{ $jb->id }}" {{ $item->category_id == $jb->id ? 'selected' : '' }}>{{ $jb->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <!-- Unit Dropdown -->
                                {{-- <div class="form-group">
                                    <label for="unit_id" class="form-label">{{ __("Unit of Goods") }} <span class="text-danger">*</span></label>
                                    <select name="unit_id" class="form-control" required @readonly(true)>
                                        <option value="">-- {{ __("Select Unit") }} --</option>
                                        @foreach ($units as $s)
                                            <option value="{{ $s->id }}" {{ $item->unit_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <!-- Brand Dropdown -->
                                {{-- <div class="form-group">
                                    <label for="brand_id" class="form-label">{{ __("Brand of Goods") }} <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-control" required @readonly(true)>
                                        <option value="">-- {{ __("Select Brand") }} --</option>
                                        @foreach ($brands as $m)
                                            <option value="{{ $m->id }}" {{ $item->brand_id == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="form-group">
                                    <label for="unit_name" class="form-label">{{ __("Unit Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_name" class="form-control" value="{{ $item->unit_name }}" min="0" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="brand_name" class="form-label">{{ __("Brand Name") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="brand_name" class="form-control" value="{{ $item->brand_name }}" min="0" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="initial_qty" class="form-label">{{ __("Initial Quantity") }} <span class="text-danger">*</span></label>
                                    <input type="number" name="initial_qty" class="form-control" value="{{ $item->initial_qty }}" min="0" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="quantity" class="form-label">{{ __("Quantity") }} <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="0" required readonly>
                                </div>

                                
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __("Photo") }} <span class="text-danger">*</span></label>
                                    
                                    <!-- Display the existing PDF file if available -->
                                    @if($item->image)
                                        <embed src="{{ asset('storage/barang/' . $item->image) }}" width="100%" height="300px" class="mb-2" />
                                    @endif
                                    <!-- File input for new photo -->
                                    {{-- <input class="form-control mt-5" id="GetFile" name="image" type="file" accept=".png,.jpeg,.jpg,.svg"> --}}
                                    <small class="form-text text-muted">{{ __("Upload a new photo if you want to change it.") }}</small>
                                </div>

                                <!-- File Reference Upload -->
                                <div class="form-group">
                                    <label for="file_reference" class="form-label">{{ __("File Reference (PDF)") }} <span class="text-danger">*</span></label>
                                    
                                    <!-- Display the existing PDF file if available -->
                                    @if($item->file_reference)
                                        <embed src="{{ asset('storage/pdf_files/' . $item->file_reference) }}" width="100%" height="300px" class="mb-2" />
                                    @endif

                                    {{-- <input class="form-control" id="GetFileReference" name="file_reference" type="file" accept=".pdf" onchange="displayFileReferenceName()"> --}}
                                    <small class="form-text text-muted">{{ __("Upload a PDF file as a reference.") }}</small>
                                    <div id="fileReferenceName" class="mt-2">
                                        <!-- Display the name of the existing file if available -->
                                        {{-- @if($item->file_reference)
                                            <strong id="fileReferenceDisplay">{{ basename($item->file_reference) }}</strong>
                                        @else
                                            <span id="noFileMessage">{{ __("No file chosen") }}</span>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('barang') }}" class="btn btn-secondary">{{ __("Back") }}</a>
                            <button type="submit" class="btn btn-success">{{ __("Update") }}</button>
                        </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
