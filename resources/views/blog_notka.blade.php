@extends('layouts.base-layout')

@section('pageTitle'){{$posts['seo_title']}}@endsection

@section('description'){{$posts['meta_description']}}@endsection

@section('keywords'){{$posts['meta_keywords']}}@endsection

@section('content')
    <div class="global indent">
        <!--content-->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 thumb-box4">
                    <h2 class="center indent">blog</h2>
                    <div class="thumb-pad7 clearfix">
                        <div class="extra-wrap">
                            <div>
                                <div class="badge">
                                    {!! polDay($posts) !!}
                                    <span>{!! polMonth($posts) !!}</span>
                                    <strong>{!! commentsNumber($posts) !!}<img src="/img/page2_icon1.png"
                                                                               alt=""></strong>
                                </div>
                            </div>
                            <a href="/blog/notka/{{$posts['slug']}}" class="lnk">{{$posts['title']}}</a>
                            <p class="post">Wysłane {{$posts['created_at']}}, w
                                kategorii {!! categoryName($posts, $categories) !!}
                                <br></p>
                            <div class="thumbnail">
                                <div class="caption">
                                    <p> {!!$posts['body']!!}.</p>
                                </div>
                            </div>
                            <div class="btn-default.btn1" , style="text-align:center;">
                                {{--{{ $posts->render() }}--}}

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 thumb-box4">
                    <h2 class="center indent">Kategorie wpisów</h2>
                    <ul class="list1-1 indent">
                        @foreach($categories as $category)
                            <li><a href="/blog/kategoria/{{$category['name']}}">{{$category['name']}}</a></li>
                        @endforeach
                    </ul>
                    <h2 class="center indent">Najnowsze wpisy</h2>
                    <ul class="list1-1 indent">
                        @foreach($fiveLastPosts as $post)
                            <li><a href="/blog/notka/{{$post['slug']}}">{{$post['title']}}</a></li>
                        @endforeach
                    </ul>
                    <h2 class="center indent">Archiwum wpisów</h2>

                    <ul class="list1-1 indent">
                        @foreach($postsByDates as $post)
                            <li>
                                <a href="/blog/archiwum/{{$post->year}}/{{$post->month}}">{!! fullMonth($post) !!} {{$post->year}}  </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>


            @if( Session::has('message') )
                <p class="alert alert-info">{{ Session::get('message') }}</p>
            @endif
            <div class="thumb-pad7 clearfix">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="extra-wrap">
                    {!! Form::open([
                     'url' => '/blog/savecomments/'.$posts->id,
                     'id' => 'comment-create',
                     'class' => 'form-horizontal col-md-6',
                     'files' => true,
                     'role'  => 'form',
                     'method'  => 'post'
                     ]) !!}
                    <div class="inner-wrap">
                        {!! Form::textarea('comment', null, ['class'=>'form-control','placeholder'=>'Twój komentarz']) !!}
                    </div>
                    {!! Form::hidden('post_id', $posts->id, ['class'=>'form-control']) !!}
                    <div style="margin-top: 20px" class="inner-wrap">
                        {!! Form::text('nick', null, ['class'=>'form-control','placeholder'=>'Podpisz się']) !!}
                    </div>
                    <div style="margin-top: 20px" class="button-section">
                        {!! Form::submit(trans('Dodaj'), ['id' => 'add-comment', 'class'=>'btn btn-primary']) !!}
                        {{--{!! link_to(URL::previous(), 'Powrót', ['class' => 'btn btn-default']) !!}--}}
                        {!! Form::close() !!}
                    </div>
                    <div class="btn-default.btn1" , style="text-align:center;">
                        {{--{{ $posts->render() }}--}}
                    </div>
                </div>
            </div>
            <h3 style="text-align: center">Wasze Komentarze ({{$commentsNumber}})</h3>
            @foreach($comments as $comment)
                <div class="container_comments">
                    <div class="container2_comments">
                        <img src="{{ asset('img/comment.png') }}" width="45" height="45">
                    </div>
                    <div class="container3_comments">
                        <div class="container4_comments">
                            <b>~{{$comment['main']->nick}}</b>
                        </div>
                        <div>
                            --{{ $comment['main']->created_at->diffForHumans() }}    {{--<br>{{$comment->created_at}}--}}
                        </div>
                        <br><br><br>
                        <div class="comments">
                            {{$comment['main']->comment}}

                            {{--Odpowiedz na komentarz--}}
                            <div class="dropdown_comments" style="font-size: 12px"><br>
                                <button class="btn btn-default" onclick="myFunction({{ $comment['main']->id }})"><b>ODPOWIEDZ</b>
                                </button>
                                <div style="display:none;"
                                     class="dropdown-content myDropdown{{ $comment['main']->id }}">
                                    <form onsubmit="return validateForm()" class="form-comment-hidden" method="post"
                                          action="{{route('blog.savecomments',$posts->id)}}">
                                        {{ csrf_field() }}
                                        <div class="form-group">

                                            <textarea class="form-content" name="comment" rows="4" cols="110"
                                                      placeholder="Komentarz"></textarea>
                                            <input type="hidden" name="comment_id" value="{{$comment['main']->id}}">
                                            <input type="hidden" name="post_id" value="{{$comment['main']->posts_id}}">
                                            <input style="width: auto" type="text" class="form-control form-nick"
                                                   name="nick" placeholder="Podpisz się">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Dodaj</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <i>
                                <div class="answer">
                                    @if(isSet($comment['child']))
                                        @foreach($comment['child']  as $child)
                                            <div class="answer1" style="color:#555a78"><b> ~{{ $child->nick }} </b>
                                            </div>
                                            <div class="answer1"> --{{ $child->created_at->diffForHumans() }} </div><br>
                                            <br>
                                            <div>  {{ $child->comment }}
                                                <div class="dropdown" style="font-size: 12px"><br>
                                                    <button class="btn btn-default"
                                                            onclick="myFunction({{ $child->id }})"><b>ODPOWIEDZ</b>
                                                    </button>
                                                    <div style="display:none;"
                                                         class="dropdown-content myDropdown{{ $child->id }}">
                                                        <form onsubmit="return validateForm()"
                                                              class="form-comment-hidden" method="post"
                                                              action="{{route('blog.savecomments',$posts->id)}}">
                                                            {{ csrf_field() }}
                                                            <div class="form-group">
                                                                <textarea class="form-content" name="comment" rows="4"
                                                                          cols="110" placeholder="Komentarz"></textarea>
                                                                <input type="hidden" name="comment_id"
                                                                       value="{{$comment['main']->id}}">
                                                                <input type="hidden" name="post_id"
                                                                       value="{{$child->posts_id}}">
                                                                <input style="width: auto" type="text"
                                                                       class="form-control form-nick" name="nick"
                                                                       placeholder="Podpisz się">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Dodaj</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <br><br>
                                        @endforeach
                                    @endif
                                </div>
                            </i>
                        </div>
                    </div>
                </div>
                <br><br><br><br>
            @endforeach
        </div>
    </div>
    </div>
@endsection
