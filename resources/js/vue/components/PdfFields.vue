<template>
    <div v-if="resource.filename" class="my-4">
        Download PDF: <a :href="resource.filename" target="_blank">{{ resource.filename.replace('storage/', '') }}</a>
    </div>

    <div class="mb-3">
        <label for="pdf-file" class="form-label">Upload new PDF</label>
        <input id="pdf-file" class="form-control" :class="{ 'is-invalid': validationMsg.file.$errors.length > 0 }" type="file" @change="onFileChange" accept="application/pdf" :disabled="saving">
        <div class="invalid-feedback" v-for="error of validationMsg.file.$errors" :key="error.$uid">
            {{ error.$message }}
        </div>
    </div>
</template>

<script>
export default {
    name: "PdfFields",
    props: ['resource', 'saving', 'validationMsg'],

    methods: {
        onFileChange(e) {
            this.resource.file = e.target.files[0];
        }
    }
}
</script>
