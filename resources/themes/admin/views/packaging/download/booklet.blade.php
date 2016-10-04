<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered booklet-packaging">
        <tbody>

        <tr style="background-color: #cccccc; height: 27px">
            <th style="width: 100px">
                @lang('labels.recipe')
            </th>
            <th style="width: 15px">
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

        <tr>
            <td colspan="2"></td>
        </tr>

        <tr style="height: 20px;">
            <th colspan="2">{!! $booklet->link !!}</th>
        </tr>

        </tbody>
    </table>
</div>