@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'routes')">
        <span class="badge method-{{ strtolower(explode('|', $item['method'])[0]) }}">{{ explode('|', $item['method'])[0] }}</span>
        <span class="path">{{ $item['uri'] }}</span>
        @foreach(array_slice($item['middleware'], 0, 2) as $mw)
            <span class="tag {{ str_contains($mw, 'throttle') ? 'tag-accent' : '' }}">{{ $mw }}</span>
        @endforeach
        <span class="meta-text">{{ class_basename(explode('@', $item['action'])[0]) }}@ {{ explode('@', $item['action'])[1] ?? '' }}</span>
    </div>
@endforeach
