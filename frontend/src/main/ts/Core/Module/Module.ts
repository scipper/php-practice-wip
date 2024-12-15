import {Controller} from "./Controller";

export abstract class Module {

    protected routes?: { route: string, controller: () => Promise<Controller> }[];
    protected navigation?: string;

    public init(): void {
        const mainNavList = document.querySelector("#main-nav-list");
        const content = document.querySelector("#content");
        if (mainNavList && content) {
            let li: HTMLLIElement | undefined;
            if (this.navigation) {
                li = document.createElement("li");
                li.innerText = this.navigation;
                li.onclick = () => {

                    if (Array.isArray(this.routes) && this.routes[0]) {
                        history.pushState({}, "", this.routes[0].route);
                    }

                    this.activate(content, mainNavList, li);
                };
                mainNavList.insertAdjacentElement("beforeend", li);
            }

            if (Array.isArray(this.routes) && this.routes[0]) {
                if (location.hash.startsWith(this.routes[0].route)) {
                    this.activate(content, mainNavList, li);
                }
            }
        }
    }

    private activate(content: Element, mainNavList: Element, li?: HTMLLIElement) {
        content.innerHTML = "";

        mainNavList.querySelectorAll("li").forEach((childLi) => {
            childLi.classList.toggle("active", false);
        });

        if (Array.isArray(this.routes) && this.routes[0]) {
            this.routes[0].controller()
                .then((instance) => instance.render())
                .then((template) => {
                    if (template) {
                        content.insertAdjacentElement("beforeend", template)
                    }
                })
                .catch((error) => console.log(error));
        }

        li?.classList.toggle("active", true);
    }

}