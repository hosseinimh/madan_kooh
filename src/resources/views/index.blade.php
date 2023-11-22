@extends('_layout.index')

@section('style')
    @php
    try {
    $filename = 'assets/css/style_rtl.css';
    $fileModified = substr(md5(filemtime($filename)), 0, 6);
    } catch (\Exception) {
    $fileModified = '';
    }
    @endphp
    <link href="{{$THEME::CSS_PATH}}/style_rtl.css?v={{$fileModified}}" rel="stylesheet">
@endsection

@section('content')
    <div id="root"></div>
    @php
    try {
        $filename = 'assets/js/index.js';
        $fileModified = substr(md5(filemtime($filename)), 0, 6);
    } catch (\Exception) {
        $fileModified = '';
    }
    @endphp
    <script src="{{$THEME::JS_PATH}}/index.js?v={{$fileModified}}"></script>
@endsection