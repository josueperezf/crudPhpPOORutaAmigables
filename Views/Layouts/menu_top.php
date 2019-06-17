<nav>
    <div class="nav-wrapper">
        <a class="brand-logo" href="#">
           <font size='+2'> Bodega </font> 
        </a>
      <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul id="nav-mobile" class="left hide-on-med-and-down">
        <li class="nav-item">
            <a class="nav-link" href="#">
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;&nbsp;
            </a>
        </li>
            <li class="nav-item">
                <a class="nav-link" href="bodega/index">Bodegas</a>
            </li>
        </ul>
        <ul class="right ">
            <!-- Authentication Links -->
                <li class='hide-on-med-and-down'>
                    <a class="nav-link modal-trigger"  href="#modal1" 
                        onClick="$('.modal').modal();libAjaxGet('bodega/iniciar','divModal')"
                    >
                        Iniciar Session
                    </a>
                </li>
        </ul>
    </div>
  </nav>