@if (count($list))
    <div class="box-body table-responsive no-padding">
        <div class="margin-bottom-10">
            <div class="col-sm-12 text-right">
                <a class="download btn btn-flat btn-success btn-sm" target="_blank"
                   href="{!! route('admin.packaging.download', ['repackaging', $year, $week]) !!}">
                    @lang('labels.download_xlsx_file')
                </a>
            </div>

            <div class="clearfix"></div>
        </div>
        <table class="table table-bordered repackaging-packaging">
            <tbody>
            <tr class="title">
                <th class="col-sm-6">@lang('labels.categories_&_ingredients') </th>
                <th class="col-sm-3 text-center">@lang('labels.packaging') </th>
                <th class="col-sm-3 text-center">@lang('labels.count')(@lang('labels.count_short'))</th>
            </tr>

            @foreach($list as $category)
                <tr>
                    <td colspan="3"><h5><b>{!! $category['name'] !!}</b></h5></td>
                </tr>

                @foreach($category['ingredients'] as $ingredient)
                    <tr>
                        <td>{!! $ingredient['name'] !!}</td>
                        <td class="text-center">{!! $ingredient['package'] !!} {!! $ingredient['unit_name'] !!}</td>
                        <td class="text-center">{!! $ingredient['count'] !!}</td>
                    </tr>
                @endforeach

                <tr>
                    <td class="spliter" colspan="3"></td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
@else
    <p class="help-block text-center">
        @lang('messages.no ingredients who need a repackaging')
    </p>
@endif