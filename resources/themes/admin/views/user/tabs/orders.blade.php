<div class="user-orders-table">
    {!!
        TablesBuilder::create([
                'id' => 'user_orders_datatable',
                'class' => "table table-bordered table-striped table-hover",
            ], [
                'bStateSave' => true,
                'ajax' => route('admin.user.orders', $model->id),
                'order' => [[ 0, 'desc' ]]
            ])
            ->addHead([
                ['text' => trans('labels.id')],
                ['text' => trans('labels.basket_name').'/'.trans('labels.price')],
                ['text' => trans('labels.total_cost')],
                ['text' => trans('labels.status')],
                ['text' => trans('labels.coupon_simple')],
                ['text' => trans('labels.delivery_date')],
                ['text' => trans('labels.order_comments')],
                ['text' => trans('labels.actions')]
            ])
            ->addFoot([
                ['attr' => ['colspan' => 8]]
        ])
        ->make()
    !!}
</div>