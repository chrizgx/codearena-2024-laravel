@extends('layouts.app')

@section('content')
<div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-balance text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">From the blog</h2>
      </div>
      @if(count($posts) > 0)
        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          @foreach ($posts as $post)
              <x-post :post="$post" />
          @endforeach
        </div>
        <div class="mt-10">
          {{ $posts->links() }}
        </div>
        <!-- Authors who have authored posts -->
        @unless($authors === null)
          <section id="authors" class="mx-auto mt-10 max-w-2xl lg:max-w-none">
            <h3 class="mb-8 text-xl text-center text-gray-900 font-bold" >Discover authors</h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-5 mx-auto lg:mx-0 ">
              @foreach ($authors as $author)
                @unless($author === null)
                  <a href="{{ route('author', $author) }}" class="flex gap-4 items-center w-full p-6 rounded-2xl bg-gray-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-start to-purple-end border-none border-gray-200 rounded-full shadow-2xl"></div>
                    <p>{{$author->name}}</p>
                  </a>
                @endunless
              @endforeach
            </div>
          </section>
        @endunless
      @else
        <p class="mt-8 mx-auto text-center text-gray-500">No posts found.</p>
      @endif
    </div>
  </div>

@endsection
