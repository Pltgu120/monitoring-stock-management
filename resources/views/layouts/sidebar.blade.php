  <!-- Main Sidebar Container -->
  <aside class="main-sidebar bg-dark elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
      <img src="{{asset('images/pln-batam.png')}}" alt="AdminLTE Logo" class="brand-image">
      <span class="brand-text font-weight-bold">{{config('app.name')}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <nav class="mt-2 text-capitalize">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">{{ __("menu") }}</li>
         <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link text-white">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              {{ __("dashboard") }}
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-white">
              <i class="nav-icon fas fa-box"></i>
              <p>
                {{ __("master data") }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang HAR ELEKTRIK
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR ELEKTRIk -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('barang')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Elektrik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('barang-rusak')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Rusak</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('barang-bekas-pakai')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Bekas Pakai</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang HAR MEKANIK
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR Mekanik -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('barang-mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Mekanik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('barang-rusak-mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Rusak</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('barang-bekas-pakai-mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Bekas Pakai</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang KIMIA
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR Mekanik -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('barang-kimia')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Barang Kimia</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>

          </li>
          @if(Auth::user()->role->name != 'staff')

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-white">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>
              {{ __("transaction") }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang HAR ELEKTRIK
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR ELEKTRIk -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('transaksi.masuk')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Transaksi Masuk Elektrik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('transaksi.keluar')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Transaksi Keluar Elektrik</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang HAR MEKANIK
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR Mekanik -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('transaksi.masuk.mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Transaksi Masuk Mekanik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('transaksi.keluar.mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Transaksi Keluar Mekanik</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang KIMIA
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR Mekanik -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('transaksi.masuk.kimia')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Transaksi Masuk Kimia</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('transaksi.keluar.kimia')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Transaksi Keluar Kimia</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>

          </li>
          @endif

          
          {{-- <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-white">
              <i class="nav-icon fas fa-print"></i>
              <p>
              {{ __("report") }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('laporan.masuk')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("incoming goods report") }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('laporan.keluar')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("outgoing goods report") }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('laporan.stok')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("stock report") }}</p>
                </a>
              </li>
            </ul>
          </li> --}}

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-white">
              <i class="nav-icon fas fa-print"></i>
              <p>
              Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            {{-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('transaksi.masuk')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("incoming transaction") }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('transaksi.keluar')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("outbound transaction") }}</p>
                </a>
              </li>
            </ul> --}}
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang HAR ELEKTRIK
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR ELEKTRIk -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('laporan.masuk')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Masuk Elektrik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('laporan.keluar')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Keluar Elektrik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('laporan.stok')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Stok Elektrik</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang HAR MEKANIK
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR Mekanik -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('laporan.masuk.mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Masuk Mekanik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('laporan.keluar.mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Keluar Mekanik</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('laporan.stok.mekanik')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Stok Mekanik</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link text-white">
                  <i class="fas fa-angle-right"></i>
                  <p>
                    Gudang KIMIA
                  </p>
                </a>
                <!-- Submenu untuk Gudang HAR Mekanik -->
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('laporan.masuk.kimia')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Masuk Kimia</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('laporan.keluar.kimia')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Keluar Kimia</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('laporan.stok.kimia')}}" class="nav-link text-white">
                      <i class="fas fa-caret-right nav-icon"></i>
                      <p>Laporan Stok Kimia</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>

          </li>


          <li class="nav-header">{{ __("others") }}</li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-white">
              <i class="nav-icon fas fa-cog"></i>
              <p>
              {{ __("setting") }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            @if(Auth::user()->role->name != 'staff')
              <li class="nav-item">
                <a href="{{route('settings.employee')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("employee") }}</p>
                </a>
              </li>
            @endif
              <li class="nav-item">
                <a href="{{route('settings.profile')}}" class="nav-link text-white">
                <i class="fas fa-angle-right"></i>
                  <p>{{ __("profile") }}</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
              <a href="{{route('login.delete')}}" class="nav-link text-white">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                {{ __("messages.logout") }}
                </p>
              </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
