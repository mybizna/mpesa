<div class="mpesa_paybill">
    <h2 class="text-xl text-center pb-3">Paybill No</h2>

    <p class="mb-2 text-md font-semibold text-gray-900 dark:text-white">Please follow the steps shown
        below to make your payment using MPesa:</p>
    <ul class="max-w-md space-y-1 text-gray-800 list-inside dark:text-gray-600 text-sm">
        <li class="flex items-center mt-1">
            <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400 flex-shrink-0" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>ONLY <b class="text-green-700">SAFARICOM/MPESA</b> Accepted.</p>
        </li>
        <li class="flex items-center mt-1">
            <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400 flex-shrink-0" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>Please Go to <b class="text-green-700">LIPA na MPesa</b></p>
        </li>
        <li class="flex items-center mt-1">
            <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400 flex-shrink-0" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>Select <b class="text-green-700">Paybill</b></p>
        </li>
        <li class="flex items-center mt-1">
            <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400 flex-shrink-0" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>Send to Paybill Number <b class="text-green-700">{{ $gateway->shortcode }}</b></p>
        </li>

        <li class="flex items-center mt-1">
            <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400 flex-shrink-0" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>
                Send to Account Name <b class="text-green-700">{{ $user->username }}</b>
            </p>
        </li>
    </ul>


    <div style="text-align:center; margin-top:20px;">
        <button id='paybill' type="submit" name="view" value="paybill_{{ $invoice->id }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Verify MPesa
        </button>
    </div>

    <input type="hidden" name="url" value="{{ url(route('mpesa_paybill')) }}" />
</div>
