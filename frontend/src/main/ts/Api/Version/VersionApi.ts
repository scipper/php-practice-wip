export class VersionApi {
    public async getVersion() {
        const response = await fetch("/api/version");
        return await response.text();
    }
}