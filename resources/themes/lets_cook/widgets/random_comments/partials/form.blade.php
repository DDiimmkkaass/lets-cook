<div class="clients-reviews__add-review add-review">
    <div class="add-review__close-layout"></div>

    <div class="add-review__wrapper">
        <div class="add-review__title">@lang('front_labels.leave_comment')</div>

        <div class="add-review__content">
            <div class="add-review__userontr">
                @php($image = empty($user) || empty($user->avatar) ? variable('comment_user_default_image') : $user->avatar )
                <div class="add-review__user-photo" style="background-image: url({!! thumb($image, 65) !!});"></div>
                <div class="add-review__user-name">{!! $user ? $user->getFullName() : '' !!}</div>
            </div>

            <form action="{!! localize_route('comments.store') !!}"
                  data-authorized="{!! $user ? 1 : 0 !!}"
                  class="add-review__form">
                  {!! csrf_field() !!}
                  <textarea name="comment"
                            id="add-review"
                            cols="30"
                            rows="10"
                            class="add-review__text textarea-small"
                            placeholder="@lang('front_texts.comment helper text')"></textarea>

                <div class="add-review__bottom">
                    <input type="submit" class="add-review__submit black-short-button" value="@lang('labels.publish')">
                </div>
            </form>
        </div>

        <div class="add-review__close"></div>
    </div>
</div>