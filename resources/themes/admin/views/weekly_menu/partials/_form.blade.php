@include('weekly_menu.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                @foreach($baskets as $key => $basket)
                    <li @if ($key == 0) class="active" @endif>
                        <a aria-expanded="false" href="#basket_{!! $basket->id !!}" data-toggle="tab">{!! $basket->name !!}</a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">

                <div class="recipes-add-control margin-bottom-25">
                    <div class="form-group no-margin @if ($errors->first('week') || $errors->first('started_at') || $errors->first('ended_at')) has-error @endif">
                        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left no-padding">
                            @lang('labels.week')
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            {!! Form::text('week', old('week') ?: $model->getWeekDates(), ['id' => 'week_menu_date', 'class' => 'form-control pull-left', 'required' => true]) !!}
                        </div>

                        {!! $errors->first('week', '<p class="help-block error position-relative">:message</p>') !!}
                        {!! $errors->first('started_at', '<p class="help-block error position-relative">:message</p>') !!}
                        {!! $errors->first('ended_at', '<p class="help-block error position-relative">:message</p>') !!}
                    </div>
                </div>

                @foreach($baskets as $key => $basket)
                    <div class="tab-pane @if ($key == 0) active @endif" id="basket_{!! $basket->id !!}">
                        @include('weekly_menu.tabs.basket')
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@include('weekly_menu.partials._buttons')