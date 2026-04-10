@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'models')">
        <span class="badge method-get">MODEL</span>
        <span class="path">{{ $item['name'] }}</span>
        <span class="meta-text" style="margin-left:auto">{{ count($item['relations'] ?? []) }} relations</span>
    </div>
@endforeach
