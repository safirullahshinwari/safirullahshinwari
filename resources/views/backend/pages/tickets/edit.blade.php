@extends('backend.layouts.master')

@section('title')
Admin Edit - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
.form-check-label {
    text-transform: capitalize;
}
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Update Ticket</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.admins.index') }}">All Tickets</a></li>
                    <li><span>Edit Ticket - {{ $ticket->title }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Tickets - {{ $ticket->title }}</h4>
                    @include('backend.layouts.partials.messages')
                    <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <!-- <div class="form-group col-md-5 col-sm-12">
                                <input type="hidden" class="form-control" name="ticket_id" value="{{ $ticket->id }}">
                            </div> -->
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter title" value="{{ $ticket->title }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Priority</label>
                                <select name="priority" id="priority" class="form-control">
                                    <option value="1">
                                        LOW
                                    </option>
                                    <option value="2">
                                        MEDIUM
                                    </option>
                                    <option value="3">
                                        HIGH
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Category</label>
                                <select name="category" id="category" class="form-control">
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Department</label>
                                <select name="department" id="department" class="form-control">
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">
                                        {{ $department->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="message">Message</label>
                                <textarea rows="5" class="form-control" id="message" name="message"
                                    placeholder="Enter Your Message">
                                    {{ $ticket->message }}
                                </textarea>
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <button type="button" class="btn btn-primary assignM" data-toggle="modal"
                                    data-target="#assignModal" data-id="{{ $ticket->id}}" onclick="prep()">
                                    Assign To
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>

<div class=" modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Assign Ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('ticket.assignTo') }}">
                    @csrf
                    <input type="hidden" name="hiddenid" value="" id="hidden_id">
                    <label for=" department-name" class="col-form-label">
                        Department:</label>
                    <select id="department-name" type="department" class="form-control pb-2" name="department">
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">
                            {{ $department->name }}</option>
                        @endforeach
                    </select>
                    <label for="Assignee-name" class="col-form-label">
                        AssignTo:</label>
                    <select id="user" type="user" class="form-control pb-2" name="user">
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
})

function prep() {
    $id = $('.assignM').attr('data-id');
    console.log($id);
    $('#hidden_id').val($id);
};
</script>
@endsection