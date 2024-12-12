export class RegisteredModulesController {

    public constructor() {
        const mainNavList = document.querySelector("#main-nav-list");
        if (mainNavList) {
            const li = document.createElement("li");
            li.innerText = `Modules`;
            li.onclick = () => {
                history.pushState({}, "Get Registered Modules", "/#/registeredmodules");
                mainNavList.querySelectorAll("li").forEach((childLi) => {
                    childLi.classList.toggle("active", false);
                })
                li.classList.toggle("active", true);
            };
            if (location.hash.startsWith("#/registeredmodules")) {
                li.classList.toggle("active", true);
            }
            mainNavList.insertAdjacentElement("beforeend", li);
        }
    }

}