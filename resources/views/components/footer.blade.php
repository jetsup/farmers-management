<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-6">
                    <div class="footer-info">
                        <h3>{{ env('APP_NAME', 'Contact Us') }}</h3>
                        <p class="pb-3"><em>We are located.</em></p>
                        <p>
                            {{ env('LOCATION_STREET', 'Kenyatta Avenue') }} Street
                            <br>
                            {{ env('LOCATION_COUNTY', 'CITY') }}, {{ env('POSTAL_ADDRESS', '535022') }}-{{ env('ZIP_CODE', '535022') }},
                            {{ env('OUR_COUNTRY', 'KENYA') }}
                            <br><br>
                            <strong>Phone:</strong> {{ env('CONTACT_PHONE', '0790909090') }}<br>
                            <strong>Email:</strong> {{ env('MESSAGE_EMAIL', 'info@mail.com') }}<br>
                        </p>
                        <div class="social-links mt-3">
                            <a href="{{ env('TWITTER_USERNAME', '#') }}" class="twitter">
                                <i class="bx bxl-twitter"></i>
                            </a>
                            <a href="{{ env('FACEBOOK_USERNAME', '#') }}" class="facebook">
                                <i class="bx bxl-facebook"></i>
                            </a>
                            <a href="{{ env('INSTAGRAM_USERNAME', '#') }}" class="instagram">
                                <i class="bx bxl-instagram"></i>
                            </a>
                            <a href="{{ env('GOOGLE_PLUS', '#') }}" class="google-plus">
                                <i class="bx bxl-google-plus"></i>
                            </a>
                            <a href="{{ env('LINKEDIN_USERNAME', '#') }}" class="linkedin">
                                <i class="bx bxl-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="/">Home</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/about-us">About us</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/services">Services</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/our-terms">Terms of service</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/privacy-policy">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Organise Events</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Stay up-to-date with our weekly notifications and updates. <strong>We promise not to spam your
                            mailbox</strong>
                    </p>
                    <form action="/subscribe/newsletter" method="post">
                        <input type="email" name="email" placeholder="Enter Your Email">
                        <input type="submit" value="Subscribe">
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy;{{ now()->format('Y') }} <strong><span>{{ env('APP_NAME', 'Our Name') }}</span></strong>.
            All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/bootslander-free-bootstrap-landing-page-template/ -->
            {{-- TODO: CREDIT --}}
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </div>
</footer><!-- End Footer -->
