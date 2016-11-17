<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<div>
    <table>
        <tbody>

        @foreach($data as $email)
            <tr>
                <td>{!! $email !!}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>