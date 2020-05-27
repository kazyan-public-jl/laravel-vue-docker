<template>
  <div :class="inputClass">
    <input
      type="text"
      laceholder="タスク名を追加"
      value=""
      v-model="newTask.name"
      @keydown.enter="onKeyDownAddTask" />
    <button
      @click="addTask">
      タスク追加
    </button>
    <p v-if="!isValidName" class="text-warning">{{ messageText }}</p>
  </div>
</template>

<style lang="scss">
.add-task-row {
  &.warning {
    background-color: lightyellow;
    input {
      background-color: pink;
    }
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
import { validateTaskName } from './TaskUtil.js';
import { addTask } from './TaskApi.js';

export default {
  props: {
    tasks: Array,
  },
  data: function() {
    return {
      newTask: {
        name: ""
      },
    };
  },
  computed: {
    inputClass: function() {
      return "add-task-row" + (this.isValidName? "" : " warning");
    },
    isValidName: function() {
      return (this.newTask.name != "")
        ? validateTaskName(this.newTask, this.tasks).isValid
        : true; // 空の状態でエラー表示にしたくないため、未入力時は true;
    },
    messageText: function() {
      return validateTaskName(this.newTask, this.tasks).message;
    }
  },
  methods: {
    addTask (event) {
      const { isValid, message } = validateTaskName(this.newTask, this.tasks);
      if (!isValid) {
        alert(message);
        return;
      }

      addTask(this.newTask, (newTasks) => {
        // 新タスクを一覧に追加
        newTasks.forEach(newTask => {
          this.tasks.push(newTask);
        });
        // タスク追加フォームを初期化
        this.newTask = { name: "" };
      });
    },
    onKeyDownAddTask (event) {
      console.log('keyDown:', event.key, event.code);
      if (event.key === 'Enter') {
        this.addTask(event);
      }
    },
  }
}
</script>