<nav class="sidebar">
  <div class="sidebar-header" style="background-color: #727cf5 !important">
    <a href="#" class="sidebar-brand" style="color: #fff !important">
      RAB<span style="color: #fff !important"> Material</span>
    </a>
    <div class="sidebar-toggler not-active" style="background-color: #727cf5 !important">
      <span style="background-color: #fff !important"></span>
      <span style="background-color: #fff !important"></span>
      <span style="background-color: #fff !important"></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <!-- <li class="nav-item nav-category">Menu</li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li> -->

      <li class="nav-item nav-category">Master</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" href="#material" role="button" aria-expanded="false" aria-controls="material">
          <i class="link-icon" data-feather="database"></i>
          <span class="link-title">Material</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down link-arrow"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </a>
        <div class="collapse" id="material" style="">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.master.material.jenis-material.index') }}" class="nav-link">
                Jenis Material
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.master.material.satuan-material.index') }}" class="nav-link">
                Satuan Material
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.master.material.material.index') }}" class="nav-link">
                Material
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" href="#jasa" role="button" aria-expanded="false" aria-controls="jasa">
          <i class="link-icon" data-feather="check-circle"></i>
          <span class="link-title">Jasa</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down link-arrow"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </a>
        <div class="collapse" id="jasa">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.master.jasa.jenis-jasa.index') }}" class="nav-link">
                Jenis Jasa
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.master.jasa.satuan-jasa.index') }}" class="nav-link">
                Satuan Jasa
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.master.jasa.jasa.index') }}" class="nav-link">
                Jasa
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" href="#pekerjaan" role="button" aria-expanded="false" aria-controls="pekerjaan">
          <i class="link-icon" data-feather="briefcase"></i>
          <span class="link-title">Pekerjaan</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down link-arrow"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </a>
        <div class="collapse" id="pekerjaan" style="">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.master.pekerjaan.satuan-sub-pekerjaan.index') }}" class="nav-link">
                Satuan Sub Pekerjaan
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.master.pekerjaan.pekerjaan.index') }}" class="nav-link">
                Pekerjaan
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item nav-category">Proyek</li>
      <li class="nav-item">
        <a href="{{ route('admin.proyek.proyek.index') }}" class="nav-link">
          <i class="link-icon" data-feather="book"></i>
          <span class="link-title">RAB, RAP & Rekap Material</span>
        </a>
      </li>

      <li class="nav-item nav-category">Logout</li>
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="link-icon" data-feather="log-out"></i>
          <span class="link-title">Logout</span>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </a>
      </li>
    </ul>
  </div>
</nav>