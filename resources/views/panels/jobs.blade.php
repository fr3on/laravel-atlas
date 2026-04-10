@foreach($data as $item)
    <div class="list-row atlas-row" onclick="inspect({{ json_encode($item) }}, 'jobs')">
        <span class="badge method-post">JOB</span>
        <span class="path">{{ $item['name'] }}</span>
        <span class="tag tag-accent">{{ $item['queue'] }}</span>
        <span class="meta-text" style="margin-left:auto">Tries: {{ $item['tries'] }} · Timeout: {{ $item['timeout'] }}s</span>
    </div>
@endforeach
