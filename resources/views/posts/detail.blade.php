@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('static/css/posts.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/comments_show') }}">
@endsection
@section('body')
    @include('navbar')
    <main>
        <div class="container">
            <div class="card shadow-lg my-3" id="{{ $post_detail['id'] }}">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="border border-dark rounded-circle icon-svg">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h5 class="d-flex align-items-center card-title text-margin ms-2"> {{ $post_detail['name'] }}</h5>
                        @if ($post_detail['is_creator'])
                            <div class="ps-2">
                                <button class="btn btn-warning" id="edit-post-{{ $post_detail['id'] }}; ?>"
                                    onclick="post_edit_form({{ $post_detail['id'] }})">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-danger" id="delete-post-{{ $post_detail['id'] }}"
                                    onclick="post_delete({{ $post_detail['id'] }}, 1)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <small class="text-muted">Posting time: {{ $post_detail['updated_at'] }}</small>
                    <p class="card-text" id="content-text-{{ $post_detail['id'] }}">{!! $post_detail['content'] !!}</p>
                    @if ($post_detail['photo'] != null)
                        <img class="img-thumbnail" id="content-image-{{ $post_detail['id'] }}"
                            src="{{ asset('media/posts/' . $ppost_detail['photo']) }}" alt="{{ $post_detail['photo'] }}">
                    @endif
                    <div id="input-file-{{ $post_detail['id'] }}"></div>
                    <hr>

                    @if ($post_detail['is_liked'] == 1)
                        <span class="mt-3" id="like-text-{{ $post_detail['id'] }}">
                            You and {{ $post_detail['post_likes_count'] - 1 }} people liked
                        </span>
                    @else
                        <span class="mt-3" id="like-text-{{ $post_detail['id'] }}">
                            {{ $post_detail['post_likes_count'] }} people liked
                        </span>
                    @endif

                    <div class="d-flex">
                        <div class="feature-item pe-2">
                            <button class="btn btn-outline-dark w-100" id="post-like-{{ $post_detail['id'] }}"
                                onclick="like_post_change(
                                            @if (Auth::check()) {{ Auth::user()->id }} @else 0 @endif,
                                            {{ $post_detail['id'] }},
                                            {{ $post_detail['post_likes_count'] }},
                                            {{ $post_detail['is_liked'] }},
                                        )">
                                @if ($post_detail['is_liked'] == 1)
                                    <i class="fa-solid fa-thumbs-up"></i>
                                @else
                                    <i class="fa-regular fa-thumbs-up"></i>
                                @endif
                            </button>
                        </div>
                        <div class="feature-item ps-2">
                            <a role="button" class="btn btn-outline-dark w-100"
                                href="{{ route('posts.detail', ['post_id' => $post_detail->id]) }}">
                                Comments ({{ $post_detail['comments_count'] }})
                            </a>
                        </div>
                        <div class="feature-item px-2">
                            <a role="button" href="#" class="btn btn-outline-dark w-100">
                                Share
                                <i class="fa-solid fa-share"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <!-- comment -->
                        <div class="card shadow-0 border my-3" style="background-color: #f0f2f5;" id="comment">
                            <div class="card-body p-4">
                                @if (Auth::check())
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Comment & Question</strong>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" id="comment-add-0" enctype="multipart/form-data"
                                                comment-path="0000">
                                                <textarea class="form-control mb-2" id="content_0" placeholder="New comment or question"></textarea>
                                                <!-- <input class="form-control mb-2" type="file" name="image"> -->
                                                <button class="form-control btn btn-outline-dark" type="submit"
                                                    name="btn_submit">
                                                    Send
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" id="post_id" value="{{ $post_detail['id'] }}">
                                <div id="comments"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('footer')
@endsection
@section('js')
    <script src="media/js/comments.js"></script>
    <script src="media/js/post_action.js"></script>
@endsection
