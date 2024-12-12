import {TodoApi} from "../../Api/Todo/TodoApi";

export class TodoController {
    private todoApi: TodoApi;

    public constructor(todoApi: TodoApi) {
        this.todoApi = todoApi;
    }

    public getAllTodos() {
        return this.todoApi.getAllTodos();
    }

}