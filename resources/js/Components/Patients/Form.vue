<template>
    <Modal :show="modalVisible">
        <div class="p-6">
            <div class="w-full flex justify-between pb-6">
                <h1 class="text-gray-600">New Patient</h1>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 cursor-pointer" @click="emit('close')">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="w-[90%] mx-[5%]">
                <InputLabel for="name" value="Name" class="ml-2 mb-1 mt-2"/>
                <TextInput v-model="form.name" class="w-full" placeholder="Name"/>
                <InputError v-if="errorList.name" :message="errorList.name" class="ml-2 mt-1"/>

                <InputLabel for="email" value="Email" class="ml-2 mb-1 mt-2"/>
                <TextInput v-model="form.email" class="w-full" placeholder="Email"/>
                <InputError v-if="errorList.email" :message="errorList.email" class="ml-2 mt-1"/>

                <InputLabel for="number" value="Phone number" class="ml-2 mb-1 mt-2"/>
                <div class="w-full flex">
                    <PrefixSelect v-model="form.prefix" class="w-1/3 mr-1"/>
                    <TextInput v-model="form.number" class="w-2/3 ml-1" placeholder="Phone number"/>
                </div>
                <InputError v-if="errorList.number" :message="errorList.number" class="ml-2 mt-1"/>

                <InputLabel for="doc" value="ID picture" class="ml-2 mb-1 mt-2"/>
                <input type="file" @change="handleFileUpload" ref="fileInput" accept="image/*">
                <InputError v-if="errorList.docImg" :message="errorList.docImg" class="ml-2 mt-1" />
            </div>

            <div class="w-full flex justify-end pt-6">
                <div>
                    <SecondaryButton @click="closeFormModal" class="mr-2">Cancel</SecondaryButton>
                    <PrimaryButton @click="submitForm">Submit</PrimaryButton>
                </div>
            </div>
        </div>
    </Modal>

    <Notification
        :message="notificationMessage"
        :type="notificationType"
        :isVisible="showNotification"
        @close="showNotification = false"
    />
</template>

<script setup lang="ts">
    import Modal from "@/Components/Modal.vue";
    import TextInput from "@/Components/TextInput.vue";
    import {reactive, ref} from "vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import InputError from "@/Components/InputError.vue";
    import axios from "axios";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import PrefixSelect from "@/Components/Patients/PrefixSelect.vue";
    import Notification from "@/Components/Notification.vue";

    defineProps<{
        modalVisible: boolean
    }>()

    const emit = defineEmits(['close', "update:patientList"])

    const notificationMessage = ref('')
    const notificationType = ref('info')
    const showNotification = ref(false)

    const form = reactive({
        name: '',
        email: '',
        number: '',
        prefix: ""
    })
    const formData = new FormData() // FormData para poder manejar la subida del archivo.
    const fileInput = ref(null)

    let errorList = ref({})

    function handleFileUpload(event) {
        formData.append("docImg", event.target.files[0])
    }

    const submitForm = () => {
        errorList.value = {}
        // Le asigno los valores de los campos del formulario al formdata para poder envíar el archivo.
        formData.append("name", form.name)
        formData.append("email", form.email)
        formData.append("number", form.number)
        formData.append("prefix", form.prefix)
        // Agrego la imagen
        axios.post(route("user.store"), formData).then(response => {
            notificationMessage.value = "Patient created successfully."
            notificationType.value = "success"
            showNotification.value = true
            emit("update:patientList", response.data.patient)
            emit("close")
        }).catch(error => {
            if (error.status === 422) {
                Object.entries(error.response.data.errors).forEach(([key, value]) => {
                    errorList.value[key] = value[0]
                })
            } else {
                notificationMessage.value = "There was an error creating the patient."
                notificationType.value = "error"
                showNotification.value = true
            }
        })
    }

    const closeFormModal = () => {
        emit('close')
        form.name = ''
        form.email = ''
        form.number = ''
        fileInput.value = ""
        errorList.value = {}
    }
</script>
