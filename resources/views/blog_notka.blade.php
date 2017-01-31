@extends('layouts.base-layout')

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
                                    {!! pol_day($posts) !!}
                                    <span>{!! pol_month($posts) !!}</span>
                                        <strong>6 <img src="/img/page2_icon1.png" alt=""></strong>
                                    </div>
                                </div>

                            <a href="/blog/{{$posts['id']}}" class="lnk">{{$posts['title']}}</a>
                            <p class="post">Wysłane {{$posts['created_at']}}, w kategorii {!! category_name($posts, $categories) !!}
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
                            <li><a href="/blog/{{$category['name']}}">{{$category['name']}}</a></li>
                        @endforeach
                    </ul>

                    {{--<h2 class="center indent">Archiwum wpisów</h2>--}}
                    {{--<ul class="list1-1">--}}
                    {{--<li><a href="#">June 2013</a></li>--}}
                    {{--<li><a href="#">May 2013</a></li>--}}
                    {{--<li><a href="#">April 2013</a></li>--}}
                    {{--<li><a href="#">March 2013</a></li>--}}
                    {{--<li><a href="#">February 2013</a></li>--}}
                    {{--<li><a href="#">January 2013</a></li>--}}
                    {{--<li><a href="#">December 2012</a></li>--}}
                    {{--<li><a href="#">November 2012</a></li>--}}
                    {{--<li><a href="#">October 2012</a></li>--}}
                    {{--</ul>--}}
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





                <h3 style="text-align: center">Wasze Komentarze ({{count($comments)+count($anscomments)}})</h3>


                @foreach($comments as $comment)
              <div class="container" >

                  <div  class="container2" >
                      <img src="{{ asset('img/comment.png') }}" width="45" height="45">
                  </div>

               <div class="container3"  >



                        <div   class="container4" >
                         <b>~{{$comment->nick}}</b>
                       </div>




                        <div >
                           -{{ $comment->created_at->diffForHumans() }}    {{--<br>{{$comment->created_at}}--}}
                       </div><br><br><br>








                       <div   class="comments" >
                           {{$comment->comment}}

                           {{--Odpowiedz na komentarz--}}
                           <div class="dropdown" style="font-size: 12px"><br>
                               <button  class="btn btn-default" onclick="myFunction({{ $comment->id }})"><b>ODPOWIEDZ</b></button>
                               <div  class="dropdown-content myDropdown{{ $comment->id }}">
                                   <form onsubmit="return validateForm()" class="form-comment-hidden" method="post" action="{{route('blog.savecomments',$posts->id)}}">
                                       {{ csrf_field() }}
                                       <div class="form-group" >

                                           <textarea class="form-content" name="comment" rows="4" cols="110" placeholder="Komentarz"></textarea>
                                           <input type="hidden" name="comment_id" value="{{$comment->id}}">
                                           <input type="hidden" name="post_id" value="{{$comment->posts_id}}">



                                           <input style="width: auto" type="text" class="form-control form-nick" name="nick" placeholder="Podpisz się">
                                       </div>

                                       <button type="submit" class="btn btn-primary">Dodaj</button>
                                   </form>

                               </div>
                           </div>

                           <i><div class="answer">
                           @foreach($anscomments as $anscomment)

                               @if( $comment->id == $anscomment->parent)

                                           <div style="color:#555a78;"><b> ~{{ $anscomment->nick }} </b></div>


                                          <div> {{ $anscomment->created_at->diffForHumans() }} </div>



                               <div>Odp. do <b>~{{ $comment->nick }}</b><br>  {{ $anscomment->comment }}<br><br></div>

                               @endif
                                    @endforeach
                       </div> </i>




                       </div>




               </div>

              </div>
                <br><br><br><br>
                @endforeach




            </div>

        </div>

    </div>
@endsection
