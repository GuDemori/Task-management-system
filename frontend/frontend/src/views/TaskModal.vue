<template>
    <div v-if="show" class="modal-overlay" @click.self="close">
        <div class="modal-box">
            <div class="modal-header">
                <div class="header-left">
                    <div class="status" :class="{ expired: isExpired(task.date), onTime: !isExpired(task.date) }">
                        {{ isExpired(task.date) ? 'Vencido' : 'No prazo' }}
                    </div>
                </div>
                <div class="header-right">
                    <button class="iconMoreOptions" @click="showMoreOptions">
                        <img src="../assets/options.svg" alt="options icon" class="options-icon" />
                    </button>

                    <button class="iconOpenSubtaskModal" @click="openSubtaskModal">
                        <img src="../assets/add.svg" alt="Add Subtask" class="add-subtask-icon" />
                    </button>

                    <button class="iconClose" @click="close">
                        <img src="../assets/close.svg" alt="close icon" class="close-icon" />
                    </button>
                </div>
            </div>
            <div class="header-separator"></div>
            <div class="modal-content">
                <div class="modal-body">
                    <div class="main-task">
                        <input type="checkbox" class="main-checkbox" v-model="task.completed" />
                        <h2 class="task-title">{{ task.title }}</h2>
                    </div>
                    <p class="task-desc">{{ task.description }}</p>
                    <h3>Subtarefas</h3>
                    <div v-for="subtask in task.subtasks" :key="subtask.id" class="subtask-item">
                        <input type="checkbox" class="subtask-checkbox" v-model="subtask.completed" />
                        {{ subtask.title }}
                    </div>
                </div>
                <div class="modal-side">
                    <p>Criado em: {{ formatDatetime(task.createdAt) }}</p>
                    <p>
                        Data de vencimento:
                        <span :class="{ expired: isExpired(task.date), today: isToday(task.date) }">
                            {{ formatDate(task.date) }}
                        </span>
                    </p>
                    <p>Modificado em: {{ formatDatetime(task.modifiedAt) }}</p>
                    <p>ID da tarefa: {{ task.id }}</p>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showSubtaskModal" class="modal-overlay" @click.self="closeSubtaskModal">
        <div class="modal-box subtask-modal-box">
            <div class="modal-header">
                <h2>Criar Subtarefa</h2>
                <button class="icon" @click="closeSubtaskModal">X</button>
            </div>
            <div class="header-separator"></div>
            <div class="modal-content">
                <form class="subtask-form" @submit.prevent="handleCreateSubtask">
                    <label for="subtask-title">Título</label>
                    <input type="text" id="subtask-title" v-model="subtaskForm.title" required />

                    <label for="subtask-description">Descrição</label>
                    <textarea id="subtask-description" v-model="subtaskForm.description" required></textarea>

                    <label for="subtask-due-date">Data de Vencimento</label>
                    <input type="date" id="subtask-due-date" v-model="subtaskForm.due_date" required />

                    <button type="submit" class="create-subtask-btn">Criar Subtarefa</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    show: Boolean,
    task: Object
})

const emit = defineEmits(['close'])

const close = () => {
    emit('close')
}

const showMoreOptions = () => {
}

const isExpired = (date) => new Date(date) < new Date()
const isToday = (date) => new Date(date).toDateString() === new Date().toDateString()

const formatDate = (date) => {
    const opts = { day: '2-digit', month: '2-digit', year: 'numeric' }
    return new Date(date).toLocaleDateString('pt-BR', opts)
}

const formatDatetime = (date) => {
    const d = new Date(date)
    return (
        d.toLocaleDateString('pt-BR') +
        ' ' +
        d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
    )
}

const showSubtaskModal = ref(false)
const subtaskForm = ref({
    title: '',
    description: '',
    due_date: '',
    status: 'pending',
    task_id: null
})

const openSubtaskModal = () => {
    subtaskForm.value.task_id = props.task.id
    showSubtaskModal.value = true
}

const closeSubtaskModal = () => {
    showSubtaskModal.value = false
    subtaskForm.value = {
        title: '',
        description: '',
        due_date: '',
        status: 'pending',
        task_id: null
    }
}

const handleCreateSubtask = async () => {
    try {
        const response = await fetch('http://127.0.0.1:8000/api/subtasks', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(subtaskForm.value)
        })
        if (!response.ok) {
            console.error('Erro ao criar subtask', response.status)
        } else {
            const data = await response.json()
            console.log('Subtask criada com sucesso:', data)
            closeSubtaskModal()
        }
    } catch (error) {
        console.error('Erro na requisição:', error)
    }
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal-box {
    width: 70%;
    max-width: 900px;
    min-height: 400px;
    background: #fff;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    padding: 20px;
    position: relative;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left {
    display: flex;
    align-items: center;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-separator {
    border-bottom: 1px solid #ddd;
    margin: 10px 0;
}

.modal-content {
    display: flex;
    flex: 1;
}

.modal-body {
    flex: 1;
    margin-right: 20px;
}

.main-task {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.main-checkbox {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid #aaa;
    border-radius: 50%;
    cursor: pointer;
}

.main-checkbox:checked {
    background-color: #000;
    border-color: #000;
}

.task-title {
    font-size: 1.4rem;
    margin: 0;
}

.task-desc {
    margin-bottom: 16px;
}

.subtask-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 6px 0;
}

.subtask-checkbox {
    appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #aaa;
    border-radius: 50%;
    cursor: pointer;
}

.subtask-checkbox:checked {
    background-color: #000;
    border-color: #000;
}

.modal-side {
    width: 200px;
    border-left: 1px solid #ddd;
    padding-left: 20px;
    font-size: 0.9rem;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.status {
    font-size: 0.9rem;
    padding: 4px 8px;
    border-radius: 4px;
    color: #fff;
}

.status.expired {
    background: #ffebee;
}

.status.onTime {
    background: #ffebee;
}

.expired {
    background: #ffebee;
    color: #d32f2f;
}

.today {
    background: #e8f5e9;
    color: #2e7d32;
}

.iconMoreOptions,
.iconOpenSubtaskModal,
.iconClose {
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.iconMoreOptions:hover,
.iconOpenSubtaskModal:hover,
.iconClose:hover {
    background-color: #f0f0f0;
}

.iconMoreOptions {
    font-size: 1.2rem;
    color: #555;
}

.iconClose {
    font-size: 1.2rem;
    color: #f44336;
}

.iconOpenSubtaskModal {
    color: #555;
}

.add-subtask-icon {
    width: 20px;
    height: 20px;
    display: block;
    filter: brightness(0) saturate(100%);
}

.options-icon{
    filter: brightness(0) saturate(100%);
}

.close-icon{
    filter: brightness(0) saturate(100%);

}

.subtask-modal-box {
    max-width: 600px;
    min-height: 200px;
}

.subtask-form {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 10px;
}

.subtask-form label {
    font-weight: bold;
}

.subtask-form input,
.subtask-form textarea {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.create-subtask-btn {
    margin-top: 10px;
    padding: 10px;
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.create-subtask-btn:hover {
    background-color: #333;
}
</style>
