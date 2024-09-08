<tr>
    <td class="index">{{ $index }}</td>
    @foreach ($columns as $column)
        <td>{{ $column ?? '' }}</td>
    @endforeach
    @if (!empty($actions))
        <td>
            @foreach ($actions as $action)
                <a href="{{ route($action['route'], $item->id) }}" class="btn btn-{{ $action['class'] }} mr-2 mb-2">
                    <i class="{{ $action['icon'] }}"></i>
                </a>
            @endforeach
        </td>
    @endif
</tr>
