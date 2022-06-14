<template>
    <div class="m-3">
        <div class="fw-bold">Description</div>
        <pre>{{ resource.description }}</pre>
    </div>

    <div class="m-3">
        <div class="fw-bold mb-2">
            HTML snippet
            <span class="btn btn-outline-dark btn-sm copy-clipboard-btn ms-2" title="Copy to clipboard" @click="copyHtml">{{ copyBtnText }}</span>
        </div>
        <div class="code-block-container rounded border p-2">
            <pre class="mb-0">{{ resource.html }}</pre>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        copyBtnText: 'Copy',
    }),
    name: "HtmlDetails",
    props: ['resource'],
    methods: {
        async copyHtml() {
            await navigator.clipboard.writeText(this.resource.html);
            this.copyBtnText = "Copied!"
            setTimeout(() => {
                this.copyBtnText = "Copy";
            }, 3000);
        }
    },
}
</script>

<style scoped>
.code-block-container {
    background-color: #f8f9fa;
}
</style>
