@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')


<!-- ======================= Searchbar Banner ======================== -->
<!--             <div class="bg-cover py-5" style="background:#28b661 url(assets/img/bn-2.jpg) no-repeat;" data-overlay="2">
                <div class="ht-200"></div>
            </div> -->
            <!-- ======================= Searchbar Banner ======================== -->
            
            <section>
            
                <div class="container">
                
                    <!-- row Start -->
                    <div class="row">
                    
                        <!-- Blog Detail -->
                        <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                            <div class="article_detail_wrapss single_article_wrap format-standard">
                                <div class="article_body_wrap">
                                
                                    <div class="article_featured_image">
                                        <img class="img-fluid" src="{{ getImage('assets/images/frontend/blog/'.$blog->data_values->blog_image_1,'728x465') }}" alt="@lang('blog')">
                                    </div>
                                    
                                    <div class="article_top_info">
                                        <ul class="article_middle_info">
                                           <!--  <li><a href="#"><span class="icons"><i class="ti-user"></i></span>by Rosalina Doe</a></li> -->
                                            <li><a href="#"><span class="icons"><i class="ti-date-alt"></i></span> {{showDateTime($blog->data_valuescreated_at, 'd')}} {{showDateTime($blog->data_valuescreated_at, 'M')}} {{showDateTime($blog->data_valuescreated_at, 'Y')}}</a></li>
                                        </ul>
                                    </div>
                                    <h2 class="post-title">{{__($blog->data_values->title)}}</h2>
                                    <p>@php echo $blog->data_values->description @endphp</p>
                                </div>
                            </div>
                            
                            <!-- Author Detail -->
                            <!-- <div class="article_detail_wrapss single_article_wrap format-standard">
                                
                                <div class="article_posts_thumb">
                                    <span class="img"><img class="img-fluid" src="assets/img/user-1.jpg" alt=""></span>
                                    <h3 class="pa-name">Rosalina William</h3>
                                    <ul class="social-links">
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-behance"></i></a></li>
                                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                    <p class="pa-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                    aliqua. Ut enim ad minim veniam, quis nostrud
                                    exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
                                </div>
                                
                            </div> -->
                            
                            <!-- Blog Comment -->
                            <!-- <div class="article_detail_wrapss single_article_wrap format-standard">
                                
                                <div class="comment-area">
                                    <div class="all-comments">
                                        <h3 class="comments-title">05 Comments</h3>
                                        <div class="comment-list">
                                            <ul>
                                                <li class="article_comments_wrap">
                                                    <article>
                                                        <div class="article_comments_thumb">
                                                            <img src="assets/img/user-1.jpg" alt="">
                                                        </div>
                                                        <div class="comment-details">
                                                            <div class="comment-meta">
                                                                <div class="comment-left-meta">
                                                                    <h4 class="author-name">Rosalina Kelian <span class="selected"><i class="fas fa-bookmark"></i></span></h4>
                                                                    <div class="comment-date">19th May 2018</div>
                                                                </div>
                                                                <div class="comment-reply">
                                                                    <a href="#" class="reply"><span class="icona"><i class="ti-back-left"></i></span> Reply</a>
                                                                </div>
                                                            </div>
                                                            <div class="comment-text">
                                                                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                                    perspiciatis unde omnis iste natus error.</p>
                                                            </div>
                                                        </div>
                                                    </article>
                                                    <ul class="children">
                                                        <li class="article_comments_wrap">
                                                            <article>
                                                                <div class="article_comments_thumb">
                                                                    <img src="assets/img/user-2.jpg" alt="">
                                                                </div>
                                                                <div class="comment-details">
                                                                    <div class="comment-meta">
                                                                        <div class="comment-left-meta">
                                                                            <h4 class="author-name">Rosalina Kelian</h4>
                                                                            <div class="comment-date">19th May 2018</div>
                                                                        </div>
                                                                        <div class="comment-reply">
                                                                            <a href="#" class="reply"><span class="icons"><i class="ti-back-left"></i></span> Reply</a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="comment-text">
                                                                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                                            perspiciatis unde omnis iste natus error.</p>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                            <ul class="children">
                                                                <li class="article_comments_wrap">
                                                                    <article>
                                                                        <div class="article_comments_thumb">
                                                                            <img src="assets/img/user-4.jpg" alt="">
                                                                        </div>
                                                                        <div class="comment-details">
                                                                            <div class="comment-meta">
                                                                                <div class="comment-left-meta">
                                                                                    <h4 class="author-name">Rosalina Kelian</h4>
                                                                                    <div class="comment-date">19th May 2018</div>
                                                                                </div>
                                                                                <div class="comment-reply">
                                                                                    <a href="#" class="reply"><span class="icons"><i class="ti-back-left"></i></span> Reply</a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="comment-text">
                                                                                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                                                    perspiciatis unde omnis iste natus error.</p>
                                                                            </div>
                                                                        </div>
                                                                    </article>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="article_comments_wrap">
                                                    <article>
                                                        <div class="article_comments_thumb">
                                                            <img src="assets/img/user-5.jpg" alt="">
                                                        </div>
                                                        <div class="comment-details">
                                                            <div class="comment-meta">
                                                                <div class="comment-left-meta">
                                                                    <h4 class="author-name">Rosalina Kelian</h4>
                                                                    <div class="comment-date">19th May 2018</div>
                                                                </div>
                                                                <div class="comment-reply">
                                                                    <a href="#" class="reply"><span class="icons"><i class="ti-back-left"></i></span> Reply</a>
                                                                </div>
                                                            </div>
                                                            <div class="comment-text">
                                                                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                                    perspiciatis unde omnis iste natus error.</p>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-box submit-form">
                                        <h3 class="reply-title">Post Comment</h3>
                                        <div class="comment-form">
                                            <form action="#">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Your Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Your Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <textarea name="comment" class="form-control" cols="30" rows="6" placeholder="Type your comments...."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <a href="#" class="btn theme-bg text-white">Submit Now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div> -->
                            
                            
                        </div>
                        
                        <!-- Single blog Grid -->
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            
                            <!-- Searchbard -->
                            <!-- <div class="single_widgets widget_search">
                                <h4 class="title">Search</h4>
                                <form action="#" class="sidebar-search-form">
                                    <input type="search" name="search" placeholder="Search..">
                                    <button type="submit"><i class="ti-search"></i></button>
                                </form>
                            </div> -->

                            <!-- Categories -->
                            <div class="single_widgets widget_category">
                                <h4 class="title">@lang('CATEGORIES')</h4>
                                <ul>
                                    @foreach($categorys as $category)
                                    @php
                                    $services=DB::table('services')->where('category_id',$category->id)->count();
                                    @endphp
                                    <li><a href="{{route('service.category', [slug($category->name),$category->id])}}">{{$category->name}} <span>{{$services}}</span></a></li>
                                    @endforeach
                                    
                                </ul>
                            </div>
                            
                            <!-- Trending Posts -->
                            <div class="single_widgets widget_thumb_post">
                                <h4 class="title">@lang('FEATURED SERVICE')</h4>
                                <ul>
                                     @foreach($fservices as $ser)
                                    <li>
                                        <span class="left">
                                            <img src="{{getImage('assets/images/service/'.$ser->image, imagePath()['service']['size']) }}"  alt="@lang('service image')" class="">
                                        </span>
                                        <span class="right">
                                            <a class="feed-title" href="{{route('service.details', [slug($ser->title), encrypt($ser->id)])}}">{{__($ser->title)}}</a> 
                                            <span class="post-date"><i class="fa fa-thumbs-up"></i>({{__($ser->likes)}})</span>
                                        </span>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                            
                            <!-- Tags Cloud -->
                            <!-- <div class="single_widgets widget_tags">
                                <h4 class="title">Tags Cloud</h4>
                                <ul>
                                    <li><a href="#">Lifestyle</a></li>
                                    <li><a href="#">Travel</a></li>
                                    <li><a href="#">Fashion</a></li>
                                    <li><a href="#">Branding</a></li>
                                    <li><a href="#">Music</a></li>
                                </ul>
                            </div>
                             -->
                        </div>
                        
                    </div>
                    <!-- /row -->                   
                    
                </div>
                        
            </section>

@endsection
@section('script')

@endsection