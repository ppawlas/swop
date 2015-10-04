<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/export.css">
</head>

<table>
    <tr>
        <td rowspan="2"><strong>#</strong></td>
        <td rowspan="2"><strong>Pracownik</strong></td>
        @foreach ($report['headers']['indicators'] as $indicator)
            @if ($indicator['visible'])
                <td colspan="{{ $indicator['colspan'] }}"><strong>{{ $indicator['name'] }}</strong></td>
            @endif
        @endforeach
        <td rowspan="2"><strong>Suma punktów</strong></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        @foreach ($report['headers']['components'] as $component)
            @if ($component['visible'])
                <td><strong>{{ $component['displayName'] }}</strong></td>
            @endif
        @endforeach
    </tr>

    @foreach ($report['results'] as $id => $user)
        <tr>
            <td>{{ 1 + $id}}</td>
            <td>{{ $user['displayName'] }}</td>
            @foreach ($report['headers']['components'] as $component)
                @if ($component['visible'])
                    <td>{{ number_format($user['indicators'][$component['id']][$component['type']]['data']) }}</td>
                @endif
            @endforeach
            <td>{{ number_format($user['sum']) }}</td>
        </tr>
    @endforeach

    <tr>
        <td></td>
        <td>Średnia</td>
        @foreach ($report['headers']['components'] as $component)
            @if ($component['visible'])
                <td>{{ number_format($report['statistics']['avg']['indicators'][$component['id']][$component['type']]['data']) }}</td>
            @endif
        @endforeach
        <td>{{ number_format($report['statistics']['avg']['sum']) }}</td>
    </tr>

    <tr>
        <td></td>
        <td>Minimum</td>
        @foreach ($report['headers']['components'] as $component)
            @if ($component['visible'])
                <td>{{ number_format($report['statistics']['min']['indicators'][$component['id']][$component['type']]['data']) }}</td>
            @endif
        @endforeach
        <td>{{ number_format($report['statistics']['min']['sum']) }}</td>
    </tr>

    <tr>
        <td></td>
        <td>Maximum</td>
        @foreach ($report['headers']['components'] as $component)
            @if ($component['visible'])
                <td>{{ number_format($report['statistics']['max']['indicators'][$component['id']][$component['type']]['data']) }}</td>
            @endif
        @endforeach
        <td>{{ number_format($report['statistics']['max']['sum']) }}</td>
    </tr>
</table>
</html>