<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold">{{ __('Contact') }}</h1>
                </div>
            </div>
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Contact Information</h2>
                    <span class=" mb-2 sm:mb-0">Phone: 123-456-7890</span> <br>
                    <span>Email: example@example.com</span> <!-- Stack on small screens -->

                <h2 class="text-2xl font-bold mt-6">Location</h2>
                <p class="mb-4">123 Main Street</p>
                <p class="mb-4">City, State, ZIP</p>
                <h2 class="text-2xl font-bold mt-6">Google Maps</h2>
                <div class="aspect-w-16 aspect-h-9">
                    <div class="w-full aspect-video">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2447.810583354337!2d5.3870058760157695!3d52.155954363119406!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6469f08186aeb%3A0x5139b2b23c3c09be!2sKapsalon%20&#39;t%20Hof!5e0!3m2!1sen!2snl!4v1712840759723!5m2!1sen!2snl" width="400" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
