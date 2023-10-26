@extends('layouts.home.app')
@section('title', ' Notifications')
@Section('main_content')
    <main>
        <!-- /secondary_nav -->

        <div class="bg_gray mt-5">
            <div class="container margin_detail">
                <h3>Notifications</h3>
                <div class="row">

                
                @forelse ($notifications as $item)
                    <div class="card rounded col-md-8  m-auto my-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->data['title'] }} notification</h5>
                            <div class="d-sm-flex justify-content-between">
                                <div>
                                    <p class="card-text">{{ $item->data['description'] }}.</p>
                                </div>
                                <div>
                                    <p class="card-text">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Notification not found!</h5>
                        </div>
                    </div>
                @endforelse
            </div>
            </div>
        </div>

    </main>
@endsection
