@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Magazine</h2>

    <form action="{{ route('admin.magazines.update', $magazine->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.magazine._form', ['magazine' => $magazine, 'mode' => 'edit'])
        <button type="submit">Update</button>
    </form>
</div>
@endsection
