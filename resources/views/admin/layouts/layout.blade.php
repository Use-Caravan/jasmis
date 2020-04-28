@include('admin.layouts._header')
<aside class="main-sidebar">
    <section class="sidebar full_row">
      <ul class="sidebar-menu" data-widget="tree">
        @include('admin.layouts._sideBar')
      </ul>
    </section>
  </aside>
@yield('content')
@include('admin.layouts._footer')
