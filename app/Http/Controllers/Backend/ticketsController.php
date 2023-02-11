<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Notification;
use App\Notifications\ticketNotification;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\ticket;
use App\Models\department;
use App\Models\User;
use App\Models\comment;
use Illuminate\Support\Str;


class ticketsController extends Controller
{
    public function index(){
        
        //  $count_notifications = auth()->user()->unreadNotifications->count();
        $users = User::all();
        $departments = department::all();
        $categories = category::all();
       if(auth()->id()==1){
         $tickets = ticket::all();
       }else{
        $tickets = ticket::where('assign_To_id', auth()->id())->get();
        //  dd();
       }
    //    dd($id);
       
        //   dd($departments);
       return view('backend.pages.tickets.index',compact('tickets','users','departments','categories')); 
    }
    public function create(){
        $categories = category::all();
        $departments = department::all();
        $count_notifications = auth()->user()->unreadNotifications->count();
        $read_notifications = auth()->user()->readNotifications->count();
        $non_read_notifications = $count_notifications-$read_notifications;
        return view('backend.pages.tickets.create', compact('categories','departments'));
    }
    public function store(Request $request){
        $request->validate([
         'title' => 'required',
         'message' => 'required',
         'category' => 'required',
         'priority' => 'required',
         'department' => 'required'  
        ]);
        $ticket = new ticket();
        $ticket->title = $request->title;
        $ticket->user_id = auth()->user()->id;
        $ticket->ticket_id = strtoupper(Str::random(10));
        $ticket->category_id = $request->category;
        $ticket->priority = $request->priority;
        $ticket->department_id = $request->department;
        $ticket->message = $request->message;
        $ticket->status = 'open';
        $ticket->assignee_id = '0';
        $ticket->assign_To_id = '0';
        $ticket->save();
        session()->flash('success', 'Ticket with ID'. $ticket->ticket_id.'Has been created');
        if(auth()->user()){
            $user = User::first();
            $department = department::find($request->department);
            $details = [
                'greeting' => 'Hi Artisan',
                'body' => 'The ticket With Id <b>'.$ticket->ticket_id.'</b> has been generated against '.$department->name.' Department',  
            ];
            Notification::send($user, new ticketNotification($details));
            return redirect()->route('tickets.index');
        }
        return redirect()->route('tickets.index');
    }
    public function markAsRead(Request $request){
        $id = $request->id;
        //    dd($id);
        auth()->user()->Notifications->where('id',$id)->markAsRead();
        $count_notifications = auth()->user()->unreadNotifications->count();
        // dd($count_notifications);
         return response()->json($count_notifications);
        //  return back();
}
     public function getDepartmentUsers($id){
        $users = User::where('department_id',$id)->get();
        return response()->json($users);
     }
   
    public function assign($id){
        $ticket = ticket::find($id);
        return response()->json($ticket);
    }
    public function assignTo(Request $request){
        $id = $request->ticket_id;
        // dd($id);
        $ticket = ticket::find($id);
        // dd($ticket);
        $ticket->department_id = $request->department;
        $ticket->assignee_id = auth()->user()->id;
        $ticket->assign_To_id = $request->user;
        $ticket->status = 'Pending';
        $ticket->save();
        if(auth()->user()){
        $user = User::where('id',$request->user)->get();
        $details = [
                'greeting' => 'Hi Artisan',
                'body' => 'You have been Assigned ticket With Id ='.$ticket->ticket_id,
        ];
            Notification::send($user, new ticketNotification($details));
            return redirect()->route('tickets.index');
        }
    }
    public function comment(Request $request){
        $request->validate([
            'comment' => 'required'  
           ]);
           $comment = new comment();
           $comment->comment =  $request->comment;
           $comment->user_id =  auth()->user()->id;
           $comment->ticket_id = $request->ticket_comment_id;
           if($comment->save()){
            $ticket = ticket::where('ticket_id',$request->ticket_comment_id)->first();
            $ticket->status = 'closed';
            $ticket->save();
            if(auth()->user()){
                $user = User::where('type','superadmin')->get();
                $details = [
                        'greeting' => 'Hi Artisan',
                        'body' => 'The issue has been resolved',
                        'thanks' => 'Thank you',
                ];
                    Notification::send($user, new ticketNotification($details));
                    return redirect()->route('tickets.index');
                }
            // return redirect()->route('tickets.index');
          }
        }
    public function edit($id){
        //  dd($id);
        $ticket = ticket::find($id);
        $categories = category::all();
        $departments = department::all();
        $users = User::all();
        return response()->json($ticket);
        //  return view('backend.pages.tickets.edit', compact('ticket','categories','departments','users'));
    }
    public function update(Request $request){
        $id = $request->hiddenid;
        //  dd($id);
        $ticket = ticket::find($id);
        $ticket->title = $request->title;
        $ticket->user_id = auth()->user()->id;
        $ticket->ticket_id = strtoupper(Str::random(10));
        $ticket->category_id = $request->category;
        $ticket->priority = $request->priority;
        $ticket->department_id = $request->department;
        $ticket->message = $request->message;
        $ticket->status = 'open';
        $ticket->assignee_id = '0';
        $ticket->assign_To_id = '0';
        $ticket->save();
        session()->flash('success', 'Ticket with ID'. $ticket->ticket_id.'Has been updated');
        return redirect()->route('tickets.index');
    }
    public function destroy($id){
        $ticket = ticket::find($id);
        if (!is_null($ticket)) {
            $ticket->delete();
        }
        session()->flash('success', 'ticket has been deleted !!');
        return back();
    }
    public function getInfo($id){
        // $ticket = ticket::find($id);
        $data = ticket::join('comments', 'comments.ticket_id', '=', 'tickets.ticket_id')
        ->join('users','users.id','=','tickets.assign_To_id')
        ->join('categories','categories.id','=','tickets.category_id')
        ->where('tickets.ticket_id',$id)
        ->get(['tickets.title', 'tickets.message', 'tickets.priority','tickets.status','tickets.ticket_id','comments.comment','comments.created_at','users.name']);
                    //    dd($data);
        return view('backend.pages.tickets.info',compact('data'));
    }
    public function viewAllNotifications(){
        $non_read_notifications = auth()->user()->unreadNotifications;
        $count_notifications = $non_read_notifications->count();
        // $read_notifications = auth()->user()->readNotifications->count();
        // $non_read_notifications = $count_notifications-$read_notifications;
        return view('backend.pages.notifications.index', compact('count_notifications','non_read_notifications'));
        // dd($notifications);
    }
}