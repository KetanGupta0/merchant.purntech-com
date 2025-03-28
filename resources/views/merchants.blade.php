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
</style>
<div class="container-fluid py-5" style="margin-top: 30px">
    <h2 class="text-center text-white my-5">Merchants</h2>
    <div class="row align-items-stretch mt-5">
        <div class="col-lg-12 mb-3">
            <div class="h-100">
                <div class="position-relative rounded-5">
                    <div class="overlay-div rounded-5"></div>
                    <img src="{{ asset('/assets/images/merchant-bg.jpg') }}" class="rounded-5"
                        style="width:100%;height:500px;object-fit: cover;" alt="" data-aos="fade-up">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-5">
    <div class="row m-0">
        <div class="col-lg-9 m-auto" data-aos="fade-up">
            <h2 class="text-center text-white">Merchant Features and Benefits at Glance</h2>
            <p class="text-center text-white mt-3">Streamlined payment processing consolidated into a robust and
                dependable
                platform. Achieve effortless integration with the support of PurnTech's dedicated technical team.
                Experience lightning-fast settlement with T+0 settlement on PurnTech funds, coupled with internal
                real-time exchange.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="row mt-5 align-items-stretch">
                <div class="col-lg-12 mb-3" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3 h-100"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/1.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">Save Time and Effort</h4>
                        <p class="font-12 m-0">With PurnTech's Mobile Payments feature, accept payments anytime,
                            anywhere, ensuring your business stays agile and robust, no matter where you are.</p>
                    </div>
                </div>
                <div class="col-lg-12 mb-3" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3 h-100"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/2.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">Make informed decisions</h4>
                        <p class="font-12 m-0">Drive your business forward with custom reports tailored to your needs.
                            Analyze your business growth and witness your enterprise soar to new heights.
                        </p>
                    </div>
                </div>
                <div class="col-lg-12 mb-3" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3 h-100"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/3.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">Find the best fit</h4>
                        <p class="font-12 m-0">Store credit card and customer information securely for fast, hassle-free
                            recurring payments, enhancing convenience for both you and your customers.
                        </p>
                    </div>
                </div>
                <div class="col-lg-12 mb-3" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3 h-100"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/10.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">Wider access</h4>
                        <p class="font-12 m-0">Our invoicing feature simplifies the process of sending bills to
                            customers, enhancing the customer experience and allowing you to showcase your creations
                            online.</p>
                    </div>
                </div>
                <div class="col-lg-12 mb-3" data-aos="fade-up">
                    <div class="rounded-3 text-center text-white d-flex align-items-center justify-content-center flex-column p-3 gap-3 h-100"
                        style="backdrop-filter: blur(5px);background-color: rgba(255, 255, 255, .05)">
                        <div class="rounded-2 border p-2" style="border-color:#eb5d1e !important">
                            <img src="{{ asset('/assets/images/exp/11.svg') }}" style="width:42px;height:42px"
                                alt="">
                        </div>
                        <h4 class="m-0">Stay up to date</h4>
                        <p class="font-12 m-0">Our invoicing feature simplifies the process of sending bills to
                            customers, enhancing the customer experience and allowing you to showcase your creations
                            online.
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