import ListResources from './vue/routes/ListResources'
import AddResource from './vue/routes/AddResource'
import EditResource from './vue/routes/EditResource'

export const routes = [
    {
        name: 'home',
        path: '/',
        component: ListResources
    },
    {
        name: 'add',
        path: '/add',
        component: AddResource
    },
    {
        name: 'edit',
        path: '/edit/:id', // include resource `id` in url so that we have a direct link to a particular edit page
        component: EditResource
    },
];
