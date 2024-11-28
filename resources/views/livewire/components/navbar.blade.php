<nav class="bg-green-900 text-white shadow-lg py-4">
    <div class="container flex justify-between items-center px-4">
        <div class="flex items-center space-x-4">
            <a href="#" class="text-2xl font-bold"><x-authentication-card-logo />&nbsp; MilkFarmers Co.</a>
            <ul class="flex space-x-6">
                <li><a href="#about" class="hover:underline">About Us</a></li>
                <li><a href="#services" class="hover:underline">Our Services</a></li>
                <li><a href="#contact" class="hover:underline">Contact</a></li>
            </ul>
        </div>
        <div>
            @guest
                <button class="bg-white text-green-700 px-4 py-2 rounded shadow dropdown-hover relative">
                    Join Us
                    <div class="dropdown-menu absolute right-0 mt-2 bg-white shadow-lg py-2 hidden">
                        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-green-100">Login</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-green-100">Register</a>
                    </div>
                </button>
            @else
                <form method="POST" action="{{ route('logout') }}" class="rounded-75">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-green-600">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>

<script>
    document.querySelector('.dropdown-hover').addEventListener('mouseenter', () => {
        document.querySelector('.dropdown-menu').classList.remove('hidden');
    });
    document.querySelector('.dropdown-hover').addEventListener('mouseleave', () => {
        document.querySelector('.dropdown-menu').classList.add('hidden');
    });
</script>
