<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container collapse navbar-collapse">
            <div class="navbar-nav">
                <router-link to="/" class="navbar-brand">TRC Resource Management</router-link>
                <router-link to="/" class="nav-link" :class="{active: this.$route.name === 'home'}">Home</router-link>
                <router-link v-if="isAdmin" to="/add" class="nav-link" :class="{active: this.$route.name === 'add'}">Add Resource</router-link>
            </div>
        </div>
    </nav>
    <div class="container bg-white mt-4 shadow-sm">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    data: () => ({
        isAdmin: window.serverData.isAdmin
    })
}
</script>
