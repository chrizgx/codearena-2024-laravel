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

                    <!-- Exist to be targetted by 'reply' button of a TOP LEVEL COMMENT -->
                    <div id="comment-{{$comment->id}}"></div>

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
                </div>
            @endforeach
        </div>
    @else
        <p class="mt-8 mx-auto text-center text-gray-500">Be the first to comment...</p>
    @endif

    <form id="reply-template" class="p-1 reply-form" action="{{ route('comment', ['post' => $post->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="parent_id" value=""> <!-- dynamically set by js -> value = $comment->id -->
        <input type="hidden" name="reference_id" value="">
        <input type="text" name="name" placeholder="Name..." class="w-full p-2 pl-4 border border-gray-200 rounded-xl" required>
        <textarea name="body" class="w-full max-h-52 p-4 mt-2 border border-gray-200 rounded-xl" placeholder="Add a comment..." required></textarea>
        <button type="submit" class="mt-4 px-6 py-2 text-white bg-gradient-to-r from-purple-start to-purple-end rounded-xl">Post Reply</button>
        <button type="cancel" class="mt-4 px-6 py-2 text-white bg-gradient-to-r from-red-500 to-red-400 rounded-xl">Cancel</button>
    </form>

    <div>
        <form id="comment-form" action="{{ route('comment', ['post' => $post->id]) }}" method="POST">
            @csrf
            <input type="text" id="name" required name="name" placeholder="Name..." class="w-full p-2 pl-4 border border-gray-200 rounded-xl">
            <textarea name="body" id="body" required class="w-full max-h-80 p-4 mt-2 border border-gray-200 rounded-xl" placeholder="Add a comment..."></textarea>
            <button type="submit" class="mt-4 px-6 py-2 text-white bg-gradient-to-r from-purple-start to-purple-end rounded-xl">Post Comment</button>
        </form>
    </div>
</div>
<style>
    .reply-form {
        max-height: 0;
        overflow-y: hidden;
        opacity: 0;
        transition: max-height 0.5s linear, opacity 0.7s linear;
    }

    .reply-form.show {
        max-height: 500px;
        opacity: 1;
    }
</style>
<script>
    function showReplyForm(commentId, referenceId) {
        // If form already exists in current comment, do nothing
        if (document.getElementById(`reply-form-${referenceId}`)) return;

        const form = document.getElementById('reply-template').cloneNode(true);
        // form.style.display = 'block';
        setTimeout(() => {
            form.classList.add('show');
        }, 10);
        form.id = 'reply-form-' + referenceId;

        // Set up cancel button to hide the correct form
        form.querySelector('button[type="cancel"]').onclick = () => hideReplyForm(referenceId);

        // Set up form data
        form.querySelector('input[name="parent_id"]').value = commentId;
        form.querySelector('input[name="reference_id"]').value = referenceId;

        // Display form
        const commentElement = (commentId != referenceId) ? document.getElementById(`reply-${referenceId}`) : document.getElementById(`comment-${commentId}`);
        commentElement.appendChild(form);
    }

    function hideReplyForm(commentId) {
        const form = document.getElementById(`reply-form-${commentId}`);
        form.classList.remove('show');
        setTimeout(() => form.remove(), 550);
    }
</script>