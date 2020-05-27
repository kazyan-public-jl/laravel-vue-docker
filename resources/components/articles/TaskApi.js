exports.fetchTasks = (callback) => {
  const url = '/api/tasks';
  axios.get(url).then(response => {
    console.log('GET response:', url, response);
    // response.data = Tasksモデルデータの配列
    const newTasks = response?.data ?? [];
    callback(newTasks);
  }).catch(e=>{
    console.error(e.message);
  });
};

exports.addTask = (addTask, callback) => {
  const url = 'api/tasks';
  const postData = {
    tasks: [{ name: addTask.name }],
  };
  console.log('POST:', url, postData);

  axios
    .post(url, postData)
    .then(response => {
      // 新規タスクを tasks に追加
      console.log('POST: response', url, response);
      const newTasks = response?.data?.tasks;
      callback(newTasks);
    })
    .catch(error => {
      // error = {data, message, status};
      const responseData = error.response?.data;
      console.error('failed to add task: ', responseData);
      if (responseData) {
        switch (responseData.status) {
          case 404: // Not Found
          case 409: // Conflict
            alert(responseData.status + " ERROR:" + responseData.message);
            return;
          default:
            alert('予期せぬエラーが発生しました:' + responseData);
        }
      }
    });
};

exports.deleteTask = (targetId, callback) => {
  const url = 'api/tasks';
  const postData = {
    tasks: [{ id: targetId }],
  };
  console.log('DELETE:', url, postData);

  axios
    .delete(url, { data: postData })
    .then(response => {
      // 削除タスクを tasks から削除する
      const responseTasks = response?.data?.tasks;
      console.log('DELETE: response', url, response);
      callback(responseTasks);
    })
    .catch(error => {
      console.error(error);
    });
}