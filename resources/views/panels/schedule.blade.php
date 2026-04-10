@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'schedule')">
        <span class="badge method-put">{{ $item['expression'] }}</span>
        <span class="path">{{ $item['command'] }}</span>
        <span class="meta-text" style="margin-left:auto">{{ $item['description'] }}</span>
    </div>
@endforeach
