<template>
  <table class="task-table">
    <thead>
      <th>ID</th>
      <th>タスク名</th>
      <th>完了ステータス</th>
      <th></th>
    </thead>
    <tbody>
      <tr v-for="task in tasks" :key="task.id">
        <td>{{ task.id }}</td>
        <td><input v-model="task.name" @change="validateTaskName" type="text" /></td>
        <td><input v-model="task.status" type="checkbox" name="status" :id="'task_'+task.id"><label :for="'task_'+task.id">{{ task.status ? "完了" : "未完了" }}</label></td>
        <td><button @click="deleteTask" name="deleteTask" :value="task.id">削除</button></td>
      </tr>
    </tbody>
  </table>
</template>

<script>

export default {
  props: {
    tasks: Array,
  },
  data: function() {
    return {
      
    };
  },
  methods: {
    deleteTask(event) {
      const targetId = event.currentTarget.value;
      const deleteIndex = this.tasks.findIndex(task => {
        return task.id == targetId;
      });
      this.tasks.splice(deleteIndex, 1);
      console.log('deleted.', this.tasks);
    },
    validateTaskName(event) {
      // TODO: タスク名の重複チェック
      return true;
    }
  }
};
</script>
