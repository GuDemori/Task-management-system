<template>
    <div class="create-subtask-form">
        <h2>Criar Subtarefa</h2>
        <form @submit.prevent="handleSubmit">
            <label for="title">Título</label>
            <input type="text" id="title" v-model="form.title" required />

            <label for="description">Descrição</label>
            <textarea id="description" v-model="form.description" required></textarea>

            <label for="due_date">Data de Vencimento</label>
            <input type="date" id="due_date" v-model="form.due_date" required />

            <label for="task_id">ID da tarefa</label>
            <input type="number" id="task_id" v-model="form.task_id" required />

            <button type="submit">Criar Subtarefa</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
    title: '',
    description: '',
    due_date: '',
    task_id: ''
})

const handleSubmit = async () => {
    const payload = { ...form.value, status: "pending" }
    try {
        const response = await fetch('http://127.0.0.1:8000/api/subtasks', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        if (!response.ok) {
            console.error('Error creating subtask', response.status)
            router.push({ name: 'subtaskList' })
        } else {
            const data = await response.json()
            console.log('Subtask created successfully:', data)
            router.push({ name: 'subtaskList' })
        }
    } catch (error) {
        console.error('Request error:', error)
    }
    form.value = {
        title: '',
        description: '',
        due_date: '',
        task_id: ''
    }
}
</script>

<style scoped>
.create-subtask-form {
    max-width: 600px;
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
}

.create-subtask-form form {
    display: flex;
    flex-direction: column;
}

.create-subtask-form label {
    margin-top: 10px;
    font-weight: bold;
}

.create-subtask-form input,
.create-subtask-form textarea {
    margin-top: 5px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.create-subtask-form button {
    margin-top: 20px;
    padding: 10px;
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.create-subtask-form button:hover {
    background-color: #333;
}
</style>