@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'events')">
        <span class="badge method-get">EVENT</span>
        <span class="path">{{ class_basename($item['event']) }}</span>
        <span class="meta-text" style="margin-left:auto">{{ count($item['listeners']) }} listeners</span>
    </div>
@endforeach
