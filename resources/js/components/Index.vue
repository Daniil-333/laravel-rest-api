<template>
    <div class="container mt-5">
        <h1 class="mb-4">Оборудования</h1>
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>
        <div class="d-flex justify-content-end mb-4">
            <router-link :to="{ name: 'Create' }" class="btn btn-success"><i class="bi bi-plus-lg"></i> Добавление записи</router-link>
        </div>

        <div class="table-responsive">

            <div class="col-md-6 mb-4">
                <input type="text"
                       class="form-control"
                       v-model="input.search"
                       @input="fetchItems"
                       placeholder="Поиск по серийному номеру/примечанию"
                >
            </div>

            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">ID типа оборудования</th>
                    <th scope="col">Серийный номер</th>
                    <th scope="col" style="width: 20%;">Комментарий</th>
                    <th scope="col" style="width: 20%;">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in items" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.equipment_type.title }}</td>
                    <td>{{ item.serial_number }}</td>
                    <td>{{ item.comment }}</td>
                    <td>
                        <router-link :to="{ name: 'Edit', params: { id: item.id } }"
                                     class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil-square me-1"></i>
                            Редактировать</router-link>
                        <button class="btn btn-sm btn-outline-danger" @click="openDeleteConfirmation(item.id)"> <i
                            class="bi bi-trash me-1"></i> Удалить</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <Bootstrap5Pagination
                :data="paginateLinks"
                @pagination-change-page="fetchItems"
            />
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
         aria-hidden="true" ref="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, reactive, ref} from 'vue';
import { useRouter } from 'vue-router';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    components: [Bootstrap5Pagination],

    setup() {
        const input = reactive({ search: '' });
        const items = ref([]);
        const router = useRouter();
        const itemIdToDelete = ref(null);
        const deleteModal = ref(null);
        const successMessage = ref('');
        const paginateLinks = ref([]);

        const fetchItems = async (page = 1) => {
            let uri = `https://ktelekom.loc/api/equipment?page=${page}`;

            if(input.search) uri += `&search=${input.search}`

            try {
                const response = await axios.get(uri);
                items.value = response.data.data;
                paginateLinks.value = response.data.meta;
            } catch (error) {
                console.error('Ошибка получения записей:', error);
            }
        };

        const openDeleteConfirmation = (id) => {
            itemIdToDelete.value = id;
            const modalInstance = new bootstrap.Modal(deleteModal.value);
            modalInstance.show();
        };

        const closeModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
            if (modalInstance) {
                modalInstance.hide();
            }
        };

        const confirmDelete = async () => {
            if (itemIdToDelete.value !== null) {
                await deleteItem(itemIdToDelete.value);
                closeModal();
            }
        };

        const deleteItem = async (id) => {
            try {
                const uri = `https://ktelekom.loc/api/equipment/${id}`;
                await axios.delete(uri);
                items.value = items.value.filter((item) => item.id !== id);
                successMessage.value = 'Оборудование успешно удалено!';
                setTimeout(() => {
                    successMessage.value = '';
                    router.push({ name: 'Index' });
                }, 1000);
            } catch (error) {
                console.error('Error deleting item:', error);
            }
        };

        onMounted(fetchItems);

        return {
            items,
            openDeleteConfirmation,
            confirmDelete,
            deleteItem,
            deleteModal,
            closeModal,
            successMessage,
            input,
            paginateLinks,
            fetchItems,
        };
    }
}
</script>

<style></style>
