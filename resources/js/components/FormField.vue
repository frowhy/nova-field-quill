<template>
    <default-field :field="field">
        <template slot="field">
            <quill-editor :id="field.name"
                          :class="errorClasses"
                          v-model="value"
                          :options="editorOption">
            </quill-editor>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'

    import {quillEditor, Quill} from 'vue-quill-editor'
    import {container, ImageExtend, QuillWatch} from 'quill-image-extend-module'
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    Quill.register('modules/ImageExtend', ImageExtend);

    export default {

        data() {
            return {
                editorOption: {
                    modules: {
                        ImageExtend: {
                            loading: true,
                            name: 'img',
                            action: '/nova-api/field/quill/upload',
                            response: (res) => {
                                return res.url
                            },
                            headers: (xhr) => {
                                xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content)
                            },
                        },
                        toolbar: {
                            container: container,
                            handlers: {
                                'image': function () {
                                    QuillWatch.emit(this.quill.id)
                                }
                            }
                        }
                    },
                    placeholder: this.field.name || '请在这里插入文本'
                }
            }
        },

        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || ''
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value
            }
        },

        components: {
            quillEditor
        }
    }
</script>

<style>
    .ql-editor {
        min-height: 400px !important;
    }

    .ql-editor:focus {
        background: #FCFCFC !important;
    }

    .ql-editor:before {
        color: var(--70) !important;
    }

    .ql-container {
        border-color: #FCFCFC !important;
        border-radius: 0 0 0.5rem 0.5rem !important;
        -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .05) !important;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .05) !important;
    }

    .ql-toolbar {
        border-color: #FCFCFC !important;
        border-radius: 0.5rem 0.5rem 0 0 !important;
        -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .05) !important;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .05) !important;
    }
</style>
