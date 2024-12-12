import {Module} from "../../Core/Module/Module";
import {TodoApi} from "../../Api/Todo/TodoApi";

export class TodoModule extends Module {
    public init() {
        const mainNavList = document.querySelector("#main-nav-list");
        const content = document.querySelector("#content");
        if (mainNavList && content) {
            const li = document.createElement("li");
            li.innerText = `Todo`;
            li.onclick = () => {
                this.activateTodos(content, mainNavList, li);
            };
            mainNavList.insertAdjacentElement("beforeend", li);

            if (location.hash.startsWith("#/todo")) {
                this.activateTodos(content, mainNavList, li);
            }
        }

        // new TodoController(new TodoApi())
    }

    private activateTodos(content: Element, mainNavList: Element, li: HTMLLIElement) {
        content.innerHTML = "";
        history.pushState({}, "Get Todos", "/#/todo");
        mainNavList.querySelectorAll("li").forEach((childLi) => {
            childLi.classList.toggle("active", false);
        });
        import("./TodoController")
            .then((module) => module.TodoController)
            .then((controller) => new controller(new TodoApi()))
            .then((instance) => instance.getAllTodos())
            .then((todos) => {
                todos.forEach((todo) => {
                    const todosList = document.createElement("ul");
                    const todoEntry = document.createElement("li");
                    todoEntry.innerText = todo.title;
                    todosList.insertAdjacentElement("beforeend", todoEntry);
                    content.insertAdjacentElement("beforeend", todosList);
                });
            })
            .catch((error) => console.log(error));
        li.classList.toggle("active", true);
    }
}