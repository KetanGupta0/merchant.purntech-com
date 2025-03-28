<!-- start page title -->
<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">Dashboard</h4>
            <!-- Live INR Currency Pair Ticker -->
            <div id="currency-ticker" class="currency-ticker">
                <!-- Live ticker data will be appended dynamically -->

            </div>
            <button id="showStatusButton" class="btn btn-primary">Service Status</button>
            <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">PurnTech Service Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Iframe will be dynamically inserted here -->
                            <div id="status-content">
                                <p>Loading status information...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function showStatus() {
                    console.log("showStatus function called");

                    // Initialize the modal
                    const modalElement = document.getElementById('statusModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Dynamically create and load the clipped iframe
                    const statusContent = document.getElementById('status-content');
                    statusContent.innerHTML = ''; // Clear any previous content

                    // Create a div container to clip the view
                    const clippedIframeContainer = document.createElement('div');
                    clippedIframeContainer.style.position = 'relative';
                    clippedIframeContainer.style.width = '100%';
                    clippedIframeContainer.style.height = '340px';
                    clippedIframeContainer.style.overflow = 'hidden';

                    // Create the iframe
                    const iframe = document.createElement('iframe');
                    iframe.src = 'https://status.razorpay.com'; // Razorpay Status Page
                    iframe.style.width = '100%';
                    iframe.style.height = '950px'; // Extra height to allow clipping
                    iframe.style.marginTop = '-255px'; // Adjust this value to hide the header
                    iframe.style.border = 'none';

                    // Append the iframe to the container
                    clippedIframeContainer.appendChild(iframe);

                    // Append the container to the modal content
                    statusContent.appendChild(clippedIframeContainer);
                }

                // Attach the click event listener
                document.getElementById('showStatusButton').addEventListener('click', showStatus);
            </script>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>

            </div>
        </div>
    </div>
</div>
<style>
    /* Reduced-width Ticker Styling */
    .currency-ticker {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 10px 15px;
        margin-top: 15px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        position: relative;
        width: 50%;
        /* Reduced width for a compact ticker */
        margin: 0 auto;
        /* Center the ticker */
    }

    .currency-ticker span {
        display: inline-flex;
        align-items: center;
        margin-right: 20px;
        color: #007bff;
        /* Attractive blue text */
    }

    /* Flag Styling */
    .flag {
        width: 20px;
        height: 15px;
        margin-right: 5px;
        border: 1px solid #ddd;
    }

    /* Arrow Icons for Rate Changes */
    .rate-up {
        color: green;
        margin-left: 5px;
    }

    .rate-down {
        color: red;
        margin-left: 5px;
    }

    /* Animation for Scrolling Ticker */
    .ticker-content {
        display: inline-block;
        white-space: nowrap;
        animation: ticker-scroll 15s linear infinite;
    }

    @keyframes ticker-scroll {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }
</style>

<script>
    const previousRates = {}; // Object to store previous rates for comparison

    async function fetchCurrencyRates() {
        // Free API providing rates against INR
        const apiUrl = "https://open.er-api.com/v6/latest/INR"; // Example API endpoint
        const majorPairs = [{
                code: "USD",
                flag: "https://flagcdn.com/w320/us.png"
            },
            {
                code: "EUR",
                flag: "https://flagcdn.com/w320/eu.png"
            },
            {
                code: "GBP",
                flag: "https://flagcdn.com/w320/gb.png"
            },
            {
                code: "JPY",
                flag: "https://flagcdn.com/w320/jp.png"
            },
            {
                code: "AUD",
                flag: "https://flagcdn.com/w320/au.png"
            },
            {
                code: "CAD",
                flag: "https://flagcdn.com/w320/ca.png"
            },
            {
                code: "SGD",
                flag: "https://flagcdn.com/w320/sg.png"
            },
            {
                code: "CHF",
                flag: "https://flagcdn.com/w320/ch.png"
            },
            {
                code: "NZD",
                flag: "https://flagcdn.com/w320/nz.png"
            },
        ];
        const markup = 0.02; // Add 2% markup to rates

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            const baseRateINR = data.rates["INR"]; // Base rate for INR (should always be 1 in this case)
            const tickerDiv = document.getElementById("currency-ticker");
            const tickerContent = document.createElement("div");
            tickerContent.classList.add("ticker-content");

            // Loop through majorPairs and calculate rates against INR
            majorPairs.forEach(({
                code,
                flag
            }) => {
                if (data.rates[code]) {
                    const actualRate = (baseRateINR / data.rates[code]).toFixed(
                    4); // Correct INR conversion
                    const rateWithMarkup = (actualRate * (1 + markup)).toFixed(2); // Apply markup

                    // Determine if the rate has increased or decreased
                    const previousRate = previousRates[code];
                    let trendIcon = "";
                    if (previousRate) {
                        if (rateWithMarkup > previousRate) {
                            trendIcon = `<span class="rate-up">▲</span>`; // Up arrow
                        } else if (rateWithMarkup < previousRate) {
                            trendIcon = `<span class="rate-down">▼</span>`; // Down arrow
                        }
                    }
                    previousRates[code] = rateWithMarkup; // Update previous rate

                    // Add flag, rate, and trend icon
                    const pairHTML = `
                        <span>
                            <img src="${flag}" alt="${code}" class="flag">
                            ${code}/INR: ₹${rateWithMarkup} ${trendIcon}
                        </span>
                    `;
                    tickerContent.innerHTML += pairHTML;
                }
            });

            tickerDiv.innerHTML = ""; // Clear previous content
            tickerDiv.appendChild(tickerContent); // Add new ticker content
        } catch (error) {
            console.error("Error fetching currency rates:", error);
            document.getElementById("currency-ticker").innerHTML =
                "<span>Unable to fetch live currency rates at the moment.</span>";
        }
    }

    // Fetch rates on page load
    document.addEventListener("DOMContentLoaded", fetchCurrencyRates);

    // Refresh the ticker every minute
    setInterval(fetchCurrencyRates, 60000);
</script>




<!-- end page title -->
@isset($transaction_limits)
    @php
        $totalPayin = (float) $transaction_limits->total_payin_limit;
        $totalPayout = (float) $transaction_limits->total_payout_limit;
        $availablePayin = (float) $transaction_limits->available_payin_limit;
        $availablePayout = (float) $transaction_limits->available_payout_limit;

        $payinPercent = ($availablePayin * 100.0) / $totalPayin;
        $payoutPercent = ($availablePayout * 100.0) / $totalPayout;
    @endphp

    @if ($payinPercent >= 10 && $payinPercent <= 20)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Your payin limits is running low!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif($payinPercent > 0 && $payinPercent < 10)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Your payin limits is running very low!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif($payinPercent == 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Your payin limits is over!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($payoutPercent >= 10 && $payoutPercent <= 20)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Your payout limits is running low!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif($payoutPercent > 0 && $payoutPercent < 10)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Your payout limits is running very low!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif($payoutPercent == 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Your payout limits is over!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endisset
@isset($merchant)
    @if ($merchant->merchant_is_verified == 'Approved')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Your account is approved!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @else
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Your account is not approved!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endisset

<div class="alert alert-success alert-dismissible fade show" role="alert">
    ⚠️⚠️ PayOut Pipe Switching !! Payouts will be queued / you may need to process them later again... ⌛⌛ ℹ️🕑

</div>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    ⚠️⚠️ PayIN Down for Standard Merchants !! Contact Support ,if you can face any issue ...ℹ️🕑

</div>
<div class="row">
    <div class="col-xxl-12">
        <div class="d-flex flex-column h-100">
            {{-- <div class="row h-100">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="alert alert-warning border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
                                <i data-feather="alert-triangle" class="text-warning me-2 icon-sm"></i>
                                <div class="flex-grow-1 text-truncate">
                                    Your free trial expired in <b>17</b> days.
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="#" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                                </div>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-sm-8">
                                    <div class="p-3">
                                        <p class="fs-16 lh-base">Upgrade your plan from a <span class="fw-semibold">Free
                                                trial</span>, to ‘Premium Plan’ <i class="mdi mdi-arrow-right"></i></p>
                                        <div class="mt-3">
                                            <a href="#" class="btn btn-success">Upgrade Account!</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="px-3">
                                        <img src="{{ asset('assets/images/user-illustarator-2.png') }}" class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-body-->
                    </div>
                </div> <!-- end col-->
            </div> <!-- end row--> --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Balance</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold">₹<span class="counter-value"
                                            data-target="{{ $wallet->balance ?? 0 }}">0</span></h2>
                                    <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"> Pending
                                            Balance: </span> ₹{{ $wallet->pending_balance }}</p>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2"
                                            style="color: #299CDB!important;">₹</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
                <div class="col-md-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Rolling Balance</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold">₹<span class="counter-value"
                                            data-target="{{ $wallet->roling_balance }}">0</span><span
                                            class="badge bg-light text-danger mb-0"> On Hold </span></h2>
                                    <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"> <i
                                                class="ri-arrow-up-line align-middle"></i>{{ $rollingCharge->rolling_charge }}
                                            % </span> Time - 7 days/Every Transaction</p>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2"
                                            style="color: #299CDB!important;">₹</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
                <div class="col-md-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Transaction Volume</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold">₹<span class="counter-value"
                                            data-target="{{ $creditedAmount }}">0</span></h2>
                                    <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"> Total
                                            Transactions: </span>{{ $completedTransactions }}</p>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2"
                                            style="color: #299CDB!important;">₹</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
                <div class="col-md-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Success Rate</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                            data-target="{{ sprintf('%.2f', $successPercent) }}">0</span>%</h2>
                                    <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0"> <i
                                                class="ri-arrow-up-line align-middle"></i> </span> </p>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                            <i data-feather="external-link" class="text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->
        </div>
    </div> <!-- end col-->
    <div class="col-xxl-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Charges Upto ₹500 <span
                                        class="badge bg-light text-success mb-0">Active</span> </p>
                                <p class="mt-4 ff-secondary fw-semibold">Payin <span class="counter-value"
                                        data-target="{{ sprintf('%.2f', $merchantGateway->payin_charge) }}">0</span>₹
                                    including GST</p>
                                <p class="mt-4 ff-secondary fw-semibold">Payout ( Upto ₹999 )<span
                                        class="counter-value"
                                        data-target="{{ sprintf('%.2f', $merchantGateway->payout_charge) }}">0</span>₹
                                    including GST</p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2"
                                        style="color: #299CDB!important;">₹</span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Charges Above ₹500 <span
                                        class="badge bg-light text-success mb-0">Active</span> </p>
                                <p class="mt-4 ff-secondary fw-semibold">Payin <span class="counter-value"
                                        data-target="{{ sprintf('%.2f', $merchantGateway->payin_charge2) }}">0</span>%
                                    including GST</p>
                                <p class="mt-4 ff-secondary fw-semibold">Payout ( Above ₹999 )<span
                                        class="counter-value"
                                        data-target="{{ sprintf('%.2f', $merchantGateway->payout_charge2) }}">0</span>%
                                    including GST</p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2"
                                        style="color: #299CDB!important;">₹</span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Settlement Schedule <span
                                        class="badge bg-light text-success mb-0">Active</span>💵 EOD Settlement 9.10 PM
                                    IST</p>
                                <p>
                                    @if (isset($settlementSchedules))
                                        @foreach ($settlementSchedules as $s)
                                            @if ($s->type === 'time_range')
                                                <div>Transactions Time
                                                    {{ date('h:i A', strtotime($s->transaction_start_time)) }} -
                                                    {{ date('h:i A', strtotime($s->transaction_end_time)) }} Settlement
                                                    between {{ date('h:i A', strtotime($s->settlement_start_time)) }} -
                                                    {{ date('h:i A', strtotime($s->settlement_end_time)) }}</div>
                                            @elseif ($s->type === 'fixed_hours')
                                                <div>Settlement in {{ $s->settlement_delay_hours }} hrs</div>
                                            @else
                                                <div>Settlement in T+{{ $s->settlement_delay_days }} day</div>
                                            @endif
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2"
                                        style="color: #299CDB!important;">₹</span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    </div>
</div>
<div class="col-xxl-12">
    <div class="row h-100">
        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Live Users By Country</h4>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-soft-primary btn-sm">Export Report</button>
                    </div>
                </div><!-- end card header -->
                <!-- card body -->
                <div class="card-body">
                    <div id="users-by-country" data-colors='["--vz-light"]' class="text-center"
                        style="height: 252px"></div>
                    <div class="table-responsive table-card mt-3">
                        <table class="table table-borderless table-sm table-centered align-middle table-nowrap mb-1">
                            <thead class="text-muted border-dashed border border-start-0 border-end-0 bg-light-subtle">
                                <tr>
                                    <th>Duration (Secs)</th>
                                    <th style="width: 30%;">Sessions</th>
                                    <th style="width: 30%;">Views</th>
                                </tr>
                            </thead>
                            <tbody class="border-0">
                                <tr>
                                    <td>0-30</td>
                                    <td>2,250</td>
                                    <td>4,250</td>
                                </tr>
                                <tr>
                                    <td>31-60</td>
                                    <td>1,501</td>
                                    <td>2,050</td>
                                </tr>
                                <tr>
                                    <td>61-120</td>
                                    <td>750</td>
                                    <td>1,600</td>
                                </tr>
                                <tr>
                                    <td>121-240</td>
                                    <td>540</td>
                                    <td>1,040</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Sessions by Countries</h4>
                    <div>
                        <button type="button" class="btn btn-soft-secondary btn-sm">ALL</button>
                        <button type="button" class="btn btn-soft-primary btn-sm">1M</button>
                        <button type="button" class="btn btn-soft-secondary btn-sm">6M</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div>
                        <div id="countries_charts"
                            data-colors='["--vz-danger", "--vz-info", "--vz-danger", "--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-info"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div> <!-- end col-->
    </div> <!-- end row-->
</div><!-- end col -->
</div> <!-- end row-->
