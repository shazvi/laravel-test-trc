<template>
    <div class="m-3">
        <span class="fw-bold">Link: </span>
        <a :href="resource.link" :target="resource.open_new_tab ? '_blank' : '_self'">{{ resource.link }}</a>
    </div>
</template>

<script>
export default {
    name: "LinkDetails",
    props: ['resource']
}
</script>
