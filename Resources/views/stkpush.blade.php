<div class="mpesa_stkpush">
    <h2 class="text-xl text-center pb-3">MPesa STK Push</h2>

    <p class="mb-2 text-md font-semibold text-gray-900 dark:text-white">Please Enter you <b class="text-green-500">Phone
            Number</b> that you would like us to send <b class="text-green-500">MPESA STK Push</b>.</p>

    <div class="mb-4 text-center">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
            Phone Number
        </label>
        <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="phone" value="{{ $phone }}" @if ($request_sent) readonly @endif name="phone"
            type="number" placeholder="Phone">
    </div>

    <div class="text-center">
        <button id='stkpush_button' type="submit" name="view" value="stkpush_{{ $invoice->id }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Send STK Push
        </button>
    </div>

    <div id="stkpush-verifying-message" class="text-center text-red-400" style="display: none;">
        <p>Payment request sent to your Phone Number. <br>Waiting for your payment...</p>
        <div class="fa-3x">
            <i class="fa-solid fa-sync fa-spin"></i>
        </div>
    </div>

    <input id="stkpush_account" type="hidden" name="account" value="{{ $user->username }}" />
    <input id="stkpush_url" type="hidden" name="url" value="{{ secure_url(route('mpesa_stkpush')) }}" />
    <input id="stkpush_isp_access_thankyou" type="hidden" name="isp_access_thankyou"
        value="{{ secure_url(route('isp_access_thankyou')) }}" />
</div>

<script>
    var request_sent = false;
    var checkout_request_id = '';
    var input = document.querySelector("#phone");

    window.intlTelInput(input, {
        // any initialisation options go here
        onlyCountries: ["ke"],
        separateDialCode: true,
    });

    document.querySelector('#stkpush_button').addEventListener('click', initializeSTKPush);

    setInterval(validateSTKPush, 3000);

    function initializeSTKPush() {

        let phone = document.querySelector('#phone').value;
        let invoice_id = document.querySelector('#invoice_id').value;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let url = document.querySelector('#stkpush_url').value;
        let account = document.querySelector('#stkpush_account').value;

        document.querySelector('#stkpush-verifying-message').style.display = "block";

        if (phone != '') {
            phone = parseInt(phone);
        }

        if (phone == '' || phone.toString().length != 9) {
            alert('Phone Number is incorrect. ');
            return;
        }

        fetch(url, {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                },
                method: 'post',
                credentials: "same-origin",
                body: JSON.stringify({
                    phone: phone,
                    slug: '{{ $gateway->slug }}',
                    shortcode: '{{ $gateway->business_shortcode }}',
                    invoice_id: invoice_id,
                    account: account
                })
            })
            .then(response => response.json())
            .then((data) => {
                request_sent = true;
                checkout_request_id = data.stkpush.checkout_request_id;
            })
            .catch(function(error) {
                console.log(error);
            });
    }


    function validateSTKPush() {

        console.log('validateSTKPush');
        if(!request_sent && checkout_request_id != ''){
            return false;
        }

        console.log(checkout_request_id);

        let phone = document.querySelector('#phone').value;
        let url = document.querySelector('#stkpush_url').value;
        let thankyou = document.querySelector('#stkpush_isp_access_thankyou').value;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let invoice_id = document.querySelector('#invoice_id').value;

        fetch(url, {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                },
                method: 'post',
                credentials: "same-origin",
                body: JSON.stringify({
                    phone: phone,
                    slug: '{{ $gateway->slug }}',
                    checkout_request_id: checkout_request_id,
                    verifying: true,
                    invoice_id: invoice_id
                })
            })

            .then(response => response.json())
            .then((data) => {
                if (data.verified) {
                    window.location.href = thankyou;
                } else {
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }
</script>
