<div class="mx-auto max-w-3xl">
    @if(count($comments) > 0)
        <h3 class="">Comments</h3>
        <div class="flex flex-col gap-1 mx-auto mt-10 mb-6">
            @foreach ($comments as $comment)
                <div class="p-4 px-6 bg-gradient-to-r from-purple-transparent-start to-purple-transparent-end rounded-3xl">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-r from-purple-start to-purple-end bg-opacity-10"></div>
                        <p class="">{{$comment->name}}</p>
                        <span class="grow"></span>
                        <p class="text-xs text-gray-600">{{$comment->created_at->diffForHumans()}}</p>
                    </div>
                    <p class="ml-12 text-gray-700 font-light">{{$comment->body}}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="mt-8 mx-auto text-center text-gray-500">Be the first to comment...</p>
    @endif

    <div>
        <form id="comment-form" action="{{ route('comment') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="text" id="name" required name="name" placeholder="Name..." class="w-full p-2 pl-4 border border-gray-200 rounded-xl">
            <textarea name="body" id="body" required class="w-full p-4 mt-2 border border-gray-200 rounded-xl" placeholder="Add a comment..."></textarea>
            <button type="submit" class="mt-4 px-6 py-2 text-white bg-gradient-to-r from-purple-start to-purple-end rounded-xl">Post Comment</button>
        </form>
    </div>
</div>