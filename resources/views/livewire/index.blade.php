<div>
    @livewire('components.navbar')

    <!-- Hero Section -->
    <section class="bg-green-100 py-20">
        <div class="container mx-auto text-center px-6">
            <h1 class="text-4xl font-bold text-green-700 mb-4">Empowering Farmers for a Sustainable Future</h1>
            <p class="text-lg text-gray-700 mb-8">
                At MilkFarmers Co., we help farmers improve productivity, monitor their sales, and support their
                livelihoods. Join our network today!
            </p>
            <button class="bg-green-700 text-white px-6 py-3 rounded shadow hover:bg-green-800">
                @guest
                    Get Started
                @else
                    Dashboard
                @endguest
            </button>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-3xl font-bold text-green-700 mb-4">About Us</h2>
                <p class="text-gray-600 mb-6">
                    We are dedicated to supporting milk farmers by providing tools and knowledge to maximize their
                    productivity. Our mission is to empower local farmers and foster sustainable agriculture.
                </p>
                <p class="text-gray-600">
                    Join us to access modern farming techniques, sell your milk seamlessly, and be part of impactful
                    development projects.
                </p>
            </div>
            <div>
                <img src="{{ asset('images/web-assets/industrial-milking.jpg') }}" alt="About Us"
                    class="rounded shadow w-full">
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section id="services" class="py-20 bg-green-50">
        <div class="container mx-auto px-6">
            <h2 class="text-center text-3xl font-bold text-green-700 mb-10">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow rounded">
                    <img src="{{ asset('images/web-assets/cows-whisper.jpg') }}" alt="Service 1"
                        class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold text-green-700">Milk Sales Tracking</h3>
                    <p class="text-gray-600 mt-2">
                        Monitor your sales, manage payments, and ensure a steady income stream.
                    </p>
                </div>
                <div class="bg-white p-6 shadow rounded">
                    <img src="{{ asset('images/web-assets/manual-milking.jpg') }}" alt="Service 2"
                        class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold text-green-700">Farming Workshops</h3>
                    <p class="text-gray-600 mt-2">
                        Learn modern techniques to boost productivity and improve quality.
                    </p>
                </div>
                <div class="bg-white p-6 shadow rounded">
                    <img src="{{ asset('images/web-assets/row-of-cows-being-milked.jpeg') }}" alt="Service 3"
                        class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold text-green-700">Development Projects</h3>
                    <p class="text-gray-600 mt-2">
                        Access funding and projects aimed at community and agricultural growth.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-green-700 mb-4">Contact Us</h2>
            <p class="text-gray-600 mb-8">
                Have questions? Need assistance? Reach out to us!
            </p>
            <form action="{{ route('contact-us') }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-3xl mx-auto">
                <input type="text" placeholder="Your Name" class="p-3 border rounded focus:outline-green-700"
                    required>
                <input type="email" placeholder="Your Email" class="p-3 border rounded focus:outline-green-700"
                    required>
                <textarea placeholder="Your Message" class="p-3 border rounded col-span-2 focus:outline-green-700" required></textarea>
                <button type="submit"
                    class="bg-green-700 text-white px-6 py-3 rounded shadow hover:bg-green-800 col-span-2">
                    Send Message
                </button>
            </form>
        </div>
    </section>

    @livewire('components.footer')
</div>
