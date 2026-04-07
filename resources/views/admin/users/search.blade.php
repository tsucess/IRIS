<style>
    main {
        background-image: url('{{ asset('images/data-circuit.gif') }}');
        /* background-size: cover; */
        /* background-repeat: no-repeat; */
        background-position: center;
    }
</style>


<x-app-layout>
    
    <div class="flex items-center justify-center h-screen bg-transparent">
        <div class="w-full max-w-xl text-center">
            <h1 class="text-2xl font-bold mb-6 text-white">🔍 Eagle Eye Search</h1>

            <!-- Search Form -->
            <form id="searchForm">
                <div class="flex items-center border rounded-full overflow-hidden shadow-md">
                    <input type="text" id="query" placeholder="Enter name, ID, email..."
                        class="flex-grow px-4 py-3 focus:outline-none" required>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 font-semibold">
                        Search
                    </button>
                </div>
            </form>

            <!-- Scanning Loader -->
            <div id="loader" class="mt-8 hidden">
                <div class="flex justify-center items-center">
                    <div class="relative w-24 h-24">
                        <div class="absolute inset-0 border-4 border-white-400 rounded-full animate-ping"></div>
                        <div
                            class="absolute inset-0 border-4 border-white-600 rounded-full animate-spin border-t-transparent">
                        </div>
                        <span
                            class="absolute inset-0 flex items-center justify-center font-bold text-white">AI</span>
                    </div>
                </div>
                <p class="mt-4 text-white">Scanning with Eagle Eye AI...</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="resultsModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-11/12 max-w-5xl rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] p-6 relative">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-500">✖</button>
            <h2 class="text-xl font-bold mb-4">Search Results</h2>
            <table id="resultsTable" class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">Photo</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">ID Number</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            const searchForm = document.getElementById('searchForm');
            const loader = document.getElementById('loader');
            const resultsModal = document.getElementById('resultsModal');
            const resultsTableBody = document.querySelector('#resultsTable tbody');
            const closeModal = document.getElementById('closeModal');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const query = document.getElementById('query').value;
                loader.classList.remove('hidden');
                resultsModal.classList.add('hidden');

                fetch("{{ route('users.search.results') }}?query=" + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(users => {
                        loader.classList.add('hidden');
                        resultsTableBody.innerHTML = '';

                        if (users.length === 0) {
                            resultsTableBody.innerHTML =
                                '<tr><td colspan="5" class="text-center text-gray-500">No users found.</td></tr>';
                        } else {
                            users.forEach(user => {
                                resultsTableBody.innerHTML += `
                                <tr class="border-b">
                                    <td class="p-3">
                                        <img src="${user.photo ? '/uploads/' + user.photo : '/images/avatar.png'}" class="w-12 h-12 rounded-full">
                                    </td>
                                    <td class="p-3">${user.firstname} ${user.middle_name || ''} ${user.lastname || ''}</td>
                                    <td class="p-3">${user.email}</td>
                                    <td class="p-3">${user.id_number || ''}</td>
                                    <td class="p-3">
                                        <a href="/admin/users/${user.id}/view" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">View</a>
                                    </td>
                                </tr>
                            `;
                            });
                        }
                        resultsModal.classList.remove('hidden');
                    });
            });

            closeModal.addEventListener('click', function() {
                resultsModal.classList.add('hidden');
            });
        </script>
    @endpush
</x-app-layout>
