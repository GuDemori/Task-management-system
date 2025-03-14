<template>
    <div class="container">
        <div class="top-bar">
            <span class="emoji-left">🤘</span>
            <div class="icons-right">
                <span class="icon" @click="createTask">
                    <img src="../assets/add.svg" alt="add new task" class="add-new-task-icon" />
                </span>
                <span class="icon">
                    <img src="../assets/help.svg" alt="help button" class="help-button-icon" />
                </span>
                <span class="icon">
                    <img src="../assets/bell.svg" alt="bell button" class="bell-button-icon" />
                </span>
                <span class="icon">🙎</span>
            </div>
        </div>

        <div class="tasks-box">
            <aside class="sidebar">
                <ul>
                    <li @click="setFilter('all')" :class="{ active: filterType === 'all' }">
                        <img src="../assets/drawer.svg" alt="Open tasks" class="open-tasks-filter-icon" /> Entrada
                    </li>
                    <li @click="setFilter('today')" :class="{ active: filterType === 'today' }">
                        <img src="../assets/calendar.svg" alt="calendar icon" class="calendar-icon" /> Tarefas de hoje
                    </li>
                    <li @click="setFilter('expired')" :class="{ active: filterType === 'expired' }">
                        <img src="../assets/alert.svg" alt="alert icon" class="alert-icon" /> Vencidos
                    </li>
                </ul>
            </aside>

            <div class="task-list">
                <h2 class="task-list-title">Entrada</h2>
                <div v-for="task in tasks" :key="task.id" class="task-card" @mouseover="task.hover = true"
                    @mouseleave="task.hover = false">
                    <div class="task-top">
                        <div class="task-header">
                            <input type="checkbox" v-model="task.completed" @change="toggleMainTask(task)" />
                            <div class="task-info">
                                <h3 @click="openModal(task)">{{ task.title }}</h3>
                                <p>{{ task.description }}</p>
                            </div>
                        </div>
                        <div class="task-footer">
                            <div class="task-date"
                                :class="{ expired: isExpired(task.due_date), today: isToday(task.due_date) }">
                                <img src="../assets/calendar.svg" alt="calendar icon" class="calendar-icon" /> {{ formatDate(task.due_date) }}
                            </div>
                            <div v-if="task.hover" class="task-icons">
                                <button @click="editTask(task)">
                                    <img src="../assets/edit.svg" alt="edit icon" class="edit-icon" />
                                </button>
                                <button @click="setDeadLine(task)">
                                    <img src="../assets/calendar.svg" alt="calendar icon" class="calendar-icon" />
                                </button>
                                <button @click="deleteTask(task)">
                                    <img src="../assets/trash.svg" alt="trash icon" class="trash-icon" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="task.subtasks.length" class="separator"></div>
                    <div v-if="task.subtasks.length" class="subtask-list">
                        <div v-for="subtask in task.subtasks" :key="subtask.id" class="subtask-item">
                            <input type="checkbox" v-model="subtask.completed" @change="toggleSubtask(task)" />
                            {{ subtask.title }}
                        </div>
                        <div class="subtask-counter">
                            {{ getCompletedSubtasks(task) }}/{{ task.subtasks.length }}
                        </div>
                    </div>
                </div>
                <div class="create-task" @click="createTask">
                    <img src="../assets/add.svg" alt="add icon" class="add-icon" /> Criar tarefa
                </div>
            </div>
        </div>
    </div>

    <TaskModal :show="showModal" :task="selectedTask" @close="showModal = false" />

    <CreateTaskModal :show="showCreateTaskModal" @close="showCreateTaskModal = false" @taskCreated="onTaskCreated" />

    <EditTaskModal :show="showEditTaskModal" :task="selectedTask" @close="showEditTaskModal = false"
        @taskUpdated="updateTask" />
        
    <SetDeadLineModal :show="showSetDeadLineModal" :task="selectedTask" @close="showSetDeadLineModal = false"
        @DeadLineUpdated="updateTask" />

    <!-- Modal para excluir Task -->
    <DeleteTaskModal :show="showDeleteTaskModal" :task="selectedTask" @close="showDeleteTaskModal = false"
        @taskDeleted="removeTask" />
</template>

<script setup>
import { ref, watch } from 'vue'
import moment from 'moment'
import TaskModal from './TaskModal.vue'
import CreateTaskModal from './CreateTaskModal.vue'
import EditTaskModal from './EditTaskModal.vue'
import SetDeadLineModal from './SetDeadLineModal.vue'
import DeleteTaskModal from './DeleteTaskModal.vue'

const selectedTask = ref(null)
const showModal = ref(false)
const showCreateTaskModal = ref(false)
const showEditTaskModal = ref(false)
const showSetDeadLineModal = ref(false)
const showDeleteTaskModal = ref(false)
const filterType = ref('all')
const tasks = ref([])

const loadTasks = async () => {
    try {
        const res = await fetch(`http://127.0.0.1:8000/api/tasks?filter=` + filterType.value)
        tasks.value = await res.json()
    } catch (error) {
        console.error('Erro ao carregar tarefas:', error)
    }
}

watch(filterType, () => {
    loadTasks()
}, { immediate: true })

const openModal = (task) => {
    selectedTask.value = task
    showModal.value = true
}

const toggleMainTask = (task) => {
    task.subtasks.forEach(sub => {
        sub.completed = task.completed
    })
}

const toggleSubtask = (task) => {
    const total = task.subtasks.length
    const done = task.subtasks.filter(s => s.completed).length
    task.completed = (done === total)
}

const setFilter = (filter) => {
    filterType.value = filter
}

const formatDate = (date) => moment(date).format('DD/MM/YYYY')
const isExpired = (date) => moment(date).isBefore(moment(), 'day')
const isToday = (date) => moment(date).isSame(moment(), 'day')

const getCompletedSubtasks = (task) =>
    task.subtasks.filter(subtask => subtask.completed).length

const createTask = () => {
    showCreateTaskModal.value = true
}

const onTaskCreated = (newTask) => {
    tasks.value.push(newTask)
    showCreateTaskModal.value = false
}

// Abre o modal de edição
const editTask = (task) => {
    selectedTask.value = task
    showEditTaskModal.value = true
}

// Abre o modal para alterar DeadLine
const setDeadLine = (task) => {
    selectedTask.value = task
    showSetDeadLineModal.value = true
}

const deleteTask = (task) => {
    selectedTask.value = task
    showDeleteTaskModal.value = true
}

const updateTask = (updatedTask) => {
    const index = tasks.value.findIndex(t => t.id === updatedTask.id)
    if (index !== -1) {
        tasks.value[index] = updatedTask
    }
}

const removeTask = (taskId) => {
    tasks.value = tasks.value.filter(t => t.id !== taskId)
}
</script>

<style scoped>

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100vw;
    min-height: 100vh;
    background-color: black;
}

.top-bar {
    width: 100%;
    max-width: 1920px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #fff;
}

.emoji-left {
    font-size: 1.5rem;
    padding-left: 15px;
}

.icons-right {
    display: flex;
    gap: 36px;
    padding-right: 15px;
}

.icon {
    font-size: 1.3rem;
    cursor: pointer;
}

.tasks-box {
    display: flex;
    flex-direction: row;
    height: 100%;
    width: 100%;
    min-height: 1000px;
    background: white;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.5s ease-in-out;
}

.sidebar {
    font-size: 1.2rem;
    width: 240px;
    background-color: #fafafa;
    padding: 20px;
    border-right: 1px solid #e0e0e0;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar li {
    padding: 12px;
    cursor: pointer;
    color: #555;
}

.sidebar li.active {
    font-weight: bold;
    color: #000;
}

.task-list {
    flex: 1;
    padding-top: 20px;
    padding-left: 150px;
    padding-right: 150px;
    overflow-y: auto;
}
.calendar-icon{
    filter: brightness(0) saturate(100%);
}

.edit-icon{
    filter: brightness(0) saturate(100%);
}

.calendar-icon{
    filter: brightness(0) saturate(100%);
}

.drawer-icon{
    filter: brightness(0) saturate(100%);
}

.alert-icon{
    filter: brightness(0) saturate(100%);
}

.add-icon{
    margin-top: 10px;
    filter: brightness(0) saturate(100%);
}

.task-list-title {
    font-size: 2rem;
    font-weight: bolder;
    margin-bottom: 20px;
}

.task-card {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    margin-bottom: 16px;
    padding: 16px;
    transition: background-color 0.3s ease;
}

.task-card:hover {
    background-color: #f9f9f9;
}

.task-top {
    display: flex;
    flex-direction: column;
}

.task-header {
    display: flex;
    align-items: center;
    gap: 12px;
}

.task-header input[type="checkbox"] {
    appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #aaa;
    border-radius: 50%;
    cursor: pointer;
}

.task-header input[type="checkbox"]:checked {
    background-color: #000;
    border-color: #000;
}

.task-info {
    flex: 1;
}

.task-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
}

.task-date {
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 0.9rem;
}

.task-date.expired {
    background-color: #ffebee;
    color: #d32f2f;
}

.task-date.today {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.task-icons {
    display: flex;
    gap: 8px;
}

.task-icons button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    color: #999;
}

.task-icons button:hover {
    color: #555;
}

.separator {
    border-top: 1px solid #e0e0e0;
    margin: 10px 0;
}

.subtask-list {
    margin-top: 8px;
}

.subtask-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    padding-left: 30px;
}

.subtask-item input[type="checkbox"] {
    appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #aaa;
    border-radius: 50%;
    cursor: pointer;
}

.subtask-item input[type="checkbox"]:checked {
    background-color: #000;
    border-color: #000;
}

.create-task {
    padding: 12px;
    cursor: pointer;
    border: 1px dashed #ccc;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
}

.create-task:hover {
    background-color: #f5f5f5;
}

.subtask-counter {
    padding-left: 30px;
}
</style>