@extends('backend.layouts.master')
@section('admin-content')
<!-- Main Body -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-5 col-md-6 col-12 pb-4 mt-4 left-side">
                <h2 class="mt-4">Comments</h2>
                <div class="comment mt-4 text-justify float-left">
                    <div style="display:flex;align-items:center;gap:10px">
                        <img src="{{ asset('assets/images/author/avatar.png') }}" alt="" class="rounded-circle"
                            width="40" height="40">
                        <h4>
                            {{ $data[0]['name'] }}

                        </h4>
                        <span>
                            &#8226;<b>{{ $data[0]['created_at']->diffForHumans() }}</b>
                        </span>
                    </div>
                    <br>
                    <p style="margin-top:30px">{{ $data[0]['comment'] }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-4 offset-md-1 offset-sm-1 col-12 mt-4 right-side">
                <form id="algin-form">
                    <div class="form-group">
                        <label for="message" class="mt-4">Title</label>
                        <p>{{ $data[0]['title']}}</p>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <p>{{ $data[0]['message']}}</p>
                    </div>
                    <div class=" form-group">
                        <label for="name">Ticket Id</label>
                        <h3>{{  $data[0]['ticket_id'] }}</h3>
                    </div>
                    <div class="form-group">
                        <label for="assigne">Status:</label>
                        <p class="badge badge-success">{{  $data[0]['status'] }}</p>
                    </div>
                    <div class="form-group">
                        <label for="assigne">Assigned To</label>
                        <p><b>{{ $data[0]['name'] }}</b></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection