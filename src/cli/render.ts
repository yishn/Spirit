import { loadTemplate } from "./template.ts";

export async function renderList(): Promise<string> {
  const template = await loadTemplate("list");

  return template;
}

console.log(await renderList());
