<div class="mx-auto max-w-3xl">
    @if(count($comments) > 0)
        <h3 class="">Comments</h3>
        <div class="flex flex-col gap-1 mx-auto mt-10 mb-6">
            @foreach ($comments as $comment)
                <div class="group/main p-4 px-6 bg-gradient-to-r from-purple-transparent-start to-purple-transparent-end rounded-3xl">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-r from-purple-start to-purple-end bg-opacity-10"></div>
                        <div class="flex flex-col sm:flex-row sm:gap-2">
                            <p class="">{{$comment->name}}</p>
                            <p class="text-xs sm:self-center text-gray-600">{{$comment->created_at->diffForHumans()}}</p>
                        </div>
                        <span class="grow"></span>
                        <button onclick="showReplyForm({{$comment->id}}, {{$comment->id}})" class="bg-gradient-to-r from-purple-start to-purple-end bg-clip-text text-transparent opacity-0 group-hover/main:opacity-90 max-md:opacity-90 hover:!opacity-100 transition-all">Reply</button>
                        <form action="{{ route('comment.delete', $comment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mr-2 opacity-0 group-hover/main:opacity-100 max-md:opacity-100 text-red-400 hover:text-red-600 transition-all" confirm="Are you sure?">Delete</button>
                        </form>
                    </div>
                    <p class="sm:ml-12 text-gray-700 font-light">{{$comment->body}}</p>

                    @if(count($comment->replies) > 0)
                        @foreach ($comment->replies as $reply)
                            <div id="reply-{{$reply->id}}"  class="group p-2 sm:ml-9 my-4 bg-transparent from-purple-transparent-start to-purple-transparent-end target:bg-gradient-to-r rounded-3xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-r from-purple-start to-purple-end bg-opacity-10"></div>
                                    <div class="flex flex-col sm:flex-row sm:gap-2">
                                        <p class="">{{$reply->name}}</p>
                                        <p class="-mt-0.5 sm:mt-0 sm:self-center text-xs text-gray-600">{{$reply->created_at->diffForHumans()}}</p>
                                    </div>
                                    <span class="grow"></span>
                                    <button onclick="showReplyForm({{$comment->id}}, {{$reply->id}})" class="bg-gradient-to-r from-purple-start to-purple-end bg-clip-text text-transparent opacity-0 group-hover:opacity-90 max-md:opacity-90 hover:!opacity-100 transition-all">Reply</button>
                                    <form action="{{ route('comment.delete', $reply) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="opacity-0 group-hover:opacity-100 max-md:opacity-100 text-red-400 hover:text-red-600 transition-all" confirm="Are you sure?">Delete</button>
                                    </form>
                                </div>
                                @if ($reply->reference_id && $reply->reference_id !== $comment->id)
                                    <a href="#reply-{{$reply->reference_id}}" class="block -mt-1 sm:-mt-2 ml-11 text-sm font-light">Replying to <span class="bg-gradient-to-r from-purple-start to-purple-end bg-clip-text text-transparent">{{$reply->reference->name}}</span> <span class="text-gray-500">"{{Str::limit($reply->reference->body, 12)}}"</span></a>
                                @endif
                                <p class="mt-2 ml-11 font-light">{{$reply->body}}</p>
                            </div>
                        @endforeach
                    @endif

                    <form id="reply-{{$comment->id}}" class="mt-3 hidden" action="{{ route('comment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <input type="hidden" name="reference_id" value="">
                        <input type="text" name="name" placeholder="Name..." class="w-full p-2 pl-4 border border-gray-200 rounded-xl" required>
                        <textarea name="body" class="w-full p-4 mt-2 border border-gray-200 rounded-xl" placeholder="Add a comment..." required></textarea>
                        <button type="submit" class="mt-4 px-6 py-2 text-white bg-gradient-to-r from-purple-start to-purple-end rounded-xl">Post Reply</button>
                        <button class="mt-4 px-6 py-2 text-white bg-gradient-to-r from-red-500 to-red-400 rounded-xl" onclick="hideReplyForm({{$comment->id}})">Cancel</button>
                    </form>
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
<script>
    function showReplyForm(commentId, referenceId) {
        const form = document.getElementById(`reply-${commentId}`);
        form.style.display = 'block';
        form.querySelector('input[name="reference_id"]').value = referenceId;
    }

    function hideReplyForm(commentId) {
        const form = document.getElementById(`reply-${commentId}`);
        form.style.display = 'none';
    }
</script>