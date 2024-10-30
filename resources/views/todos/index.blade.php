<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <style>
        /* Custom styles for the to-do app */
        .todo-container {
            background-color: #f9f3ec;
            padding: 20px;
            border-radius: 15px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .todo-input-group {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        .todo-input-group input {
            border: none;
            outline: none;
            padding: 15px;
            border-radius: 20px 0 0 20px;
            flex-grow: 1;
        }
        .todo-input-group button {
            border: none;
            border-radius: 0 20px 20px 0;
            background-color: #46c1e3;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
        }
        .list-group-item {
            border: none;
            border-bottom: 1px solid #e0e0e0;
            padding-left: 0;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .completed {
            text-decoration: line-through;
            color: #c0c0c0;
        }
        .checkbox-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #f1f1f1;
            border: 1px solid #d0d0d0;
            margin-right: 10px;
            cursor: pointer;
        }
        .checkbox-circle.completed {
            background-color: #46c1e3;
            color: white;
        }
        .clear-completed {
            color: #ff6b6b;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div id="app" class="todo-container mt-5">
        <h5 class="mb-4">What do you need to do?</h5>
        
        <form @submit.prevent="addTodo" class="input-group todo-input-group mb-4">
            <input type="text" v-model="newTodo" placeholder="Enter To Do">
            <button type="submit">ADD</button>
        </form>

        <ul class="list-group">
            <li v-for="todo in todos" :key="todo.id" class="list-group-item d-flex align-items-center">
                <div 
                    :class="['checkbox-circle', { completed: todo.completed }]"
                    @click="toggleComplete(todo)"
                ></div>
                <span :class="{ completed: todo.completed }">@{{ todo.content }}</span>
            </li>
        </ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.min.js"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                todos: @json($todos),
                newTodo: ''
            },
            methods: {
                addTodo() {
                    if (this.newTodo.trim()) {
                        axios.post('{{ route("todos.store") }}', { content: this.newTodo })
                            .then(response => {
                                this.todos.push(response.data);
                                this.newTodo = '';
                            });
                    }
                },
                toggleComplete(todo) {
                    todo.completed = !todo.completed;
                },
            },
            mounted() {
                Echo.channel('todos')
                    .listen('TodoCreated', (event) => {
                        this.todos.push(event.todo);
                    });
            }
        });
    </script>
</body>
</html>
