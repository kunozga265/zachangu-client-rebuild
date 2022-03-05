<template>
    <div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 sm:px-20 bg-white border-b border-gray-200">

                        <div class="mt-8 text-2xl text-center">
                            Select your employer
                        </div>

                        <jet-validation-errors class="mb-4" />

                        <form @submit.prevent="submit">
                            <div class="mt-4">
                                <!--                 <jet-label for="employer" value="Select Employer"/>-->
                                <select class="w-full  border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="form.employerId" id="employer" required autofocus>
                                    <option
                                        v-for="employer in $page.props.employers"
                                        :value="employer.id"
                                        :key="employer.id"
                                    >{{ employer.name }}</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Proceed
                                </jet-button>
                            </div>
                        </form>

                    </div>

                    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
                        <div class="p-6">

                            <div class="">
                                <div class="md:m-0 text-gray-600 dark:text-gray-400 text-lg font-bold">
                                    Your employer is not listed?
                                </div>
                                <div class=" text-gray-600 dark:text-gray-400 text-sm">
                                    Download the agreement form and inform your employer to get in touch with us
                                </div>

                                <a :href="$page.props.publicPath+'files/ZACHANGU_MICROFINANCE_AGENCY_EMPLOYER_AGREEMENT.pdf'" target="_blank">
                                    <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                        <div>Agreement Form</div>

                                        <div class="ml-1 text-indigo-500">
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">

                            <div class="">
                                <div class=" text-gray-600 dark:text-gray-400 text-lg font-bold">
                                    Contact details
                                </div>

                                <div class=" text-gray-600 dark:text-gray-400 text-sm">
                                    Zachangu Microfinance Agency
                                </div>
                                <div class=" text-gray-600 dark:text-gray-400 text-sm">
                                    P. O. Box 2598, Lilongwe
                                </div>
                                <div class=" text-gray-600 dark:text-gray-400 text-sm">
                                    info@zachanguloans.com
                                </div>
                                <div class=" text-gray-600 dark:text-gray-400 text-sm">
                                    +265 995 15 99 64
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import JetLabel from '@/Jetstream/Label.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default {
        components: {
            JetLabel,
            JetButton,
            JetValidationErrors
        },

        data() {
            return {
                form: this.$inertia.form({
                    employerId:'',
                }),
                // employers1:['Name 1','Name 2',"Name 3"],
                // employers:[
                //     {
                //         name:"Name 1",
                //         address:"Address 1"
                //     },
                //     {
                //         name:"Name 2",
                //         address:"Address 2"
                //     },
                //     {
                //         name:"Name 3",
                //         address:"Address 3"
                //     },
                // ],
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('employer.select'))
            }
        }
    }
</script>
