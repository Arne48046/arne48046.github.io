@php
    $showCookiePopup = !session()->has('cookie_popup');
@endphp

@if ($showCookiePopup)
<div id="static-modal" class="fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen">
    <div class="p-4 max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">Cookie Popup</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="static-modal">
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <p class="text-base leading-relaxed text-gray-500">This website uses cookies to ensure you get the best experience. By accepting cookies, you consent to our use of cookies.</p>
                <ul class="list-disc pl-6">
                    <li>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox" checked disabled>
                            <span class="ml-2 text-gray-500">Functional cookies</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox">
                            <span class="ml-2">Analytical cookies</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="flex items-center p-4 border-t">
            <button data-modal-hide="static-modal" type="button"
                    class="bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    onclick="savePreferences()">Voorkeuren opslaan</button>            
                </div>
        </div>
    </div>
</div>

<script>
function savePreferences() {
    var analyticalCookies = document.querySelector('input[type="checkbox"][name="analytical-cookies"]:checked');
    if (analyticalCookies) {
        sessionStorage.setItem('analyticalCookies', true);
    } else {
        sessionStorage.setItem('analyticalCookies', false);
    }

    document.getElementById('static-modal').style.display = 'none';
}



</script>

@php
    session()->put('cookie_popup', true);
@endphp
@endif
