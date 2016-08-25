<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

@if (count($list))
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered repackaging-packaging">
            <tbody>
            <tr style="background-color: #cccccc">
                <th>{!! trans('labels.categories_&_ingredients') !!}</th>
                <th style="text-align: center">{!! trans('labels.count') !!}</th>
                <th style="text-align: center">{!! trans('labels.unit') !!}</th>
            </tr>

            <tr>
                <td colspan="3"></td>
            </tr>

            @foreach($list as $category)
                <tr>
                    <th colspan="3"><h4>{!! $category['name'] !!}</h4></th>
                </tr>

                <tr>
                    <td colspan="3"></td>
                </tr>

                @foreach($category['ingredients'] as $ingredient)
                    <tr>
                        <td>{!! $ingredient['name'] !!}</td>
                        <td style="text-align: center">{!! $ingredient['count'] !!}</td>
                        <td style="text-align: center">{!! $ingredient['unit'] !!}</td>
                    </tr>
                @endforeach

                <tr><td colspan="3" style="height: 1px; border-bottom: 1px solid #000"></td></tr>
                <tr>
                    <td colspan="3"></td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>
@endif