<!-- resources/views/users/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-teal-400">
        Users - Edit
    </x-section-heading>
    <form action="{{ route('users.update', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this user?');">
        @csrf
        @method('PUT')

        <!-- Main grid -->
        <div class="grid grid-cols-4 gap-4 pt-5 w-1/2">
            <!-- first row -->
           <div class="md:col-span-2 mb-2">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Name
                </label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        value="{{ old('name', $user->name) }}"
                        required
                />
            </div>
            <div class="md:col-span-2 mb-2">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Email
                </label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        value="{{ old('email', $user->email) }}"
                        required
                />
            </div>
            <!-- second row -->
            <div class="md:col-span-4 mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Password
                </label>
                <input
                        type="text"
                        id="password"
                        name="password"
                        class="w-1/2 col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Leave blank to keep current password"
                />
                <button type="button" onclick="openModal()" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 mt-2">
                    &nbsp;&nbsp;Generate Password
                </button>
            </div>
        <!-- third row -->
        <div class="md:col-span-2 mb-2">
            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Role:</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 text-gray-900 dark:text-gray-100" id="role" name="role" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="designer" {{ $user->role == 'designer' ? 'selected' : '' }}>Designer</option>

            </select>
        </div>
            <div class="md:col-span-1 mb-2 items-center text-center">
                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Active?:</label>
                <!-- Hidden input to send false value -->
                <input type="hidden" name="is_active" value="0">
                <!-- Checkbox to send true value -->
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
            </div>
        <div class="col-span-1>"></div>
        </div>
        <div class="col-span-4 w-1/2">
            <div class="flex justify-between items-center w-full py-5">
                <a href="{{ route('users.index') }}" class="text-red-800 hover:text-red-600">
                    Cancel
                </a>
                <button type="submit" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">
                    Save
                </button>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-800 hover:text-red-600 pl-5">
                        Delete
                    </button>
                </form>
            </div>
        </div>



        </form>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color: #ef4444">{{ $error }}</li>
                @endforeach
            </ul>
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