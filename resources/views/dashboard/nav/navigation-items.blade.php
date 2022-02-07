<ul class="list-reset sm:flex sm:flex-row md:flex-col z-50 pt-3 p-1 md:py-5 md:px-2 sm:text-center md:text-left">
    @if (in_array(Auth::user()->role, ['administrator', 'staff']))
        {{-- Dashboard --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('dashboard') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('dashboard')) text-purple-400 @endif">
                <i class="fas fa-chart-bar pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Analytics') }}</span>
            </a>
        </li>
        {{-- Items --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('/items') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('items')) text-purple-400 @endif">
                <i class="fas fa-bars pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Items') }}</span>
            </a>
            <ul class="sub-item">
               {{-- New Item --}}
                <li class="mr-3 flex-1 p-2 relative sm:p-0">
                    <span class="absolute hidden sm:block left-1.5 -top-2"><i class="fas fa-sort-up text-purple-400"></i></span>
                    <a href="{{ url('/items/create') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('items/create')) text-purple-400 @endif">
                        <i class="fas fa-plus pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('New Item') }}</span>
                    </a>
                </li> 
            </ul>
        </li>
        {{-- Inbound --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('/inbound') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('inbound')) text-purple-400 @endif">
                <i class="fas fa-sign-in-alt pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Inbound') }}</span>
            </a>
        </li>
        {{-- Outbound --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('/outbound') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('outbound')) text-purple-400 @endif">
                <i class="fas fa-sign-out-alt pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Outbound') }}</span>
            </a>
        </li>
        {{-- Reports --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('/reports') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('reports')) text-purple-400 @endif">
                <i class="fas fa-file-alt pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Reports') }}</span>
            </a>
        </li>
        {{-- Customers --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('/customers') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('customers')) text-purple-400 @endif">
                <i class="fas fa-user pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Customers') }}</span>
            </a>
            <ul class="sub-item">
                {{-- New User --}}
                 <li class="mr-3 flex-1 p-2 relative sm:p-0">
                    <span class="absolute hidden sm:block left-1.5 -top-2"><i class="fas fa-sort-up text-purple-400"></i></span>
                     <a href="{{ url('/register') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('register')) text-purple-400 @endif">
                         <i class="fas fa-user-plus pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('New Customer') }}</span>
                     </a>
                 </li> 
             </ul>
        </li>
    @endif                  
    @if (Auth::user()->role == 'administrator')
        {{-- Settings --}}
        <li class="menu-item mr-3 flex-1 p-2 sm:p-0">
            <a href="{{ url('/settings') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-purple-400 @if(request()->is('settings')) text-purple-400 @endif">
                <i class="fas fa-cog pr-0 md:pr-3 fa-2x h-5"></i><span class="inline pb-1 md:pb-0 text-sm md:text-base">{{ __('Settings') }}</span>
            </a>
        </li>
    @endif
</ul>