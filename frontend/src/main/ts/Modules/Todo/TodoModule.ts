import {Module} from "../../Core/Module/Module";
import {TodoApi} from "../../Api/Todo/TodoApi";

export class TodoModule extends Module {

    public constructor() {
        super();
        this.routes = [
            {
                route: "#/todo",
                controller: () => import("./TodoList")
                    .then((module) => module.TodoList)
                    .then((controller) => new controller(new TodoApi()))
            }
        ]
        this.navigation = "Todos";
    }

}