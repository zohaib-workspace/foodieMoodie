@foreach($timezones as $key=>$tz)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>
        <span class="d-block font-size-sm text-body">
            {{ $tz['timezone'] }}
        </span>
    </td>
    <td>
        {{ $tz['gmt_time'] }}
    </td>
    <td>
        {{ $tz['status'] }}
    </td>

    <td>
        <!-- Dropdown -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button"
                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="tio-settings"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                {{--@if ($tz['timezone'] != 'Asia/Karachi')--}}
                    <a class="dropdown-item"
                        href="{{ route('admin.business-settings.timezone-update', [$tz['id']]) }}">Edit</a>
                    <a class="dropdown-item" href="javascript:"
                        onclick="$('#currency-{{ $tz['id'] }}').submit()">Delete</a>
                    <form
                        action="{{ route('admin.business-settings.timezone-delete', [$tz['id']]) }}"
                        method="post" id="currency-{{ $tz['id'] }}">
                        @csrf @method('delete')
                    </form>
                {{--@else
                    <a class="dropdown-item" href="javascript:">
                        Default
                    </a>
                @endif--}}
            </div>
        </div>
        <!-- End Dropdown -->
    </td>
</tr>
@endforeach
