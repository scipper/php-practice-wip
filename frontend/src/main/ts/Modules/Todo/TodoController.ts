export class TodoController {

    public constructor() {
        const mainNavList = document.querySelector("#main-nav-list");
        if (mainNavList) {
            const li = document.createElement("li");
            li.innerText = `Todo`;
            mainNavList.insertAdjacentElement("beforeend", li);
        }
    }

}