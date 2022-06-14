<template>
    <div class="alert alert-danger mb-0" role="alert">
        {{ message }}
    </div>
</template>

<script>
export default {
    name: "ErrorAlert",
    props: ["message"]
}
</script>
