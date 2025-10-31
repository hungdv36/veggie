<ul>
    @foreach ($product->reviews as $review)
     <li>
                                                    <div class="ltn__comment-item clearfix">
                                                        <div class="ltn__commenter-img">
                                                           <img src="{{ asset('storage/uploads/users/' . ($review->user->avatar ?? 'default-avatar.png')) }}" 
     alt="{{ $review->user->name }}" 
     style="width:60px; height:60px; border-radius:50%; object-fit:cover;">

                                                        </div>
                                                        <div class="ltn__commenter-comment">
                                                            <h6>{{ $review->user->name }}</h6>
                                                            <div class="product-ratting">
                                                                <ul>
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                     <li><a href="javascript:void(0)">
                                                                        <i class="{{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star' }}"></i>
                                                                    </a>
                                                                    </li>
                                                                    @endfor
                                                                   
                                                                    
                                                                </ul>
                                                            </div>
                                                            <p>{{ $review->comment }}</p>
                                                            <span class="ltn__comment-reply-btn">{{ $review->created_at->format('d-m-Y') }}</span>
                                                        </div>
                                                    </div>
                                                </li> 
    @endforeach
</ul>