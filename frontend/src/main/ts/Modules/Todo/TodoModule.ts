import {Module} from "../../Core/Module/Module";
import {TodoApi} from "../../Api/Todo/TodoApi";

export class TodoModule extends Module {

    public constructor() {
        super();
        this.routes = [
            {
                route: "#/todo",
                controller: () => import("./TodoController")
                    .then((module) => module.TodoController)
                    .then((controller) => new controller(new TodoApi()))
            }
        ]
        this.navigation = "Todos";
    }

}