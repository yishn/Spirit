import fs from "node:fs/promises";
import path from "node:path";
import crypto from "node:crypto";
import { marked, type Tokens } from "marked";
import exifr from "exifr";

function capitalize(str: string): string {
  return str
    .split(/\s+/)
    .map((word) => word[0].toUpperCase() + word.slice(1))
    .join(" ");
}

function exifGpsToCoords(
  latitude: number[],
  latitudeRef: "N" | "S",
  longitude: number[],
  longitudeRef: "E" | "W",
): [number, number] {
  const dmsToDecimal = (
    [deg, min, sec]: number[],
    ref: "N" | "S" | "E" | "W",
  ) => {
    let decimal = deg + min / 60 + sec / 3600;
    if (ref === "S" || ref === "W") {
      decimal *= -1;
    }
    return decimal;
  };

  const lat = dmsToDecimal(latitude, latitudeRef);
  const lon = dmsToDecimal(longitude, longitudeRef);

  return [lat, lon];
}

export async function parseJourney(dirPath: string) {
  const markdown = await fs.readFile(
    path.resolve(dirPath, "index.md"),
    "utf-8",
  );
  const sections = markdown
    .split(/^\s*----*\s*$/m)
    .map((section) => section.trim());

  let name = capitalize(path.basename(dirPath).replaceAll("-", " "));
  let content = "";

  // Detect header section
  if (sections[0] != null) {
    let headerSection = false;

    marked.use({
      renderer: {
        heading(token: Tokens.Heading) {
          if (token.depth === 1) {
            headerSection = true;
            name = token.text;
          }
          return "";
        },
      },
    });

    const parsed = marked.parse(sections[0]) as string;

    if (headerSection) {
      content = parsed;
      sections.unshift();
    }
  }

  const items = await Promise.all(
    sections.map(async (section) => {
      let images: { imgSrc: string }[] = [];

      marked.use({
        renderer: {
          image(token: Tokens.Image) {
            if ([".jpg", ".jpeg"].some((ext) => token.href.endsWith(ext))) {
              images.push({ imgSrc: token.href });
            }
            return "";
          },
        },
      });

      const description = (marked.parse(section) as string)
        .replace(/<p>\s*<\/p>/g, "")
        .trim();
      if (images.length === 0) return null;

      const id = crypto.hash("shake256", images[0].imgSrc, { outputLength: 4 });
      const meta = (await exifr
        .parse(path.resolve(dirPath, images[0].imgSrc))
        .catch(() => ({}))) as {
        DateTimeOriginal?: Date;
        CreateDate?: Date;
        GPSLatitude?: number[];
        GPSLatitudeRef?: "N" | "S";
        GPSLongitude?: number[];
        GPSLongitudeRef?: "E" | "W";
      };

      const location =
        meta.GPSLatitude != null &&
        meta.GPSLatitudeRef != null &&
        meta.GPSLongitude != null &&
        meta.GPSLongitudeRef != null
          ? exifGpsToCoords(
              meta.GPSLatitude,
              meta.GPSLatitudeRef,
              meta.GPSLongitude,
              meta.GPSLongitudeRef,
            )
          : undefined;

      return {
        id,
        location,
        date: meta.DateTimeOriginal ?? meta.CreateDate,
        description,
        images,
      };
    }),
  );

  return {
    name,
    content,
    items: items.filter((photo) => photo != null),
  };
}

export async function parseIndex(dirPath: string) {
  const markdown = await fs.readFile(
    path.resolve(dirPath, "index.md"),
    "utf-8",
  );

  let title: string = "Spirit";

  marked.use({
    renderer: {
      heading(token: Tokens.Heading) {
        if (token.depth === 1) {
          title = token.text;
        }
        return "";
      },
    },
  });

  const content = marked.parse(markdown) as string;

  const years = new Set<number>();
  const journeys = await Promise.all(
    (
      await fs.readdir(dirPath, {
        withFileTypes: true,
      })
    )
      .filter((dirent) => dirent.isDirectory())
      .map(async (dirent) => {
        const journey = await parseJourney(path.resolve(dirPath, dirent.name));

        const year = journey.items
          .find((photo) => photo.date != null)
          ?.date?.getFullYear();
        if (year == null) return null;

        years.add(year);

        return {
          id: dirent.name,
          name: journey.name,
          year,
          imgSrc: path.posix.join(
            dirent.name,
            journey.items[0].images[0].imgSrc,
          ),
        };
      }),
  );

  return {
    title,
    content,
    years: [...years].sort((a, b) => a - b),
    journeys: journeys.filter((journey) => journey != null),
  };
}
