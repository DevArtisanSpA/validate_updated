<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-flex justify-content-center">
  <div class="col-md-9 w-100 px-0">
      <div class="row mb-0 justify-content-between">
          <a class="navbar-brand" href="{{ url('/home') }}"><img src={{ asset('img/logo.png') }} width="150" /></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
              <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Left Side Of Navbar -->
              <ul class="navbar-nav mr-auto">

              </ul>

              <!-- Right Side Of Navbar -->
              <ul class="navbar-nav ml-auto">
                  <!-- Authentication Links -->
                  @guest
                  <li class="nav-item d-none">
                      <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                  </li>
                  @if (Route::has('register'))
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                  </li>
                  @endif
                  @else
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ __('Empresas') }} <span class="caret"></span>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ url('/companies') }}">
                              {{ __('Lista de empresas') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/companies/create') }}">
                              {{ __('Agregar empresa') }}
                          </a>
                          <hr class="line">
                          <a class="dropdown-item" href="{{ url('/branch_offices') }}">
                              {{ __('Lista de sucursales') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/branch_offices/create') }}">
                              {{ __('Agregar sucursal') }}
                          </a>
                          <hr class="line">
                          <a class="dropdown-item" href="{{ url('/documents/company/fixed') }}">
                              {{ __('Lista documentos empresa fijo') }}
                          </a>
                          @if (Auth::user()->id_type == 1 || Auth::user()->id_company != null)
                          <a class="dropdown-item" href="{{ url('/documents/company/create/fixed') }}">
                              {{ __('Agregar documentos empresa fijo') }}
                          </a>
                          @endif
                          <hr class="line">
                          <a class="dropdown-item" href="{{ url('/documents/company/monthly') }}">
                              {{ __('Lista documentos empresa mensual') }}
                          </a>
                          @if (Auth::user()->id_type == 1 || Auth::user()->id_company != null)
                          <a class="dropdown-item" href="{{ url('/documents/company/create/monthly') }}">
                              {{ __('Agregar documentos empresa mensual') }}
                          </a>
                          @endif
                      </div>
                  </li>
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ __('Empleados') }} <span class="caret"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ url('/employees') }}">
                              {{ __('Lista de empleados') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/employees/create') }}">
                              {{ __('Agregar empleado') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/employee/terminate') }}">
                              {{ __('Finiquitar Empleado') }}
                          </a>
                          <hr class="line">
                          <a class="dropdown-item" href="{{ url('/documents/employee/fixed') }}">
                              {{ __('Lista documentos empleado fijo') }}
                          </a>
                          @if (Auth::user()->id_type == 1 || Auth::user()->id_company != null)
                          <a class="dropdown-item" href="{{ url('/documents/employee/create/fixed') }}">
                              {{ __('Agregar documentos empleado fijo') }}
                          </a>
                          @endif
                          <hr class="line">

                          <a class="dropdown-item" href="{{ url('/documents/employee/monthly') }}">
                              {{ __('Lista documentos empleado mensual') }}
                          </a>
                          @if (Auth::user()->id_type == 1 || Auth::user()->id_company != null)
                          <a class="dropdown-item" href="{{ url('/documents/employee/create/monthly') }}">
                              {{ __('Agregar documentos empleado mensual') }}
                          </a>
                          @endif
                      </div>
                  </li>
                  @if (Auth::user()->id_type == 1)
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ __('Revisión') }} <span class="caret"></span>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ url('/review/company/fixed') }}">
                              {{ __('Empresa fijo') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/review/company/monthly') }}">
                              {{ __('Empresa mensual') }}
                          </a>
                          <hr class="line">
                          <a class="dropdown-item" href="{{ url('/review/employee/fixed') }}">
                              {{ __('Empleado fijo') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/review/employee/monthly') }}">
                              {{ __('Empleado mensual') }}
                          </a>
                      </div>
                  </li>
                  @endif
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ __('Reportes') }} <span class="caret"></span>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ url('/reports') }}">
                              {{ __('Reportes empresas') }}
                          </a>
                          @if (Auth::user()->id_type == 1)
                          <a class="dropdown-item" href="{{ url('/certificate') }}">
                              {{ __('Generar certificado') }}
                          </a>
                          @endif
                      </div>
                  </li>
                  @if (Auth::user()->id_type == 1)
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ __('Usuarios') }} <span class="caret"></span>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ url('/users') }}">
                              {{ __('Lista de usuarios') }}
                          </a>
                          <a class="dropdown-item" href="{{ url('/users/create') }}">
                              {{ __('Agregar usuario') }}
                          </a>
                      </div>
                  </li>
                  @endif
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ Auth::user()->name }} <span class="caret"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          @if (Auth::user()->id_type > 1 && Auth::user()->id_company != null)
                          <a class="dropdown-item" href="{{ url('/companies/' . Auth::user()->id_company . '/edit') }}">
                              Detalle empresa
                          </a>
                          @endif
                          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                              {{ __('Logout') }}
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                      </div>
                  </li>
                  @endguest
              </ul>
          </div>
      </div>
  </div>
</nav>
