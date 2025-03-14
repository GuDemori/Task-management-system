<template>
    <div class="sidebar">
        <div class="logo">🤘</div>
        <ul>
            <li :class="{ active: activeFilter === 'all' }" @click="$emit('setFilter', 'all')">
                <span class="icon">📁</span> Entrada
            </li>
            <li :class="{ active: activeFilter === 'today' }" @click="$emit('setFilter', 'today')">
                <span class="icon">📅</span> Tarefas de hoje
            </li>
            <li :class="{ active: activeFilter === 'expired' }" @click="$emit('setFilter', 'expired')">
                <span class="icon">⚠️</span> Vencidos
            </li>
        </ul>
    </div>
</template>

<script setup>
defineProps({
    activeFilter: String
});
</script>

<style scoped>
.sidebar {
    width: 250px;
    background-color: #f9f9f9;
    padding: 20px;
    border-right: 1px solid #eee;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    height: 100vh;
}

.logo {
    font-size: 2rem;
    margin-bottom: 20px;
}

ul {
    list-style: none;
    padding: 0;
    width: 100%;
}

li {
    padding: 12px;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #555;
    transition: background 0.3s ease;
    border-radius: 5px;
}

li:hover {
    background-color: #f0f0f0;
}

li.active {
    background-color: #e0e0e0;
    font-weight: bold;
}

.icon {
    font-size: 1.2rem;
}
</style>