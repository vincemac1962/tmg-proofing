@extends('layouts.app')

@section('content')
    <x-section-heading class="border-teal-400">
        Customers - Create
    </x-section-heading>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <!-- Main grid -->
        <div class="grid grid-cols-4 gap-4 pt-5 w-full">
            <!-- first row -->
            <div class="mb-2 col-span-1">
                <label for="contract_reference" class="block text-sm font-medium text-gray-700 mb-1">
                    Contract Reference <span class="text-red-500">*</span>
                </label>
                <input
                        type="text"
                        id="contract_reference"
                        name="contract_reference"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('contract_reference') }}" required
                />
            </div>

            <div class="md:col-span-2 mb-2">
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Company Name <span class="text-red-500">*</span>
                </label>
                <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('company_name') }}"
                        required
                />
            </div>
            <!-- second row -->
            <div class="md:col-span-2 mb-2">
                <label for="customer_city" class="block text-sm font-medium text-gray-700 mb-1">
                    City <span class="text-red-500">*</span>
                </label>
                <input
                        type="text"
                        id="customer_city"
                        name="customer_city"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('customer_city') }}" required
                />
            </div>

            <div class="md:col-span-2 mb-2">
                <label for="customer_country" class="block text-sm font-medium text-gray-700 mb-1">
                    Country <span class="text-red-500">*</span>
                </label>
                <select
                        id="customer_country"
                        name="customer_country"
                        class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                >
                    <option value="" disabled {{ old('customer_country') ? '' : 'selected' }}>Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ old('customer_country') === $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- third row -->
            <div class="md:col-span-2 mb-2">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Contact <span class="text-red-500">*</span>
                </label>
                <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('customer_name') }}" required
                />
            </div>

            <div class="md:col-span-2 mb-4">
                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input
                        type="email"
                        id="customer_email"
                        name="customer_email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('user->email') }}" required
                />
            </div>

            <!-- fourth row -->

            <div class="col-span-2 mb-2">
                <label for="customer_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password  <span class="text-red-500">*</span>
                </label>
                <input
                        type="text"
                        id="customer_password"
                        name="customer_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('customer_password') }}"
                />
                <button type="button" onclick="openModal()" class="text-blue-600 hover:text-blue-500 mt-2">
                    Generate Password
                </button>
            </div>

            <div class="col-span-2 mb-2">
                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number
                </label>
                <input
                        type="text"
                        id="contact_number"
                        name="contact_number"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('contact_number') }}"
                />
            </div>

            <!-- fifth row -->
            <div class="w-full md:col-span-4 mb-2">
                <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Notes
                </label>
                <textarea
                        rows="4"
                        name="notes"
                        id="customer_notes"
                        class="text-indent-0 col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                {{ old('notes') }}
            </textarea>
            </div>
            <!-- sixth row -->
            <div class="md:col-span-2 flex justify-center items-center">
                <a href="{{ route('customers.index') }}" class="text-red-800 hover:text-red-600 pl-5">Cancel</a>
            </div>
            <div class="md:col-span-2 flex justify-center items-center">
                <button
                        type="submit"
                        class="text-blue-800 hover:text-blue-600"
                >
                    Save
                </button>
            </div>
        </div>
    </form>
    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="md:col-span-4">
            <h2 class="text-red-600 font-bold">Please fix the following errors:</h2>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <!-- modal for password generation -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-bold mb-4">Password Generator</h2>
            <label>
                Password Length:
                <input id="len" min="6" type="number" value="8" class="border rounded p-1 w-16" />
            </label>
            <br />
            <label>
                <input id="upper" type="checkbox" checked /> Include Uppercase
            </label>
            <br />
            <label>
                <input id="nums" type="checkbox" checked /> Include Numbers
            </label>
            <br />
            <label>
                <input id="special" type="checkbox" /> Include Special Characters
            </label>
            <br />
            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4" onclick="generate()">Generate</button>
            <button class="bg-gray-500 text-white px-4 py-2 rounded mt-4" onclick="closeModal()">Close</button>
            <div class="output mt-4 p-2 bg-gray-100 rounded" id="passOut">Your password will appear here</div>
            <a href="#" onclick="copyToClipboard()" class="text-blue-500 underline mt-2 block">Copy to Clipboard</a>
        </div>
    </div>
    <!-- scripting for password generation -->
    <script>

        function openModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        function genPass(len, upper, nums, special) {
            const lower = "abcdefghijklmnopqrstuvwxyz";
            const upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const numChars = "0123456789";
            const specialChars = "!@#$%^&*()-_=+[]{}|;:,.<>?";
            let chars = lower;

            if (upper) chars += upperChars;
            if (nums) chars += numChars;
            if (special) chars += specialChars;

            let pass = "";
            for (let i = 0; i < len; i++) {
                const randIdx = Math.floor(Math.random() * chars.length);
                pass += chars[randIdx];
            }

            return pass;
        }

        function generate() {
            const len = parseInt(document.getElementById("len").value);
            const upper = document.getElementById("upper").checked;
            const nums = document.getElementById("nums").checked;
            const special = document.getElementById("special").checked;

            const pass = genPass(len, upper, nums, special);
            document.getElementById("passOut").textContent = pass;
        }

        function copyToClipboard() {
            const passOut = document.getElementById("passOut").textContent;
            navigator.clipboard.writeText(passOut).then(() => {
                alert("Password copied to clipboard!");
            }).catch(err => {
                alert("Failed to copy password: " + err);
            });
        }
    </script>



@endsection