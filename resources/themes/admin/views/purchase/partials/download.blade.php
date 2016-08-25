<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered stickers-packaging">
        <tbody>
        <tr>
            <td style="text-align: center;" colspan="4">
                <b>{!! $title !!}</b>
            </td>
        </tr>

        <tr>
            <td colspan="4"></td>
        </tr>

        <tr>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.ingredient')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.category')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.count')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.unit')</th>
        </tr>

        <tr>
            <td colspan="4"></td>
        </tr>

        @foreach($list as $ingredient)
            <tr>
                <td>{!! $ingredient['ingredient'] !!}</td>
                <td style="background-color: #dddddd;">{!! $ingredient['category'] !!}</td>
                <td style="text-align: right;">{!! $ingredient['count'] !!}</td>
                <td style="background-color: #dddddd; text-align: left;">{!! $ingredient['unit'] !!}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>