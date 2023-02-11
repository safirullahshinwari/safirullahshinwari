<!-- header area start -->
<div class="header-area">
    <div class="row align-items-center">
        <!-- nav and search button -->
        <div class="col-md-6 col-sm-8">
            <form action="{{ route('search') }}" method="POST">
                @csrf
                <div class="form-group" style="display:flex" l>
                    <input type="text" name="query" class="form-control" />
                    <input type="submit" class="btn btn-sm btn-primary" value="Search" />
                </div>
            </form>
        </div>
        <!-- profile info & task notification -->
        <div class="col-md-6 col-sm-4 clearfix">
            <ul class="notification-area pull-right">
                <li id="full-view"><i class="ti-fullscreen"></i></li>
                <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                <li class="dropdown">
                    <i class="ti-bell dropdown-toggle" data-toggle="dropdown">
                        <span class="notification_count">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </i>
                    <div class="dropdown-menu bell-notify-box notify-box">
                        <span class="notify-title">
                            <a href="{{ route('notifications.viewAll') }}">view all</a></span>
                        <div class="nofity-list">
                            @foreach(auth()->user()->unreadNotifications as $notification)
                            <a href="#" class="notify-item">
                                <div class="notify-thumb"><i class="ti-key btn-danger"></i></div>
                                <div class="notify-text">
                                    <p>{{ $notification->data['body'] }}</p>
                                    <span>Just Now</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </li>
                <li id="full-view">
                    <i class="ti-user" data-toggle="dropdown"></i>
                    <div class="">
                        <div class="dropdown-menu pull-right" style="margin-left:14px">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('admin-logout-form').submit()">LogOut</a>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                        </div>
                        <form id="admin-logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- header area end -->