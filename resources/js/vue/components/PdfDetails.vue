<template>
    <div class="m-3">
        <span class="fw-bold">Download PDF: </span>
        <span><a :href="resource.filename" target="_blank">{{ resource.filename.replace('storage/', '') }}</a></span>
    </div>
</template>

<script>
export default {
    name: "PdfDetails",
    props: ['resource']
}
</script>
