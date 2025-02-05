<div id="livePreview" class="transition-all duration-300">
    <div class="preview-header">
        <h3 id="previewHeaderTitle"></h3>
        <img id="previewLogo" src="" alt="Logo">
    </div>
    <div class="preview-body">
        <div class="preview-body-title">รายงานยอดประจำวัน</div>
        <div class="preview-body-date" id="previewDateTime"></div>
        <div id="previewItems"></div>
    </div>
    <div class="preview-footer">
        <a href="#" id="previewButton" class="preview-button"></a>
        <button id="togglePreview" class="w-full mt-2 py-2 text-gray-600 hover:text-gray-800 text-sm transition-all duration-200">
            <span class="hide-preview-text">ซ่อน Preview</span>
            <span class="show-preview-text" style="display: none;">แสดง Preview</span>
        </button>
    </div>
</div>

<button id="showPreviewBtn" class="fixed top-4 right-4 z-50 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-lg transition-all duration-200" style="display: none;">
    แสดง Preview
</button>