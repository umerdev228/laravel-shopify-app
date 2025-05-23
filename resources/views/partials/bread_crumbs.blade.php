<div class="pagetitle">
    <h1>{{$page_title}}</h1>
    <nav>
        <ol class="breadcrumb">
            @foreach($bread_crumbs as $key => $bread_crumb)
                <li class="breadcrumb-item @if($loop->last) active @endif"><a href="{{$bread_crumb}}">{{$key}}</a></li>
            @endforeach
        </ol>
    </nav>
</div>
