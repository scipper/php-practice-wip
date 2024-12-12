import "./Version.scss";
import {VersionApi} from "../../Api/Version/VersionApi";

export class VersionController {

    public constructor(versionApi: VersionApi) {
        const footer = document.querySelector("footer");
        if (footer) {
            versionApi.getVersion()
                .then((version) => {
                    const versionDiv = document.createElement("div");
                    versionDiv.innerText = `version ${version}`;
                    versionDiv.classList.add("version");
                    footer.insertAdjacentElement("beforeend", versionDiv);
                })
        }
    }

}