<div id="sidebar"
        class="min-w-[200px] p-2 min-h-screen border-r border-slate-700 -ms-[280px] hidden transition-all lg:block lg:ms-0 absolute z-10 lg:relative bg-slate-900">
        <ul class="flex flex-col gap-1 w-full">
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('dashboard.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('dashboard.index') }}"><i class="bi bi-graph-up text-lg"></i>
                                Dashboard</a></li>
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('products.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('products.index')}}"><i class="bi bi-box-seam"></i> Product
                                Management</a>
                </li>
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('orders.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('orders.index')}}"><i class="bi bi-cart2"></i> Customer
                                Orders</a></li>
                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('productcategories.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('productcategories.index')}}"><i class="bi bi-collection"></i> Product
                                Category Management</a>
                        @endif
                </li>
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('servicehistories.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('servicehistories.index')}}"><i class="bi bi-card-checklist"></i> Service
                                History</a></li>
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('customers.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('customers.index')}}"><i class="bi bi-people"></i> Customer
                                Management</a></li>
                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('users.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('users.index')}}"><i class="bi bi-person-fill-gear"></i> User
                                Management</a></li>
                @endif
                @if(auth()->user()->role == 'superadmin')
                <ul class="menu p-0 rounded-box w-full">
                        <li>
                                <details open>
                                        <summary
                                                class="p-2 w-full  hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('report-analysis.*')) text-white bg-slate-800 @else text-slate-400 @endif">
                                                <i class="bi bi-graph-up"></i> Report & Analysis
                                        </summary>
                                        <ul>
                                                <li><a href="{{route('report-analysis.product-sales')}}">Product
                                                                Sales</a></li>
                                                <li><a href="{{route('report-analysis.service-history')}}">Service
                                                                History</a></li>
                                        </ul>
                                </details>
                        </li>
                </ul>
                @endif
                <li><a class="p-2 w-full block hover:text-white hover:bg-slate-800 rounded-2xl @if(request()->routeIs('settings.*')) text-white bg-slate-800 @else text-slate-400 @endif"
                                href="{{ route('settings.index')}}"><i class="bi bi-gear"></i> Settings</a></li>
        </ul>
</div>