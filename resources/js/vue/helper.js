import {required, requiredIf, url} from "@vuelidate/validators";

export default {
    validations: (resource, typeId) => ({
        resource: {
            title: { required },
            type: { required },
            description: {
                required: requiredIf(() => resource.type === typeId['HTML'])
            },
            html: {
                required: requiredIf(() => resource.type === typeId['HTML'])
            },
            link: {
                required: requiredIf(() => resource.type === typeId['Link']),
                url,
            },
            file: {
                required: requiredIf(() => resource.type === typeId['PDF'])
            },
        }
    }),

    // Convert laravel validation error object to an array of error messages
    serverValidationErrorsToArray: errors => {
        let result = [];
        Object.values(errors).forEach(errorArr => {
            errorArr.forEach(error => {
                result.push(error);
            })
        });
        return result;
    },

    jsonToFormData: json => {
        const formData = new FormData();
        Object.keys(json).forEach(key => {
            formData.append(key, json[key]);
        });
        return formData;
    },

    formSubmissionConfig: {
        headers: {
            'content-type': 'multipart/form-data',
        }
    },
}
