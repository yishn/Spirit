import fs from "node:fs/promises";
import path from "node:path";
import { renderIndex, renderJourney } from "./render.ts";
import { parseIndex, parseJourney } from "./parser.ts";

async function compileJourney(dirPath: string): Promise<void> {
  const journey = await parseJourney(dirPath);
  const html = await renderJourney({
    name: journey.name,
    content: journey.content,
    items: journey.items.map((item) => ({
      id: item.id,
      date:
        item.date == null
          ? ""
          : `${item.date.getFullYear()}-${item.date.getMonth() + 1}-${item.date.getDate()}`,
      location: item.location?.map((x) => x.toFixed(5)).join(", ") ?? "",
      description: item.description,
      images: item.images,
    })),
  });

  const outputPath = path.resolve(dirPath, "index.html");
  await fs.writeFile(outputPath, html, "utf-8");
}

export async function compile(dirPath: string): Promise<void> {
  const index = await parseIndex(dirPath);
  const html = await renderIndex({
    title: index.title,
    content: index.content,
    lists: index.years.map((year) => ({
      name: year.toString(),
      items: index.journeys
        .filter(
          (journey): journey is NonNullable<typeof journey> =>
            journey?.year === year,
        )
        .map((journey) => ({
          id: journey.id,
          name: journey.name,
          imgSrc: journey.imgSrc,
        })),
    })),
  });

  const outputPath = path.resolve(dirPath, "index.html");
  await fs.writeFile(outputPath, html, "utf-8");

  for (const journey of index.journeys) {
    await compileJourney(path.resolve(dirPath, journey.id));
  }

  await fs.copyFile(
    path.resolve(import.meta.dirname, "../../dist/main.js"),
    path.resolve(dirPath, "main.js"),
  );
  await fs.copyFile(
    path.resolve(import.meta.dirname, "../../dist/main.js.map"),
    path.resolve(dirPath, "main.js.map"),
  );
}
