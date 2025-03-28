<style>
    .lefticon-accordion .accordion-button::after {
        top: 28px !important;
    }
    
    .table tr td {
        vertical-align: middle
    }
    </style>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Developer Section</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Developer Section</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="api-keys-tab" data-bs-toggle="tab" data-bs-target="#api-keys" type="button" role="tab" aria-controls="api-keys" aria-selected="true">API Keys</button>
                        <button class="nav-link" id="documentation-tab" data-bs-toggle="tab" data-bs-target="#documentation" type="button" role="tab" aria-controls="documentation" aria-selected="false">Documentation</button>
                        <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">Analytics</button>
                        <button class="nav-link" id="rate-limits-tab" data-bs-toggle="tab" data-bs-target="#rate-limits" type="button" role="tab" aria-controls="rate-limits" aria-selected="false">Rate Limits</button>
                        <button class="nav-link" id="trx-volume-tab" data-bs-toggle="tab" data-bs-target="#trx-volume" type="button" role="tab" aria-controls="trx-volume" aria-selected="false">Transaction Limits</button>
                        <button class="nav-link" id="webhooks-&-callbacks-tab" data-bs-toggle="tab" data-bs-target="#webhooks-&-callbacks" type="button" role="tab" aria-controls="webhooks-&-callbacks" aria-selected="false">Webhooks & Callbacks</button>
                        <button class="nav-link" id="signature-tab" data-bs-toggle="tab" data-bs-target="#signature" type="button" role="tab" aria-controls="signature" aria-selected="false">Signature Verification</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="api-keys" role="tabpanel" aria-labelledby="api-keys-tab" tabindex="0">
                        <div class="mt-3">
                            <div class="alert alert-warning text-dark" role="alert">
                                <div class="fw-bold">Security Notice</div>
                                <p>API keys grant access to your account. Keep them secure and never share them in public
                                    repositories.
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h5 class="card-title">Account Id</h5>
                            <p class="card-text">{{ $accountId ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-3">
                            <h5 class="card-title">API Key</h5>
                            <p class="card-text">{{ $merchantGateway->api_key ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-3">
                            <h5 class="card-title">Merchant ID</h5>
                            <p class="card-text">{{ $merchantGateway->merchant_id ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-3">
                            <h5 class="card-title">Salt Key</h5>
                            <p class="card-text">{{ $merchantGateway->salt_key ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="documentation" role="tabpanel" aria-labelledby="documentation-tab" tabindex="0">
                        <div class="mt-3">
                            <div class="card border">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#overviewTab" role="tab">
                                                <i class="ri-file-list-3-line"></i> Overview
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#endpointsTab" role="tab">
                                                <i class="ri-code-box-line"></i> Endpoints
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#webhooksTab" role="tab">
                                                <i class="ri-links-line"></i> Webhooks
                                            </a>
                                        </li>
                                    </ul>
    
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="overviewTab" role="tabpanel">
                                            <div class="alert alert-secondary text-dark d-flex gap-2 flex-wrap"
                                                role="alert">
                                                <div>
                                                    <i class="ri-error-warning-line fs-1 text-secondary"></i>
                                                </div>
                                                <div>
                                                    <p class=" pt-2">Required Authentication Keys</p>
                                                    <p>Three keys are required for API integration:</p>
                                                    <ul>
                                                        <li><strong>Merchant ID (merchant_id):</strong> Your unique merchant
                                                            identifier required in all API requests</li>
                                                        <li><strong>API Key:</strong> Used in the Authorization header as a
                                                            Bearer
                                                            token for API authentication</li>
                                                        <li><strong>Salt Key:</strong> Used for generating request
                                                            signatures and
                                                            verifying webhook responses</li>
                                                    </ul>
                                                    <p>Sample API Request Header:</p>
                                                    <div class="bg-light p-2">
                                                        <p class="m-0">Authorization: Bearer YOUR_API_KEY</p>
                                                        <p class="m-0">Content-Type: application/json</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-secondary text-dark d-flex gap-2 flex-wrap"
                                                role="alert">
                                                <div>
                                                    <i class="ri-error-warning-line fs-1 text-secondary"></i>
                                                </div>
                                                <div>
                                                    <p class=" pt-2">Payment Flows</p>
                                                    <p>P2P Payment Flow:</p>
                                                    <ol>
                                                        <li>Create payment request using /v2/pay-request with
                                                            sub_pay_mode="qr_ap"
                                                        </li>
                                                        <li>Customer makes payment using the generated QR code</li>
                                                        <li>Collect UTR from customer after payment completion</li>
                                                        <li>Update UTR using /v2/utrUpdate endpoint</li>
                                                        <li>Monitor transaction status using /api/seamless/txnStatus</li>
                                                        <li>Receive final status via webhook callback</li>
                                                    </ol>
                                                    <hr>
                                                    <p>Intent Payment Flow:</p>
                                                    <ol>
                                                        <li>Create payment request using /v2/pay-request with
                                                            sub_pay_mode="intent"
                                                        </li>
                                                        <li>Customer completes payment via UPI intent</li>
                                                        <li>Status updates received automatically via webhook</li>
                                                        <li>Monitor status using /api/seamless/txnStatus if needed</li>
                                                    </ol>
                                                    <hr>
                                                    <p>Payout Flow:</p>
                                                    <ol>
                                                        <li>Create payout using /v2/payout-request</li>
                                                        <li>Monitor status using /api/seamless/txnStatus</li>
                                                        <li>Receive status updates via webhook callback</li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <div class="card border">
                                                <div class="card-header">
                                                    <h5 class="m-0"> Daily API Usage</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <th>Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-primary rounded-3">initiated</button>
                                                                    </td>
                                                                    <td>Transaction has been created and QR/payment link is
                                                                        generated</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-secondary rounded-3">processing</button>
                                                                    </td>
                                                                    <td>Payment is being processed or verified</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-success rounded-3">completed</button>
                                                                    </td>
                                                                    <td>Transaction has been successfully completed</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-danger rounded-3">failed</button>
                                                                    </td>
                                                                    <td>Transaction has failed</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-warning rounded-3">queued</button>
                                                                    </td>
                                                                    <td>Payout has been queued for processing</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card border">
                                                <div class="card-header">
                                                    <h5 class="m-0">Best Practices</h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul>
                                                        <li>Always store and track the order_id and txn_id for
                                                            reconciliation</li>
                                                        <li>Implement proper error handling for all API responses</li>
                                                        <li>Set up reliable webhook handling for real-time updates</li>
                                                        <li>For P2P payments, ensure proper UTR collection and verification
                                                        </li>
                                                        <li>Regularly monitor transaction statuses for pending transactions
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="endpointsTab" role="tabpanel">
                                            <div class="accordion lefticon-accordion custom-accordionwithicon accordion-border-box"
                                                id="accordionlefticon">
                                              <div class="alert alert-secondary text-dark d-flex gap-2 flex-wrap"
                                                role="alert">
                                                <div>
                                                    <i class="ri-error-warning-line fs-1 text-secondary"></i>
                                                </div>
                                                <div>
                                                      <p class=" pt-2">End Points as per Account Level</p>
                                                    <p><strong>Base URL : </strong> <em>https://pay.merchant.purntech.com/api</em>
</p>
                                                    <ul>
                                                        <li><strong>Standard : </strong> /v1/</li>
                                                        <li><strong>Business :</strong> /v2/
                                                           </li>
                                                        <li><strong>Enterprises :</strong> /v3/</li>
                                                      </ul>
                                                  </div>
                                                        </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="accordionlefticonExample1">
                                                      
                                                      
                                                        <button class="accordion-button collapsed d-flex gap-3"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#createPaymentReq" aria-expanded="true"
                                                            aria-controls="createPaymentReq">
                                                            <span class="btn btn-outline-secondary">POST</span>
                                                            <span
                                                                class="text-danger bg-light px-1 border rounded">/v1/pay-request</span>
                                                            <span class="text-muted">Create Payment Request</span>
                                                        </button>
                                                    </h2>
                                                    <div id="createPaymentReq" class="accordion-collapse collapse"
                                                        aria-labelledby="accordionlefticonExample1"
                                                        data-bs-parent="#accordionlefticon">
                                                        <div class="accordion-body">
                                                            <div class="alert alert-secondary text-dark d-flex gap-2"
                                                                role="alert">
                                                                <div>
                                                                    <i class="ri-error-warning-line text-secondary"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="m-0">Initialize a new payment request.
                                                                        Supports both QR and P2P payment modes.</p>
                                                                </div>
                                                            </div>
                                                            <h5>Parameters</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>acc_id</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Account ID </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>amount</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Payment amount (Min 100) </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>currency</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Currency code (e.g., INR) </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>order_id</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Unique order identifier</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>sub_pay_mode</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Sub payment mode (qr_ap, intent)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>merchant_id</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Your merchant ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>vpa</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Customer's UPI ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>cust_name</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Customer's name</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>cust_email</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Customer's email</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>callback_url</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>URL for payment status updates</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>redirect_url</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>URL to redirect after payment</td>
                                                                        </tr>
    
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <h5 class="my-3">Request Example</h5>
                                                            <code>
    <pre class="language-json">
    {
        url --location 'https://pay.merchant.purntech.com/api/v1/pay-request' \
         --header 'Content-Type: applica on/json' \ 
         --header 'Authorization: Bearer <your_token>' \ 
         --data-raw '{ 
                "acc_id":"hgfddjyfghf", 
                "amount":"120", 
                "currency":"INR", 
                "order_id":"939", 
                "sub_pay_mode":"qr_ap", 
                "merchant_id":"<your_merchant_id>", 
                "vpa":"customer@upi", 
                "cust_name":"namam arya", 
                "cust_email":"johndoe@example.com", 
                "callback_url":"h ps://example.com/clientCallback", 
                "redirect_url":"h ps://www.example.com/" 
                      }' 
    }
    </pre>
    </code>
                                                            <h5 class="my-3">Response Example</h5>
                                                            <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-bs-toggle="tab"
                                                                        href="#mainRespTab" role="tab">
                                                                        Main Response
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-bs-toggle="tab"
                                                                        href="#intentRespTab" role="tab">
                                                                        Intent Response
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="mainRespTab"
                                                                    role="tabpanel">
                                                                    <code>
    <pre class="language-json">
        {
            "title":"QR Response"
            "content":{
                "status":"Success"
                "data":{
                    "order_id":"941"
                    "status":"initiated"
                    "amount":"120"
                    "txn_id":"UI250107062326589346"
                    "qr_string":"upi://pay?ver=01&mode=04&pa=8887843595@ybl&pn=Kori&mc=5699&am=120.00&cu=INR&tr=UI250107062326589346"
                    "qr_code":"data:image/png;base64,..."
                    "paymentLink": "https://merchant.purntech.com/pay-67e2b4584b3c0dabde81ef07"
                }
            }
        }
    </pre>
    </code>
                                                                </div>
                                                                <div class="tab-pane" id="intentRespTab" role="tabpanel">
                                                                    <code>
    <pre class="language-json">
        {
            "status":"Success"
            "data":{
                "order_id":"940"
                "status":"initiated"
                "amount":120
                "client_ref_id":"cust_PgRUEmkOo7lvlf"
                "qr_string":"upi://pay?ver=01&mode=19&pa=eko159138.rzp@icici&pn=Eko&tr=RZPPgRUF8RUsxiZFIqrv2&cu=INR&mc=4814&qrMedium=04&tn=PaymenttoEko"
                "qr_code":"data:image/png;base64,..."
                "paymentLink": "https://merchant.purntech.com/pay-67e2b4584b3c0dabde81ef07"
            }
        }   
    </pre>
    </code>
                                                                </div>
                                                            </div>
    
                                                            <h5 class="my-3">Code Examples</h5>
                                                            <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-bs-toggle="tab"
                                                                        href="#curlTab" role="tab">
                                                                        cURL
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-bs-toggle="tab" href="#nodeTab"
                                                                        role="tab">
                                                                        Node.js
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-bs-toggle="tab"
                                                                        href="#pythonTab" role="tab">
                                                                        Python
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="curlTab" role="tabpanel">
                                                                    <code>
    <pre class="language-json">
    curl --location 
    'https://pay.merchant.purntech.com/api/v1/pay-request' \
     --header 'Content-Type: applica on/json' \ 
     --header 'Authoriza on: Bearer <your_token>' \ 
        --data-raw '{ 
            "acc_id":"hgfdd yfghf", 
            "amount":"120", 
            "currency":"INR", 
            "order_id":"939", 
            "sub_pay_mode":"qr_ap", 
            "merchant_id":"<your_merchant_id>", 
            "vpa":"customer@upi", 
            "cust_name":"namam arya", 
            "cust_email":"johndoe@example.com", 
            "callback_url":"https://example.com/clientCallback", 
            "redirect_url":"https://www.example.com/" 
        }' 
    </pre>
    </code>
                                                                </div>
                                                                <div class="tab-pane" id="nodeTab" role="tabpanel">
                                                                    <code>
    <pre class="language-json">
    const axios = require('axios');
    async function makeCreatePaymentRequest() {
        try {
         const response = await axios({
            method: 'post',
            url: 'https://pay.merchant.purntech.com/api/v1/pay-request' \,
            headers: {
              'Authorization': 'Bearer YOUR_API_KEY',
              'Content-Type': 'application/json'
            },
        data: {
            "amount": "120",
            "currency": "INR",
            "order_id": "939",
            "sub_pay_mode": "qr_ap",
            "merchant_id": "4f634daa11454a681a15a9ff357631",
            "vpa": "customer@upi",
            "cust_name": "namam arya",
            "cust_email": "johndoe@example.com",
            "callback_url":"https://example.com/clientCallback", 
            "redirect_url":"https://www.example.com/" 
            }
          });
          console.log(response.data);
          return response.data;
        } catch (error) {
          console.error('Error:', error.response?.data || error.message);
          throw error;
        }
      }
    </pre>
    </code>
                                                                </div>
                                                                <div class="tab-pane" id="pythonTab" role="tabpanel">
                                                                    <code>
    <pre class="language-json">
        import requests
        import json
        def make_create_payment_request():
            url = 'https://pay.merchant.purntech.com/api/v1/pay-request' \'
            headers = {
                'Authorization': 'Bearer YOUR_API_KEY',
                'Content-Type': 'application/json'
            }
            payload = {
        "amount": "120",
        "currency": "INR",
        "order_id": "939",
        "sub_pay_mode": "qr_ap",
        "merchant_id": "4f634daa11454a681a15a9ff357631",
        "vpa": "customer@upi",
        "cust_name": "namam arya",
        "cust_email": "johndoe@example.com",
        "callback_url":"https://example.com/clientCallback", 
        "redirect_url":"https://www.example.com/" 
    }
          
        try:
            response = requests.post(url, headers=headers, json=payload)
            response.raise_for_status()
            return response.json()
            except requests.exceptions.RequestException as e:
            print(f'Error: {str(e)}')
            raise
    </pre>
    </code>
                                                                </div>
    
                                                            </div>
                                                        </div>
                                                    </div>
    
    
    
                                                    <!--   TRANSACTION STATUS  -->
    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="transactionStatusHeading">
                                                            <button class="accordion-button collapsed d-flex gap-3"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#transactionStatusContent"
                                                                aria-expanded="true"
                                                                aria-controls="transactionStatusContent">
                                                                <span class="btn btn-outline-secondary">POST</span>
                                                                <span
                                                                    class="text-danger bg-light px-1 border rounded">/v1/seamless/txn-status</span>
                                                                <span class="text-muted">Transaction Status</span>
                                                            </button>
                                                        </h2>
                                                        <div id="transactionStatusContent"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="transactionStatusHeading"
                                                            data-bs-parent="#accordionEndpoints">
                                                            <div class="accordion-body">
                                                                <div class="alert alert-secondary text-dark d-flex gap-2"
                                                                    role="alert">
                                                                    <div>
                                                                        <i class="ri-error-warning-line text-secondary"></i>
                                                                    </div>
                                                                    <div>
                                                                        <p>Retrieves the status of a payment transaction. This
                                                                    endpoint allows you to check if a transaction has been
                                                                    completed, is pending, or has failed.</p>
                                                                    </div>
                                                                </div>
    
                                                                <h5>Parameters</h5>
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>acc_id</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Account ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>type</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Transaction type (payin/payout)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>merchant_id</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Your merchant ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>order_id</td>
                                                                            <td>string</td>
                                                                            <td><button
                                                                                    class="btn btn-primary rounded-pill">Required</button>
                                                                            </td>
                                                                            <td>Order ID to check</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <h5>Example Curl Request</h5>
                                                                <code>
    <pre class="language-json">
       curl --location 'https://pay.merchant.purntech.com/api/v1/seamless/txn-status' \ 
       --header 'Content-Type: application/json' \ 
       --header 'Authorization: Bearer <your_token>' \ 
       --data '{ 
       "acc_id":"hgfdd yfghf", 
       "type":"payin", 
       "merchant_id":"<your_merchant_id>", 
       "order_id":"4529" 
       }'
    </pre>
    </code>
                                                                <h5>Example Response</h5>
                                                                <code>
    <pre class="language-json">
      {
          "status":"SUCCESS",
          "data":{
              "order_id":"4529",
              "status":"completed",
              "tx_status":"5",
              "amount":"20.0",
              "utr":"469645373926",
              "txnId":"3297457814"
          }
      }
    </pre>
    </code>
                                                                <h5>Transaction Statuses</h5>
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Status</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>initiated</td>
                                                                            <td>Transaction has been created and QR/payment
                                                                                link is generated</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>processing</td>
                                                                            <td>Payment is being processed or verified</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>completed</td>
                                                                            <td>Transaction has been successfully completed
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>failed</td>
                                                                            <td>Transaction has failed</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>queued</td>
                                                                            <td>Payout has been queued for processing</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
    
    
    
    
    
                                                    <!-- PAYOUT REQUEST  -->
    
    
    
    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="payoutRequestHeading">
                                                            <button class="accordion-button collapsed d-flex gap-3"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#payoutRequestContent" aria-expanded="true"
                                                                aria-controls="payoutRequestContent">
                                                                <span class="btn btn-outline-secondary">POST</span>
                                                                <span
                                                                    class="text-danger bg-light px-1 border rounded">/v1/payout-request</span>
                                                                <span class="text-muted">Payout Request</span>
                                                            </button>
                                                        </h2>
                                                        <div id="payoutRequestContent" class="accordion-collapse collapse"
                                                            aria-labelledby="payoutRequestHeading"
                                                            data-bs-parent="#accordionEndpoints">
                                                            <div class="accordion-body">
                                                            <div class="alert alert-secondary text-dark d-flex gap-2"
                                                                role="alert">
                                                                <div>
                                                                    <i class="ri-error-warning-line text-secondary"></i>
                                                                </div>
                                                                <div>
                                                                <p>Initiates a payout transaction to disburse funds to a
                                                                specified account.</p>
                                                                </div>
                                                            </div>
    
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Name</th>
                                                                                <th>Type</th>
                                                                                <th>Required</th>
                                                                                <th>Description</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>acc_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Account ID </td>
                                                                            </tr>
    
                                                                            <tr>
                                                                                <td>amount</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Payment amount (Min 100) </td>
                                                                            </tr>
    
                                                                            <tr>
                                                                                <td>merchant_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Your merchant ID</td>
                                                                            </tr>
    
                                                                            <tr>
                                                                                <td>currency</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Currency code (e.g., INR) </td>
                                                                            </tr>
    
    
    
                                                                            <tr>
                                                                                <td>pay_mode</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Payment mode (NB)</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>sub_pay_mode</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Sub payment mode (qr_ap, intent)</td>
                                                                            </tr>
    
    
                                                                            <tr>
                                                                                <td>bene_name</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Beneficiary name</td>
                                                                            </tr>
    
                                                                            <tr>
                                                                                <td>bank_ifsc</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Bank IFSC code </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>account_number</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Beneficiary account number </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>vpa</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Beneficiary UPI ID </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>remarks</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Optional transaction remarks</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>order_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Unique order identifier</td>
                                                                            </tr>
    
                                                                        </tbody>
                                                                    </table>
                                                                </div>
    
    
                                                                <h5>Example Curl Request</h5>
                                                                <code>
    <pre class="language-json">
    curl --location 'https://pay.merchant.purntech.com/api/v1/payout-request' \ 
    --header 'Content-Type: application/json' \ 
    --header 'Authorization: Bearer <your_token>' \ 
        --data '{ 
            "acc_id":"hgfdd yfghf", 
            "amount": 128, 
            "merchant_id": "<your_merchant_id>", 
            "currency": "INR", 
            "pay_mode": "NB", 
            "sub_pay_mode": "IMPS", 
            "bene_name": "Chandan Sharma", 
            "bank_ifsc": "KKBK0005689", 
            "account_number": "1234567890", 
            "vpa": "example@upi", 
            "remarks": "Payment for Testing", 
            "order_id": "1212619" 
        }'
    </pre>
    </code>
                                                                <h5>Example Response</h5>
                                                                <code>
    <pre class="language-json">
    {
        "status":"SUCCESS",
        "data":{
            "txn_id":"REF5757440236606423A",
            "status":"queued",
            "amount":128,
            "remarks":"Payment for services",
            "order_id":"1212619"
        }
    }
    </pre> 
    </code>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <!--  UTR UPDATE  -->
    
    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="utrUpdateHeading">
                                                            <button class="accordion-button collapsed d-flex gap-3"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#utrUpdateContent" aria-expanded="true"
                                                                aria-controls="utrUpdateContent">
                                                                <span class="btn btn-outline-secondary">POST</span>
                                                                <span
                                                                    class="text-danger bg-light px-1 border rounded">/v1/utr-update</span>
                                                                <span class="text-muted">UTR Update</span>
                                                            </button>
                                                        </h2>
                                                        <div id="utrUpdateContent" class="accordion-collapse collapse"
                                                            aria-labelledby="utrUpdateHeading"
                                                            data-bs-parent="#accordionEndpoints">
                                                            <div class="accordion-body">
                                                            <div class="alert alert-secondary text-dark d-flex gap-2"
                                                                role="alert">
                                                                <div>
                                                                    <i class="ri-error-warning-line text-secondary"></i>
                                                                </div>
                                                                <div>
                                                                <p>Updates the Unique Transaction Reference (UTR) number for
                                                                a particular transaction.</p>
                                                                </div>
                                                            </div>
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Name</th>
                                                                                <th>Type</th>
                                                                                <th>Required</th>
                                                                                <th>Description</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>acc_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Account ID </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>merchant_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Your merchant ID</td>
                                                                            </tr>
    
                                                                            <tr>
                                                                                <td>order_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Unique order identifier</td>
                                                                            </tr>
    
    
                                                                            <tr>
                                                                                <td>utr</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>UTR number from the payment </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <h5>Example Curl Request</h5>
                                                                <code>
    <pre class="language-json">
    curl --location 'https://pay.merchant.purntech.com/api/v1/utr-update' \ 
    --header 'Content-Type: application/json' \ 
    --header 'Authorization: Bearer <your_token>' \ 
    --data '{ 
    "acc_id":"hgfdd yfghf", 
    "merchant_id":"<your_merchant_id>", 
    "order_id":"433", 
    "utr":"434651785169" 
    }'
    </pre>
    </code>
                                                                <h5>Example Response</h5>
                                                                <code>
    <pre class="language-json">
    {
        "status": "Success",
        "data": {
            "order_id": "433",
            "remark": "UTR updated successfully"
        }
    }
    </pre>
    </code>
                                                            </div>
                                                        </div>
                                                    </div>
    
    
                                                    <!-- CHECK BALANCE  -->
    
    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="checkBalanceHeading">
                                                            <button class="accordion-button collapsed d-flex gap-3"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#checkBalanceContent" aria-expanded="true"
                                                                aria-controls="checkBalanceContent">
                                                                <span class="btn btn-outline-secondary">POST</span>
                                                                <span
                                                                    class="text-danger bg-light px-1 border rounded">/v1/balance</span>
                                                                <span class="text-muted">Check Balance</span>
                                                            </button>
                                                        </h2>
                                                        <div id="checkBalanceContent" class="accordion-collapse collapse"
                                                            aria-labelledby="checkBalanceHeading"
                                                            data-bs-parent="#accordionEndpoints">
                                                            <div class="accordion-body">
                                                            <div class="alert alert-secondary text-dark d-flex gap-2"
                                                                role="alert">
                                                                <div>
                                                                    <i class="ri-error-warning-line text-secondary"></i>
                                                                </div>
                                                                <div>
                                                                <p>This endpoint retrieves the merchant’s available active
                                                                    balance, pending balance, and rolling reserved balance.
                                                                </p>
                                                                </div>
                                                            </div>
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Name</th>
                                                                                <th>Type</th>
                                                                                <th>Required</th>
                                                                                <th>Description</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>acc_id</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Account ID </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>mid</td>
                                                                                <td>string</td>
                                                                                <td><button
                                                                                        class="btn btn-primary rounded-pill">Required</button>
                                                                                </td>
                                                                                <td>Your merchant ID</td>
                                                                            </tr>
    
    
    
                                                                        </tbody>
                                                                    </table>
                                                                </div>
    
    
    
    
                                                                <h5>Example Curl Request</h5>
                                                                <code>
    <pre class="language-json">
    curl --location 'https://pay.merchant.purntech.com/api/v1/balance' \ 
    --header 'Content-Type: application/json' \ 
    --header 'Authorization: Bearer <your_token>' \ 
    --data '{ 
        "acc_id":"hgfdd yfghf", 
        "merchant_id":"<your_merchant_id>" 
            }'
    </pre>
    </code>
                                                                <h5>Example Curl Response</h5>
                                                                <code>
    <pre class="language-json">
    { 
        "active_balance": "1030.97", 
        "pending_balance": "588.33", 
        "rolling_balance": "310.89" 
    } 
    </pre>
    </code>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <!--  END OF THE ENDPOINT POST -->
    
    
    
    
    
    
    
    
                                                </div>
                                            </div>
                                        </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
                                        <div class="tab-pane" id="webhooksTab" role="tabpanel">
                                            <div class="alert alert-secondary text-dark d-flex gap-2" role="alert">
                                                <div>
                                                    <i class="ri-error-warning-line fs-1 text-secondary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="pt-2">Webhook Overview</h5>
                                                    <p class="mb-0">Webhooks provide real-time notifications about
                                                        transaction status
                                                        changes. Configure your webhook endpoint to receive automatic
                                                        updates.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card border">
                                                <div class="card-header">
                                                    <h5 class="m-0">Webhook Events</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Event</th>
                                                                    <th>Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-secondary rounded-3">payment.success</button>
                                                                    </td>
                                                                    <td>Payment has been successfully completed</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-secondary rounded-3">payment.failed</button>
                                                                    </td>
                                                                    <td>Payment has failed</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-secondary rounded-3">payment.pending</button>
                                                                    </td>
                                                                    <td>Payment is awaiting confirmation</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-secondary rounded-3">payout.success</button>
                                                                    </td>
                                                                    <td>Payout has been successfully processed</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button
                                                                            class="btn btn-outline-secondary rounded-3">payout.failed</button>
                                                                    </td>
                                                                    <td>Payout has failed</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card border">
                                                <div class="card-header">
                                                    <h5 class="m-0">Sample Webhook Payload</h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab"
                                                                href="#paymentSuccessTab" role="tab">
                                                                Payment Success
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                href="#payoutSuccessTab" role="tab">
                                                                Payout Success
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="paymentSuccessTab" role="tabpanel">
                                                            <pre class="language-json"><code>
    {
        "status": "SUCCESS",
        "data": {
            "order_id": "1001",
            "txn_id": null,
            "status": "completed",
            "amount": 120,
            "utr": null,
            "vpa": "johndoe@bank",
            "remarks": "Payment for services",
            "signature": "ef914e51b90bcf2c545eb814d7bb924ec2ab44d2f916a23a610bb85580036d93"
        }
    }
        </code>
    </pre>
                                                        </div>
                                                        <div class="tab-pane" id="payoutSuccessTab" role="tabpanel">
                                                            <pre class="language-json"><code>
    {
        "status":"SUCCESS"
        "data":{
            "order_id":"1001"
            "txn_id":NULL
            "status":"completed"
            "amount":120
            "utr":NULL
            "vpa":"johndoe@bank"
            "remarks":"Payment for services"
            "signature":"ef914e51b90bcf2c545eb814d7bb924ec2ab44d2f916a23a610bb85580036d93"
        }
    }   
    </code>
    </pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-warning text-dark d-flex gap-2" role="alert">
                                                <div>
                                                    <i class="ri-error-warning-line fs-1 text-warning"></i>
                                                </div>
                                                <div>
                                                    <h5 class="pt-3">Implementation Tips</h5>
                                                    <ul>
                                                        <li>Implement proper signature verification for webhook payloads
                                                        </li>
                                                        <li>Return 2xx status code to acknowledge receipt</li>
                                                        <li>Process webhooks asynchronously to avoid timeouts</li>
                                                        <li>Implement proper error handling and retry logic</li>
                                                        <li>Store webhook events for audit and debugging</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab" tabindex="0">
                        <div class="mt-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 col-lg-3">
                                            <p>Total Requests</p>
                                            <h3>0</h3>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <p>Success Rate</p>
                                            <h3 class="text-success">0.0%</h3>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <p>Average Latency</p>
                                            <h3>0ms</h3>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <p>Error Rate</p>
                                            <h3>0.0%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border">
                                <div class="card-header">
                                    <h5 class="m-0"> Daily API Usage</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="dailyAPiTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Total Requests</th>
                                                    <th>Success Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>01-03-2025</td>
                                                    <td>500</td>
                                                    <td>N/A</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card border">
                                <div class="card-header">
                                    <h5 class="m-0"> Endpoint Performance</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="endPerfTable">
                                            <thead>
                                                <tr>
                                                    <th>Endpoint</th>
                                                    <th>Total Calls</th>
                                                    <th>Success Rate</th>
                                                    <th>Avg. Latency</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- <tr>
                                                    
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="rate-limits" role="tabpanel" aria-labelledby="rate-limits-tab"
                        tabindex="0">
                        <div class="table-responsive">
                            @php
                                $rand1 = rand(10,95);
                                $rand2 = rand(10,95);
                                $rand3 = rand(10,95);
                            @endphp
                            <table class="table align-middle">
                                <tr>
                                    <th>Plan</th>
                                    <th>Requests/Minute</th>
                                    <th>Burst Size</th>
                                    <th>Current Usage</th>
                                </tr>
                                <tr>
                                    <td><button type="button" class="btn btn-outline-primary" disabled>STANDARD</button>
                                    </td>
                                    <td>100</td>
                                    <td>200</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Default striped example"
                                            aria-valuenow="{{ $rand1 }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped" style="width: {{ $rand1 }}%">
                                                {{ $rand1 }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><button type="button" class="btn btn-outline-primary" disabled>BUSINESS</button>
                                    </td>
                                    <td>500</td>
                                    <td>1000</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Default striped example"
                                            aria-valuenow="{{ $rand2 }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped" style="width: {{ $rand2 }}%">
                                                {{ $rand2 }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><button type="button" class="btn btn-outline-primary" disabled>ENTERPRISE</button>
                                    </td>
                                    <td>2000</td>
                                    <td>5000</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Default striped example"
                                            aria-valuenow="{{ $rand3 }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped" style="width: {{ $rand3 }}%">
                                                {{ $rand3 }}%</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="trx-volume" role="tabpanel" aria-labelledby="trx-volume-tab"
                        tabindex="0">
                        <div class="table-responsive">
                            @php
                                $totalPayin = (float)$transaction_limits->total_payin_limit; 
                                $totalPayout = (float)$transaction_limits->total_payout_limit; 
                                $availablePayin = (float)$transaction_limits->available_payin_limit;
                                $availablePayout = (float)$transaction_limits->available_payout_limit;

                                $payinPercent = ($availablePayin * 100.00) / $totalPayin;
                                $payoutPercent = ($availablePayout * 100.00) / $totalPayout;
                            @endphp
                            <table class="table align-middle">
                                <tr>
                                    <th>Plan</th>
                                    <th>Payin Limit</th>
                                    <th>Payout Limit</th>
                                    <th>Current Payin Usage</th>
                                    <th>Current Payout Usage</th>
                                    <th>Status</th>
                                </tr>
                                @isset($transaction_limits)
                                    <tr>
                                        <td><button type="button" class="btn btn-outline-primary" style="text-transform: uppercase;" disabled>{{$transaction_limits->plan}}</button>
                                        </td>
                                        <td>₹{{$transaction_limits->total_payin_limit}}</td>
                                        <td>₹{{$transaction_limits->total_payout_limit}}</td>
                                        <td>
                                            <div class="progress" role="progressbar" aria-label="Default striped example"
                                                aria-valuenow="{{ 100.00 - $payinPercent }}" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped" style="width: {{ 100.00 - $payinPercent }}%">
                                                    {{ 100.00 - $payinPercent }}%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress" role="progressbar" aria-label="Default striped example"
                                                aria-valuenow="{{ 100.00 - $payoutPercent }}" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped" style="width: {{ 100.00 - $payoutPercent }}%">
                                                    {{ 100.00 - $payoutPercent }}%</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-success mb-0">Active</span></td>
                                    </tr>
                                @endisset
                                {{-- <tr>
                                    <td><button type="button" class="btn btn-outline-primary" disabled>STANDARD</button>
                                    </td>
                                    <td>100</td>
                                    <td>200</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Default striped example"
                                            aria-valuenow="{{ $rand1 }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped" style="width: {{ $rand1 }}%">
                                                {{ $rand1 }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><button type="button" class="btn btn-outline-primary" disabled>BUSINESS</button>
                                    </td>
                                    <td>500</td>
                                    <td>1000</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Default striped example"
                                            aria-valuenow="{{ $rand2 }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped" style="width: {{ $rand2 }}%">
                                                {{ $rand2 }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><button type="button" class="btn btn-outline-primary" disabled>ENTERPRISE</button>
                                    </td>
                                    <td>2000</td>
                                    <td>5000</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Default striped example"
                                            aria-valuenow="{{ $rand3 }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped" style="width: {{ $rand3 }}%">
                                                {{ $rand3 }}%</div>
                                        </div>
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="webhooks-&-callbacks" role="tabpanel" aria-labelledby="webhooks-&-callbacks-tab" tabindex="0">
                        <div class="mt-3">
                            <div class="alert alert-info text-dark" role="alert">
                                <div class="fw-bold">About Payout Callbacks</div>
                                <p>This callback URL will be used to receive notifications about payout statuses,
                                    transaction confirmations, and other important payment events. Ensure your endpoint is
                                    secure (HTTPS) and can handle POST requests.</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h5 class="card-title">Callback URL</h5>
                            <p class="card-text">{{ $callbackUrl ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-3">
                            <div class="alert alert-info text-dark" role="alert">
                                <div class="fw-bold">Webhook Overview</div>
                                <p>Webhooks provide real-time notifications about transaction status changes. Configure your
                                    webhook endpoint to receive automatic updates.</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h5 class="card-title">Webhook URL</h5>
                            <p class="card-text">{{ $webhookUrl ?? 'N/A' }}</p>
                        </div>
                    </div>
<!-- Signature tab created by Ketan Gupta on 14-03-2025 -->
<div class="tab-pane fade" id="signature" role="tabpanel" aria-labelledby="signature-tab" tabindex="0">
    <div class="mt-3">
        <div class="alert alert-info text-dark" role="alert">
            <div class="fw-bold fs-5">🔒 Signature Verification Overview</div>
            <p>To ensure data integrity and security, all sensitive data is encrypted using <strong>AES-256-CBC</strong>. 
               When a transaction is completed, we send a callback request to your configured webhook URL.</p>
            <p>Each callback contains a signature in the header to validate its authenticity.</p>
            <p><span class="fw-bold">Signature Header:</span> <code>X-Signature</code></p>
        </div>
    </div>

    <div class="mt-3">
        <div class="card">
            <div class="card-header fw-bold">📌 How to Verify the Signature in PHP</div>
            <div class="card-body">
                <p>Use the following function to decrypt and validate the data:</p>
                <pre class="language-php">
<code>
function decryptData($encryptedData, $secretKey)
{
    $decoded = base64_decode($encryptedData);
    if ($decoded === false) {
        return "Invalid data format!";
    }

    $iv = substr($decoded, 0, 16); // Extract IV (Initialization Vector)
    $cipherText = substr($decoded, 16); // Extract encrypted data

    $decryptedData = openssl_decrypt($cipherText, 'AES-256-CBC', $secretKey, 0, $iv);
    
    return $decryptedData ?: "Decryption failed!";
}

// Example usage:
$decryptedData = decryptData($encryptedData, $secretKey);
echo $decryptedData;
</code>
                </pre>
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
        $("#dailyAPiTable,#endPerfTable").DataTable();
    });
    </script>