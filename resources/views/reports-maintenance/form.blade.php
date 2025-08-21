{{-- resources/views/reports-maintenance/form.blade.php --}}
<div class="space-y-6">
    <div>
        <label for="report_category" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Category</label>
        <input type="text" id="report_category" name="report_category"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
               value="{{ old('report_category', $report->report_category ?? '') }}" required>
    </div>

    <div>
        <label for="report_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Name</label>
        <input type="text" id="report_name" name="report_name"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500  dark:bg-gray-900 text-gray-900 dark:text-gray-100"
               value="{{ old('report_name', $report->report_name ?? '') }}" required>
    </div>

    <div>
        <label for="report_description" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Description</label>
        <textarea id="report_description" name="report_description" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500  dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                  required>{{ old('report_description', $report->report_description ?? '') }}</textarea>
    </div>

    <div>
        <label for="report_view" class="block text-sm font-medium text-gray-700">View</label>
        <input type="text" id="report_view" name="report_view"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500  dark:bg-gray-900 text-gray-900 dark:text-gray-100"
               value="{{ old('report_view', $report->report_view ?? '') }}" required>
    </div>

    <div>
        <label for="report_fields" class="block text-sm font-medium text-gray-700">Fields</label>
        <textarea id="report_fields" name="report_fields" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500  dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                  required>{{ old('report_fields', $report->report_fields ?? '') }}</textarea>
    </div>
</div>
