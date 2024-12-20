<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h1 class="card-title text-center">Редактирование записи</h1>
            </div>
            <div class="card-body">
                <div v-if="successMessage" class="alert alert-success">
                    {{ successMessage }}
                </div>
                <form @submit.prevent="updateItem">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="equipmentType" class="form-label">Тип оборудования:</label>
                            <CFormSelect
                                aria-label="Default select example"
                                v-model="item.equipment_type_id"
                                :options="equipmentTypes">
                            </CFormSelect>
                            <div v-if="errorEquipTypeMessage" class="alert alert-warning">
                                {{ errorEquipTypeMessage }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="itemName" class="form-label">Серийный номер:</label>
                            <input type="text" id="itemName" class="form-control" v-model="item.serial_number"
                                   placeholder="Enter item name" required>
                            <div v-if="errorSNMessage" class="alert alert-warning">
                                {{ errorSNMessage }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="itemPrice" class="form-label">Комментарий:</label>
                            <textarea class="form-control" id="itemPrice" v-model="item.comment"
                                      placeholder="Комментарий"></textarea>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-4">Сохранить</button>
                    </div>
                </form>

                <div v-if="errors.length" class="alert alert-danger">
                    <p v-for="error in errors" :key="error.id">{{ error }}</p>
                </div>

                <div class="mt-4 d-flex justify-content-start">
                    <router-link :to="{ name: 'Index' }" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Вернуться к списку оборудования
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}
</style>

<script>
import { onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

export default {
    setup() {
        const item = reactive({ equipment_type_id: '', serial_number: '', comment: '' });
        const equipmentTypes = ref([]);
        const route = useRoute();
        const router = useRouter();
        const errorEquipTypeMessage = ref('');
        const errorSNMessage = ref('');
        const successMessage = ref('');
        const errors = ref([]);

        const outputError = (errorType, msg) => {
            errorType.value = msg;
            setTimeout(() => errorType.value = '', 1000)
        }

        const getItem = async () => {
            try {
                const uri = `https://ktelekom.loc/api/equipment/${route.params.id}`;
                const response = await axios.get(uri);
                Object.assign(item, response.data.data);
            }
            catch (error) {
                console.error("Ошибка запроса получения оборудования:", error);
            }
        };

        const updateItem = async () => {
            if(!item.equipment_type_id) {
                return outputError(errorEquipTypeMessage, 'Тип оборудования не заполнен!')
            }
            else if(!item.serial_number) {
                return outputError(errorSNMessage, 'Серийный номер не заполнен!')
            }

            const uri = `https://ktelekom.loc/api/equipment/${route.params.id}`;
            const response = await axios.put(uri, item);

            if(response.data.errors) {
                errors.value = response.data.errors;
                setTimeout(() => errors.value = [],2000)
                return;
            }

            successMessage.value = 'Запись успешно обновлена!';
            setTimeout(() => {
                successMessage.value = '';
                router.push({ name: 'Index' });
            }, 1000);
        };

        const fetchItems = async () => {
            try {
                const uri = 'https://ktelekom.loc/api/equipment-type';
                const response = await axios.get(uri);
                equipmentTypes.value = response.data.data.map(eq => ({
                    value: eq.id,
                    label: eq.title,
                }));
            } catch (error) {
                console.error('Ошибка получения типов оборудования:', error);
            }
        };

        onMounted(fetchItems);
        onMounted(getItem);

        return {
            item,
            equipmentTypes,
            updateItem,
            errorEquipTypeMessage,
            errorSNMessage,
            successMessage,
            errors
        };
    }
}
</script>
