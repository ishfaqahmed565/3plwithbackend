@props([
    'inputName' => 'attachments',
    'inputId' => 'file-upload',
    'previewId' => 'file-preview',
    'modalId' => 'upload-modal',
    'color' => 'green',
    'label' => 'Images / PDF Documents',
    'required' => false
])

@php
    $colorClasses = [
        'green' => [
            'btn' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
            'ring' => 'focus:ring-green-500',
            'text' => 'text-green-600'
        ],
        'purple' => [
            'btn' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500',
            'ring' => 'focus:ring-purple-500',
            'text' => 'text-purple-600'
        ]
    ];
    $colors = $colorClasses[$color] ?? $colorClasses['green'];
    // Sanitize modalId for JavaScript function names (replace dashes with underscores)
    $jsModalId = str_replace('-', '_', $modalId);
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }} @if($required)<span class="text-red-600">*</span>@endif
    </label>
    
    <input type="file" name="{{ $inputName }}[]" id="{{ $inputId }}" multiple accept="image/*,application/pdf" class="hidden" @if($required) required @endif>
    
    <button type="button" onclick="openUploadModal_{{ $jsModalId }}()" 
            class="{{ $colors['btn'] }} text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        Upload Files
    </button>
    
    <p class="text-sm text-gray-500 mt-2">Click to upload or paste from clipboard (Max: 5MB each)</p>
    
    <div id="{{ $previewId }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
</div>

<!-- Upload Modal -->
<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Upload Files</h3>
                <button type="button" onclick="closeUploadModal_{{ $jsModalId }}()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Paste & Drop Area -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Paste or Drag & Drop</label>
                <div id="{{ $modalId }}-paste-area" 
                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-{{ $color }}-400 transition cursor-pointer"
                     tabindex="0">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-gray-600 mb-1"><strong>Drag & drop</strong> files here or <strong>paste</strong> with <kbd class="px-2 py-1 bg-gray-100 border rounded">Ctrl+V</kbd></p>
                    <p class="text-xs text-gray-500">Supports images, PDFs, documents, and all file types</p>
                    <p class="text-xs text-gray-400 mt-1">Tip: Copy files with Ctrl+C from file explorer, then paste here</p>
                </div>
            </div>
            
            <!-- OR Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-sm text-gray-500 font-semibold">OR</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>
            
            <!-- Upload from Files -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload from Files</label>
                <button type="button" onclick="document.getElementById('{{ $inputId }}').click()" 
                        class="w-full border-2 border-dashed border-gray-300 rounded-lg p-8 hover:border-{{ $color }}-400 transition text-center">
                    <svg class="w-12 h-12 mx-auto {{ $colors['text'] }} mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm {{ $colors['text'] }} font-semibold">Click to browse files</p>
                    <p class="text-xs text-gray-500 mt-1">Images and PDFs accepted</p>
                </button>
            </div>
            
            <!-- Preview in Modal -->
            <div id="{{ $modalId }}-preview" class="grid grid-cols-2 gap-3 max-h-64 overflow-y-auto"></div>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button" onclick="closeUploadModal_{{ $jsModalId }}()" 
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                Cancel
            </button>
            <button type="button" onclick="confirmUpload_{{ $jsModalId }}()" 
                    class="{{ $colors['btn'] }} text-white px-6 py-3 rounded-lg font-semibold transition flex-1">
                Confirm & Close
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('{{ $modalId }}');
    const input = document.getElementById('{{ $inputId }}');
    const preview = document.getElementById('{{ $previewId }}');
    const pasteArea = document.getElementById('{{ $modalId }}-paste-area');
    const modalPreview = document.getElementById('{{ $modalId }}-preview');
    let tempFiles = [];
    
    window.openUploadModal_{{ $jsModalId }} = function() {
        modal.classList.remove('hidden');
        pasteArea.focus();
        updateModalPreview();
    };
    
    window.closeUploadModal_{{ $jsModalId }} = function() {
        modal.classList.add('hidden');
    };
    
    window.confirmUpload_{{ $jsModalId }} = function() {
        const dt = new DataTransfer();
        tempFiles.forEach(file => dt.items.add(file));
        input.files = dt.files;
        updateMainPreview();
        closeUploadModal_{{ $jsModalId }}();
    };
    
    function updateModalPreview() {
        modalPreview.innerHTML = '';
        tempFiles.forEach((file, index) => {
            const card = createPreviewCard(file, index, true);
            modalPreview.appendChild(card);
        });
    }
    
    function updateMainPreview() {
        preview.innerHTML = '';
        Array.from(input.files).forEach((file, index) => {
            const card = createPreviewCard(file, index, false);
            preview.appendChild(card);
        });
    }
    
    function createPreviewCard(file, index, isModal) {
        const card = document.createElement('div');
        card.className = 'border rounded-lg p-3 bg-gray-50 relative';
        
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'absolute top-2 right-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 transition';
        removeButton.textContent = 'Remove';
        removeButton.addEventListener('click', () => {
            if (isModal) {
                tempFiles.splice(index, 1);
                updateModalPreview();
            } else {
                const dt = new DataTransfer();
                Array.from(input.files).forEach((f, i) => {
                    if (i !== index) dt.items.add(f);
                });
                input.files = dt.files;
                tempFiles = Array.from(input.files);
                updateMainPreview();
            }
        });
        
        const title = document.createElement('p');
        title.className = 'text-sm font-medium text-gray-700 mb-2 break-all pr-16';
        title.textContent = file.name;
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.className = 'w-full h-32 object-cover rounded';
            img.src = URL.createObjectURL(file);
            img.onload = () => URL.revokeObjectURL(img.src);
            card.appendChild(img);
        } else if (file.type === 'application/pdf') {
            const pdfBadge = document.createElement('div');
            pdfBadge.className = 'flex flex-col items-center justify-center h-32 bg-red-50 border-2 border-red-200 rounded text-sm text-red-700';
            pdfBadge.innerHTML = '<svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span class="font-bold">PDF</span>';
            card.appendChild(pdfBadge);
        } else {
            const fileBadge = document.createElement('div');
            fileBadge.className = 'flex flex-col items-center justify-center h-32 bg-blue-50 border-2 border-blue-200 rounded text-sm text-blue-700';
            const ext = file.name.split('.').pop().toUpperCase();
            fileBadge.innerHTML = '<svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span class="font-bold">' + ext + '</span>';
            card.appendChild(fileBadge);
        }
        
        card.appendChild(title);
        card.appendChild(removeButton);
        return card;
    }
    
    // File input change
    input.addEventListener('change', () => {
        tempFiles = Array.from(input.files);
        updateModalPreview();
        updateMainPreview();
    });
    
    // Paste handling - supports images, PDFs, and all file types
    pasteArea.addEventListener('paste', async (e) => {
        e.preventDefault();
        
        // Method 1: Check for files copied from file explorer (Ctrl+C on files)
        const files = e.clipboardData.files;
        if (files.length > 0) {
            for (let file of files) {
                tempFiles.push(file);
            }
            updateModalPreview();
            return;
        }
        
        // Method 2: Check for image data (screenshots, copy image)
        const items = e.clipboardData.items;
        for (let item of items) {
            if (item.kind === 'file') {
                const file = item.getAsFile();
                if (file) {
                    tempFiles.push(file);
                }
            }
        }
        updateModalPreview();
    });
    
    // Drag and drop handling
    pasteArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        pasteArea.classList.add('border-{{ $color }}-500', 'bg-{{ $color }}-50');
        pasteArea.classList.remove('border-gray-300');
    });
    
    pasteArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        pasteArea.classList.remove('border-{{ $color }}-500', 'bg-{{ $color }}-50');
        pasteArea.classList.add('border-gray-300');
    });
    
    pasteArea.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        pasteArea.classList.remove('border-{{ $color }}-500', 'bg-{{ $color }}-50');
        pasteArea.classList.add('border-gray-300');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            for (let file of files) {
                tempFiles.push(file);
            }
            updateModalPreview();
        }
    });
    
    // Click to focus paste area
    pasteArea.addEventListener('click', () => {
        pasteArea.focus();
    });
    
    // Close modal on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeUploadModal{{ $modalId }}();
        }
    });
    
    // Close modal on backdrop click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeUploadModal{{ $modalId }}();
        }
    });
})();
</script>
