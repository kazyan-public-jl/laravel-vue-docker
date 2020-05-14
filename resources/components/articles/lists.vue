<template>
  <section>
    <h1>タスク一覧ページ</h1>
    <section>
      <h2>タスク一覧</h2>
      <task-list 
        :tasks="tasks"
      />
      <div>
        <input
          type="text"
          laceholder="タスク名を追加"
          value=""
          v-model="newTask.name" />
        <button
          @click="addTask">
          タスク追加
        </button>
      </div>
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

export default {
  data: function() {
    return {
      tasks: [],
      newTask: {
        name: "",
      }
    };
  },
  mounted () {
    console.log('mounted lists.vue');
    this.fetchTasks();
  },
  components: {
    TaskList,
  },
  methods: {
    fetchTasks () {
      const url = '/api/tasks';
      axios.get(url).then(response => {
        console.log('GET response:', url, response);
        // response.data = Tasksモデルデータの配列
        const newTasks = response?.data ?? [];
        this.tasks = newTasks;
      });
    },
    addTask (event) {
      const url = 'api/tasks/add_task';
      const postData = {
        name: this.newTask.name
      }
      console.log('POST:', url, postData);

      // TODO: server API 対応
      axios.post(url, postData).then(response => {
        // 新規タスクを tasks に追加
        const newTask = response?.data?.task;
        console.log('POST: response', url, response);
        this.tasks.push(newTask);
        // タスク追加フォームを初期化
        this.newTask = { name: "" };
      });
    }
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