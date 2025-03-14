<script setup>
import { ref, defineAsyncComponent, markRaw } from "vue";

const Login = markRaw(defineAsyncComponent(() => import("@/components/Login.vue")));
const Register = markRaw(defineAsyncComponent(() => import("@/components/Register.vue")));
const TaskList = markRaw(defineAsyncComponent(() => import("@/views/TaskList.vue")));

const views = {
  Login,
  Register,
  TaskList
};

const currentView = ref(views.Login);

const toggleView = () => {
  console.log("Mudando de tela para:", currentView.value === views.Login ? "Register" : "Login");
  currentView.value = currentView.value === views.Login ? views.Register : views.Login;
};

const showTaskList = () => {
  console.log("Redirecionando para TaskList");
  currentView.value = views.TaskList;
};
</script>

<template>
  <div>
      <transition name="fade">
          <component :is="currentView" 
            @switchAuthView="toggleView"
            @redirectToTaskList="showTaskList" 
          />
      </transition>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
