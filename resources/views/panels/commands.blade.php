@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'commands')">
        <span class="badge method-put">CMD</span>
        <span class="path">{{ $item['name'] }}</span>
        <span class="meta-text">{{ $item['description'] }}</span>
    </div>
@endforeach
