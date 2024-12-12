import {Module} from "../../Core/Module/Module";
import {TodoController} from "./TodoController";

export class TodoModule extends Module {
    public init() {
        new TodoController()
    }
}