<template>
    <div v-if="$page.props.flash.error || hasErrors">
        <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

        <div v-if="$page.props.flash.error" class="text-sm text-red-600">
            {{$page.props.flash.error}}
        </div>
        <div v-else>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        }
    }
</script>
