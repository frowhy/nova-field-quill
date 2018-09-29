Nova.booting((Vue, router) => {
    Vue.component('index-nova-field-quill', require('./components/IndexField'));
    Vue.component('detail-nova-field-quill', require('./components/DetailField'));
    Vue.component('form-nova-field-quill', require('./components/FormField'));
})
