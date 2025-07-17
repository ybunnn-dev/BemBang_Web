<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content" style="font-family: 'Poppins', sans-serif; padding-left: 30px; padding-right: 30px; margin-top: 3%;">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0; padding-top: 30px !important">
                <h5 class="modal-title" id="reviewModalLabel" style="display: flex; gap: 10px; align-items: center; color: #566A7F; font-weight: 600;">
                    <img src="{{ asset('images/comments.svg') }}" width="25px" height="25px"> Reviews
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 600px; overflow-y: auto; color: #697A8D;">
                <div class="container-fluid">
                    <div class="row rating-section" style="padding-bottom: 20px; border-bottom: 1px solid #dee2e6; margin-bottom: 20px;">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                @php
                                    $averageRating = $rev->isNotEmpty() ? $rev->avg('rate') : 0;
                                    $reviewCount = $rev->count();
                                @endphp
                                <span class="rating-large" style="font-size: 3rem; font-weight: 600; color: #566A7F; margin-right: 15px;">{{ number_format($averageRating, 1) }}</span>
                                <div>
                                    <div class="stars" style="display: flex; gap: 2px;">
                                        @php
                                            $fullStars = floor($averageRating);
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $fullStars)
                                                <img src="{{ asset('images/stars/active.svg') }}" width="20px" height="20px">
                                            @else
                                                <img src="{{ asset('images/stars/inactive.svg') }}" width="20px" height="20px">
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="review-count" style="color: #697A8D; font-size: 16px; margin-top: -2px;">{{ $reviewCount }} Review{{ $reviewCount != 1 ? 's' : '' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 filter-reviews" style="text-align: right; padding-top: 8px;">
                            <div class="form-group">
                                <label for="filterSelect" class="form-label" style="color: #566A7F;">Filter Reviews</label>
                                <select class="form-select" id="filterSelect" style="width: 160px; color: #697A8D; margin-left: 170px">
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                    <option value="0" selected>All Reviews</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Individual Reviews -->
                    @foreach($rev as $review)
                        @php
                            $createdAt = $review->created_at->toDateTime();
                            $createdAt->setTimezone(new DateTimeZone('Asia/Manila'));
                            $formattedDate = $createdAt->format('F j, Y g:i A');
                        @endphp
                        <div class="review-item" data-rating="{{ $review->rate }}" style="margin-bottom: 30px;">
                            <div class="review-header" style="display: flex; align-items: center; margin-bottom: 10px;">
                                <div class="avatar" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('images/prof-cir.svg') }}" alt="User avatar" style="width: 40px; height: 40px;">
                                </div>
                                <div class="review-author" style="margin-left: 15px;">
                                    <div>
                                        <strong style="color: #566A7F;">{{ $review->guest_id->firstName ?? '' }} {{ $review->guest_id->lastName ?? '' }}</strong>
                                        <div class="user-stars" style="display: flex; gap: 2px; margin-top: 4px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rate)
                                                    <img src="{{ asset('images/stars/active.svg') }}" width="15px" height="15px">
                                                @else
                                                    <img src="{{ asset('images/stars/inactive.svg') }}" width="15px" height="15px">
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="review-date" style="color: #697A8D; font-size: 14px; margin-left: 5px;">Posted on {{ $formattedDate }}</span>
                                </div>
                            </div>
                            <div class="review-content">
                                <p style="color: #697A8D;">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($rev->isEmpty())
                        <div class="text-center py-4">
                            <p style="color: #697A8D;">No reviews yet for this room type.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter reviews by rating
    const filterSelect = document.getElementById('filterSelect');
    filterSelect.addEventListener('change', function() {
        const selectedRating = parseInt(this.value);
        const reviewItems = document.querySelectorAll('.review-item');
        
        reviewItems.forEach(item => {
            const itemRating = parseInt(item.dataset.rating);
            if (selectedRating === 0 || itemRating === selectedRating) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>