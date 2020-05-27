<template>
  <tr :class="rowClassName">
    <td>{{ task.order }} ({{ task.id }})</td>
    <td>
      <input v-model="task.name"
        type="text"
        @blur="updateTaskName"
      />
      <p v-if="!isValidName" class="text-warning">{{ messageText }}</p>
    </td>
    <td>
      <input v-model="task.status"
        type="checkbox"
        name="status"
        @change="updateTaskStatus"
        :id="'task_'+task.id"
      />
      <label :for="'task_'+task.id">{{ task.status ? "完了" : "未完了" }}</label>
    </td>
    <td>
      <button @click="deleteTask" name="deleteTask" :value="task.id">削除</button>
    </td>
  </tr>
</template>

<style lang="scss">
.task-row {
  background-color: #eee;
  &.warning {
    background-color: lightyellow;
  }
  .text-warning {
    margin: 0;
    font-size: 0.7em;
    color: #ffbf00;
    line-height: 1.3em;
  }
}
</style>

<script>
import { deleteTask } from "./TaskApi.js";
import { validateTaskName } from "./TaskUtil.js";

export default {
  name: "task-component",
  props: {
    task: { // task本体
      id: Number,
      name: String,
      order: Number,
      status: Boolean,
    },
    tasks: Array, // 参照用: タスク一覧
  },
  data() {
    return {
    }
  },
  computed: {
    rowClassName: function() {
      return this.isValidName ? "task-row" : "task-row warning";
    },
    isValidName: function() {
      console.log('computed: isValidName?', validateTaskName(this.task, this.tasks));
      return validateTaskName(this.task, this.tasks).isValid;
    },
    messageText: function() {
      return validateTaskName(this.task, this.tasks).message;
    },
  },
  methods: {
    /**
     * ドラッグ開始
     */
    setDragStart(event) {
      return;
    },
    /**
     * 項目のupdate時のイベント
     */
    updateTaskName(event) {
      this.updateTask(this.task);
    },
    updateTaskStatus(event) {
      this.updateTask(this.task);
    },
    updateTask(task) {
      if (!this.isValidName)
        return;

      const url = 'api/tasks';
      const postData = {
        tasks: [ task ]
      };
      axios
        .post(url, postData)
        .then(response => {
          const responseTasks = response.data?.tasks;
          if (!responseTasks) {
            return;
          }
          console.log('POST: response=', url, response);
          // 画面上で更新住みなので何もしない
          console.log('updated.', responseTasks);
        })
        .catch(error => {
          console.error(error);
        });
    },
    /**
     * 削除ボタン実行時のイベント
     */
    deleteTask(event) {
      const targetId = event.currentTarget.value;
      const deleteIndex = this.tasks.findIndex(task => {
        return task.id == targetId;
      });
      if (deleteIndex == -1) {
        console.log(targetId + ' is not found.');
        return;
      }
      if (!confirm(this.tasks[deleteIndex].name + ' を削除しますか？')) {
        return;
      }

      deleteTask(targetId, (newTasks)=>{
        this.$emit('onUpdateTasks', newTasks);
      });
      // const url = 'api/tasks';
      // const postData = {
      //   tasks: [{ id: targetId }],
      // };
      // console.log('DELETE:', url, postData);

      // axios
      //   .delete(url, { data: postData })
      //   .then(response => {
      //     // 削除タスクを tasks から削除する
      //     const responseTasks = response?.data?.tasks;
      //     console.log('DELETE: response', url, response);
      //     if (!responseTasks) {
      //       return;
      //     }
      //     responseTasks.forEach(task => {
      //       // 画面上から削除
      //       if (task.id == targetId) {
      //         this.tasks.splice(deleteIndex, 1);
      //       }
      //     });

      //     console.log('deleted.', this.tasks);
      //   })
      //   .catch(error => {
      //     console.error(error);
      //   });
    },
  }
};
</script>
