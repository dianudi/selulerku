<div class="navbar bg-base-100 shadow-sm">
    <div class="flex-1 flex items-center">
        <div id="sidebarToggle" class="lg:hidden mr-2">
            <i id="sidebarToggleIcon" class="bi bi-list text-2xl transition-all"></i>
        </div>
        <a class="btn btn-ghost text-xl">@yield('title')</a>
    </div>
    <div class="flex-none">
        <div class="dropdown dropdown-end hidden">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="badge badge-sm indicator-item">8</span>
                </div>
            </div>
            <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-1 mt-3 w-52 shadow">
                <div class="card-body">
                    <span class="text-lg font-bold">8 Items</span>
                    <span class="text-info">Subtotal: $999</span>
                    <div class="card-actions">
                        <button class="btn btn-primary btn-block">View cart</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="flex items-center gap-2">
                <i class="bi bi-person-circle text-2xl"></i>
                <div>{{auth()->user()->name}}</div>
            </div>

            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li><a href="{{route('account.index')}}">Settings</a></li>
                <li>
                    <form class="w-full block" action="{{route('auth.logout')}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="cursor-pointer block w-full text-start text-white font-bold py-1 rounded"
                            type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>