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

    <input id="stkpush_url" type="hidden" name="url" value="{{ url(route('mpesa_stkpush')) }}" />
</div>

<script>
    var input = document.querySelector("#phone");

    window.intlTelInput(input, {
        // any initialisation options go here
        onlyCountries: ["ke"],
        separateDialCode: true,
    });

    document.querySelector('#stkpush_button').addEventListener('click', initializeSTKPush);

    function initializeSTKPush() {

        let phone = document.querySelector('#phone').value;
        let invoice_id = document.querySelector('#invoice_id').value;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let url = document.querySelector('#stkpush_url').value;

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
                    shortcode: '{{ $gateway->shortcode }}',
                    invoice_id: invoice_id
                })
            })
            .then((data) => {
                console.log(data);
                document.querySelector('#stkpush-verifying-message').style.display = "block";
                setInterval(validateSTKPush, 2000);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function validateSTKPush(checkout_request_id) {

        let phone = document.querySelector('#phone').value;
        let invoice_id = document.querySelector('#invoice_id').value;
        let token = document.querySelector('name="_token"').value;
        let url = document.querySelector('#stkpush_url').value;

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
                    invoice_id: invoice_id
                })
            })
            .then((data) => {
                console.log(data);
                window.location.href = thank_page;
            })
            .catch(function(error) {
                console.log(error);
            });
    }
</script>
