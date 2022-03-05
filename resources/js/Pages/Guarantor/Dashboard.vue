<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 sm:px-20 bg-white border-b border-gray-200">

                        <div>
                            <div class="text-2xl font-bold text-center">Guarantee a loan</div>
                            <jet-validation-errors class="mt-6" />
                            <div class="mt-6 text-center">Enter Loan Code</div>
                        </div>
                        <div>
                            <jet-input id="code" type="text" class="mt-1 block w-full text-center" v-model="code" required  autocomplete="Code" />
                        </div>
                        <div class="flex items-center justify-center mt-6">
                            <jet-button class="" :disabled="!validation" @click.native="proceed">
                                Proceed
                            </jet-button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

<!--        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">-->
<!--              -->

<!--        </div>-->

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import SelectEmployer from '@/Pages/Components/SelectEmployer'
    import Home from '@/Pages/Components/Home'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default {
        components: {
            AppLayout,
            Welcome,
            SelectEmployer,
            Home,
            JetButton,
            JetInput,
            JetValidationErrors
        },
        data() {
            return {
                code:''
            }
        },
        computed:{
            validation:function(){
                return !(this.code === '' || (this.code).length < 10);
            }
        },

        methods: {
            proceed() {
                this.$inertia.get(route('guarantor.guarantee',{'code':this.code}))
            }
        }
    }
</script>
