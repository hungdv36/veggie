<ul>
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= floor($product->average_rating))
            <li><a href="javascript:void(0)"><i class="fas fa-star"></i></a></li>
        @elseif($i == ceil($product->average_rating) && $product->average_rating - floor($product->average_rating) >= 0.5)
            <li><a href="javascript:void(0)"><i class="fas fa-star-half-alt"></i></a></li>
        @else
            <li><a href="javascript:void(0)"><i class="far fa-star"></i></a></li>
        @endif
    @endfor
    <li class="review-total"> <a href="javascript:void(0)"> ( {{ $product->reviews->count() }} Đánh
            giá )</a></li>
</ul>
<style>
    /* ----- Phần đánh giá sao ----- */
.product-rating ul {
    list-style: none;        /* bỏ dấu chấm của ul */
    padding: 0;
    margin: 0;
    display: flex;           /* cho nằm ngang */
    justify-content: center; /* căn giữa các sao */
    align-items: center;
    gap: 4px;                /* khoảng cách giữa các sao */
}

.product-rating ul li {
    display: inline-block;
}

.product-rating ul li a {
    color: #f6c000; /* màu vàng cho sao */
    font-size: 16px;
    text-decoration: none;
}

.product-rating ul li.review-total a {
    color: #333; /* màu chữ đánh giá */
    font-size: 14px;
    margin-left: 6px;
}

</style>