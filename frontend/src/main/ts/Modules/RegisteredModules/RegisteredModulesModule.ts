import {Module} from "../../Core/Module/Module";

export class RegisteredModulesModule extends Module {
    public init() {
        const mainNavList = document.querySelector("#main-nav-list");
        const content = document.querySelector("#content");
        if (mainNavList && content) {
            const li = document.createElement("li");
            li.innerText = `Modules`;
            li.onclick = () => {
                this.activateModules(content, mainNavList, li);
            };
            mainNavList.insertAdjacentElement("beforeend", li);

            if (location.hash.startsWith("#/modules")) {
                this.activateModules(content, mainNavList, li);
            }
        }

        // new TodoController(new TodoApi())
    }

    private activateModules(content: Element, mainNavList: Element, li: HTMLLIElement) {
        content.innerHTML = "";
        history.pushState({}, "Get Modules", "/#/modules");
        mainNavList.querySelectorAll("li").forEach((childLi) => {
            childLi.classList.toggle("active", false);
        });
        li.classList.toggle("active", true);
    }
}