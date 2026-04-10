<div class="table-responsive pt-2">
    <table id="{{ $id }}" class="datatable table align-middle table-nowrap {{ $class ?? '' }}"
        style="width:100%" data-ajax="{{ $ajax }}" data-columns='@json($columns)'>
        <thead class="table-light">
            @foreach ($columns as $column)
                <th>{{ $column['title'] ?? ucfirst($column['data']) }}</th>
            @endforeach
        </thead>
        <tfoot></tfoot>
    </table>
</div>