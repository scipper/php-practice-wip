export class TodoController {

    public constructor() {
        const mainNavList = document.querySelector("#main-nav-list");
        if (mainNavList) {
            const li = document.createElement("li");
            li.innerText = `Todo`;
            li.onclick = () => {
                history.pushState({}, "Get Todos", "/#/todo");
                mainNavList.querySelectorAll("li").forEach((childLi) => {
                    childLi.classList.toggle("active", false);
                })
                li.classList.toggle("active", true);
            };
            if (location.hash.startsWith("#/todo")) {
                li.classList.toggle("active", true);
            }
            mainNavList.insertAdjacentElement("beforeend", li);
        }
    }

}