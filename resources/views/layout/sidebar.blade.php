@section('sidebar')
    <nav class="sidebar">
        <div class="sidebar-header">
            {{-- <img src="{{ asset('assets/images/menalco.png')}}" alt=""> --}}
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
        </div>
        <div class="sidebar-body">
        <ul class="nav">

            <li class="nav-item">
                    <li class="nav-item">
                        <a class="nav-link " id="3" href="/">
                            Scanner
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " id="upload" href="{{route('upload.index')}}">
                            Transférer
                        </a>
                    </li>
            </li>

        </ul>
        </div>
    </nav>

@endsection


