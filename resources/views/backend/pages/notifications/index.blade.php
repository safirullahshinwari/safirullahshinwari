@extends('backend.layouts.master')
@section('admin-content')

<div style="width:900px;min-height:200px;background-color:white;display:flex;flex-direction:column;justify-content:center;
    margin:0 auto;text-align:center">
    @foreach($non_read_notifications as $notification)
    <div class="alert alert-success" id="notification{{ $notification->id }}" role="alert">
        {{ $notification->data['body'] }}
        Generated by:<span style="color:yellow;background-color:green;padding:5px;border-radius:5px">
            <b>{{ auth()->user()->name }}</b>
            <a href="#" class=" float-right mark-as-read" data-id="{{ $notification->id}}">
                Mark as read
            </a>
    </div>
    @endforeach
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
var notification = '';
$('.mark-as-read').click(function() {
    let id = $(this).data('id');
    // notification = "notification" + id;
    console.log(notification);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax("{{ route('notifications.read') }}", {
        method: 'GET',
        dataType: 'json',
        data: {
            id,
        },
        success: function(response) {
            console.log($('.notification_count'));
            $('.notification_count').html('');
            $('.notification_count').html(response);
            $('#notification' + id).css({
                'background-color': 'gray'
            });
            console.log(response);
        }
    });
});
</script>
@endsection