<div class="tab-pane" id="coupons">

    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>@lang('labels.name')</th>
            <th>@lang('labels.code')</th>
            <th>@lang('labels.discount')</th>
            <th>@lang('labels.coupon_started_date')</th>
            <th>@lang('labels.coupon_expire_date')</th>
            <th>@lang('labels.status')</th>
            <th>@lang('labels.activation')</th>
        </tr>

        @if ($user_coupons->count())
            @foreach($user_coupons as $coupon)
                @include('user.partials.coupon', ['user' => $model])
            @endforeach
        @else
            <tr class="no-coupons">
                <td colspan="6">
                    <p class="help-block text-center">
                        @lang('messages.user not have any saved coupons')
                    </p>
                </td>
            </tr>
        @endif

        </tbody>
    </table>

    <div class="user-coupon-form" data-action="{!! localize_route('admin.user.coupon.store') !!}" role="form">
        {!! csrf_field() !!}
        <input type="hidden" name="coupon_user_id" value="{!! $model->id !!}">

        <div class="form-group">
            <div class="col-xs-6 col-sm-4 col-md-2">
                <input type="text" class="form-control input-sm" name="code">
            </div>

            <div class="col-sm-2">
                <div class="btn btn-primary btn-flat btn-sm save-user-coupon">
                    @lang('labels.add_coupon')
                </div>
            </div>
        </div>
    </div>

</div>