@extends('layouts.app')
@section('title', isset($magazine) ? 'Edit Magazine' : 'Add Magazine')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            {{-- Alert Messages --}}
            @include('common.alert')
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ isset($magazine) ? 'Edit' : 'Add' }} Magazine</h4>
                        <div class="page-title-right">
                            <a href="{{ route('magazine.index') }}" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ isset($magazine) ? route('magazine.update', $magazine->id) : route('magazine.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($magazine))
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Title <span style="color:red;">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $magazine->title ?? '') }}">
                        @if($errors->has('title'))
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Image <span style="color:red;">*</span></label>
                        <input type="file" name="image" class="form-control">
                        @if(isset($magazine) && $magazine->image)
                            <img src="{{ asset($magazine->image) }}" width="80" class="mt-2">
                        @endif
                        @if($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">PDF <span style="color:red;">*</span></label>
                        <input type="file" name="pdf" class="form-control">
                        @if(isset($magazine) && $magazine->pdf)
                            <a href="{{ asset($magazine->pdf) }}" target="_blank">Current PDF</a>
                        @endif
                        @if($errors->has('pdf'))
                            <span class="text-danger">{{ $errors->first('pdf') }}</span>
                        @endif
                    </div>
                    <!--<div class="col-md-6 mb-4">
                        <label class="form-label">Month <span style="color:red;">*</span></label>
                        <input type="text" name="month" class="form-control" value="{{ old('month', $magazine->month ?? '') }}">
                        @if($errors->has('month'))
                            <span class="text-danger">{{ $errors->first('month') }}</span>
                        @endif
                    </div>-->
                    <!--<div class="col-md-6 mb-4">
                        <label class="form-label">
                            Month <span style="color:red;">*</span>
                        </label>
                    
                        <select name="month" class="form-control">
                            <option value="">-- Select Month --</option>
                    
                            @php
                                $months = [
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December',
                                ];
                    
                                $selectedMonth = old('month', $magazine->month ?? '');
                            @endphp
                    
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}"
                                    {{ (int)$selectedMonth === $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    
                        @if($errors->has('month'))
                            <span class="text-danger">{{ $errors->first('month') }}</span>
                        @endif
                    </div>


                    <div class="col-md-6 mb-4">
                        <label class="form-label">Year <span style="color:red;">*</span></label>
                        <input type="number" name="year" class="form-control" value="{{ old('year', $magazine->year ?? '') }}">
                        @if($errors->has('year'))
                            <span class="text-danger">{{ $errors->first('year') }}</span>
                        @endif
                    </div>-->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Publish Date <span style="color:red;">*</span></label>
                        <input type="date" name="publish_date" class="form-control" value="{{ old('year', $magazine->publish_date ?? '') }}">
                        @if($errors->has('publish_date'))
                            <span class="text-danger">{{ $errors->first('publish_date') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status</label><br>
                        <input type="checkbox" name="iStatus" value="1" {{ old('iStatus', $magazine->iStatus ?? 1) ? 'checked' : '' }}> Active
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">{{ isset($magazine) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
