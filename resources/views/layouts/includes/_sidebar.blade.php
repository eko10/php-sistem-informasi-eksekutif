<aside id="left-panel" class="{{ (Request::segment(1) == 'report') ? 'left-panel' : 'left-panel open-menu' }}">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{ setActive('dashboard', 'active') }}">
                    <a href="/dashboard"><i class="menu-icon fa fa-dashboard"></i>Dashboard</a>
                </li>
                @if(auth()->user()->role == 'admin')
                <li class="{{ setActive('user', 'active') }}">
                    <a href="/user"> <i class="menu-icon ti-user"></i>User </a>
                </li>
                <li class="{{ setActive('category', 'active') }}">
                    <a href="/category"> <i class="menu-icon ti-layout-media-center-alt"></i>Kategori Barang </a>
                </li>
                <li class="{{ setActive('product', 'active') }}">
                    <a href="/product"> <i class="menu-icon ti-layout-media-center-alt"></i>Barang </a>
                </li>
                <li class="{{ setActive('supplier', 'active') }}">
                    <a href="/supplier"> <i class="menu-icon ti-truck"></i>Supplier </a>
                </li>
                <li class="{{ setActive('faculty', 'active') }}">
                    <a href="/faculty"> <i class="menu-icon ti-layout-column3"></i>Fakultas </a>
                </li>
                <li class="{{ setActive('major', 'active') }}">
                    <a href="/major"> <i class="menu-icon ti-layout-column2"></i>Jurusan </a>
                </li>
                <li class="{{ setActive('purchasing', 'active') }}">
                    <a href="/purchasing"> <i class="menu-icon fa fa-cart-plus"></i>Pembelian Barang </a>
                </li>
                <li class="{{ setActive('sale', 'active') }}">
                    <a href="/sale"> <i class="menu-icon fa fa-cart-arrow-down"></i>Penjualan Barang </a>
                </li>
                @elseif(auth()->user()->role == 'eksekutif')
                <li class="{{ setActive('report', 'active') }}">
                    <a href="/report"> <i class="menu-icon ti-book"></i>Laporan </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>
</aside>