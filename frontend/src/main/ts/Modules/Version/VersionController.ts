import "./Version.scss";
import {VersionApi} from "../../Api/Version/VersionApi";
import {Controller} from "../../Core/Module/Controller";

export class VersionController implements Controller {

    public constructor(private versionApi: VersionApi) {
    }

    async render(): Promise<HTMLElement | undefined> {
        const footer = document.querySelector("footer");
        if (footer) {
            this.versionApi.getVersion()
                .then((version) => {
                    const versionDiv = document.createElement("div");
                    versionDiv.innerText = `version ${version}`;
                    versionDiv.classList.add("version");
                    footer.insertAdjacentElement("beforeend", versionDiv);
                })
        }

        return;
    }

}