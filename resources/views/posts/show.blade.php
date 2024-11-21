@extends('layouts.app')

@section('content')
    <div class="bg-white px-6 py-32 lg:px-8">
        <div class="mx-auto max-w-3xl text-base/7 text-gray-700">
            <p class="text-base/7 font-semibold text-indigo-600">Introducing</p>
            <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">{{ $post->title }}</h1>
            <p class="mt-6 text-xl/8">{{ $post->excerpt }}</p>
            <figure class="mt-16">
                <img class="aspect-video rounded-xl bg-gray-50 object-cover"
                    src="{{ $post->image }}"
                    alt="">
            </figure>
            <div class="mt-16 max-w-2xl">
                <p class="mt-6">{{ $post->body }}</p>
            </div>
            <div>
                By {{ $post->author->name }}
            </div>
        </div>
        <div class="mt-16">
            <x-comment-section :comments="$post->comments" :post="$post" />
        </div>
    </div>
@endsection
