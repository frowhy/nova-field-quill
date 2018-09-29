<template>
    <span>{{ fieldPlain }}</span>
</template>

<script>
    export default {
        props: ['resourceName', 'field'],

        computed: {
            fieldPlain() {
                let field = this.field;
                let fieldPlain = field.value.replace(/<\/?.+?>/g, "");
                if (fieldPlain.length > 20) {
                    fieldPlain = fieldPlain.substring(20) + '...'
                }

                return fieldPlain
            }
        }
    }
</script>
