@if(Request::segment(1) == 'report')
<aside id="left-panel" class="left-panel">
@else
<aside id="left-panel" class="left-panel open-menu">
@endif
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{ setActive('dashboard', 'active') }}">
                    <a href="{{ url('dashboard') }}"><i class="menu-icon fa fa-dashboard"></i>Dashboard</a>
                </li>
                @if(auth()->user()->role == 'admin')
                <li class="{{ setActive('user', 'active') }}">
                    <a href="{{ url('user') }}"> <i class="menu-icon ti-user"></i>User</a>
                </li>
                <li class="{{ setActive('category', 'active') }}">
                    <a href="{{ url('category') }}"> <i class="menu-icon ti-layout-media-center-alt"></i>Kategori Barang </a>
                </li>
                <li class="{{ setActive('product', 'active') }}">
                    <a href="{{ url('product') }}"> <i class="menu-icon ti-layout-media-center-alt"></i>Barang </a>
                </li>
                <li class="{{ setActive('supplier', 'active') }}">
                    <a href="{{ url('supplier') }}"> <i class="menu-icon ti-truck"></i>Supplier </a>
                </li>
                <li class="{{ setActive('faculty', 'active') }}">
                    <a href="{{ url('faculty') }}"> <i class="menu-icon ti-layout-column3"></i>Fakultas </a>
                </li>
                <li class="{{ setActive('major', 'active') }}">
                    <a href="{{ url('major') }}"> <i class="menu-icon ti-layout-column2"></i>Jurusan </a>
                </li>
                <li class="{{ setActive('purchasing', 'active') }}">
                    <a href="{{ url('purchasing') }}"> <i class="menu-icon fa fa-cart-plus"></i>Pembelian Barang </a>
                </li>
                <li class="{{ setActive('sale', 'active') }}">
                    <a href="{{ url('sale') }}"> <i class="menu-icon fa fa-cart-arrow-down"></i>Penjualan Barang </a>
                </li>
                @elseif(auth()->user()->role == 'eksekutif')
                <li class="{{ setActive('report', 'active') }}">
                    <a href="{{ url('report') }}"> <i class="menu-icon fa fa-line-chart"></i>Grafik </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>
</aside>