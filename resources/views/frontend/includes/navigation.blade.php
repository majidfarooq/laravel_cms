<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
 <div class="container">
  <a class="navbar-brand" href="{{ route('home') }}">Start Bootstrap</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
          aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
   Menu
   <i class="fas fa-bars"></i>
  </button>
  <div class="collapse navbar-collapse" id="navbarResponsive">
   <ul class="navbar-nav ms-auto py-4 py-lg-0">
    @if(isset($header))
     @foreach($header as $menu)
      @isset($menu->MenuItem)
       @foreach($menu->MenuItem as $item)
        @if($item->page_type == 'cms')
         <li class="nav-item">
          <a class="nav-link px-lg-3 py-3 py-lg-4"
             href="{{route('pages.show',$item->page->slug)}}"
             target="_self">{{$item->title}}</a>
         </li>
        @elseif($item->page_type == 'static')
         <li class="nav-item static">
          <a class="nav-link px-lg-3 py-3 py-lg-4"
             href="{{ \Illuminate\Support\Facades\URL::to("/").$item->url}}"
             target="_self">{{$item->title}}</a>
         </li>
        @elseif($item->page_type == 'external')
         <li class="nav-item external">
          <a class="nav-link px-lg-3 py-3 py-lg-4" href="{{$item['url']}}"
             target="_blank">{{$item->title}}</a>
         </li>
        @endif
       @endforeach
      @endisset
     @endforeach
    @endif
    
    @guest
     <li class="nav-item">
      <a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('login')}}" target="_self">Sign In</a>
     </li>
     <li class="nav-item">
      <a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('register')}}" target="_self">Sign Up</a>
     </li>
    @endguest
    @auth
     <li class="nav-item">
      <a class="nav-link px-lg-3 py-3 py-lg-4" href="{{route('user.dashboard')}}" target="_self">Dashboard</a>
     </li>
     <li class="nav-item">
      <a class="nav-link px-lg-3 py-3 py-lg-4" href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
     </li>
    @endauth
   </ul>
  </div>
 </div>
</nav>
