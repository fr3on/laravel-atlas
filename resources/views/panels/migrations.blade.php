@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'migrations')">
        <span class="badge status-{{ $item['status'] }}">{{ strtoupper($item['status']) }}</span>
        <span class="path">{{ $item['title'] }}</span>
        <span class="meta-text">{{ $item['date'] }}</span>
    </div>
@endforeach
