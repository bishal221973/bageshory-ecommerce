@props([
    'name' => 'pdfs',
    'label' => 'Upload PDF Documents',
    'required' => false
])

<div class="mb-4" x-data="{ files: [] }">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>

    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors relative">
        <div class="space-y-1 text-center">
            <!-- Icon -->
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            
            <div class="flex text-sm text-gray-600 justify-center">
                <label for="{{ $name }}" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                    <span>Select files</span>
                    <input 
                        id="{{ $name }}" 
                        name="pdfs[]" 
                        type="file" 
                        accept="application/pdf" 
                        multiple 
                        {{ $required ? 'required' : '' }}
                        class="sr-only"
                        @change="files = Array.from($event.target.files)"
                    >
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PDF files up to 10MB each</p>
        </div>
    </div>

    <!-- File Preview List -->
    <template x-if="files.length > 0">
        <ul class="mt-3 space-y-2">
            <template x-for="(file, index) in files" :key="index">
                <li class="flex items-center justify-between p-2 bg-gray-50 rounded border text-sm text-gray-700">
                    <div class="flex items-center space-x-2 truncate">
                        <!-- PDF Icon Accent -->
                        <span class="bg-red-100 text-red-700 font-bold px-1.5 py-0.5 rounded text-xs">PDF</span>
                        <span x-text="file.name" class="truncate font-medium"></span>
                        <span x-text="`(${Math.round(file.size / 1024 / 1024 * 100) / 100} MB)`" class="text-xs text-gray-400"></span>
                    </div>
                </li>
            </template>
        </ul>
    </template>

    <!-- Error Validation Display -->
    @error($name)
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
    @error($name . '.*')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
