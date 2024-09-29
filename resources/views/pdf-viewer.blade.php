@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1>View PDF</h1>
    <div class="pdf-container">
        <!-- Menggunakan iframe untuk embed PDF -->
        <iframe src="{{ $pdfPath }}" width="100%" height="600px" style="border: none;"></iframe>

        <!-- Atau, bisa juga menggunakan embed -->
        <!-- <embed src="{{ $pdfPath }}" width="100%" height="600px" type="application/pdf"> -->
    </div>
</div>
@endsection

<style>
    .pdf-container {
        border: 1px solid #ccc;
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>