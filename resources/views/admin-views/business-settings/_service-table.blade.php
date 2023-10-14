@foreach($services as $key=>$service)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>
        <span class="media align-items-center">
            <img class="avatar avatar-lg mr-3 avatar--3-1"
                src="{{ asset('storage/app/public/service') }}/{{ $service['image'] }}"
                onerror="this.src='{{ asset('public/assets/admin/img/900x400/img1.jpg') }}'"
                alt="{{ $service->name }} image">
            <div class="media-body">
                <h5 class="text-hover-primary mb-0">
                    {{ Str::limit($service['name'], 25, '...') }}</h5>
            </div>
        </span>
        {{-- <span class="d-block font-size-sm text-body">
            {{ $service['name'] }}
        </span> --}}
    </td>
    <td>
        <label class="toggle-switch toggle-switch-sm"
            for="statusCheckbox{{ $service->id }}">
            <input type="checkbox"
                onclick="location.href='{{ route('admin.business-settings.status', [$service['id'], $service->status ? 0 : 1]) }}'"
                class="toggle-switch-input" id="statusCheckbox{{ $service->id }}"
                {{ $service->status ? 'checked' : '' }}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
    </td>
    <td>
        <!-- Button trigger modal -->

        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#exampleModal{{ $service['id'] }}">
            <i class="tio-invisible"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal{{ $service['id'] }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <a class="table-rest-info" href="">
                            <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                src="}" alt="">
                            <div class="info">
                                <h5 class="text-hover-primary mb-0">
                                    {{ $service['name'] }}
                                </h5>
                                <span class="d-block text-body">
                                    <!-- Rating -->
                                     <span class="rating">
                                        <i class="tio-star"></i>
                                        {{ count($dm->rider->rating) > 0 ? number_format($dm->rider->rating[0]->average, 1, '.', ' ') : 0 }}
                                    </span> 
                                    <!-- Rating -->
                                </span>
                            </div>
                        </a> --}}
                        {{ $service['name'] }}

                    </div>
                    <div class="modal-body">
                        <div class="container">
                            {{-- <div class="row">
                                <div class="col">
                                    <p class="h4">Start Time:</p>
                                </div>
                                <div class="col">
                                    <p class="h4">{{ $dm['start_time'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="h4">End Time:</p>
                                </div>
                                <div class="col">
                                    <p class="h4">{{ $dm['end_time'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="h4">Zone:</p>
                                </div>
                                <div class="col">
                                    <p class="h4">{{ $dm->zone['name'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="h4">Shift Date:</p>
                                </div>
                                <div class="col">
                                    <p class="h4">{{ $dm['shift_date'] }}</p>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <p class="h4">Status:</p>
                                        </div>
                                        <div class="col">
                                            <p
                                                class="h4 {{ $service['status'] == '1' ? 'badge badge-success' : 'badge badge-danger' }}">
                                                {{ $service['status'] == '1' ? 'Active' : 'Offline' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="h4">Zone:</p>
                                        </div>
                                        <div class="col">
                                            <p class="h4 ">
                                                {{ $service['zone_id'] }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="h4">WA No:</p>
                                        </div>
                                        <div class="col">
                                            <p class="h4 ">
                                                {{ $service['wa_number'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <span class="media align-items-center">
                                        <img class="avatar avatar-lg mr-3 avatar--3-1"
                                            src="{{ asset('storage/app/public/service') }}/{{ $service['image'] }}"
                                            onerror="this.src='{{ asset('public/assets/admin/img/900x400/img1.jpg') }}'"
                                            alt="{{ $service->name }} image">
                                        {{-- <div class="media-body">
                                            <h5 class="text-hover-primary mb-0">
                                                {{ Str::limit($service['name'], 25, '...') }}</h5>
                                        </div> --}}
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="container">

                            <p class="h2">{{ translate('description') }}</p>
                            {{ $service['description'] }}

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ translate('close') }}</button>
                        {{-- <button type="button" class="btn btn-primary">Save
                            changes</button> --}}
                    </div>
                </div>
            </div>
        </div>

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

                <a class="dropdown-item"
                    href="{{ route('admin.business-settings.service-update', [$service['id']]) }}">
                    {{ translate('edit') }}
                </a>

                <a class="dropdown-item" href="javascript:"
                    onclick="$('#service-{{ $service['id'] }}').submit()">{{ translate('delete') }}</a>
                <form
                    action="{{ route('admin.business-settings.service-delete', [$service['id']]) }}"
                    method="post" id="service-{{ $service['id'] }}">
                    @csrf @method('delete')
                </form>

            </div>
        </div>
        <!-- End Dropdown -->

    </td>
</tr>
@endforeach
