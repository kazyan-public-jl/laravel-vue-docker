<template>
  <section>
    <h1>タスク一覧ページ</h1>
    <section>
      <h2>タスク一覧</h2>
      <task-list 
        :tasks="tasks"
        @onUpdateTasks="updateTasks"
      />
      <add-task-area :tasks="tasks" />
    </section>
    <section>
      <h2>ページ一覧</h2>
      <ul>
        <li>
          <router-link to="/">TOP</router-link>
        </li>
      </ul>
    </section>
  </section>
</template>

<script>
import TaskList from './taskList.vue';
import AddTaskArea from './AddTaskArea.vue';
import { fetchTasks } from './TaskApi.js';

export default {
  data: function() {
    return {
      tasks: [],
    };
  },
  mounted () {
    this.fetchTasks();
  },
  components: {
    TaskList,
    AddTaskArea,
  },
  methods: {
    fetchTasks () {
      fetchTasks((newTasks)=>{
        this.updateTasks(newTasks);
      });
    },
    updateTasks (newTasks) {
      this.tasks = newTasks;
    },
  }
};
</script>

<style scoped>
#task_list .task-item {
  font-size: 1em;
  line-height: 1.2em;
  font-weight: bold;
}
</style>