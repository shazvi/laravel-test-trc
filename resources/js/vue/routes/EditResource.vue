<template>
    <h3 class="py-4 mx-3">Edit Resource</h3>

    <big-spinner v-if="fetching"></big-spinner>

    <error-alert v-else-if="fetchFailed" message="Sorry, something went wrong. Please try again."></error-alert>

    <div v-else class="row mx-2">
        <div v-if="errors.length > 0" class="alert alert-danger" role="alert">
            <div v-for="error in errors">{{ error }}</div>
        </div>

        <div class="col-md-6">
            <form @submit.prevent="updateResource">
                <div class="form-group mb-3">
                    <label for="title-field">Title</label>
                    <input id="title-field" type="text" class="form-control" :class="{ 'is-invalid': v$.resource.title.$errors.length > 0 }" placeholder="Title" v-model="resource.title" :disabled="saving">
                    <div class="invalid-feedback" v-for="error of v$.resource.title.$errors" :key="error.$uid">
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
                    Update
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import LinkFields from "../components/LinkFields";
import PdfFields from "../components/PdfFields";
import HtmlFields from "../components/HtmlFields";
import BigSpinner from "../components/BigSpinner";
import Helper from "../helper";
import useVuelidate from '@vuelidate/core';
import ErrorAlert from "../components/ErrorAlert";

export default {
    components: {ErrorAlert, BigSpinner, HtmlFields, PdfFields, LinkFields},

    setup () {
        return { v$: useVuelidate() }
    },

    data: () => ({
        resource: {},
        fetching: false,
        fetchFailed: false,
        saving: false,
        successMsg: '',
        errors: [],
        typeId: window.serverData.typeId
    }),

    validations() {
        return Helper.validations(this.resource, this.typeId);
    },

    created() {
        if (typeof this.$route.params.resource === 'string') {
            // Navigated from list view, resource passed in params
            this.resource = JSON.parse(this.$route.params.resource);
        }
        else if (this.$route.params.id != null) {
            // Navigated from URL, fetch resource from server
            this.fetching = true;
            axios.get(`http://localhost:8000/api/resources/${this.$route.params.id}`)
                .then(response => this.resource = response.data)
                .catch(() => this.fetchFailed = true)
                .finally(() => this.fetching = false)
        }
        else {
            // Invalid params
            this.$router.replace({name: 'home'});
        }
    },

    mounted() {
        document.title = `Edit - ${this.$appName}`;
    },

    methods: {
        async updateResource() {
            if (!await this.v$.$validate()) {
                return;
            }

            this.errors = [];
            this.saving = true;

            const formData = Helper.jsonToFormData(this.resource);

            // laravel doesn't recognize content-type: 'multipart/form-data' in a PUT request.
            // Therefore, we have to send a post request with following field.
            formData.append('_method', 'PUT');

            axios.post(`http://localhost:8000/api/resources/${this.$route.params.id}`, formData, Helper.formSubmissionConfig)
                .then(response => {
                    this.successMsg = 'Resource saved.';

                    // update download link to new filename
                    if(this.resource.type === this.typeId['PDF']) {
                        this.resource.filename = response.data.filename;
                    }
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
        }
    }
}
</script>
