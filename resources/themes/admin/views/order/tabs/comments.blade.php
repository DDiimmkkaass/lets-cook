@if ($model->exists)
    <div id="order_comments_block" class="box box-primary order-comments-block">
        <div class="box-header">
            <h4 class="margin-top-10 margin-bottom-0">@lang('labels.comments')</h4>
        </div>
        <div class="box-body">
            <div class="direct-chat-messages height-auto comments-block">
                @each('order.partials.comment', $model->comments, 'comment')
            </div>
        </div>
        <div class="box-footer">
            <div data-action="{!! route('admin.order.comment.store') !!}" class="order-comment-form">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="hidden" id="order_id" name="order_id" value="{!! $model->id !!}">
                            <input type="text" id="order_comment" name="order_comment"
                                   placeholder="@lang('labels.type_a_comment')" class="form-control input-sm">
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-warning btn-flat btn-sm">@lang('labels.add')</button>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif