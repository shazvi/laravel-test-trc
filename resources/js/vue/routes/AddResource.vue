<template>
    <h3 class="py-4 mx-3">Add Resource</h3>

    <div class="row mx-2">
        <div v-if="errors.length > 0" class="alert alert-danger" role="alert">
            <div v-for="error in errors">{{ error }}</div>
        </div>

        <div class="col-md-6">
            <form @submit.prevent="addResource" class="add-resource-form">
                <div class="form-group mb-3">
                    <label for="title-field">Title</label>
                    <input id="title-field" type="text" class="form-control" :class="{ 'is-invalid': v$.resource.title.$errors.length > 0 }" placeholder="Title" v-model="resource.title" :disabled="saving">
                    <div class="invalid-feedback" v-for="error of v$.resource.title.$errors" :key="error.$uid">
                        {{ error.$message }}
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="type-field">Resource type</label>
                    <select id="type-field" class="form-select" v-model="resource.type" :disabled="saving" :class="{ 'is-invalid': v$.resource.type.$errors.length > 0 }">
                        <option selected disabled>Select resource type</option>
                        <template v-for="(id, name) in typeId">
                            <option :value="id">{{ name }}</option>
                        </template>
                    </select>
                    <div class="invalid-feedback" v-for="error of v$.resource.type.$errors" :key="error.$uid">
                        {{ error.$message }}
                    </div>
                </div>

                <link-fields v-if="resource.type === typeId['Link']" :resource="resource" :saving="saving" :validationMsg="v$.resource"></link-fields>
                <pdf-fields v-if="resource.type === typeId['PDF']" :resource="resource" :saving="saving" :validationMsg="v$.resource"></pdf-fields>
                <html-fields v-if="resource.type === typeId['HTML']" :resource="resource" :saving="saving" :validationMsg="v$.resource"></html-fields>

                <div v-if="successMsg !== ''" class="alert alert-success" role="alert">
                    {{ successMsg }}
                </div>

                <button type="submit" class="btn btn-primary my-3" :disabled="saving">
                    <span v-if="saving" class="spinner-border spinner-border-sm" role="status"></span>
                    Add
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import LinkFields from "../components/LinkFields";
import PdfFields from "../components/PdfFields";
import HtmlFields from "../components/HtmlFields";
import useVuelidate from '@vuelidate/core';
import Helper from "../helper";

export default {
    components: {HtmlFields, PdfFields, LinkFields},

    setup () {
        return { v$: useVuelidate() }
    },

    data: () => ({
        resource: {
            title: '',
            type: '',
            description: '',
            html: '',
            link: '',
            open_new_tab: false,
            file: '',
        },
        errors: [],
        successMsg: '',
        saving: false,
        typeId: window.serverData.typeId
    }),

    validations() {
        return Helper.validations(this.resource, this.typeId);
    },

    mounted() {
        document.title = `Add - ${this.$appName}`;
    },

    methods: {
        async addResource() {
            if (!await this.v$.$validate()) {
                return;
            }

            this.errors = [];
            this.saving = true;

            const formData = Helper.jsonToFormData(this.resource);

            axios.post('http://localhost:8000/api/resources', formData, Helper.formSubmissionConfig)
                .then(() => {
                    this.successMsg = 'Resource saved.';
                })
                .catch(error => {
                    if (typeof error.response?.data?.errors === 'object') {
                        this.errors = Helper.serverValidationErrorsToArray(error.response.data.errors);
                    } else {
                        this.errors = ['Sorry, something went wrong. Please try again.'];
                    }
                })
                .finally(() => {
                    this.saving = false;
                });
        },
    },
}
</script>
