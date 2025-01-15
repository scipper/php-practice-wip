import {Renderable} from "../../Core/Module/Renderable";
import {TodoApi} from "../../Api/Todo/TodoApi";
import {IconButton} from "../../Components/Button/IconButton";
import {TodoEntry} from "../../Api/Todo/TodoEntry";

export class TodoItem implements Renderable {

    public constructor(private todo: TodoEntry,
                       private todoApi: TodoApi,
                       private refresh: () => void) {
    }

    async render(): Promise<HTMLElement> {
        const deleteButton = new IconButton("X", (event: MouseEvent) => {
            event.stopPropagation();
            event.preventDefault();

            if (confirm(`Delete todo ${this.todo.title}?`)) {
                this.deleteTodo(this.todo.id);
            }
        });
        deleteButton.addClass("red");
        const li = document.createElement("li");
        li.innerText = this.todo.title;
        li.classList.toggle("done", this.todo.done);
        li.insertAdjacentElement("beforeend", await deleteButton.render());
        li.onclick = () => this.markAsDone(this.todo.id, !this.todo.done);
        return li;
    }

    private async deleteTodo(id: number) {
        try {
            await this.todoApi.delete(id);
            this.refresh();
        } catch (error) {
            console.error(error);
        }
    }

    private async markAsDone(id: number, done: boolean) {
        try {
            await this.todoApi.done(id, done);
            this.refresh();
        } catch (error) {
            console.error(error);
        }
    }
}