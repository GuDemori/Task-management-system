<template>
    <div v-if="show" class="modal-overlay" @click.self="handleClose">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Editar Tarefa</h2>
                <button class="icon" @click="handleClose">
                    <img src="../assets/close.svg" alt="close modal" class="close-modal-icon" />
                </button>
            </div>
            <div class="header-separator"></div>
            <form @submit.prevent="handleSubmit">
                <label for="title">Título</label>
                <input type="text" id="title" v-model="form.title" required />

                <label for="description">Descrição</label>
                <textarea id="description" v-model="form.description" required></textarea>

                <label for="dueDate">Data de Vencimento</label>
                <input type="date" id="dueDate" v-model="form.dueDate" required />

                <button type="submit">Atualizar Tarefa</button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue'

const props = defineProps({
    show: { type: Boolean, default: false },
    task: { type: Object, default: () => ({}) }
})
const emit = defineEmits(['close', 'taskUpdated'])

const form = ref({
    title: '',
    description: '',
    dueDate: ''
})

// Preenche o formulário com os dados da task assim que ela mudar
watch(() => props.task, (newTask) => {
    if (newTask && Object.keys(newTask).length) {
        form.value.title = newTask.title
        form.value.description = newTask.description
        if (newTask.due_date) {
            // Converte para YYYY-MM-DD para o input date
            const dateObj = new Date(newTask.due_date)
            const yyyy = dateObj.getFullYear()
            const mm = String(dateObj.getMonth() + 1).padStart(2, '0')
            const dd = String(dateObj.getDate()).padStart(2, '0')
            form.value.dueDate = `${yyyy}-${mm}-${dd}`
        } else {
            form.value.dueDate = ''
        }
    }
}, { immediate: true })

const handleSubmit = async () => {
    try {
        // Cria o payload com a chave 'due_date'
        const payload = {
            title: form.value.title,
            description: form.value.description,
            due_date: form.value.dueDate
        }
        const response = await fetch(`http://127.0.0.1:8000/api/tasks/${props.task.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        if (!response.ok) {
            console.error('Error updating task', response.status)
        } else {
            const data = await response.json()
            console.log('Task updated:', data)
            emit('taskUpdated', data)
        }
    } catch (error) {
        console.error('Request error:', error)
    }
    handleClose()
}

const handleClose = () => {
    emit('close')
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-box {
    background: #fff;
    border-radius: 8px;
    width: 70%;
    max-width: 600px;
    padding: 20px;
    position: relative;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-separator {
    border-bottom: 1px solid #ddd;
    margin: 10px 0;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-top: 10px;
    font-weight: bold;
}

input,
textarea {
    margin-top: 5px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button[type="submit"] {
    margin-top: 20px;
    padding: 10px;
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #333;
}

.icon {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
}
</style>