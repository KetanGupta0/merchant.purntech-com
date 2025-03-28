<style>
    :root {
        --token-4fdd7769-e1d0-45b2-8e3d-fe484baba321: rgb(235, 93, 30);
    }

    .highlighted {
        background: #eb5d1e;
        color: white;
        padding: 5px 15px;
        display: inline-block;
        position: relative;
        border-radius: 3px;
        transform: skewX(-10deg);
    }

    .banner-section2 {
        background: linear-gradient(180deg, rgba(191, 51, 19, 0) 16.35%, rgb(191, 51, 19) 77.35%);
        height: 855px;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .content-wrapper {
        position: absolute;
        bottom: 0;
        z-index: 10;
        text-align: center;
    }

    .rounded-image {
        width: 70%;
    }

    .brands-slider {
        overflow: hidden;
        white-space: nowrap;
        position: relative;
        width: 100%;
        padding: 10px 0;
    }

    .slider-track {
        display: flex;
        gap: 20px;
        animation: scroll 20s linear infinite;
        width: calc(200px * 12);
    }

    .slide {
        flex: 0 0 200px;
        text-align: center;
        background: #232323;
        padding: 15px;
        border-radius: 10px;
    }

    .slide img {
        width: 120px;
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    .overlay-div {
        position: absolute;
        pointer-events: none;
        width: 100%;
        height: 100%;
        top: 0;
        background: linear-gradient(to bottom, transparent 65%, black 103%);
        z-index: 1;
    }

    .text-slide {
        top: -172px;
        position: relative;
    }

    .exp-cont {
        height: 800px;
        background-size: cover !important;
        width: 100%;
        background-repeat: no-repeat !important;
        position: relative;
    }

    .framer-se0ayk {
        background: radial-gradient(45.1% 44.7% at 52.6% 42.8%, var(--token-4fdd7769-e1d0-45b2-8e3d-fe484baba321, #d94c00) 0%, var(--token-2f560859-5998-4075-847c-9f666c5cfc0b, rgb(0, 0, 0)) 100%);
        bottom: 0;
        flex: none;
        height: 100%;
        left: calc(50.00000000000002% - 100% / 2);
        mix-blend-mode: multiply;
        opacity: .91;
        overflow: hidden;
        position: absolute;
        width: 100%;
        z-index: 1;
    }    

    .testimonial-heading {
        font-size: 11vw;
        font-weight: bold;
        text-align: center;
        color: white;
        z-index: 2;
        position: absolute;
        width: 100%;
    }

    .framer-se0ayk2 {
        background: radial-gradient(45.1% 44.7% at 52.6% 42.8%, var(--token-4fdd7769-e1d0-45b2-8e3d-fe484baba321, #d94c00) 0%, transparent 100%);
        bottom: 0;
        flex: none;
        height: 100%;
        left: calc(50.00000000000002% - 100% / 2);
        mix-blend-mode: multiply;
        opacity: .91;
        overflow: hidden;
        /* position: fixed; */
        width: 100%;
        z-index: 3;
    }

    .testimonial-container {
        height: 550px;
        overflow: hidden;
        position: relative;
        z-index: 5;
    }

    .testimonial-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: continuousSlide 5s linear infinite;
    }

    @keyframes continuousSlide {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-50%);
        }
    }

    .blog-card {
        transition: 0.5s;
    }

    .blog-card img{
        height:272px;
    }

    .blog-card:hover {
        margin-top: -10px;
        transition: 0.5s;
    }

    .blog-card:hover img {
        filter: grayscale(1);
        transition: 0.5s;
    }

    .payout-container {
        position: relative;
        width: 100%;
        min-height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payout-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset('/assets/images/pay-out.svg') }}');
        z-index: 1;
        opacity: 0.1;
    }

    .home-accordion-cont .accordion .accordion-item {
        backdrop-filter: blur(5px) !important;
        background-color: rgba(255, 255, 255, .05) !important;
        color: #ffffff !important;
        border-radius: 45px !important;
    }

    .home-accordion-cont .accordion .accordion-button {
        backdrop-filter: blur(5px) !important;
        background-color: rgba(255, 255, 255, .05) !important;
        color: #ffffff !important;
        box-shadow: none !important;
        outline: none !important;
        border-radius: 45px !important;
        font-weight: bold !important;
        font-size: 12px !important;
        padding-top: 24px !important;
        padding-bottom: 24px !important;
    }

    .home-accordion-cont .accordion .accordion-button::after {
        background-color: #eb5d1e;
        padding: 12px !important;
        background-size: auto !important;
        border-radius: 5px;
    }
    @media (max-width:767px) {
        .blog-card img{
            height:180px !important;
        }
        .banner-section2 {
            height: 200px;
        }
        .exp-cont {
            height: auto;
        }

        .text-slide {
            top: -20px;
        }

        .brands-trust {
            margin-top: 103px;
        }        
    }
</style>
<div class="container" style="margin-top:180px">
    <div class="banner-section mt-5 text-center text-white">
        <h1 class="display-2 position-realtive" data-aos="fade-up">
            Seamless
            <span class="highlighted">Payments,</span>
            Smarter
            <img src="{{ asset('/assets/images/bell.png') }}" class="rounded-circle p-1"
                style="width:72px;height:72px;background-color:#eb5d1e" alt="">
            Business.
        </h1>
        <p class="my-4" data-aos="fade-up">Empower your business with seamless payments, actionable financial
            insights, <br> and all the
            tools you need on
            one powerful platform.</p>
        <a data-aos="fade-up" href="{{ url('/contact-us') }}" class="btn btn-primary rounded-pill px-4 py-3">GET STARTED
            <i class="fa fa-long-arrow-right"></i> </a>
    </div>
</div>
<div class="position-relative">
    <div class="overlay-div"></div>
    <div class="banner-section2">
        <div class="content-wrapper">
            <div class="position-relative" style="display: flex; align-items: center; justify-content: center;">
                <h1 class="display-1 text-white text-slide">
                    <marquee>Seamless Payments, Smarter Business</marquee>
                </h1>
                <div class="position-absolute" style="bottom: 0" data-aos="fade-up">
                    <img src="{{ asset('/assets/images/banner2.png') }}" class="rounded-image" alt="">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid pt-5">
    <div class="rounded-5 border p-3" style="width:fit-content;margin-top:-70px;z-index:2;position: absolute;">
        <div class="d-flex gap-2 flex-wrap text-center text-lg-start justify-content-center justify-content-lg-start">
            <div>
                <img src="{{ asset('/assets/images/clients/1.jpg') }}" class="rounded-circle"
                    style="width:50px;height:50px;" alt="">
                <img src="{{ asset('/assets/images/clients/2.jpg') }}" class="rounded-circle"
                    style="width:50px;height:50px;margin-left:-15px" alt="">
                <img src="{{ asset('/assets/images/clients/3.jpg') }}" class="rounded-circle"
                    style="width:50px;height:50px;margin-left:-15px" alt="">
                <img src="{{ asset('/assets/images/clients/4.jpg') }}" class="rounded-circle"
                    style="width:50px;height:50px;margin-left:-15px" alt="">
            </div>
            <div>
                <p class="m-0" style="color:#eb5d1e">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </p>
                <p class="m-0 text-white">160+ 5 Star Reviews</p>
            </div>
        </div>
    </div>
    <h2 class="text-center text-white brands-trust" data-aos="fade-up">Brands that trust us</h2>
    <div class="my-5" data-aos="fade-up">
        <div class="brands-slider">
            <div class="slider-track">
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/1.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/2.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/3.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/4.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/5.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/6.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/7.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/8.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/9.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/10.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/11.png') }}" alt="">
                </div>
                <div class="slide">
                    <img src="{{ asset('/assets/images/brands-slider/12.png') }}" alt="">
                </div>
            </div>
        </div>

    </div>
</div>
<div class="row w-100 m-0">
    <div class="col-lg-8 m-auto" data-aos="fade-up">
        <h2 class="text-center text-white">Partnership Program</h2>
        <p class="text-center text-white">Join the PurnTech Payments Partnership Program and step into a world where
            collaboration meets innovation! We've
            crafted a dynamic ecosystem where ISVs and Developers thrive, offering an array of competitive
            advantages in
            payment solutions and integration options.</p>
    </div>
    <div class="col-lg-12 p-0">
        <div class="exp-cont mt-4 d-flex align-items-center justify-content-center flex-column pb-5"
            style="background: url({{ asset('/assets/images/exp-img.png') }})">
            <div class="framer-se0ayk"></div>
            <div class="d-flex flex-wrap align-items-center justify-content-center w-100 my-5" style="z-index: 2"
                data-aos="fade-up">
                <div class="rounded-circle text-center text-white d-flex align-items-center justify-content-center flex-column"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, 0.1);height:187px;width:187px">
                    <h2>15+</h2>
                    <p class="font-12">Years of Expertise</p>
                </div>
                <div class="rounded-circle text-center text-white d-flex align-items-center justify-content-center flex-column"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, 0.1);height:187px;width:187px">
                    <h2>300+</h2>
                    <p class="font-12">Successful Payment Solutions</p>
                </div>
                <div class="rounded-circle text-center text-white d-flex align-items-center justify-content-center flex-column"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, 0.1);height:187px;width:187px">
                    <h2>150+</h2>
                    <p class="font-12">Satisfied Business Clients</p>
                </div>
                <div class="rounded-circle text-center text-white d-flex align-items-center justify-content-center flex-column"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, 0.1);height:187px;width:187px">
                    <h2>160+</h2>
                    <p class="font-12">5 Star Reviews</p>
                </div>
            </div>
            <div class="row w-100 mt-5 align-items-center" style="z-index: 2">
                <div class="col-lg-4 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/1.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">APIs</h4>
                        <p class="font-12 m-0">Fast-track your integration journey with our developer-friendly APIs,
                            primed to launch your success from a single point of connection!</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/2.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">White Label</h4>
                        <p class="font-12 m-0">Elevate your brand visibility and reputation by seamlessly showcasing
                            our comprehensive suite of services, including processing platform, gateway, top-tier
                            security, fraud solutions, and streamlined onboarding processes to your esteemed clients.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/3.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">Gateway Only</h4>
                        <p class="font-12 m-0">Unlock seamless payment integration for clients retaining their merchant
                            accounts with our Gateway Only option, seamlessly integrated within your software
                            application.</p>
                    </div>
                </div>
            </div>
            <div class="row w-100 mt-5 align-items-center justify-content-center" style="z-index: 2">
                <div class="col-lg-3 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div>
                            <img src="{{ asset('/assets/images/exp/4.svg') }}" style="width:30px;height:30px"
                                alt="">
                        </div>
                        <p class="m-0">Continuous Innovation</p>
                    </div>
                </div>
                <div class="col-lg-3 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div>
                            <img src="{{ asset('/assets/images/exp/4.svg') }}" style="width:30px;height:30px"
                                alt="">
                        </div>
                        <p class="m-0">Dedicated Support</p>
                    </div>
                </div>
                <div class="col-lg-3 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div>
                            <img src="{{ asset('/assets/images/exp/4.svg') }}" style="width:30px;height:30px"
                                alt="">
                        </div>
                        <p class="m-0">Positive Client Experiences</p>
                    </div>
                </div>
                <div class="col-lg-3 mb-3 mb-lg-0" data-aos="fade-up">
                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div>
                            <img src="{{ asset('/assets/images/exp/4.svg') }}" style="width:30px;height:30px"
                                alt="">
                        </div>
                        <p class="m-0">Commitment to Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-5">
    <h1 class="text-center text-white" data-aos="fade-up">Resources</h1>
    <div class="row mt-5 align-items-stretch">
        <div class="col-lg-6 mb-3" data-aos="fade-up">
            <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100" style="background-color: #1F1D1D">
                <div class="rounded-3 p-2" style="background-color:#2A2929 !important;width:fit-content">
                    <img src="{{ asset('/assets/images/exp/7.svg') }}" style="width:30px;height:30px"
                        alt="">
                </div>
                <p class="m-0">Integration & Devices</p>
                <p class="m-0 font-12">Revolutionize your payment experience with our integration prowess and
                    cutting-edge devices! Access our omni-channel payment platform through a single point of
                    integration, featuring developer-friendly SOAP and REST APIs. Dive into seamless testing with a
                    sandbox account and launch effortlessly with our intuitive toolkit and SDKs. Plus, empower your
                    transactions with semi-integrated EMV and mobile POS devices, ensuring flexibility and convenience
                    for both you and your customers. It's integration reimagined for the modern world!"</p>
            </div>
        </div>
        <div class="col-lg-6 mb-3" data-aos="fade-up">
            <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100" style="background-color: #1F1D1D">
                <div class="rounded-3 p-2" style="background-color:#2A2929 !important;width:fit-content">
                    <img src="{{ asset('/assets/images/exp/5.svg') }}" style="width:30px;height:30px"
                        alt="">
                </div>
                <p class="m-0">Security & Compliance</p>
                <p class="m-0 font-12">Safeguard your transactions with ironclad security and robust compliance
                    measures! At PurnTech, we take security seriously, boasting PCI-DSS compliance and employing true
                    tokenization and Point-to-Point Encryption. Rest assured, every transaction – from recurring
                    payments to online invoicing – is fortified against potential threats. Our Fraud Suite offers
                    comprehensive protection for an omni-channel solution, ensuring peace of mind for you and your
                    clients.</p>
            </div>
        </div>
        <div class="col-lg-6 mb-3 mb-lg-0" data-aos="fade-up">
            <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100" style="background-color: #1F1D1D">
                <div class="rounded-3 p-2" style="background-color:#2A2929 !important;width:fit-content">
                    <img src="{{ asset('/assets/images/exp/6.svg') }}" style="width:30px;height:30px"
                        alt="">
                </div>
                <p class="m-0">World Class Support</p>
                <p class="m-0 font-12">Experience unparalleled support tailored to your success journey! At PurnTech,
                    we go the extra mile to ensure your seamless experience. Our dedicated team stands ready to provide
                    technical expertise, integration support, and exemplary customer service, guaranteeing a smooth
                    sailing for your business operations.</p>
            </div>
        </div>
        <div class="col-lg-6 mb-3 mb-lg-0" data-aos="fade-up">
            <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100" style="background-color: #1F1D1D">
                <div class="rounded-3 p-2" style="background-color:#2A2929 !important;width:fit-content">
                    <img src="{{ asset('/assets/images/exp/8.svg') }}" style="width:30px;height:30px"
                        alt="">
                </div>
                <p class="m-0">Revenue & Growth</p>
                <p class="m-0 font-12">Fuel your business growth and skyrocket your revenue with PurnTech's dynamic
                    partnership program! Our dedicated partner managers are your strategic allies, offering a suite of
                    marketing initiatives tailored to drive conversions and boost profits.</p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="position-relative">
        <div class="framer-se0ayk2"></div>
        <h1 class="testimonial-heading">TESTIMONIAL</h1>
    </div>
    <div class="testimonial-container mx-auto mt-1">
        <div class="testimonial-wrapper">

            <!-- Testimonial 1 -->
            <div class="col-12 col-lg-6 mx-auto mb-3">
                <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                    <p class="m-0" style="color:#eb5d1e">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </p>
                    <div>
                        <img src="{{ asset('/assets/images/exp/9.svg') }}" style="width:50px;height:50px"
                            alt="">
                    </div>
                    <p class="m-0 text-center" style="margin-top:-40px !important">Working with the folks at PurnTech
                        Payments has been an enjoyable experience as everyone has been very transparent and responsive
                        during the sales and implementation process.
                    </p>
                    <hr>
                    <div class="text-white d-flex align-items-center gap-2">
                        <div>
                            <img class="rounded-4" src="{{ asset('/assets/images/clients/1.jpg') }}"
                                style="width:50px;height:50px" alt="">
                        </div>
                        <p class="m-0 fw-bold font-12">John Wesly</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-12 col-lg-6 mx-auto mb-3">
                <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                    <p class="m-0" style="color:#eb5d1e">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </p>
                    <div>
                        <img src="{{ asset('/assets/images/exp/9.svg') }}" style="width:50px;height:50px"
                            alt="">
                    </div>
                    <p class="m-0 text-center" style="margin-top:-40px !important">Working with the folks at PurnTech
                        Payments has been an enjoyable experience as everyone has been very transparent and responsive
                        during the sales and implementation process.
                    </p>
                    <hr>
                    <div class="text-white d-flex align-items-center gap-2">
                        <div>
                            <img class="rounded-4" src="{{ asset('/assets/images/clients/2.jpg') }}"
                                style="width:50px;height:50px" alt="">
                        </div>
                        <p class="m-0 fw-bold font-12">Emily Smith</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-12 col-lg-6 mx-auto mb-3">
                <div class="rounded-4 text-white d-flex p-4 gap-3 flex-column h-100"
                    style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                    <p class="m-0" style="color:#eb5d1e">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </p>
                    <div>
                        <img src="{{ asset('/assets/images/exp/9.svg') }}" style="width:50px;height:50px"
                            alt="">
                    </div>
                    <p class="m-0 text-center" style="margin-top:-40px !important">Working with the folks at PurnTech
                        Payments has been an enjoyable experience as everyone has been very transparent and responsive
                        during the sales and implementation process.
                    </p>
                    <hr>
                    <div class="text-white d-flex align-items-center gap-2">
                        <div>
                            <img class="rounded-4" src="{{ asset('/assets/images/clients/3.jpg') }}"
                                style="width:50px;height:50px" alt="">
                        </div>
                        <p class="m-0 fw-bold font-12">Michael Brown</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid py-5">
    <div class="row w-100 m-0">
        <div class="col-lg-8 m-auto" data-aos="fade-up">
            <h2 class="text-center text-white">Our Specialities</h2>
            <p class="text-center text-white">Our platform is designed to optimize your business's payment operations,
                offering cutting-edge solutions that drive growth, efficiency, and reliability. With a focus on secure
                transactions, insightful analytics, and unparalleled uptime, PurnTech empowers businesses to scale with
                confidence and precision.</p>
        </div>
    </div>

    <div class="row align-items-stretch mt-5">
        <div class="col-lg-8 mb-3" data-aos="fade-up">
            <div class="h-100">
                <div class="position-relative">
                    <img src="{{ asset('/assets/images/person.jpg') }}" class="rounded-5" style="width:100%"
                        alt="">
                    <div class="position-absolute" style="bottom:5%;left:3%">
                        <div class="text-white">
                            <h1 class="display-1 fw-bold">150%</h1>
                            <p class="fw-bold text-capitalize">Payment Efficiency Boost</p>
                            <p class="py-2"
                                style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">We
                                enhance payment processes, leading to faster transactions and improved cash flow.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3" data-aos="fade-up">
            <div class="h-100 rounded-5 p-3 d-flex flex-column justify-content-between text-white"
                style="background:#1F1D1D">
                <div>
                    <h1 class="display-1 fw-bold">$74M</h1>
                    <p class="fw-bold text-capitalize">Revenue Processed</p>
                </div>
                <div>
                    <p>We’ve successfully processed over $74M in transactions for our clients globally.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-stretch">
        <div class="col-lg-6 mb-3 mb-lg-0" data-aos="fade-up">
            <div class="h-100">
                <div class="position-relative">
                    <img src="{{ asset('/assets/images/back.jpg') }}" class="rounded-5" style="width:100%"
                        alt="">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div>
                            <img src="{{ asset('/assets/images/circle img.png') }}" class="rounded-circle border"
                                style="width:100%" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-up">
            <div class="h-100 rounded-5 p-3 d-flex flex-column justify-content-between" style="background:#eb5d1e">
                <div class="text-end">
                    <p class="fw-bold text-capitalize m-0">Uptime Reliability</p>
                    <h1 class="display-1 fw-bold m-0">99.9%</h1>
                </div>
                <div>
                    <p>Our platform ensures nearly perfect uptime, providing uninterrupted payment processing and
                        financial insights.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="row w-100 m-0">
        <div class="col-lg-6" data-aos="fade-up">
            <h2 class=" text-white">Dive into our collection of engaging blog posts</h2>
        </div>
        <div class="row w-100 mt-3">
            <div class="col-lg-12 text-start text-lg-end" data-aos="fade-up">
                <a href="{{ url('/blogs') }}" class="btn btn-primary rounded-pill">READ
                    BLOGS <i class="fa fa-long-arrow-right"></i> </a>
            </div>
        </div>
    </div>
    <div class="row align-items-stretch mt-5">
        <div class="col-lg-4 mb-3" data-aos="fade-up">
            <a href="#" class="h-100 text-decoration-none">
                <div class="card border-0  blog-card"
                    style="background: #0C0C0C;border-top-left-radius: 15px !important;border-top-right-radius: 15px !important">
                    <div class="position-relative">
                        <div class="overlay-div"></div>
                        <img src="{{ asset('/assets/images/blog/1.jpg') }}"
                            style="overflow: hidden;border-top-left-radius: 15px !important;border-top-right-radius: 15px !important"
                            class="w-100" alt="">
                        <div class="position-absolute" style="bottom:8px;left:8px;z-index:2">
                            <div class="d-flex gap-2">
                                <p class="m-0 font-12 py-1 text-white rounded-pill px-3" style="background:#171717"><i
                                        class="fa fa-calendar" style="color: #eb5d1e"></i> FEB 28, 2025</p>
                                <p class="m-0 font-12 py-1 text-white rounded-pill px-3" style="background:#171717"><i
                                        class="fa fa-file-text" style="color: #eb5d1e"></i> ARTICLE</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div style="background:#171717" class="w-100 p-2 rounded">
                            <p class="text-white m-0">Global Transactions: PurnTech’s Payout Services for a
                                Seamless Future</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 mb-3" data-aos="fade-up">
            <a href="#" class="h-100 text-decoration-none">
                <div class="card border-0  blog-card"
                    style="background: #0C0C0C;border-top-left-radius: 15px !important;border-top-right-radius: 15px !important">
                    <div class="position-relative">
                        <div class="overlay-div"></div>
                        <img src="{{ asset('/assets/images/blog/2.jpg') }}"
                            style="overflow: hidden;border-top-left-radius: 15px !important;border-top-right-radius: 15px !important"
                            class="w-100" alt="">
                        <div class="position-absolute" style="bottom:8px;left:8px;z-index:2">
                            <div class="d-flex gap-2">
                                <p class="m-0 font-12 py-1 text-white rounded-pill px-3" style="background:#171717"><i
                                        class="fa fa-calendar" style="color: #eb5d1e"></i> FEB 28, 2025</p>
                                <p class="m-0 font-12 py-1 text-white rounded-pill px-3" style="background:#171717"><i
                                        class="fa fa-file-text" style="color: #eb5d1e"></i> ARTICLE</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div style="background:#171717" class="w-100 p-2 rounded">
                            <p class="text-white m-0">Global Transactions: PurnTech’s Payout Services for a
                                Seamless Future</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 mb-3" data-aos="fade-up">
            <a href="#" class="h-100 text-decoration-none">
                <div class="card border-0  blog-card"
                    style="background: #0C0C0C;border-top-left-radius: 15px !important;border-top-right-radius: 15px !important">
                    <div class="position-relative">
                        <div class="overlay-div"></div>
                        <img src="{{ asset('/assets/images/blog/3.jpg') }}"
                            style="overflow: hidden;border-top-left-radius: 15px !important;border-top-right-radius: 15px !important"
                            class="w-100" alt="">
                        <div class="position-absolute" style="bottom:8px;left:8px;z-index:2">
                            <div class="d-flex gap-2">
                                <p class="m-0 font-12 py-1 text-white rounded-pill px-3" style="background:#171717"><i
                                        class="fa fa-calendar" style="color: #eb5d1e"></i> FEB 28, 2025</p>
                                <p class="m-0 font-12 py-1 text-white rounded-pill px-3" style="background:#171717"><i
                                        class="fa fa-file-text" style="color: #eb5d1e"></i> ARTICLE</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div style="background:#171717" class="w-100 p-2 rounded">
                            <p class="text-white m-0">Global Transactions: PurnTech’s Payout Services for a
                                Seamless Future</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row align-items-stretch">
        <div class="col-lg-12 mb-3">
            <div class="h-100">
                <div class="payout-container py-5">
                    <div class="payout-bg-overlay"></div>
                    <div class="position-relative" style="transform: none;z-index: 2;width:80%">
                        <div class="text-white text-center">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-lg-2 mb-3 mb-lg-0" data-aos="fade-up">
                                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                                        <div>
                                            <img src="{{ asset('/assets/images/exp/4.svg') }}"
                                                style="width:30px;height:30px" alt="">
                                        </div>
                                        <p class="m-0">Simple</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3 mb-lg-0" data-aos="fade-up">
                                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                                        <div>
                                            <img src="{{ asset('/assets/images/exp/4.svg') }}"
                                                style="width:30px;height:30px" alt="">
                                        </div>
                                        <p class="m-0">Secure</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3 mb-lg-0" data-aos="fade-up">
                                    <div class="rounded-pill text-center text-white d-flex align-items-center justify-content-center py-2 gap-1"
                                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                                        <div>
                                            <img src="{{ asset('/assets/images/exp/4.svg') }}"
                                                style="width:30px;height:30px" alt="">
                                        </div>
                                        <p class="m-0">Swift</p>
                                    </div>
                                </div>
                            </div>
                            <h1 class="fw-bold m-0 mt-5" data-aos="fade-up">Pay-Out System</h1>
                            <p class="py-5 m-0" data-aos="fade-up">Your transactions related to global business
                                pursuits and purchases have
                                been reduced from a herculean task to a simple, straightforward process. Accomplish your
                                payout settlements from the customers throughout the world at your earliest possible
                                convenience with the duration period of the settlements reduced to mere 72 hours
                                (Transaction capture 2 business days). Have your settlement cycle customized based on
                                various influencing factors like the bank approvals, your business vertical and the
                                possible perils of a business
                            </p>
                            <div data-aos="fade-up">
                                <a href="{{ url('/resources') }}" class="btn btn-dark rounded-pill font-12">READ MORE
                                    <i class="fa fa-long-arrow-right"></i> </a>
                                <a href="{{ url('/contact-us') }}" class="btn btn-primary rounded-pill font-12">CALL
                                    BACK REQUEST <i class="fa fa-long-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="row w-100 m-0">
        <div class="col-lg-8 m-auto" data-aos="fade-up">
            <h2 class="text-center text-white">Got Questions?</h2>
            <h2 class="text-center text-white"> We've Got Answers!</h2>
        </div>
    </div>
    <div class="mt-5">
        <div class="row">
            <div class="col-lg-8 m-auto home-accordion-cont">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                        <h2 class="accordion-header ">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                What is PurnTech, and how can it benefit my business?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                PurnTech is a comprehensive payment and financial insights platform that simplifies your
                                business’s payment operations. It provides seamless transactions, real-time financial
                                data, and powerful tools to help you optimize cash flow and make informed decisions.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Is PurnTech suitable for businesses of all sizes?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                We prioritize security by implementing advanced encryption protocols and complying with
                                industry-standard regulations like PCI-DSS. Your payments and financial data are
                                safeguarded at every step to ensure maximum security.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                What types of payments can I process with PurnTech?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                PurnTech supports a wide range of payment methods, including credit/debit cards, bank
                                transfers, digital wallets, and other online payment gateways, providing flexibility for
                                both you and your customers.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                        <h2 class="accordion-header ">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Can I access real-time financial insights through PurnTech?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Yes, PurnTech provides real-time financial insights, helping you track transactions,
                                monitor cash flow, and generate detailed reports. These insights are designed to help
                                you make informed business decisions.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Does PurnTech integrate with other business software?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Absolutely! PurnTech seamlessly integrates with popular accounting and business
                                management software, allowing you to sync data and streamline operations without any
                                hassle.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                How can I get started with PurnTech?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Getting started is easy! Simply sign up for an account on our website, choose the
                                payment solutions that suit your needs, and start processing payments and gaining
                                financial insights immediately.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>