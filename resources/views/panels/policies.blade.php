@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'policies')">
        <span class="badge method-post">POLICY</span>
        <span class="path">{{ $item['model'] }}</span>
        <span class="meta-text">{{ class_basename($item['class']) }}</span>
    </div>
@endforeach
