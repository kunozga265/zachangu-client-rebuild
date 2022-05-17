<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            System Information
        </template>

        <template #description>
            Update your loan calculation determinants.
        </template>

        <template #form>

            <!-- Interest -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="interest" value="Interest" />
                <jet-input id="interest" type="text" class="mt-1 block w-full" v-model="form.interest" autocomplete="name" />
                <span class="text-xs text-gray-400">Percentage: {{(form.interest)*100}}%</span>
                <jet-input-error :message="form.errors.interest" class="mt-2" />
            </div>

            <!-- Interest -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="lowerLimit" value="Lower Limit" />
                <jet-input id="lowerLimit" type="text" class="mt-1 block w-full" v-model="form.lowerLimit" autocomplete="name" />
                <jet-input-error :message="form.errors.lowerLimit" class="mt-2" />
            </div>

            <!-- Interest -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="upperLimit" value="Upper Limit" />
                <jet-input id="upperLimit" type="text" class="mt-1 block w-full" v-model="form.upperLimit" autocomplete="name" />
                <jet-input-error :message="form.errors.upperLimit" class="mt-2" />
            </div>

            <!-- Interest -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="bankCharge" value="Bank Charge" />
                <jet-input id="bankCharge" type="text" class="mt-1 block w-full" v-model="form.bankCharge" autocomplete="name" />
                <span class="text-xs text-gray-400">Percentage: {{(form.bankCharge)*100}}%</span>
                <jet-input-error :message="form.errors.bankCharge" class="mt-2" />
            </div>


        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    interest: this.$page.props.contents.interest,
                    lowerLimit: this.$page.props.contents.lowerLimit,
                    upperLimit: this.$page.props.contents.upperLimit,
                    bankCharge: this.$page.props.contents.bankCharge,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                this.form.post(route('users.admin.update.content'), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
