@extends('backend.layouts.master')

@section('title')
Admins - Admin Panel
@endsection

@section('styles')
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Ticket</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span>All Tickets</span></li>
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
                    <h4 class="header-title float-left">Tickets List</h4>
                    <p class="float-right mb-2">
                        <a class="btn btn-primary text-white" href="{{ route('admin.tickets.create') }}">Create New
                            Ticket
                        </a>
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Title</th>
                                    <th width="10%">Message</th>
                                    <th width="10%">Category</th>
                                    <th width="10%">Priority</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Ticket Against</th>
                                    <th width="5%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($tickets)!=0)
                                @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $ticket->title }}</td>
                                    <td>{{ $ticket->message }}</td>
                                    <td>{{ $ticket->category->name }}</td>
                                    <td>{{ $ticket->priority }}</td>
                                    <td>@if($ticket->status=='open')<span
                                            class="badge badge-pill badge-success">{{ $ticket->status }}</span>
                                        @elseif($ticket->status=='Pending')
                                        <span class="badge badge-pill badge-warning">{{ $ticket->status }}</span>
                                        @else
                                        <span class="badge badge-pill badge-danger">{{ $ticket->status }}
                                            @endif
                                    </td>
                                    <td>{{ $ticket->department->name }}</td>
                                    <td>
                                        @if(auth()->user()->type == 'superadmin')
                                        <a class=" btn btn-success text-white editticket" href="#editModal }}"
                                            data-toggle="modal" data-id="{{ $ticket->id}}">Edit</a>
                                        @else
                                        @if($ticket->status =="Pending")
                                        <a class=" btn btn-success text-white commentTicket" href="#commentModal"
                                            data-toggle="modal" data-id="{{ $ticket->ticket_id}}">comment</a>
                                        @endif
                                        @endif
                                        <a class="btn btn-danger text-white"
                                            href="{{ route('ticket.destroy', $ticket->id) }}" onclick="event.preventDefault(); document.getElementById('
                                            delete-form-{{ $ticket->id }}').submit()">
                                            Delete
                                        </a>
                                        <a class="btn btn-primary text-white"
                                            href="{{ route('ticket.info', $ticket->ticket_id) }}" onclick="event.preventDefault(); document.getElementById('
                                            delete-form-{{ $ticket->id }}').submit()">
                                            Details
                                        </a>
                                        <form id="delete-form-{{ $ticket->id }}"
                                            action="{{ route('ticket.destroy', $ticket->id) }}" method="POST"
                                            style="display: none;">
                                            <!-- @method('DELETE') -->
                                            @csrf
                                        </form>
                                        @if(auth()->user()->type =='superadmin' && $ticket->status=='open' )
                                        <button type="button" class="btn btn-primary assignM" data-toggle="modal"
                                            data-target="#assignModal" data-id="{{ $ticket->id }}">
                                            Assign To
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>

<div class=" modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modelHeading">
                <h5 class="modal-title" id="exampleModalLabel">Assign Ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('ticket.assignTo') }}">
                    @csrf
                    <input type="hidden" name="ticket_id" value="" id="ticket_id">
                    <div class="form-row">
                        <div class="form-group col-md-10 col-sm-6">
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
                        <div class="form-group col-md-10 col-sm-12" id="user">
                            <label for="Agent">Agent</label>
                            <select name="user" class="form-control users">
                                @foreach ($users as $user)
                                @if($user->id!=1)
                                @if($user->department_id==1)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                                @endif
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class=" modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modelHeading">
                <h5 class="modal-title" id="exampleModalLabel">Edit Ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.tickets.update') }}">
                    @csrf
                    <input type="hidden" name="hiddenid" value="" id="hidden_id">
                    <div class="form-row">
                        <div class="form-group col-md-10 col-sm-12">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter title"
                                value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 col-sm-6">
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
                        <div class="form-group col-md-10 col-sm-6">
                            <label for="password">Category</label>
                            <select name="category" id="category" class="form-control">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 col-sm-6">
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
                        <div class="form-group col-md-10 col-sm-12">
                            <label for="message">Message</label>
                            <textarea rows="5" class="form-control" id="message" name="message"
                                placeholder="Enter Your Message">
                                </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class=" modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modelHeading">
                <h5 class="modal-title" id="exampleModalLabel">Comment on ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('ticket.comment') }}">
                    @csrf
                    <input type="hidden" name="ticket_comment_id" value="" id="ticket_comment_id">
                    <div class="form-row">
                        <textarea name="comment" id="comment" class="form-control" placeholder="Please write comment"
                            rows="10"></textarea>

                    </div>
                    <div class="form-row">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
/*================================
        datatable active
        ==================================*/
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}
$('body').on('click', '.assignM', function() {
    var id = $(this).attr('data-id');
    console.log(id);
    $.get('ticket/assign/' + id, function(data) {
        // $('#modal-two').html("Edit Software Details");
        //   $('#btn-save').val("edit-user");
        console.log(data)
        $('#assignModal').modal('show');
        $('#department').val(data.department);
        $('.users').val(data.user_id);
        $('#ticket_id').val(id);
        $(".users option:first").attr('selected', 'selected');
        $("#department option:first").attr('selected', 'selected');
        // $('#status').val(data.status);
        //   console.log(data);
    })
});
$('#assignModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})
$('body').on('change', '#department', function() {
    var id = $(this).find('option:selected').val();
    // var id = $(this).attr('data-id');
    var html = '<label for="Agent">Agent</label><select name="user" class="form-control users">';
    console.log(id);
    $.get('department/users/' + id, function(data) {
        // $('#modal-two').html("Edit Software Details");
        //   $('#btn-save').val("edit-user");
        // $('#assignModal').find('form').trigger('reset');
        console.log(data);
        $.each(data, function(i, user) {
            html += '<option value = "' + user.id + '">' + user.name + '</option>';
        });
        html += '</select>';
        console.log(html);
        $('#user').html(html);
    });
});

$('body').on('click', '.editticket', function() {
    var id = $(this).attr('data-id');
    console.log(id);
    $.get('tickets/edit/' + id, function(data) {
        // $('#modal-two').html("Edit Software Details");
        //   $('#btn-save').val("edit-user");

        console.log(data)
        $('#editModal').modal('show');
        $('#title').val(data.title);
        $('#priority').val(data.priority);
        $('#category').val(data.category_id);
        $('#department').val(data.department_id);
        $('#message').val(data.message);
        $('#hidden_id').val(id);
        $("#priority option:first").attr('selected', 'selected');

        // $('#status').val(data.status);
        //   console.log(data);
    })
});
$('body').on('click', '.commentTicket', function() {
    var id = $(this).attr('data-id');
    $('#ticket_comment_id').val(id);
    console.log(id);
    // $.get('ticket/assign/' + id, function(data) {
    //     // $('#modal-two').html("Edit Software Details");
    //     //   $('#btn-save').val("edit-user");
    //     console.log(data)
    //     $('#assignModal').modal('show');
    //     $('#department').val(data.department);
    //     $('.users').val(data.user_id);
    //     $('#ticket_id').val(id);
    //     $(".users option:first").attr('selected', 'selected');
    //     $("#department option:first").attr('selected', 'selected');
    //     // $('#status').val(data.status);
    //     //   console.log(data);
    // })
});

$('#editModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})
</script>
@endsection