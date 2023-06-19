@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('static/css/posts.css') }}">
@endsection
@section('body')
    @include('navbar')
    <main>
        <div class="container pt-4">
            <div class="row">
                <div class="col-8">
                    <div class="d-flex">
                        <strong class="w-25 d-flex align-items-center text-center">Choose of category:</strong>
                        <select class="w-25 form-select" name="categories" id="categories">
                            <option value="0" selected>All</option>
                            @isset($categories)
                                @for ($i = 0; $i < count($categories); $i++)
                                    @if ($category_id == $categories[$i]['id'])
                                        <option value="{{ $categories[$i]['id'] }}" selected>
                                            {{ $categories[$i]['name'] }}
                                        </option>
                                    @else
                                        <option value="{{ $categories[$i]['id'] }}">
                                            {{ $categories[$i]['name'] }}
                                        </option>
                                    @endif
                                @endfor
                            @endisset
                        </select>
                        <div class="d-flex align-items-center px-3">
                            <div class="form-check pe-2">
                                @if ($sort == 'desc')
                                    <input class="form-check-input" type="radio" name="sort" id="desc"
                                        value="desc" checked>
                                @else
                                    <input class="form-check-input" type="radio" name="sort" id="desc"
                                        value="desc">
                                @endif
                                <label class="form-check-label" for="desc">
                                    Latest time
                                    <i class="fa-solid fa-arrow-down-short-wide"></i>
                                </label>
                            </div>
                            <div class="form-check">
                                @if ($sort == 'asc')
                                    <input class="form-check-input" type="radio" name="sort" id="asc"
                                        value="asc" checked>
                                @else
                                    <input class="form-check-input" type="radio" name="sort" id="asc"
                                        value="asc">
                                @endif
                                <label class="form-check-label" for="asc">
                                    Oldest time
                                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex">
                        <form method="POST" class="input-group mb-3" id="search_content">
                            <input id="search_token" type="search" class="form-control" placeholder="Search"
                                value="{{ $search_token }}" />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @if (Auth::check())
                <div class="card">
                    <div class="card-header">
                        <strong>Create new posts</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" name="create_post" enctype="multipart/form-data"
                            action="{{ route('posts.create') }}">
                            @csrf
                            <select class="w-25 form-select mb-2" name="category">
                                @if ($categories)
                                    @for ($i = 0; $i < count($categories); $i++)
                                        <option value="{{ $categories[$i]['id'] }}">
                                            {{ $categories[$i]['name'] }}
                                        </option>
                                    @endfor
                                @endif
                            </select>
                            <textarea class="form-control mb-2" name="content" placeholder="New posts"></textarea>
                            <input class="form-control mb-2" type="file" name="image">
                            <button class="form-control btn btn-outline-dark" type="submit" name="btn_submit">
                                Send posting
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            <!-- --------------show post------------------- -->
            @if (count($posts) != 0)
                @for ($i = 0; $i < count($posts); $i++)
                    <div class="card shadow-lg my-3" id="post-{{ $posts[$i]['id'] }}">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="border border-dark rounded-circle icon-svg">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <h5 class="d-flex align-items-center card-title text-margin ms-2">
                                    {{ $posts[$i]['name'] }}
                                </h5>
                                @if ($posts[$i]['is_creator'])
                                    <div class="ps-2" id="group-button-{{ $posts[$i]['id'] }}">
                                        <button class="btn btn-warning" id="edit-post-{{ $posts[$i]['id'] }}"
                                            onclick="post_edit_form({{ $posts[$i]['id'] }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-danger" id="delete-post-{{ $posts[$i]['id'] }}"
                                            onclick="post_delete({{ $posts[$i]['id'] }}, 0)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <small class="text-muted">Posting time: {{ $posts[$i]['updated_at'] }}</small>
                            <p class="card-text" id="content-text-{{ $posts[$i]['id'] }}">
                                {!! $posts[$i]['content'] !!}
                            </p>
                            @if ($posts[$i]['photo'] != null)
                                <img class="img-thumbnail" id="content-image-{{ $posts[$i]['id'] }}"
                                    src="{{ asset('media/posts/' . $posts[$i]['photo']) }}"
                                    alt="{{ $posts[$i]['photo'] }}">
                            @endif
                            <div id="input-file-{{ $posts[$i]['id'] }}"></div>

                            @if ($posts[$i]['is_liked'] == 1)
                                <span class="mt-3" id="like-text-{{ $posts[$i]['id'] }}">
                                    You and {{ $posts[$i]['post_likes_count'] - 1 }} people liked
                                </span>
                            @else
                                <span class="mt-3" id="like-text-{{ $posts[$i]['id'] }}">
                                    {{ $posts[$i]['post_likes_count'] }} people liked
                                </span>
                            @endif
                            <hr class="mb-2 mt-1">
                            <div class="d-flex">
                                <div class="feature-item pe-2">
                                    <button class="btn btn-outline-dark w-100" id="post-like-{{ $posts[$i]['id'] }}"
                                        onclick="like_post_change(
                                                        @if (Auth::check()) {{ Auth::user()->id }} @else 0 @endif,
                                                        {{ $posts[$i]['id'] }},
                                                        {{ $posts[$i]['post_likes_count'] }},
                                                        {{ $posts[$i]['is_liked'] }},
                                                    )">
                                        @if ($posts[$i]['is_liked'] == 1)
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        @else
                                            <i class="fa-regular fa-thumbs-up"></i>
                                        @endif
                                    </button>
                                </div>
                                <div class="feature-item ps-2">
                                    <a role="button" class="btn btn-outline-dark w-100"
                                        href="{{ route('posts.detail', ['post_id' => $posts[$i]->id]) }}">
                                        Comments ({{ $posts[$i]['comments_count'] }})
                                    </a>
                                </div>
                                <div class="feature-item px-2">
                                    <a role="button" href="#" class="btn btn-outline-dark w-100">
                                        Share
                                        <i class="fa-solid fa-share"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            @else
                <strong class="d-block text-center">There are no records</strong>
            @endif
        </div>
        <div class="container">
            <input type="hidden" value="" id="page_current">
            <input type="hidden" value="" id="page_count">
            <ul class="d-flex justify-content-center p-3" id="pagination" style="list-style: none;">
            </ul>
        </div>
    </main>
    @include('footer')
@endsection

@section('js')
    <script>
        //----------------------------------------------------------------------------------------
        @if (session('success'))
            let successMessage = "{{ session('success') }}";
            // load page -> display success message
            $(document).ready(function() {
                alert(successMessage);
            });
        @endif
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        //-----------------------------------Pagination--------------------------------------------------
        // filter
        let select_categories = document.querySelector('#categories');
        let option_category = document.getElementById("categories").value;

        let sort_name = document.querySelector('input[name="sort"]:checked').value;
        let sort = document.querySelectorAll('input[name="sort"]');

        let searchText = document.getElementById("search_token").value

        let page_count = {{ $page_count }};
        let page_current = {{ $page_current }};

        // add event for option
        select_categories.addEventListener('change', (event) => {
            option_category = event.target.value;
            page_current = 1;
            window.location.href = "{{ route('posts.index') }}?category=" + option_category + "&sort=" +
                sort_name + "&search_token=" + searchText + "&page=" + page_current;
        });
        sort.forEach(option => {
            option.addEventListener('change', (event) => {
                sort_name = event.target.value;
                page_current = 1;
                window.location.href = "{{ route('posts.index') }}?category=" + option_category +
                    "&sort=" + sort_name + "&search_token=" + searchText + "&page=" + page_current;
            });
        });
        let form = document.getElementById('search_content');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            let product_search = document.getElementById("search_token");
            let searchText = product_search.value.toLowerCase();
            page_current = 1;
            window.location.href = "{{ route('posts.index') }}?category=" + option_category + "&sort=" +
                sort_name + "&search_token=" + searchText + "&page=" + page_current;
        });

        // -----pagination-----
        let paginationUl = document.getElementById("pagination");
        // Tạo nút Previous
        let prevPageLi = document.createElement("li");
        prevPageLi.classList.add("page-item");
        prevPageLi.setAttribute("id", "pagination_prev");
        let prevPageLink = document.createElement("a");
        prevPageLink.classList.add("page-link");
        let prevPageIcon = document.createElement("i");
        prevPageIcon.classList.add("fa-solid", "fa-angle-left");
        prevPageLink.appendChild(prevPageIcon);
        prevPageLi.appendChild(prevPageLink);
        paginationUl.appendChild(prevPageLi);
        if (page_current == 1) {
            prevPageLi.classList.add("disabled");
        }
        prevPageLink.addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "{{ route('posts.index') }}?category=" + option_category + "&sort=" +
                sort_name + "&search_token=" + searchText + "&page=" + (page_current - 1);
        });

        // Tạo các nút trang
        for (let i = 1; i <= page_count; i++) {
            let pageLi = document.createElement("li");
            pageLi.classList.add("page-item");
            if (i == page_current) {
                pageLi.classList.add("active");
            }
            let pageLink = document.createElement("a");
            pageLink.classList.add("page-link");
            pageLink.setAttribute("style", "cursor: pointer;");
            // pageLink.setAttribute("href", "?page="+""+i);
            pageLink.addEventListener("click", function(event) {
                event.preventDefault();
                window.location.href = "{{ route('posts.index') }}?category=" + option_category + "&sort=" +
                    sort_name + "&search_token=" + searchText + "&page=" + i;
            });
            pageLink.innerText = i;
            pageLi.appendChild(pageLink);
            paginationUl.appendChild(pageLi);
        }

        // Tạo nút Next
        let nextPageLi = document.createElement("li");
        nextPageLi.classList.add("page-item");
        nextPageLi.setAttribute("id", "pagination_next");
        let nextPageLink = document.createElement("a");
        nextPageLink.classList.add("page-link");
        let nextPageIcon = document.createElement("i");
        nextPageIcon.classList.add("fa-solid", "fa-angle-right");
        nextPageLink.appendChild(nextPageIcon);
        nextPageLi.appendChild(nextPageLink);
        paginationUl.appendChild(nextPageLi);
        if (page_current == page_count || page_count == 0) {
            nextPageLi.classList.add("disabled");
        }
        nextPageLink.addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "{{ route('posts.index') }}?category=" + option_category + "&sort=" +
                sort_name + "&search_token=" + searchText + "&page=" + (page_current + 1);
        });
        // ----------------------------------------end pagination---------------------------------
        // -----------------------------------------action--------------------------------------------
        // like change
        let like_post_change = (user_id, post_id, like_count, is_like) => {
            if (user_id == 0) {
                document.location.href = "login";
            } else {
                let element = document.getElementById("post-like-" + post_id);
                let check_like = 1;
                if (element.innerHTML.includes("solid")) { // da like -> xoa like
                    payload = JSON.stringify({
                        'like': 'del',
                        'post_id': post_id
                    });
                    check_like = 0;
                } else { // chua like -> them like
                    payload = JSON.stringify({
                        'like': 'add',
                    });
                    check_like = 1;
                }
                $.ajax({
                    url: "posts/like/" + post_id,
                    type: 'POST',
                    data: payload,
                    // dataType : "json",
                    contentType: "application/json"
                }).done(function(message) {
                    console.log(message);
                    let text_like = document.getElementById('like-text-' + post_id);
                    console.log(text_like);
                    if (check_like == 0) {
                        element.innerHTML = '<i class="fa-regular fa-thumbs-up"></i>';
                        if (is_like == 1) // ban dau like
                            text_like.innerHTML = (like_count - 1) + " people liked";
                        else
                            text_like.innerHTML = (like_count) + " people liked";
                    } else {
                        element.innerHTML = '<i class="fa-solid fa-thumbs-up"></i>';
                        if (is_like == 0) // ban dau chua like
                            text_like.innerHTML = "You and " + (like_count) + " people liked";
                        else
                            text_like.innerHTML = "You and " + (like_count - 1) + " people liked";

                    }
                });
            }
        }

        // delete post
        let post_delete = (post_id, is_detail) => {
            let element = document.getElementById("post-" + post_id);
            let result = confirm("Do you want delete this post?");
            console.log(element)
            if (result) {
                $.ajax({
                    url: "posts/delete/" + post_id,
                    type: 'delete',
                    // contentType: "application/json"
                }).done(function(message) {
                    console.log(message);
                    // is_detail=0 -> post, is_detail=1 -> detail
                    if (is_detail == 0) {
                        element.parentNode.removeChild(element);
                    } else {
                        window.location.href = "posts"
                    }
                });
            }
        }
        // edit post
        let post_edit_form = (post_id) => {
            console.log(post_id)
            let text_element = document.getElementById("content-text-" + post_id);
            let text = text_element.textContent;
            let image_element = document.getElementById("input-file-" + post_id);
            let delete_post = document.getElementById("delete-post-" + post_id);
            let edit_post = document.getElementById("edit-post-" + post_id);

            let textarea_element = document.createElement("textarea");
            textarea_element.setAttribute("class", "form-control mb-2");
            textarea_element.setAttribute("id", "content-text-" + post_id);
            textarea_element.setAttribute("name", "content-" + post_id);
            textarea_element.textContent = text.trim();

            let input_img_element = document.createElement("input");
            input_img_element.setAttribute("class", "form-control my-2");
            input_img_element.setAttribute("type", "file");
            input_img_element.setAttribute("name", "image-" + post_id);

            let button_edit = document.createElement('button');
            button_edit.classList.add('btn', 'btn-warning');
            button_edit.id = 'edit-post-' + post_id;
            button_edit.addEventListener('click', function() {
                post_edit_form(post_id);
            });
            let icon_edit = document.createElement('i');
            icon_edit.classList.add('fa-solid', 'fa-pen-to-square');
            button_edit.appendChild(icon_edit);

            let button_delete = document.createElement('button');
            button_delete.classList.add('btn', 'btn-danger');
            button_delete.id = 'delete-post-' + post_id;
            button_delete.addEventListener('click', function() {
                post_delete(post_id, 0);
            });
            let icon_delete = document.createElement('i');
            icon_delete.classList.add('fa-solid', 'fa-trash');
            button_delete.appendChild(icon_delete);

            // --- update ------
            let button_save = document.createElement('button');
            button_save.classList.add('btn', 'btn-success');
            button_save.id = 'edit-post-' + post_id;
            button_save.addEventListener('click', function() {
                let formData = new FormData();
                let image_temp = document.getElementById("content-image-" + post_id);
                if (image_temp !== null) {
                    image_temp_src = image_temp.getAttribute("src");
                    image_name_array = image_temp_src.split("/");
                    image_name = image_name_array[image_name_array.length - 1];
                    console.log(image_name);
                    formData.append('image_temp', image_name);
                }

                formData.append('image', input_img_element.files[0]);
                formData.append('content', textarea_element.value);
                // formData.append('post_id', post_id);
                formData.append('_method', 'PUT');
                $.ajax({
                    url: "posts/update/" + post_id,
                    type: 'POST', // PUT
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response["status"].trim() === "error") {
                            alert("You need content or image");
                        } else {
                            let response_content = response["content"];
                            let response_image = response["image"];
                            let p = document.createElement("p");
                            p.classList.add("card-text");
                            p.id = "content-text-" + post_id;
                            p.innerHTML = response_content;
                            textarea_element.replaceWith(p);
                            textarea_element.remove();

                            if (response_image != "") {
                                let img = document.createElement("img");
                                img.classList.add("img-thumbnail");
                                img.id = "content-image-" + post_id;
                                img.src = "media/posts/" + response_image;
                                img.alt = "loading";
                                image_temp.replaceWith(img);
                            }
                            button_save.replaceWith(button_edit);
                            button_cancel.replaceWith(button_delete);
                            input_img_element.remove();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(errorThrown);
                    }
                });
            });
            let icon_save = document.createElement('i');
            icon_save.classList.add('fa-solid', 'fa-floppy-disk');
            button_save.appendChild(icon_save);

            // ---- cancel edit ----
            let button_cancel = document.createElement('button');
            button_cancel.classList.add('btn', 'btn-danger');
            button_cancel.id = 'edit-post-' + post_id;
            button_cancel.addEventListener('click', function() {
                button_save.replaceWith(button_edit);
                button_cancel.replaceWith(button_delete);
                textarea_element.replaceWith(text_element);
                input_img_element.remove();
            });
            let icon_cancel = document.createElement('i');
            icon_cancel.classList.add('fa-solid', 'fa-x');
            button_cancel.appendChild(icon_cancel);

            edit_post.replaceWith(button_save);
            delete_post.replaceWith(button_cancel);
            text_element.replaceWith(textarea_element);
            image_element.appendChild(input_img_element);
        }
    </script>
@endsection
