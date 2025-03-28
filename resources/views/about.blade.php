<style>
    .overlay-div {
        position: absolute;
        pointer-events: none;
        width: 100%;
        height: 100%;
        top: 0;
        background: linear-gradient(to bottom, transparent 30%, black 103%);
        z-index: 1;
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

    .slider-track2 {
        display: flex;
        gap: 20px;
        animation: scrolls 20s linear infinite;
        width: calc(200px * 12);
    }

    .slide {
        flex: 0 0 200px;
        text-align: center;
        background: #232323;
        padding: 2px;
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

    @keyframes scrolls {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(0);
        }
    }

    .testimonial-heading {
        font-size: 19vw;
        font-weight: bold;
        text-align: center;
        color: white;
        z-index: 2;
        position: absolute;
        width: 100%;
    }

    .testimonial-container {
        position: relative;
        z-index: 5;
        bottom: -26px;
    }

    .scroll-bar {
        width: 2px;
        background: #ccc;
        height: 300px;
        position: relative;
        overflow: hidden;
    }

    .scroll-indicator {
        width: 2px;
        background: #eb5d1e;
        height: 50px;
        position: absolute;
        top: 0;
        left: 0;
        transition: top 0.1s linear;
    }

    @media (max-width:767px) {
        .indic {
            display: none !important;
        } 
    }
</style>
<div class="container-fluid py-5">
    <div class="row align-items-stretch mt-5">
        <div class="col-lg-12 mb-3">
            <div class="h-100">
                <div class="position-relative rounded-5">
                    <div class="overlay-div rounded-5"></div>
                    <img src="{{ asset('/assets/images/about-bg.jpg') }}" class="rounded-5"
                        style="width:100%;height:400px;object-fit: cover;" alt="" data-aos="fade-up">
                    <div class="position-absolute w-100" style="bottom:5%;z-index:2">
                        <div class="text-white text-center" data-aos="fade-down">
                            <h1 class="fw-bold">Hey There! Welcome to</h1>
                            <h1 class="fw-bold" style="color: #eb5d1e">PurnTech</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-5">
    <h1 class="text-center text-white" data-aos="fade-up">About our Company</h1>
    <div class="row mt-5">
        <div class="col-lg-6 mb-3" data-aos="fade-up">
            <h3 style="font-weight: 100;color:white;line-height: unset;">At PurnTech, we're not just about payments;
                we're about possibilities. Our journey began with a singular
                goal: <span style="color: #eb5d1e">to forge lasting partnerships with ISVs,</span> revolutionizing the
                payment landscape. With over 75 years
                of combined industry expertise, we've crafted a program designed to propel your success. From seamless
                integrations to unparalleled <span style="color: #eb5d1e">support and innovative revenue streams, we're
                    here to empower your growth.</span>
                Join us at the forefront of fintech innovation, where every partnership is a step towards a brighter
                future.</h3>
        </div>
        <div class="col-lg-6 home-accordion-cont">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                    <h2 class="accordion-header ">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            01. Our Mission
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Our mission is clear: to create, manage, and nurture integrated payment partnerships with
                            ISVs, ensuring that every client receives the support and tools they need to thrive. With a
                            commitment to excellence and a passion for progress, we're dedicated to shaping the future
                            of payments, one partnership at a time.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3" data-aos="fade-up">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            02. Our Vision
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            At PurnTech, our vision is to redefine the landscape of payment solutions by fostering
                            innovation, reliability, and accessibility. We envision a world where businesses of all
                            sizes can seamlessly integrate cutting-edge payment technologies into their operations,
                            empowering growth and driving success.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-5">
    <h2 class="text-center text-white brands-trust" data-aos="fade-up">Brands we have worked with</h2>
    <div class="my-5 border py-2 rounded-5" data-aos="fade-up">
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
        <div class="brands-slider">
            <div class="slider-track2">
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
<div class="container-fluid py-5">
    <div class="position-relative">
        <div class="framer-se0ayk2"></div>
        <h1 class="testimonial-heading">VALUES</h1>
    </div>
    <div class="testimonial-container text-center w-100 mt-1">
        <div>
            <img src="{{ asset('/assets/images/circle img.png') }}" class="rounded-circle border"
                style="width:250px" alt="">
        </div>
    </div>
</div>
<div class="container-fluid py-5">
    <h2 class="text-center text-white brands-trust my-5">Our 4 Stage Process</h2>
    <div class="row">
        <div class="col-lg-7 mx-auto">
            <div class="d-flex gap-5">
                <div class="d-flex align-items-center flex-column indic">
                    <h1 style="color: #eb5d1e">01</h1>
                    <div class="scroll-bar">
                        <div class="scroll-indicator"></div>
                    </div>
                </div>
                <div>
                    <div class="rounded-5 p-4" data-aos="fade-up"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-pill p-2 font-12 py-1 fw-bold"
                            style="width:fit-content;color: #eb5d1e;backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                            STEP 01</div>
                        <h4 class="text-white my-2">Onboarding and Consultation</h4>
                        <p class="m-0 text-light">In this initial phase, we work closely with your business to
                            understand your payment needs,
                            challenges, and goals. Our team conducts a thorough consultation to identify the best
                            payment
                            solutions tailored to your specific business requirements.</p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-5 mt-5">
                <div class="d-flex align-items-center flex-column indic">
                    <h1 style="color: #eb5d1e">02</h1>
                    <div class="scroll-bar">
                        <div class="scroll-indicator"></div>
                    </div>
                </div>
                <div>
                    <div class="rounded-5 p-4" data-aos="fade-up"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-pill p-2 font-12 py-1 fw-bold"
                            style="width:fit-content;color: #eb5d1e;backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                            STEP 02</div>
                        <h4 class="text-white my-2">Solution Design</h4>
                        <p class="m-0 text-light">Based on the consultation, we develop a customized payment strategy
                            that aligns with your business operations. This includes choosing the right payment methods,
                            ensuring security compliance, and integrating payment systems to streamline your
                            transactions.</p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-5 mt-5">
                <div class="d-flex align-items-center flex-column indic">
                    <h1 style="color: #eb5d1e">03</h1>
                    <div class="scroll-bar">
                        <div class="scroll-indicator"></div>
                    </div>
                </div>
                <div>
                    <div class="rounded-5 p-4" data-aos="fade-up"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-pill p-2 font-12 py-1 fw-bold"
                            style="width:fit-content;color: #eb5d1e;backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                            STEP 03</div>
                        <h4 class="text-white my-2">Implementation and Integration</h4>
                        <p class="m-0 text-light">After finalizing the strategy, we seamlessly integrate the PayKuber
                            platform with your existing systems. Our technical team ensures a smooth deployment of
                            payment gateways, data synchronization, and transaction monitoring to enhance efficiency.
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-5 mt-5">
                <div class="d-flex align-items-center flex-column indic">
                    <h1 style="color: #eb5d1e">04</h1>
                </div>
                <div>
                    <div class="rounded-5 p-4" data-aos="fade-up"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-pill p-2 font-12 py-1 fw-bold"
                            style="width:fit-content;color: #eb5d1e;backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                            STEP 04</div>
                        <h4 class="text-white my-2">Ongoing Support and Optimization</h4>
                        <p class="m-0 text-light">Once the system is live, we provide continuous support, monitoring
                            your transactions in real-time. We analyze data for optimization opportunities and make
                            necessary updates to ensure your payment processes are running at peak performance.
                        </p>
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

<script>
    $(document).ready(function() {
        $(window).on("scroll", function() {
            let scrollTop = $(window).scrollTop();
            let maxScroll = $(document).height() - $(window).height();

            $(".scroll-bar").each(function() {
                let $bar = $(this);
                let $indicator = $bar.find(".scroll-indicator");

                let barHeight = $bar.height() - $indicator.height();
                let $section = $bar.closest(".d-flex");
                let sectionTop = $section.offset().top;
                let sectionHeight = $section.outerHeight();

                if (scrollTop >= sectionTop - $(window).height() && scrollTop <= sectionTop +
                    sectionHeight) {
                    let relativeScroll = scrollTop - (sectionTop - $(window).height());
                    let sectionScrollHeight = $(window).height() + sectionHeight;
                    let position = (relativeScroll / sectionScrollHeight) * barHeight;

                    position = Math.max(0, Math.min(position, barHeight));
                    $indicator.css("top", position + "px");
                } else {
                    $indicator.css("top", "0px");
                }
            });
        });
    });
</script>
