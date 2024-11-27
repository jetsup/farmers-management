<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright &copy;{{ date('Y') }}
            {{ env('APP_NAME', "Farmer's") }} - All rights reserved</span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Here for farmer's who are always here
            for <a href="{{ route('dashboard-admin') }}" target="_blank">us</a> - {{ env('APP_NAME', "Farmer's") }}</span>
    </div>
</footer>
