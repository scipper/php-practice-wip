import {VersionController} from "./VersionController";
import {VersionApi} from "../../Api/Version/VersionApi";
import {Module} from "../../Core/Module/Module";

export class VersionModule extends Module {
    public init() {
        new VersionController(new VersionApi())
    }
}