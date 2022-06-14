<template>
    <div class="form-group mb-3">
        <label for="description-field">Description</label>
        <textarea id="description-field" class="form-control" :class="{ 'is-invalid': validationMsg.description.$errors.length > 0 }" placeholder="Description" v-model="resource.description" :disabled="saving" rows="6"></textarea>
        <div class="invalid-feedback" v-for="error of validationMsg.description.$errors" :key="error.$uid">
            {{ error.$message }}
        </div>
    </div>

<!--    todo: add syntax highlighting (codemirror, simple-code-editor, ...)-->
    <div class="form-group mb-3">
        <label for="html-field">HTML snippet</label>
        <textarea id="html-field" class="form-control" :class="{ 'is-invalid': validationMsg.html.$errors.length > 0 }" placeholder="HTML snippet" v-model="resource.html" :disabled="saving" rows="6"></textarea>
        <div class="invalid-feedback" v-for="error of validationMsg.html.$errors" :key="error.$uid">
            {{ error.$message }}
        </div>
    </div>
</template>

<script>
export default {
    name: "HtmlFields",
    props: ['resource', 'saving', 'validationMsg'],
}
</script>
