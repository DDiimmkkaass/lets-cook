<div class="direct-chat-msg">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-left">
            @if ($comment->user_id)
                {!! link_to_route('admin.user.edit', $comment->getAdminName(), $comment->user_id, ['target' => '_blank']) !!}
            @else
                {!! $comment->getAdminName() !!}
            @endif
        </span>

        @if ($comment->status)
            <span class="direct-chat-name pull-left margin-left-10">
                @lang('labels.status_changed_to'):
                @include('partials.datatables.status_label', ['status' => $comment->status, 'label' => trans('labels.order_status_'.$comment->status)])
            </span>
        @endif

        <span class="direct-chat-timestamp pull-right">
            {!! get_localized_date($comment->created_at, 'Y-m-d H:i:s', 'H:i') !!}
        </span>
    </div>

    <img class="direct-chat-img"
         src="{!! thumb(empty($comment->user_id) ? '' : $comment->user->avatar, 40) !!}"
         alt="{!! $comment->getAdminName() !!}">

    <div class="direct-chat-text">
        {!! $comment->comment !!}
    </div>
</div>