/**
 * タスク名のバリデーション
 * @param {Task} チェックするタスク
 * @param {Task[]} 重複確認用Task配列
 * @returns {boolean, string} {isValid, message}
 */
exports.validateTaskName = (task, tasks) => {
  const taskName = task.name;
  const sameNameTask = tasks.find(t=>{
    if (t.id != task.id) {
      return t.name == taskName;
    }
  });
  let isValid = true;
  let message = "";
  if (taskName == "") {
    message = "タスク名を入力してください";
    isValid = false;
  }
  else if (sameNameTask) {
    message = "タスク名は重複しないでください!";
    isValid = false;
  }
  return { isValid, message };
}
