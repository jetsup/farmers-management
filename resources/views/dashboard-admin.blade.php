<x-layout-admin title="Dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @livewire('components.sidebar-admin')

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        @livewire('components.navbar-admin')
        <!-- partial -->
        <div class="main-panel">
            
            <div class="content-wrapper">
                {{ $slot }}
            </div>

            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            @livewire('components.footer-admin')
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
</x-layout-admin>
