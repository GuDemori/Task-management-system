<template>
    <div v-if="show" class="modal-overlay" @click.self="handleClose">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Deletar Tarefa</h2>
                <button class="icon" @click="handleClose">
                    <img src="../assets/close.svg" alt="close modal" class="close-modal-icon" />
                </button>
            </div>
            <div class="header-separator"></div>
            <div class="modal-content">
                <p>Tem certeza que deseja excluir a tarefa "{{ task.title }}"?</p>
                <div class="actions">
                    <button class="btn-cancel" @click="handleClose">Cancelar</button>
                    <button class="btn-confirm" @click="handleDelete">Deletar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    show: { type: Boolean, default: false },
    task: { type: Object, default: () => ({}) }
})
const emit = defineEmits(['close', 'taskDeleted'])

const handleDelete = async () => {
    try {
        const response = await fetch(`http://127.0.0.1:8000/api/tasks/${props.task.id}`, {
            method: 'DELETE'
        })
        if (!response.ok) {
            console.error('Error deleting task', response.status)
        } else {
            console.log('Task deleted successfully')
            emit('taskDeleted', props.task.id)
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
    max-width: 400px;
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

.modal-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-cancel {
    padding: 10px;
    background-color: #ccc;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-confirm {
    padding: 10px;
    background-color: #d32f2f;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.icon {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
}
</style>