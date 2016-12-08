@if (count($list))
    <div class="box-body table-responsive no-padding">
        <div class="margin-bottom-10">
            <div class="col-sm-12 text-right">
                <a class="download btn btn-flat btn-success btn-sm" target="_blank"
                   href="{!! route('admin.packaging.download', ['booklet', $year, $week]) !!}">
                    @lang('labels.download_xlsx_file_with_booklet')
                </a>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="col-sm-6 margin-bottom-20">
            <form action="{!! route('admin.packaging.update_booklet') !!}" class="form-horizontal booklet-form"
                  method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <div class="col-sm-2">
                        <label for="link" class="control-label">@lang('labels.link')</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control input-sm" type="text" name="link" id="link"
                               value="{!! isset($booklet->link) ? $booklet->link : variable('booklet_link', '') !!}">
                        <input type="hidden" name="year" value="{!! $year !!}">
                        <input type="hidden" name="week" value="{!! $week !!}">
                    </div>
                    <div class="col-sm-2">
                        @if (!past_week($year, $week))
                            <button type="submit" class="btn btn-sm btn-default btn-flat">
                                @lang('labels.save')
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-bordered booklet-packaging">
            <tbody>

            <tr class="recipe-name">
                <th class="col-sm-6">
                    @lang('labels.recipe')
                </th>
                <th class="col-sm-6">
                    @lang('labels.count')
                </th>
            </tr>

            @foreach($list as $basket)
                @foreach($basket as $recipe)
                    <tr>
                        <td>{!! $recipe['name'] !!}</td>
                        <td>{!! $recipe['recipes_count'] !!}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2"></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
@else
    <p class="help-block text-center">
        @lang('messages.no recipes')
    </p>
@endif