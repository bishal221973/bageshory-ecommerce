@props([
'name' => 'pdfs',
'allowMultiple' => true,
'uploadedPdfs' => [],
'errors' => [],
])

<v-media-pdfs
    ref="pdfComponent"
    name="{{ $name }}"
    v-bind:allow-multiple="{{ $allowMultiple ? 'true' : 'false' }}"
    :uploaded-pdfs='@json($uploadedPdfs)'
    :errors="errors"
    {{ $attributes->get('class') }}>
</v-media-pdfs>
@pushOnce('scripts')

<!-- <script type="text/x-template" id="v-media-pdfs-template">
    <div class="grid gap-3">

        <label
            class="grid h-[110px] w-full max-w-[260px] cursor-pointer items-center justify-center rounded border border-dashed border-gray-300 hover:border-gray-400"
            :for="$.uid + '_pdfInput'"
        >
            <div class="text-center">
                <span class="icon-file-pdf text-2xl text-red-500"></span>
                <p class="text-sm font-semibold text-gray-600">Upload PDFs</p>
                <p class="text-xs text-gray-400">PDF only (Max 10MB)</p>
            </div>

            <input
                type="file"
                class="hidden"
                name="pdfs[]"
                :id="$.uid + '_pdfInput'"
                accept="application/pdf"
                :multiple="allowMultiple"
                @change="add"
            />
        </label>

        <ul v-if="pdfs.length" class="space-y-2">
            <li
                v-for="(pdf, index) in pdfs"
                :key="pdf.id"
                class="flex items-center justify-between p-2 bg-gray-50 border rounded text-sm"
            >
                <div class="flex items-center gap-2 truncate">
                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">
                        PDF
                    </span>

                    <span class="truncate font-medium">
                        @{{ pdf.name ?? 'Selected File.pdf' }}
                    </span>

                </div>

                <button
                    type="button"
                    class="text-red-500 hover:text-red-700"
                    @click="remove(index)"
                >
                    ✕
                </button>
            </li>
        </ul>

    </div>
</script> -->
<script type="text/x-template" id="v-media-pdfs-template">
    <div class="grid gap-3">
        <!-- 🔥 HIDDEN NATIVE INPUT: Synced by DataTransfer API for fresh file uploads -->
        <input
            type="file"
            class="hidden"
            ref="realInput"
            :name="'pdfs[]'"
            accept="application/pdf"
            :multiple="allowMultiple"
        />

        <!-- 🔥 HIDDEN INPUTS FOR RETAINED EXISTING PATHS: Tells backend which database files were NOT deleted -->
        <template v-for="pdf in pdfs" :key="pdf.id">
            <input 
                v-if="pdf.is_existing" 
                type="hidden" 
                :name="'existing_pdfs[]'" 
                :value="pdf.path" 
            />
        </template>

        <!-- Upload Trigger Box -->
        <label
            class="grid h-[110px] w-full max-w-[260px] cursor-pointer items-center justify-center rounded border border-dashed border-gray-300 hover:border-gray-400"
            :for="$.uid + '_pdfInput'"
        >
            <div class="text-center">
                <span class="icon-file-pdf text-2xl text-red-500"></span>
                <p class="text-sm font-semibold text-gray-600">Upload PDFs</p>
                <p class="text-xs text-gray-400">PDF only (Max 10MB)</p>
            </div>

            <!-- This dummy input gathers the file selection event -->
            <input
                type="file"
                class="hidden"
                :id="$.uid + '_pdfInput'"
                accept="application/pdf"
                :multiple="allowMultiple"
                @change="add"
            />
        </label>

        <!-- File List -->
        <ul v-if="pdfs.length" class="space-y-2">
            <li
                v-for="(pdf, index) in pdfs"
                :key="pdf.id"
                class="flex items-center justify-between p-2 bg-gray-50 border rounded text-sm"
            >
                <div class="flex items-center gap-2 truncate">
                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">
                        PDF
                    </span>
                    <span class="truncate font-medium">
                        @{{ pdf.name }}
                    </span>
                </div>
                <button
                    type="button"
                    class="text-red-500 hover:text-red-700"
                    @click="remove(index)"
                >
                    ✕
                </button>
            </li>
        </ul>
    </div>
</script>


<!-- <script type="module">
    app.component('v-media-pdfs', {
        template: '#v-media-pdfs-template',

        props: {
            name: {
                type: String,
                default: 'pdfs'
            },
            allowMultiple: {
                type: Boolean,
                default: true
            },
            uploadedPdfs: {
                type: Array,
                default: () => []
            },
            errors: {
                type: Object,
                default: () => ({})
            },
        },

        data() {
            return {
                pdfs: [],
            };
        },

        mounted() {
            this.pdfs = this.uploadedPdfs;
        },

        methods: {

            add(event) {
                const files = Array.from(event.target.files || []);

                if (!files.length) return;

                const valid = files.every(f => f.type === 'application/pdf');

                if (!valid) {
                    this.$emitter.emit('add-flash', {
                        type: 'warning',
                        message: 'Only PDF files are allowed',
                    });
                    return;
                }

                files.forEach(file => {
                    this.pdfs.push({
                        id: crypto.randomUUID(),
                        file: file,
                        name: file.name,
                    });
                });
                this.syncToInput();
                event.target.value = '';
            },

            remove(index) {
                this.pdfs.splice(index, 1);
                this.syncToInput(); 
            },

            getFiles() {
                return this.pdfs.map(p => p.file);
            },

            formatSize(size) {
                if (!size) return '';
                return (size / 1024 / 1024).toFixed(2) + ' MB';
            },
            syncToInput() {
                const input = this.$refs.realInput;

                const dt = new DataTransfer();

                this.pdfs.forEach(p => {
                    if (p.file) dt.items.add(p.file);
                });

                input.files = dt.files;
            }
        }
    });
</script> -->

<script type="module">
    app.component('v-media-pdfs', {
        template: '#v-media-pdfs-template',
        props: {
            name: {
                type: String,
                default: 'pdfs'
            },
            allowMultiple: {
                type: Boolean,
                default: true
            },
            uploadedPdfs: {
                type: Array,
                default: () => []
            },
            errors: {
                type: Object,
                default: () => ({})
            },
        },
        data() {
            return {
                pdfs: [],
            };
        },
        mounted() {
            // Map raw database string paths safely into the frontend working array
            this.pdfs = this.uploadedPdfs.map(path => {
                const parts = path.split('/');
                return {
                    id: crypto.randomUUID(),
                    name: parts[parts.length - 1], // Display filename safely
                    path: path,
                    is_existing: true, // Flag as database file
                    file: null
                };
            });
        },
        methods: {
            add(event) {
                const files = Array.from(event.target.files || []);
                if (!files.length) return;

                const valid = files.every(f => f.type === 'application/pdf');
                if (!valid) {
                    this.$emitter.emit('add-flash', {
                        type: 'warning',
                        message: 'Only PDF files are allowed',
                    });
                    return;
                }

                files.forEach(file => {
                    this.pdfs.push({
                        id: crypto.randomUUID(),
                        file: file,
                        name: file.name,
                        is_existing: false // Flag as new file upload
                    });
                });
                this.syncToInput();
                event.target.value = '';
            },

            remove(index) {
                this.pdfs.splice(index, 1);
                this.syncToInput();
            },

            syncToInput() {
                const input = this.$refs.realInput;
                if (!input) return;

                const dt = new DataTransfer();
                this.pdfs.forEach(p => {
                    if (p.file) dt.items.add(p.file);
                });
                input.files = dt.files;
            }
        }
    });
</script>


@endPushOnce