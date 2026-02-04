@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Customer' : 'Add Customer')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            @include('common.alert')

<div class="card">
<div class="card-body">
    
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4>{{ isset($customer) ? 'Edit' : 'Add' }} Customer</h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <form action="{{ isset($customer) ? route('customer.update', $customer->customer_id) : route('customer.store') }}" method="POST">
                @csrf
                @if(isset($customer))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Customer Name <span style="color:red;">*</span></label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name ?? '') }}">
                        @if($errors->has('customer_name'))
                            <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                        @endif
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Mobile Number <span style="color:red;">*</span></label>
                        <input type="text" name="customer_mobile" class="form-control" value="{{ old('customer_mobile', $customer->customer_mobile ?? '') }}">
                        @if($errors->has('customer_mobile'))
                            <span class="text-danger">{{ $errors->first('customer_mobile') }}</span>
                        @endif
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Email <span style="color:red;">*</span></label>
                        <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $customer->customer_email ?? '') }}">
                        @if($errors->has('customer_email'))
                            <span class="text-danger">{{ $errors->first('customer_email') }}</span>
                        @endif
                    </div>
                    
                    @if(!isset($customer))
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Password <span style="color:red;">*</span></label>
                        <input type="password" name="password" class="form-control">
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status</label><br>
                        <input type="checkbox" name="iStatus" value="1" {{ old('iStatus', $customer->iStatus ?? 1) ? 'checked' : '' }}> Active
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success">{{ isset($customer) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
</div>
</div>
@endsection
