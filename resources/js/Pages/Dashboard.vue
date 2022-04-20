<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <banner/>
<!--        <select-employer  v-if="$page.props.user.employer_id == null" />-->
<!--        <home :loan="$page.props.loan" v-else/>-->
        <home :loan="$page.props.loan"/>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import SelectEmployer from '@/Pages/Employer/Select'
    import Home from '@/Pages/Components/Home'
     import JetButton from '@/Jetstream/Button'
    import Banner from '@/Pages/Components/Banner'

    export default {
        components: {
            AppLayout,
            Welcome,
            SelectEmployer,
            Home,
            JetButton,
            Banner,
        },
    }
</script>
