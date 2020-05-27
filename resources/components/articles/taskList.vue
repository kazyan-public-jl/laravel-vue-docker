<template>
  <table class="task-table">
    <thead>
      <th>ID</th>
      <th>タスク名</th>
      <th>完了ステータス</th>
      <th></th>
    </thead>
    <tbody>
      <task-item v-for="task in sortedTasks"
        :key="task.id"
        :task="task"
        :tasks="tasks"
        @onChangeOrder="onChangeOrder"
        @onUpdateTasks="onUpdateTasks"
      />
    </tbody>
  </table>
</template>

<script>
import Task from "./task.vue";

export default {
  props: {
    tasks: Array,
  },
  computed: {
    sortedTasks: function () {
      return this.tasks.sort((taskA,taskB)=>{
        // 昇順に並び替え
        return taskA.order - taskB.order;
      });
    }
  },
  components: {
    taskItem: Task,
  },
  methods: {
    onChangeOrder: function(fromOrder, toOrder) {
      const fromIndex = this.tasks.findIndex(task=>task.order==fromOrder);
      const toIndex = this.tasks.findIndex(task=>task.order==toOrder);
      this.tasks[fromIndex].order = toOrder;
      this.tasks[toIndex].order = fromOrder;
    },
    onUpdateTasks: function(newTasks) {
      this.$emit('onUpdateTasks', newTasks);
    }
  }
};
</script>
