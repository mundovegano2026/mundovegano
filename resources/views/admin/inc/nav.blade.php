<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
            
            <a href="javascript:void(0);" id="show-username">
                <span>
                    {{ $currentUser->name }}
                </span>
            </a> 
            
        </span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive-->
    <nav>
        <!-- 
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->
        <ul>
            <li class="top-menu-invisible">
                <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Painel Principal</span></a>
                <ul>
                    <li class="{{ (request()->is('admin')) ? 'active' : '' }}">  
                        <a href="/admin" title="Painel Principal"><span class="menu-item-parent">Painel Principal</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/tasks') || request()->is('admin/validation*')) ? 'active' : '' }}">
                        <a href="/admin/tasks" title="Dashboard"><span class="menu-item-parent">Tarefas</span></a>
                    </li>
                </ul>	
            </li>
            <li class="top-menu-invisible">
                <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Entidades</span></a>
                <ul>
                    <li class="{{ (request()->is('admin/products')) ? 'active' : '' }}">
                        <a href="/admin/products" title="Produtos"><i class="fa fa-lg fa-fw fa-shopping-basket"></i> <span class="menu-item-parent">Produtos</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/reviews')) ? 'active' : '' }}">
                        <a href="/admin/reviews" title="Avaliações"><i class="fa fa-lg fa-fw fa-star"></i> <span class="menu-item-parent">Avaliações</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/chains')) ? 'active' : '' }}">
                        <a href="/admin/chains" title="Cadeias"><i class="fa fa-lg fa-fw fa-copyright"></i> <span class="menu-item-parent">Cadeias</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/categories*')) ? 'active' : '' }}">
                        <a href="/admin/categories" title="Categorias"><i class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">Categorias</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/stores')) ? 'active' : '' }}">
                        <a href="/admin/stores" title="Lojas"><i class="fa fa-lg fa-fw fa-shopping-cart"></i> <span class="menu-item-parent">Lojas</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/brands')) ? 'active' : '' }}">
                        <a href="/admin/brands" title="Marcas"><i class="fa fa-lg fa-fw fa-trademark"></i> <span class="menu-item-parent">Marcas</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/tags')) ? 'active' : '' }}">
                        <a href="/admin/tags" title="Tags"><i class="fa fa-lg fa-fw fa-tags"></i> <span class="menu-item-parent">Tags</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/users')) ? 'active' : '' }}">
                        <a href="/admin/users" title="Utilizadores"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">Utilizadores</span></a>
                    </li>
                    <li class="{{ (request()->is('admin/valuelists')) ? 'active' : '' }}">
                        <a href="/admin/valuelists" title="Valores"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">Valores</span></a>
                    </li>
                </ul>
            </li>					
        </ul>
    </nav>			

    <span class="minifyme" data-action="minifyMenu"> 
        <i class="fa fa-arrow-circle-left hit"></i> 
    </span>

</aside>