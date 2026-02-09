@extends('layouts.pdf')

@section('title', $article->article_title ?? 'Sadhana Weekly')
@section('og:title', $article->article_title ?? 'Sadhana Weekly')
@section('og:image', asset($article->article_image ?? $magazine->image))

@section('content')
    @if($article)
    <iframe
        src="{{ asset($article->article_pdf) }}"
        class="pdf-viewer"
        title="PDF Viewer">
    </iframe>
    @else
        <div style="text-align:center; padding:40px;">
            <h2>📄 Article not available</h2>
            <p>The requested PDF could not be found.</p>
        </div>
    @endif
@endsection
