<template>
    <AppLayout title="Patients">
        <div class="p-6">
            <div class="w-[90%] mx-auto sm:px-6 lg:px-8 flex flex-col justify-evenly">
                <div class="w-full flex">
                    <div class="w-full">
                        <div class="bg-white shadow sm:rounded-lg p-4 sm:p-8">
                            <div class="w-full justify-end flex pb-2">
                                <PrimaryButton class="flex justify-end" @click="newUserModalVisible = true">Add Patient</PrimaryButton>
                            </div>
                            <PatientTable :patients="patients" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <PatientForm :modalVisible="newUserModalVisible" @close="newUserModalVisible = false" @update:patientList="addPatient"/>
    </AppLayout>
</template>

<script setup lang="ts">
    import AppLayout from "@/Layouts/AppLayout.vue";
    import PatientTable from "@/Components/Patients/Table.vue";
    import PatientForm from "@/Components/Patients/Form.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import { ref } from "vue";

    const { patients } = defineProps<{
        patients: Patient[]
    }>()

    const patientList = ref(patients)
    const newUserModalVisible = ref(false)

    const addPatient = (patient: Patient) => {
        patientList.value.push(patient)
    }
</script>
