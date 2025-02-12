@extends('layouts.app')

@section('title', $page->name)

@section('content')
    <section class="achievements-section mt-4">
        <div class="container">
            {!!$page->content!!}
        </div>
    </section>
@endsection
