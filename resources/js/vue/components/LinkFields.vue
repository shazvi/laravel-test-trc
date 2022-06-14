<template>
    <div class="form-group mb-3">
        <label for="link-field">Link</label>
        <input id="link-field" type="text" class="form-control" :class="{ 'is-invalid': validationMsg.link.$errors.length > 0 }" placeholder="Link" v-model="resource.link" :disabled="saving">
        <div class="invalid-feedback" v-for="error of validationMsg.link.$errors" :key="error.$uid">
            {{ error.$message }}
        </div>
    </div>

    <div class="form-check mb-3">
        <input id="type-chk-box" class="form-check-input" type="checkbox" v-model="resource.open_new_tab" :disabled="saving">
        <label class="form-check-label" for="type-chk-box">Open in new tab</label>
    </div>
</template>

<script>
export default {
    name: "LinkFields",
    props: ['resource', 'saving', 'validationMsg'],
}
</script>
