@extends('layouts.app')

@section('content')
 <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h2>Add Magazine</h2>

    <form action="{{ route('admin.magazines.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.magazine._form', ['magazine' => $magazine, 'mode' => 'create'])
        <button type="submit">Save</button>
    </form>
</div>
@endsection
