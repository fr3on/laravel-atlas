@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'config')">
        <span class="path" style="opacity:0.6">{{ $item['key'] }}</span>
        <span class="path" style="color:var(--accent); text-align:right">{{ $item['value'] }}</span>
    </div>
@endforeach
