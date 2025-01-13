export interface Renderable {
    render(): Promise<HTMLElement | undefined>;
}