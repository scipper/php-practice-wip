import "./Version.scss";
import {VersionApi} from "../../Api/Version/VersionApi";
import {Renderable} from "../../Core/Module/Renderable";

export class VersionController implements Renderable {

    public constructor(private versionApi: VersionApi) {
    }

    async render(): Promise<HTMLElement | undefined> {
        const footer = document.querySelector("footer");
        if (footer) {
            const version = await this.versionApi.getVersion()
            const versionDiv = document.createElement("div");
            versionDiv.innerText = `version ${version}`;
            versionDiv.classList.add("version");
            footer.insertAdjacentElement("beforeend", versionDiv);
        }

        return;
    }

}