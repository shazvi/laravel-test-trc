<template>
    <div class="row text-center">
        <div v-if="fetching" class="my-5">
            <big-spinner></big-spinner>
        </div>

        <div v-else-if="fetchFailed" class="col-6 offset-3 my-5">
            <error-alert message="Sorry, something went wrong. Please try again."></error-alert>
        </div>

        <div v-else-if="data.length < 1" class="alert alert-dark col-6 offset-3 my-5" role="alert">
            You don't have any resources yet. Try adding some.
        </div>

        <template v-else>
            <div class="col-3 ps-0">
                <ul class="list-group">
                    <li v-for="resource in data" :key="resource.id" class="list-group-item list-group-item-action text-start resource-list-item"
                        :class="{active: (selectedResource && selectedResource.id === resource.id)}" @click="selectedResource = resource">

                        <div class="resource-list-title">{{ resource.title }}</div>
                        <div class="resource-list-type"><span class="badge bg-secondary">{{ typeName[resource.type] }}</span></div>
                    </li>
                </ul>
            </div>

            <div class="col-9 mb-5">
                <p v-if="selectedResource == null" class="select-resource-text mt-5">Select a resource to see its details</p>

                <div v-else class="text-start">
                    <div class="row mb-5 pe-5">
                        <div class="col-2"><button class="btn-close mt-4" @click="onCloseDetails" /></div>
                        <div class="col-8 text-center"><h3 class="mt-3">Resource details</h3></div>

                        <div v-if="isAdmin" class="m-3">
                            <router-link :to="{name: 'edit', params: { id: selectedResource.id, resource: JSON.stringify(selectedResource) }}" class="btn btn-primary me-2">Edit
                            </router-link>
                            <button class="btn btn-danger mx-1" @click="deleteResource(selectedResource.id)" :disabled="deleting">
                                <span v-if="deleting" class="spinner-border spinner-border-sm" role="status"></span>
                                Delete
                            </button>
                        </div>

                        <div class="m-3">
                            <span class="fw-bold">Title: </span>
                            <span>{{ selectedResource.title }}</span>
                        </div>

                        <div class="m-3">
                            <span class="fw-bold">Type: </span>
                            <span>{{ typeName[selectedResource.type] }}</span>
                        </div>

                        <pdf-details v-if="selectedResource.type === typeId['PDF']" :resource="selectedResource"></pdf-details>
                        <link-details v-if="selectedResource.type === typeId['Link']" :resource="selectedResource"></link-details>
                        <html-details v-if="selectedResource.type === typeId['HTML']" :resource="selectedResource"></html-details>
                    </div>
                </div>
            </div>
        </template>

    </div>
</template>

<script>
import BigSpinner from "../components/BigSpinner";
import ErrorAlert from "../components/ErrorAlert";
import PdfDetails from "../components/PdfDetails";
import LinkDetails from "../components/LinkDetails";
import HtmlDetails from "../components/HtmlDetails";
export default {
    components: {HtmlDetails, LinkDetails, PdfDetails, ErrorAlert, BigSpinner},
    data: () => ({
        data: [],
        fetching: true,
        fetchFailed: false,
        deleting: false,
        selectedResource: null,
        typeName: window.serverData.typeName,
        typeId: window.serverData.typeId,
        isAdmin: window.serverData.isAdmin,
    }),

    created() {
        axios.get('http://localhost:8000/api/resources')
            .then(response => this.data = response.data)
            .catch(() => this.fetchFailed = true)
            .finally(() => this.fetching = false);
    },

    mounted() {
        document.title = this.$appName;
    },

    methods: {
        onCloseDetails() {
            this.selectedResource = null;
        },
        deleteResource(id) {
            this.deleting = true;
            axios.delete(`http://localhost:8000/api/resources/${id}`)
                .then(() => {
                    let index = this.data.findIndex(item => item.id === id);
                    this.data.splice(index, 1);
                    this.selectedResource = null;
                })
                .catch(() => {})
                .finally(() => this.deleting = false);
        }
    }
}
</script>
