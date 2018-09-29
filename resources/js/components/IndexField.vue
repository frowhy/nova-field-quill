<template>
    <span>{{ fieldPlain }}</span>
</template>

<script>
    export default {
        props: ["resourceName", "field"],

        computed: {
            fieldPlain() {
                let _field = this.field;
                let _fieldPlain = _field.value.replace(/<\/?.+?>/g, "");
                if (_fieldPlain.length > 20) {
                    _fieldPlain = _fieldPlain.substring(20) + "...";
                }

                return _fieldPlain;
            },
        },
    };
</script>
