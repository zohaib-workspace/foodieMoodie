@foreach ($shifts as $key => $shift)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($shift->rider)
                                            <a class="table-rest-info"
                                                href="{{ route('admin.delivery-man.preview', [$shift['id']]) }}">
                                                <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ asset('storage/app/public/delivery-man') }}/{{ $shift['image'] }}"
                                                    alt="{{ $shift->rider['f_name'] }} {{ $shift->rider['l_name'] }}">
                                                <div class="info">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ $shift->rider['f_name'] . ' ' . $shift->rider['l_name'] }}</h5>
                                                    <span class="d-block text-body">
                                                        <!-- Rating -->
                                                        <span class="rating">
                                                            <i class="tio-star"></i>
                                                            {{ count($shift->rider->rating) > 0 ? number_format($shift->rider->rating[0]->average, 1, '.', ' ') : 0 }}
                                                        </span>
                                                        <!-- Rating -->
                                                    </span>
                                                </div>
                                            </a>
                                        @else
                                            <span class="table-rest-info">
                                                <img src={{ asset('public/assets/admin/img/160x160/img1.jpg') }}
                                                    alt="">
                                                <div class="info">
                                                    <h5 class="text-hover-primary mb-0">
                                                        No Rider Assigned</h5>

                                                </div>
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $shift['start_time'] }}
                                    </td>
                                    <td>
                                        {{ $shift['end_time'] }}
                                        {{-- <span class="d-block font-size-sm">{{$banner['image']}}</span> --}}
                                    </td>
                                    <td>
                                        {{ $shift->zone['name'] }}
                                    </td>

                                    <td>
                                        {{ $shift['shift_date'] }}
                                    </td>

                                    <td>


                                        {{-- <label class="toggle-switch toggle-switch-sm"
                                            for="statusCheckbox{{ $shift->id }}">
                                            <input type="checkbox"
                                                onclick="location.href='{{ route('admin.delivery-man.status', [$shift['id'], $shift->status ? 0 : 1]) }}'"
                                                class="toggle-switch-input" id="statusCheckbox{{ $shift->id }}"
                                                {{ $shift->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label> --}}
                                        

                                        <form action="{{ route('admin.delivery-man.status', $shift->id) }}">
                                            <select name="status" id="priority"
                                                class=" form-control form--control-select {{ $shift->status == 0 ? 'text--title' : '' }} {{ $shift->status == 1 ? 'text--info' : '' }} {{ $shift->status == 2 ? 'text--success' : '' }} "
                                                onchange="this.form.submit()">
                                                <option class="text--title" value="0"
                                                    {{ $shift->status == 0 ? 'selected' : '' }}>
                                                    {{ translate('messages.normal') }}</option>
                                                <option class="text--info" value="1"
                                                    {{ $shift->status == 1 ? 'selected' : '' }}>
                                                    {{ translate('messages.medium') }}</option>
                                                <option class="text--success" value="2"
                                                    {{ $shift->status == 2 ? 'selected' : '' }}>
                                                    {{ translate('messages.high') }}</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">

                                            <!-- Button trigger modal -->
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.delivery-man.shift-update',[$shift['id']])}}"title="{{translate('messages.edit')}} {{translate('messages.banner')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal{{ $shift['id'] }}"><i class="tio-invisible"></i>
                                            </a>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:" onclick="form_alert('banner-{{$shift['id']}}','{{translate('Want to delete this shift')}}')" title="{{translate('messages.delete')}} {{translate('messages.shift')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.delivery-man.shift-delete',[$shift['id']])}}"
                                                        method="post" id="banner-{{$shift['id']}}">
                                                    @csrf @method('delete')
                                            </form>
                                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $shift['id'] }}">
                                                <i class="tio-invisible"></i>
                                            </button> --}}

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{ $shift['id'] }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            @if ($shift->rider)
                                                                <a class="table-rest-info"
                                                                    href="{{ route('admin.delivery-man.preview', [$shift['id']]) }}">
                                                                    <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                                        src="{{ asset('storage/app/public/delivery-man') }}/{{ $shift['image'] }}"
                                                                        alt="{{ $shift->rider['f_name'] }} {{ $shift->rider['l_name'] }}">
                                                                    <div class="info">
                                                                        <h5 class="text-hover-primary mb-0">
                                                                            {{ $shift->rider['f_name'] . ' ' . $shift->rider['l_name'] }}
                                                                        </h5>
                                                                        <span class="d-block text-body">
                                                                            <!-- Rating -->
                                                                            <span class="rating">
                                                                                <i class="tio-star"></i>
                                                                                {{ count($shift->rider->rating) > 0 ? number_format($shift->rider->rating[0]->average, 1, '.', ' ') : 0 }}
                                                                            </span>
                                                                            <!-- Rating -->
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <span class="table-rest-info">
                                                                    <img src={{ asset('public/assets/admin/img/160x160/img1.jpg') }}
                                                                        alt="">
                                                                    <div class="info">
                                                                        <h5 class="text-hover-primary mb-0">
                                                                            No Rider Assigned</h5>

                                                                    </div>
                                                                </span>
                                                            @endif

                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Start Time:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift['start_time'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">End Time:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift['end_time'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Zone:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift->zone['name'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Shift Date:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift['shift_date'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Status:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p
                                                                            class="h4 {{ $shift['status'] == '1' ? 'badge badge-success' : 'badge badge-danger' }}">
                                                                            {{ $shift['status'] == '1' ? 'Active' : 'Offline' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container">

                                                                <p class="h2">Description</p>
                                                                {{ $shift['description'] }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            {{-- <button type="button" class="btn btn-primary">Save
                                                                    changes</button> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <form action="{{ route('admin.delivery-man.delete', [$shift['id']]) }}"
                                                method="post" id="delivery-man-{{ $shift['id'] }}">
                                                @csrf @method('delete')
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
