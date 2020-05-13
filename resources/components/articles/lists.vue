<template>
  <section>
    <h1>タスク一覧ページ</h1>
    <section>
      <h2>タスク一覧</h2>
      <ul id="task_list">
        <li v-for="task in tasks" :key="task.id">{{ task.id }}: {{ task.name }} → {{ task.done ? "完了" : "未完了" }}</li>
      </ul>
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
module.exports = {
  data: function() {
    return {
      tasks: [
        { id: 1, name: "task1" , done: true},
        { id: 2, name: "task2" , done: true},
        { id: 3, name: "task3" , done: false},
        { id: 4, name: "task4" , done: false},
      ]
    };
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